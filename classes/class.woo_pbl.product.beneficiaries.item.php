<?php
    defined('ABSPATH') or die('You silly human !');

    class WooPBLProductBeneficiariesItem {
        private $product_id;
        function __construct($product_id = false, $variation_id = false)
        {
            if ($variation_id) {
                $this->product_id = $variation_id;
            } else if ($product_id) {
                $this->product_id = $product_id;
            }
        }

        public function getProductBeneficiariesListFromRequestData() {
            $fields = [
                'first_name',
                'last_name',
                'email',
            ];
            $beneficiaries = [];

            foreach($_REQUEST as $key => $values) {
                if(in_array($key, $fields)) {
                    foreach($values as $i => $value) {
                        $beneficiaries[$i][$key] = $value;
                    }
                }
            }
            
            return $beneficiaries;
        }

        private function normalizeProductBeneficiaryItem($item) {
            return [
                "first_name" => isset($item['first_name']) ? $item['first_name'] : '',
                "last_name" => isset($item['last_name']) ? $item['last_name'] : '',
                "email" => isset($item['email']) ? $item['email'] : '',
                "phone_number" => isset($item['phone_number']) ? $item['phone_number'] : '',
                "others" => isset($item['others']) ? $item['others'] : '',
            ];
        }

        public function getByOrderId($order_id) {
            global $wpdb;

            $woo_pbl_product_beneficiary_table = $wpdb->prefix . 'woo_pbl_product_beneficiary';
            return $wpdb->get_results(
                $wpdb->prepare(
                    "
                        SELECT *
                        FROM $woo_pbl_product_beneficiary_table
                        WHERE order_id = %d
                    ",
                    $order_id
                ), ARRAY_A
            );
        }
        public function merge($product_beneficiary_item, $order_id) {
            global $wpdb;

            $woo_pbl_product_beneficiary_table = $wpdb->prefix . 'woo_pbl_product_beneficiary';
            $normalized_item = $this->normalizeProductBeneficiaryItem($product_beneficiary_item);
            $product_beneficiary_item_id = $wpdb->get_var(
                $wpdb->prepare(
                    "
                        SELECT id
                        FROM $woo_pbl_product_beneficiary_table
                        WHERE order_id = %d AND email = %s
                    ",
                    $order_id,
                    $normalized_item['email']
                )
            );

            if(is_null($product_beneficiary_item_id)) {
                $current_time = current_time('mysql', 1);
                $wpdb->insert( 
                    $woo_pbl_product_beneficiary_table, 
                    array( 
                        'order_id' => $order_id, 
                        'first_name' => $normalized_item['first_name'], 
                        'last_name' => $normalized_item['last_name'], 
                        'email' => $normalized_item['email'], 
                        'phone_number' => $normalized_item['phone_number'], 
                        'others' => $normalized_item['others'], 
                        'created_at' => $current_time,
                        'updated_at' => current_time('mysql', 1),
                    ), 
                    array( '%d', '%s','%s', '%s', '%s', '%s', '%s', '%s') 
                );
            } else {
                $wpdb->update( 
                    $woo_pbl_product_beneficiary_table, 
                    [
                        'first_name' => $normalized_item['first_name'], 
                        'last_name' => $normalized_item['last_name'], 
                        'email' => $normalized_item['email'], 
                        'phone_number' => $normalized_item['phone_number'], 
                        'others' => $normalized_item['others'], 
                        'updated_at' => current_time('mysql', 1),
                    ], 
                    ['id' => $product_beneficiary_item_id],
                    ['%s', '%s', '%s','%s','%s', '%s'],
                    ['%d']
                );
            }
        }
        
    }