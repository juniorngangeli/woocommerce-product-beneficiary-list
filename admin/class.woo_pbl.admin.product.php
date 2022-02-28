<?php
    defined('ABSPATH') or die('You silly human !');

    class WooPBLDbAdminProduct {
        public function init() {
            add_filter( 'woocommerce_product_data_tabs', [$this, 'addProductBeneficiariesOptionTab'] , 99 , 1 );
            add_action( 'woocommerce_product_data_panels', [$this, 'addProductBeneficiariesOptionDataFields'] );
            add_action( 'woocommerce_process_product_meta', [$this, 'saveProductBeneficiariesOptionData'] );

            add_action(
                'woocommerce_before_order_itemmeta',
                array( $this, 'before_order_item_meta' ),
                10,
                3
            );
        }

        public function addProductBeneficiariesOptionTab($product_data_tabs) {
            $product_data_tabs['beneficiaries-options'] = array(
                'label' => __( 'Beneficiaries options', 'woo-pbl' ),
                'target' => 'beneficiaries_options_options',
                'class' => 'beneficiaries_options',
            );
            return $product_data_tabs;
        }

        public function addProductBeneficiariesOptionDataFields() {
            global $wpdb, $post;
            $woo_pbl_product_rule_of_use_table = $wpdb->prefix . "woo_pbl_product_rule_of_use";
            $wooPblProductRuleOfUse = $wpdb->get_row(
                $wpdb->prepare(
                    "
                        SELECT *
                        FROM $woo_pbl_product_rule_of_use_table
                        WHERE product_id = %d
                    ",
                    $post->ID
                ), ARRAY_A
            );

            
            $product_price_per_beneficiary = isset($wooPblProductRuleOfUse['product_price_per_beneficiary']) ?  $wooPblProductRuleOfUse['product_price_per_beneficiary'] : null;
            $product_max_beneficiary =  isset($wooPblProductRuleOfUse['product_max_beneficiary']) ?  $wooPblProductRuleOfUse['product_max_beneficiary'] : -1;
            $beneficiaries_options_enabled =  isset($wooPblProductRuleOfUse['beneficiaries_options_enabled']) ?  $wooPblProductRuleOfUse['beneficiaries_options_enabled'] : 0;
            require(WOO_PBL_DIR . 'admin/views/html-wc-product-beneficiaries-option-data-fields.php');
        }

        public function saveProductBeneficiariesOptionData($post_id) {
            global $wpdb;
            
            $woo_pbl_product_rule_of_use_table = $wpdb->prefix . "woo_pbl_product_rule_of_use";

            $product_id =  $post_id;
            $product_beneficiaries_option_id = $wpdb->get_var(
                $wpdb->prepare(
                    "
                        SELECT id
                        FROM $woo_pbl_product_rule_of_use_table
                        WHERE product_id = %d
                    ",
                    $product_id
                )
            );

            $product_price_per_beneficiary =  $_POST['product_price_per_beneficiary'];
            $product_max_beneficiary =  $_POST['product_max_beneficiary'];
            $beneficiaries_options_enabled =  $_POST['beneficiaries_options_enabled'] == 'yes' ? 1 : 0;
    

            if(is_null($product_beneficiaries_option_id)) {
                $current_time = current_time('mysql', 1);
                $wpdb->insert( 
                    $woo_pbl_product_rule_of_use_table, 
                    array( 
                        'product_id' => $product_id, 
                        'product_price_per_beneficiary' => $product_price_per_beneficiary, 
                        'product_max_beneficiary' => $product_max_beneficiary, 
                        'beneficiaries_options_enabled' => $beneficiaries_options_enabled, 
                        'created_at' => $current_time,
                        'updated_at' => current_time('mysql', 1),
                    ), 
                    array( '%d', '%d','%d', '%d', '%s', '%s') 
                );
            } else {
                $wpdb->update( 
                    $woo_pbl_product_rule_of_use_table, 
                    [
                        'product_price_per_beneficiary' => $product_price_per_beneficiary, 
                        'product_max_beneficiary' => $product_max_beneficiary,
                        'beneficiaries_options_enabled' => $beneficiaries_options_enabled, 
                        'updated_at' => current_time('mysql', 1),
                    ], 
                    ['id' => $product_beneficiaries_option_id, ],
                    ['%d', '%d', '%d','%s',],
                    ['%d']
                );
            }
        }

        public function before_order_item_meta($item_id, $item, $product) {
            global $wpdb;
            $wooPblProductBeneficiariesItem = new WooPBLProductBeneficiariesItem(null, null);
            $product_beneficiaries_list = $wooPblProductBeneficiariesItem->getByOrderItemId($item->get_order_id());

            require(WOO_PBL_DIR . 'admin/views/html-product-beneficiaries-order-details.php');
        }
    }