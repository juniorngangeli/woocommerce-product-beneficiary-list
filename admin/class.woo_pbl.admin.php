<?php
    defined('ABSPATH') or die('You silly human !');

    class WooPBLDbAdmin {
        public function init() {
            add_action('admin_menu', [$this, 'init_admin_menu']);
            add_filter( 'plugin_action_links_' . WOO_PBL_BASENAME, array( $this, 'action_links' ) );
        }

        public function init_admin_menu() {
            add_menu_page(
                __('Woocommerce Product Beneficiary List', 'woo-pbl'),
                __('Woo PLB', 'woo-pbl'),
                'manage_options',
                'woo-pbl-settings',
                array($this, 'woo_pbl_settings'),
                'dashicons-list-view',
                14
            );
        }

        public function action_links($links) {
            $settings_link = sprintf(
                '<a href="%1$s" aria-label="%2$s">%3$s</a>',
                esc_url( admin_url( 'admin.php?page=woo-pbl-settings' ) ),
                _x( 'View Woocommerce Product Beneficiary List settings', 'aria-label: settings link', 'woo-pbl' ),
                _x( 'Settings', 'plugin action link', 'woo-pbl' )
            );
    
            array_unshift( $links, $settings_link );
    
            return $links;
        }
        
        public function woo_pbl_settings() {

        }
    }