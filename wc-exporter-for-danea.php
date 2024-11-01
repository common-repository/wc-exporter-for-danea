<?php
/**
 * Plugin Name: WC Exporter for Danea
 * Plugin URI: http://www.ilghera.com/product/woocommerce-exporter-for-danea/
 * Description: If you've built your online store with Woocommerce and you're using Danea Easyfatt as management software, you definitely need Woocommerce Exporter for Danea!
 * With this Free version you can export the suppliers and the products from your store.
 * With Premium version, you'll be able also to export clients and orders.
 * Author: ilGhera
 * Version: 0.9.0
 * Author URI: http://ilghera.com 
 * Requires at least: 4.0
 * Tested up to: 4.5.2
 */


//EVITO ACCESSO DIRETTO
if ( !defined( 'ABSPATH' ) ) exit;


add_action( 'plugins_loaded', 'load_wc_exporter_for_danea', 100 );	

function load_wc_exporter_for_danea() {

	//INTERNATIONALIZATION
	load_plugin_textdomain('wcexd', false, basename( dirname( __FILE__ ) ) . '/languages' );

	//RICHIAMO FILE NECESSARI
	include( plugin_dir_path( __FILE__ ) . 'includes/wcexd-admin-functions.php');
	include( plugin_dir_path( __FILE__ ) . 'includes/wcexd-functions.php');
	include( plugin_dir_path( __FILE__ ) . 'includes/wcexd-suppliers-download.php');
	include( plugin_dir_path( __FILE__ ) . 'includes/wcexd-products-download.php');

}