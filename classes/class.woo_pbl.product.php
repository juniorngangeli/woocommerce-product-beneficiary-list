<?php
    defined('ABSPATH') or die('You silly human !');

    class WooPBLDbProduct {
        public function init() {
            add_action( 'woocommerce_after_add_to_cart_quantity', [$this, 'productBeneficiariesForm']);
        }

        public function productBeneficiariesForm() {
            global $product, $wpdb;
            $woo_pbl_product_rule_of_use_table = $wpdb->prefix . "woo_pbl_product_rule_of_use";

            $wooPblProductRuleOfUse = $wpdb->get_row(
                $wpdb->prepare(
                    "
                        SELECT *
                        FROM $woo_pbl_product_rule_of_use_table
                        WHERE product_id = %d
                    ",
                    $product->get_id()
                ), ARRAY_A
            );

            $product_price_per_beneficiary = isset($wooPblProductRuleOfUse['product_price_per_beneficiary']) ?  $wooPblProductRuleOfUse['product_price_per_beneficiary'] : null;
            $product_max_beneficiary =  isset($wooPblProductRuleOfUse['product_max_beneficiary']) ?  $wooPblProductRuleOfUse['product_max_beneficiary'] : -1;
            $beneficiaries_options_enabled =  isset($wooPblProductRuleOfUse['beneficiaries_options_enabled']) ?  $wooPblProductRuleOfUse['beneficiaries_options_enabled'] : 0;

            if($beneficiaries_options_enabled == 1) {
                require_once(WC()->plugin_path() . "/includes/admin/wc-meta-box-functions.php");
                
                require(WOO_PBL_DIR . 'views/html-product-beneficiaries-form.php');
            }
        }
    }