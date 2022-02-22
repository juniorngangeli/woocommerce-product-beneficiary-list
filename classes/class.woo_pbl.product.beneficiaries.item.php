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
    }