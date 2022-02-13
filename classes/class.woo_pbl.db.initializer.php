<?php
    defined('ABSPATH') or die('You silly human !');

    require_once( ABSPATH . 'wp-admin/includes/upgrade.php' );

    class WooPBLDbInitializer {
        public function initializeTables() {
            global $wpdb;
            $charset_collate = $wpdb->get_charset_collate();

            $sql = "CREATE TABLE woo_pbl_product_beneficiary (
                id bigint(20) NOT NULL AUTO_INCREMENT,
                
                first_name varchar(50) NOT NULL,
                last_name varchar(50) NOT NULL,
                email varchar(100) NULL,
                phone_number varchar(20) NULL,
                others varchar(255) NULL,
                
                created_at datetime DEFAULT '0000-00-00 00:00:00' NOT NULL,
                updated_at datetime DEFAULT '0000-00-00 00:00:00' NOT NULL,
                
                PRIMARY KEY  (id)
              ) $charset_collate;";

            $sql .= "CREATE TABLE woo_pbl_product_rule_of_use (
                id bigint(20) NOT NULL AUTO_INCREMENT,
                product_id bigint(20) NOT NULL,
                product_price_per_beneficiary decimal(10,2) NULL,
                product_max_beneficiary int(10) DEFAULT '-1' NULL,

                created_at datetime DEFAULT '0000-00-00 00:00:00' NOT NULL,
                
                PRIMARY KEY  (id)
              ) $charset_collate;";
            
            dbDelta( $sql );
        }
        
        public function destroyTables() {
            $sql = "DROP TABLE IF EXISTS woo_pbl_product_beneficiary;";
            $sql .= "DROP TABLE IF EXISTS woo_pbl_product_rule_of_use;";
            dbDelta( $sql );
        }
    }