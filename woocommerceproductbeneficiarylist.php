<?php
/*
Plugin Name: Woocommerce Product Beneficiary List
Plugin URI: https://www.kinshasadigital.com
Description: Allows you to associate a list of beneficiaries with a Woocommerce product.
Author: Junior Ngangeli
Version: 1.0.0
Author URI: https://www.linkedin.com/in/junior-ngangeli
Text Domain: woo-pbl
Domain Path: /languages
*/

defined('ABSPATH') or die('You silly human !');

define('WOO_PBL_URL', plugins_url('', __FILE__));
define('WOO_PBL_DIR', plugin_dir_path(__FILE__));
define('WOO_PBL_VER', '1.0.0');
define('WOO_PBL_CART_ITEM_KEY', 'woo_pbl_cart_item_key');

// Define plugin file constant.
if ( ! defined( 'WOO_PBL_FILE' ) ) {
	define( 'WOO_PBL_FILE', __FILE__ );
	define( 'WOO_PBL_BASENAME', plugin_basename( WOO_PBL_FILE ) );
}

require_once(WOO_PBL_DIR . 'classes/class.woo_pbl.db.initializer.php');
require_once(WOO_PBL_DIR . 'admin/class.woo_pbl.admin.php');
require_once(WOO_PBL_DIR . 'classes/class.woo_pbl.product.php');


class WoocommerceProductBeneficiaryList
{
	/**
	 * Construct
	 */
	function __construct()
	{
	}

    public function register()
	{
        $wooPblDbAdmin = new WooPBLDbAdmin();
        $wooPblDbProduct = new WooPBLDbProduct();
		$wooPblDbAdmin->init();
		$wooPblDbProduct->init();

		add_action('init', array($this, 'enqueue_scripts_callback'));
    }

    /**
	 * Install
	 */
	public function activate()
	{
		update_option('woo_pbl_ver', WOO_PBL_VER);
		update_option('woo_pbl_need_flush', true);

        $wooPblDbInitializer = new WooPBLDbInitializer();
        $wooPblDbInitializer->initializeTables();
	}

	public function deactivate()
	{
        //TODO: implement something here.
	}

    /**
	 * Uninstall
	 */
	function uninstall()
	{
		delete_option('woo_pbl_ver');
		delete_option('woo_pbl_need_flush');

        $wooPblDbInitializer = new WooPBLDbInitializer();
        $wooPblDbInitializer->destroyTables();
	}

	function enqueue_scripts_callback(){
		wp_enqueue_style('woo-pbl', WOO_PBL_URL . '/assets/css/woo-pbl.min.css', array(), null);
		wp_enqueue_script( 'woo-pbl', WOO_PBL_URL . '/assets/js/woo-pbl.js', ['jquery'], WOO_PBL_VER, true);
	
	}
}



if (class_exists('WoocommerceProductBeneficiaryList')) {
	$wooPBL = new WoocommerceProductBeneficiaryList();
	$wooPBL->register();

	register_activation_hook(__FILE__, [$wooPBL, 'activate']);
	register_deactivation_hook(__FILE__, [$wooPBL, 'deactivate']);
	register_uninstall_hook(__FILE__, [$wooPBL, 'uninstall']);
}