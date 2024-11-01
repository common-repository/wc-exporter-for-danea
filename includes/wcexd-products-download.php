<?php
/*
WOOCOMMERCE EXPORTER FOR DANEA | TEMPLATE CSV PRODOTTI
*/


add_action('admin_init', 'wcexd_products_download');

function wcexd_products_download() {

	if($_POST['wcexd-products-hidden'] && wp_verify_nonce( $_POST['wcexd-products-nonce'], 'wcexd-products-submit' )) {

		//INIZIO DOCUMENTO CSV
		header('Pragma: public');
		header('Expires: 0');
		header('Cache-Control: must-revalidate, post-check=0, pre-check=0');
		header('Cache-Control: private', false);
		header('Content-Type: text/csv; charset=utf-8');
		header('Content-Disposition: attachment; filename=wcexd-products-list.csv');
		header("Content-Transfer-Encoding: binary");

		$args = array('post_type' => 'product', 'post_status'=>'publish', 'posts_per_page' => -1);

		$products = new WP_Query($args);
		if($products->have_posts()) :

			$fp = fopen('php://output', 'w');
			
			$list = array('Cod.', 'Descrizione',	'Tipologia', 'Categoria', 'Sottocategoria', 'Cod. Udm',	 
					'Cod. Iva', 'Listino 1',	'Listino 2', 'Listino 3',	 'Formula listino 1',	
					'Formula listino 2',	'Formula listino 3',	'Note', 'Cod. a barre',	'Internet',	
					'Produttore',	'Descriz. web (Sorgente HTML)',	'Pubblicaz. su web',	'Extra 1',	'Extra 2',	
					'Extra 3',	'Extra 4',	'Cod. fornitore',	'Fornitore',	'Cod. prod. forn.', 'Prezzo forn.', 
					'Note fornitura', 'Ord. a multipli di', 'Gg. ordine', 'Scorta min.', 'Ubicazione', 'Tot. q.tà caricata', 
					'Tot. q.tà scaricata', 'Q.tà giacenza', 'Q.tà impegnata', 'Q.tà disponibile', 'Q.tà in arrivo', 'Vendita media mensile	', 
					'Stima data fine magazz.', 'Stima data prossimo ordine', 'Data primo carico', 'Data ultimo carico', 'Data ultimo scarico	', 
					'Costo medio d\'acq.',	 'Ultimo costo d\'acq.',	'Prezzo medio vend.',	'Stato magazzino', 'Immagine'	);
					
			fputcsv($fp, $list);
			
			  while($products->have_posts()) : $products->the_post();
			  
				//RICHIAMO IL SINGOLO "DOCUMENT"
				$product = new WC_Product( get_the_ID() );
				
				//OTTENGO IL NOME DEL FORNITORE (POST AUTHOR)
				if($_POST['sensei'] && ( WCtoDanea::get_sensei_author($product->id) != null) ) {
					
				  $id_fornitore = WCtoDanea::get_sensei_author($product->id);
				  $fornitore = get_userdata($id_fornitore);
				  //Salvo il dato nel database
				  update_option( 'wcexd-sensei-option', 1 ); 

				} else {
					
				  $id_fornitore = $product->post->post_author; 
				  $fornitore = get_userdata($id_fornitore);
				  update_option( 'wcexd-sensei-option', 0 );
				  
				}
				
				//SCORPORO IVA
				$free_tax_price = $product->price/ ( 1 + ( WCtoDanea::get_tax_rate()/ 100 ) );
				
				//TRASFORMO IL FORMATO DEL PREZZO
				$price = round($free_tax_price, 2);
				$prezzo = str_replace('.', ',', $price);
				
				$data = array($product->id, $product->post->post_title,'Articolo', WCtoDanea::get_product_category_name($product->id),'','', WCtoDanea::get_tax_rate(), 
				$prezzo, '','','','','','','','', '','','','','','','', $id_fornitore, $fornitore->first_name . ' ' . $fornitore->last_name,'','','','','','','','','', 
				$product->get_total_stock(),'', $product->get_stock_quantity(),'','','','','','','','','','','','');	
				fputcsv($fp, $data);
			  endwhile;

			fclose($fp);
		endif;

		//FINE DOCUMENTO CSV

		exit;

	}

}