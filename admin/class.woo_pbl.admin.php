<?php
    require_once(WOO_PBL_DIR . 'admin/class.woo_pbl.admin.product.php');
    defined('ABSPATH') or die('You silly human !');

    class WooPBLDbAdmin {
        public function init() {
            add_action('admin_menu', [$this, 'init_admin_menu']);
            add_filter( 'plugin_action_links_' . WOO_PBL_BASENAME, array( $this, 'action_links' ) );

            add_action('admin_init', array($this, 'process_woo_pbl_settings_form'));

            $WooPBLDbAdminProduct = new WooPBLDbAdminProduct();
            $WooPBLDbAdminProduct->init();
        }

        public function init_admin_menu() {
            add_menu_page(
                __('Woocommerce Product Beneficiary List', 'woo-pbl'),
                __('Woo PLB', 'woo-pbl'),
                'manage_options',
                'woo-pbl-settings',
                array($this, 'woo_pbl_settings_page'),
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
        
        public function woo_pbl_settings_page() {
            require_once(WOO_PBL_DIR . 'admin/views/html-admin-general-options.php');
        }

        public function process_woo_pbl_settings_form() {
            if (!isset($_POST['update_woo_pbl_general_options'])) return false;
            check_admin_referer('nonce_woo_pbl_general_options');

            $order_status_email_trigger = $_POST['order_status_email_trigger'];
            $email_notification_content = $_POST['email_notification_content'];

            update_option('order_status_email_trigger', $order_status_email_trigger);
            update_option('email_notification_content', $email_notification_content);

            wp_redirect('admin.php?page=woo-pbl-settings&msg=update');
        }
    }