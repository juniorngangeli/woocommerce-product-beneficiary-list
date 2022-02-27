<?php
    defined('ABSPATH') or die('You silly human !');

    require_once( ABSPATH . 'wp-admin/includes/upgrade.php' );

    class WooPBLDbInitializer {
        public function initializeTables() {
            global $wpdb;
            $charset_collate = $wpdb->get_charset_collate();

            $woo_pbl_product_beneficiary_table = $wpdb->prefix . 'woo_pbl_product_beneficiary';
            $sql = "CREATE TABLE $woo_pbl_product_beneficiary_table (
                id bigint(20) NOT NULL AUTO_INCREMENT,
                
                first_name varchar(50) NOT NULL,
                last_name varchar(50) NOT NULL,
                email varchar(100) NULL,
                phone_number varchar(20) NULL,

                others varchar(255) NULL,
                order_id bigint(20) NOT NULL,
                
                was_beneficiary_notified tinyint(1) DEFAULT '0' NOT NULL,

                created_at datetime DEFAULT '0000-00-00 00:00:00' NOT NULL,
                updated_at datetime DEFAULT '0000-00-00 00:00:00' NOT NULL,
                
                PRIMARY KEY  (id)
              ) $charset_collate;";

            $woo_pbl_product_rule_of_use_table = $wpdb->prefix . 'woo_pbl_product_rule_of_use';
            $sql .= "CREATE TABLE $woo_pbl_product_rule_of_use_table (
                id bigint(20) NOT NULL AUTO_INCREMENT,
                product_id bigint(20) NOT NULL,
                product_price_per_beneficiary decimal(10,2) NULL,
                product_max_beneficiary int(10) DEFAULT '-1' NOT NULL,
                beneficiaries_options_enabled tinyint(1) DEFAULT '1' NOT NULL,

                created_at datetime DEFAULT '0000-00-00 00:00:00' NOT NULL,
                updated_at datetime DEFAULT '0000-00-00 00:00:00' NOT NULL,
                
                PRIMARY KEY  (id)
              ) $charset_collate;";
            
            dbDelta( $sql );
        }
        
        public function destroyTables() {
            global $wpdb;
            $woo_pbl_product_beneficiary_table = $wpdb->prefix . 'woo_pbl_product_beneficiary';
            $woo_pbl_product_rule_of_use_table = $wpdb->prefix . 'woo_pbl_product_rule_of_use';
            
            $sql = "DROP TABLE IF EXISTS $woo_pbl_product_beneficiary_table;";
            $sql .= "DROP TABLE IF EXISTS $woo_pbl_product_rule_of_use_table;";
            
            dbDelta( $sql );
        }
    }