<?php
    require_once(WOO_PBL_DIR . 'admin/class.woo_pbl.admin.product.php');
    defined('ABSPATH') or die('You silly human !');

    class WooPBLDbAdmin {
        public function init() {
            add_action('admin_menu', [$this, 'init_admin_menu']);
            add_filter( 'plugin_action_links_' . WOO_PBL_BASENAME, array( $this, 'action_links' ) );

            add_action( 'admin_enqueue_scripts', [$this, 'admin_enqueue_scripts_callback']);
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

            $default_enabled_product_category = $_POST['default_enabled_product_category'];
            $max_beneficiaries_per_product = $_POST['max_beneficiaries_per_product'];

            update_option('max_beneficiaries_per_product', $max_beneficiaries_per_product);
            update_option('default_enabled_product_category', $default_enabled_product_category);

            wp_redirect('admin.php?page=woo-pbl-settings&msg=update');
        }

        function admin_enqueue_scripts_callback(){
            wp_enqueue_style( 'select2-css', 'https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css', array(), '4.1.0-rc.0');
            wp_enqueue_script( 'select2-js', 'https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js', 'jquery', '4.1.0-rc.0');
            wp_enqueue_script( 'select2-init', '/wp-content/plugins/select-2-tutorial/select2-init.js', 'jquery', '4.1.0-rc.0');
        
        }
    }