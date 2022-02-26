<?php
    defined('ABSPATH') or die('You silly human !');
    require_once(WOO_PBL_DIR . 'classes/class.woo_pbl.product.beneficiaries.item.php');
    class WooPBLDbProduct {
        public function init() {
            add_action( 'woocommerce_after_add_to_cart_quantity', [$this, 'productBeneficiariesForm']);

            add_filter(
                'woocommerce_product_add_to_cart_text',
                array( $this, 'add_to_cart_text' ),
                10,
                2
            );
            add_filter(
                'woocommerce_product_single_add_to_cart_text',
                array( $this, 'add_to_cart_text' ),
                10,
                2
            );

            add_filter(
                'woocommerce_product_add_to_cart_url',
                array( $this, 'add_to_cart_url' ),
                20,
                2
            );

            add_filter(
                'woocommerce_product_supports',
                array( $this, 'product_supports' ),
                10,
                3
            );

            add_filter(
                'post_class',
                array( $this, 'product_class' ),
                10,
                3
            );

            add_filter(
                'woocommerce_add_cart_item_data',
                array( $this, 'add_cart_item_data' ),
                10,
                3
            );

            add_filter(
                'woocommerce_get_item_data',
                array( $this, 'get_item_data' ),
                10,
                2
            );

            add_filter( 'woocommerce_cart_item_quantity', [$this, 'change_quantity_input'], 10, 3);
        }

        public function getProductRuleOfUse($product_id) {
            global $wpdb;
            $woo_pbl_product_rule_of_use_table = $wpdb->prefix . "woo_pbl_product_rule_of_use";

            $wooPblProductRuleOfUse = $wpdb->get_row(
                $wpdb->prepare(
                    "
                        SELECT *
                        FROM $woo_pbl_product_rule_of_use_table
                        WHERE product_id = %d
                    ",
                    $product_id
                ), ARRAY_A
            );

            return $wooPblProductRuleOfUse;
        }

        public function productBeneficiariesForm() {
            global $product;

            $wooPblProductRuleOfUse = $this->getProductRuleOfUse($product->get_id());

            $product_price_per_beneficiary = isset($wooPblProductRuleOfUse['product_price_per_beneficiary']) ?  $wooPblProductRuleOfUse['product_price_per_beneficiary'] : null;
            $product_max_beneficiary =  isset($wooPblProductRuleOfUse['product_max_beneficiary']) ?  $wooPblProductRuleOfUse['product_max_beneficiary'] : -1;
            $beneficiaries_options_enabled =  isset($wooPblProductRuleOfUse['beneficiaries_options_enabled']) ?  $wooPblProductRuleOfUse['beneficiaries_options_enabled'] : 0;

            if($beneficiaries_options_enabled == 1) {
                require_once(WC()->plugin_path() . "/includes/admin/wc-meta-box-functions.php");
                
                require(WOO_PBL_DIR . 'views/html-product-beneficiaries-form.php');
            }
        }

        public function add_to_cart_text($text, $product) {
            global $product;
            $wooPblProductRuleOfUse = $this->getProductRuleOfUse($product->get_id());
            $beneficiaries_options_enabled =  isset($wooPblProductRuleOfUse['beneficiaries_options_enabled']) ?  $wooPblProductRuleOfUse['beneficiaries_options_enabled'] : 0;
            
            if ( $beneficiaries_options_enabled == 0 ) {
                return $text;
            } else {
                return _('Book item');
            }
        }

        public function add_to_cart_url( $url, $product )
        {
            global $product;
            $wooPblProductRuleOfUse = $this->getProductRuleOfUse($product->get_id());
            $beneficiaries_options_enabled =  isset($wooPblProductRuleOfUse['beneficiaries_options_enabled']) ?  $wooPblProductRuleOfUse['beneficiaries_options_enabled'] : 0;

            if ( $beneficiaries_options_enabled == 1 ) {
                return $product->get_permalink();
            } else {
                return $url;
            }
        
        }

        public function product_supports( $support, $feature, $product )
        {
            $wooPblProductRuleOfUse = $this->getProductRuleOfUse($product->get_id());
            $beneficiaries_options_enabled =  isset($wooPblProductRuleOfUse['beneficiaries_options_enabled']) ?  $wooPblProductRuleOfUse['beneficiaries_options_enabled'] : 0;

            $requires_beneficiaries = ( $beneficiaries_options_enabled == 1);

            if ( $feature == 'ajax_add_to_cart' && $requires_beneficiaries ) {
                $support = FALSE;
            }
            return $support;
        }

        public function product_class( $classes = array(), $class = false, $product_id = false )
        {
            $wooPblProductRuleOfUse = $this->getProductRuleOfUse($product_id);
            $beneficiaries_options_enabled =  isset($wooPblProductRuleOfUse['beneficiaries_options_enabled']) ?  $wooPblProductRuleOfUse['beneficiaries_options_enabled'] : 0;

            $requires_beneficiaries = ( $beneficiaries_options_enabled == 1);
            
            if ( $requires_beneficiaries ) {
                $classes[] = 'product_requires_beneficiaries';
            }
            return $classes;
        }

        public function is_sold_individually( $value, $_product )
        {
            $wooPblProductRuleOfUse = $this->getProductRuleOfUse($_product->get_id());
            $beneficiaries_options_enabled =  isset($wooPblProductRuleOfUse['beneficiaries_options_enabled']) ?  $wooPblProductRuleOfUse['beneficiaries_options_enabled'] : 0;

            $requires_beneficiaries = ( $beneficiaries_options_enabled == 1);
            
            if ( !$requires_beneficiaries ) {
                return $value;
            } else {
                return true;
            }
        
        }

        public function add_cart_item_data($cart_item_data, $product_id, $variation_id) {
            $wooPblProductRuleOfUse = $this->getProductRuleOfUse($product_id);
            $beneficiaries_options_enabled =  isset($wooPblProductRuleOfUse['beneficiaries_options_enabled']) ?  $wooPblProductRuleOfUse['beneficiaries_options_enabled'] : 0;

            $requires_beneficiaries = ( $beneficiaries_options_enabled == 1);
            if ( $requires_beneficiaries ) {
                $wooPblProductBeneficiariesItem = new WooPBLProductBeneficiariesItem($product_id, $variation_id);
                $beneficiariesList = $wooPblProductBeneficiariesItem->getProductBeneficiariesListFromRequestData();
                $cart_item_data[WOO_PBL_CART_ITEM_KEY] = $beneficiariesList;
            } else {
                $cart_item_data[WOO_PBL_CART_ITEM_KEY] = [];
            }

            return $cart_item_data;
        }
        
        public function get_item_data($item_data, $cart_item) {
            if ( !is_array( $item_data ) ) {
                $item_data = array();
            }

            $wooPblProductRuleOfUse = $this->getProductRuleOfUse($cart_item['product_id']);
            $beneficiaries_options_enabled =  isset($wooPblProductRuleOfUse['beneficiaries_options_enabled']) ?  $wooPblProductRuleOfUse['beneficiaries_options_enabled'] : 0;
            $requires_beneficiaries = ( $beneficiaries_options_enabled == 1);
            

            if ( $requires_beneficiaries ) {
                $beneficiariesList = $cart_item[WOO_PBL_CART_ITEM_KEY];
                foreach ($beneficiariesList as $key => $beneficiary) {
                    $userRow = $beneficiary['first_name'];
                    $userRow .= ' ' . $beneficiary['last_name'];
                    $userRow .= ' ' . $beneficiary['email'];

                    $item_data[] = array(
                        'key'   => _('FullName'),
                        'value' => "<li>$userRow</li>",
                    );
                }
            }

           return $item_data;
        }

        public function before_calculate_totals( $cart )
        {
            if ( is_admin() && !defined( 'DOING_AJAX' ) ) {
                return;
            }
            
            if ( method_exists( $cart, 'get_cart' ) ) {
                $cartContents = $cart->get_cart();
            } else {
                $cartContents = $cart->cart_contents;
            }

            foreach ( $cartContents as $cart_item ) {
                $price = 0.0;
                if(isset( $cart_item[WOO_PBL_CART_ITEM_KEY] )) {
                    $productId = $cart_item['data']->get_id();
                    $wooPblProductRuleOfUse = $this->getProductRuleOfUse($productId);
                    $beneficiaries_options_enabled =  isset($wooPblProductRuleOfUse['beneficiaries_options_enabled']) ?  $wooPblProductRuleOfUse['beneficiaries_options_enabled'] : 0;
                    $requires_beneficiaries = ( $beneficiaries_options_enabled == 1);
                    if($requires_beneficiaries) {
                        $product_price_per_beneficiary = isset($wooPblProductRuleOfUse['product_price_per_beneficiary']) ?  doubleVal($wooPblProductRuleOfUse['product_price_per_beneficiary']) : 0;
                        $price = $product_price_per_beneficiary * count($cart_item[WOO_PBL_CART_ITEM_KEY]);
                        $cart_item['data']->set_price( $price );
                    }
                }
            }

            remove_action( 'woocommerce_before_calculate_totals', array( $this, 'before_calculate_totals' ), 9 );
        }

        public function change_quantity_input( $product_quantity, $cart_item_key, $cart_item ) {
            $productId = $cart_item['data']->get_id();
            $wooPblProductRuleOfUse = $this->getProductRuleOfUse($productId);
            $beneficiaries_options_enabled =  isset($wooPblProductRuleOfUse['beneficiaries_options_enabled']) ?  $wooPblProductRuleOfUse['beneficiaries_options_enabled'] : 0;
            $requires_beneficiaries = ( $beneficiaries_options_enabled == 1);
            
            if ( $requires_beneficiaries ) {
                return '<span>' . $cart_item['quantity'] . '</span>';
            }
        
            return $product_quantity;
        }
    }