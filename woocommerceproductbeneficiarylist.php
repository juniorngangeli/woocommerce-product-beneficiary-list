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

require_once(WOO_PBL_DIR . 'classes/class.woo_pbl.db.initializer.php');


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
        $this->initializeWooCommerceHooks();
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

	function initializeWooCommerceHooks()
	{
		//TODO:
	}
}



if (class_exists('WoocommerceProductBeneficiaryList')) {
	$wooPBL = new WoocommerceProductBeneficiaryList();
	$wooPBL->register();

	register_activation_hook(__FILE__, [$wooPBL, 'activate']);
	register_deactivation_hook(__FILE__, [$wooPBL, 'deactivate']);
	register_uninstall_hook(__FILE__, [$wooPBL, 'uninstall']);
}