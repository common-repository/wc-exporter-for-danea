<?php
/*
WOOCOMMERCE EXPORTER FOR DANEA | FUNZIONI
*/


//EVITO ACCESSO DIRETTO
if ( !defined( 'ABSPATH' ) ) exit;


class WCtoDanea {
	
	//RECUPERO IL VALORE DELL'IVA
	public static function get_tax_rate() {
	
		global $wpdb;
		$query = "SELECT tax_rate, tax_rate_name FROM wp_woocommerce_tax_rates";
		$get_tax = $wpdb->get_results($query, ARRAY_A);
			if($get_tax[0]['tax_rate_name'] == 'IVA') {
				return intval($get_tax[0]['tax_rate']);
			}
	
	}
	
	//GET PRODUCT DISCOUNT
	public static function get_price_details($product_id) {
		
	$product = new WC_Product($product_id);
	$regular_price = $product->get_regular_price();
	$sale_price = $product->get_sale_price();
	$price_no_tax = $product->get_price_excluding_tax();
	
		if( $sale_price != null ) {
			$math = $sale_price * 100 / $regular_price;
			$discount = 100 - $math;
		} else {
			$discount = null;
		}
		return array('price_no_tax' => $price_no_tax, 'regular_price' => $regular_price, 'sale_price' => $sale_price, 'discount' => $discount);
		
	}
	
	//OTTENGO LA CATEGORIA DI APPARTENENZA DEL PRODOTTO
	public static function get_product_category_name($product_id) {
		
		$product_cat = get_the_terms($product_id, 'product_cat');
		if( $product_cat != null ) {
			sort($product_cat);
			$cat = get_object_vars($product_cat[0]);	
			$cat_name = $cat['name'];
		} else {
			$cat_name = null;	
		}
		
		return $cat_name;
		
	}
		
	//URL IMMAGINE PRODOTTO
	public static function get_image_product() {
			
		$thumb_id = get_post_thumbnail_id();
		$thumb_url = wp_get_attachment_image_src($thumb_id, 200, true);
		return $thumb_url[0];
		
	}
	
	
	//RECUPERO L'AUTORE DEL CORSO SENSEI LEGATO AL PRODOTTO WOOCOMMERCE
	public static function get_sensei_author($product_id) {
	
		global $wpdb;
		$query_course = "SELECT post_id
						  FROM wp_postmeta
						  WHERE
						  meta_key = '_course_woocommerce_product'
						  AND meta_value = $product_id
						  ";
						  
		$courses = $wpdb->get_results($query_course);
		if($courses != null) {
		  $course_id = get_object_vars($courses[0]);
		  $author = get_post_field( 'post_author', $course_id['post_id']);
		  return $author;
		}
		
	}
	
	
} //CHIUSURA WCtoDanea