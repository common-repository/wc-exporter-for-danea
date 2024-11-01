<?php
/*
WOOCOMMERCE EXPORTER FOR DANEA | FUNZIONI DI AMMINISTRAZIONE
*/


add_action( 'admin_init', 'wcexd_register_style' );
add_action( 'admin_menu', 'wcexd_add_menu' );

add_action( 'admin_init', 'wcexd_register_js_menu' );
add_action( 'admin_menu', 'wcexd_js_menu' );


//CREONE Wcexd STYLE
function wcexd_register_style() {
	wp_register_style( 'wcexd-style', plugins_url('css/wc-exporter-for-danea.css', 'wc-exporter-for-danea/css'));
}

function wcexd_add_style() {
	wp_enqueue_style( 'wcexd-style');
}


//RICHIAMO SCRIPT JS NECESSARIO ALLA NAVIGAZIONE DEL MENU
function wcexd_register_js_menu() {
	wp_register_script('wcexd-admin-nav', plugins_url('js/wcexd-admin-nav.js', 'wc-exporter-for-danea/js'), array('jquery'), '1.0', true );
}

function wcexd_js_menu() {
	wp_enqueue_script('wcexd-admin-nav');
}


//VOCE DI MENU 
function wcexd_add_menu() {
	$wcexd_page = add_submenu_page( 'woocommerce','WED Options', 'Woocommerce Exporter for Danea', 'manage_options', 'wc-exporter-for-danea', 'wcexd_options');
	
	//Richiamo lo style per Wcexd
	add_action( 'admin_print_styles-' . $wcexd_page, 'wcexd_add_style' );
	//Richiamo lo script per Wcexd
	add_action( 'admin_print_scripts-' . $wcexd_page, 'wcexd_js_menu');
	
	return $wcexd_page;
}

//PAGINA OPZIONI
function wcexd_options() {
	
	//Controllo se l'utente ha i diritti d'accessso necessari
	if ( !current_user_can( 'manage_options' ) )  {
		wp_die( __( 'Sembra che tu non abbia i permessi sufficienti per visualizzare questa pagina.', 'wcexd' ) );
	}


	//INIZIO TEMPLATE DI PAGINA
	echo '<div class="wrap">'; 
	echo '<div class="wrap-left">';
	
	//CONTROLLO SE WOOCOMMERCE E' INSTALLATO
	if ( !class_exists( 'WooCommerce' ) ) { ?>
      <!--Messaggio per l'utente-->
      <div id="message" class="error"><p><strong>
		<?php echo __('ATTENZIONE! Sembra che Woocommerce non sia installato.', 'wcexd' ); ?>
      </strong></p></div>
	<?php exit; 
	} ?>	

	<div id="wcexd-generale">
	<?php
		//HEADER
		echo "<h1 class=\"wcexd main\">" . __( 'Woocommerce Exporter per Danea', 'wcexd' ) . "<span style=\"font-size:60%;\"> 0.9.0</span></h1>";
	?>
	</div>

	        
    <!--LIBRERIA JQUERY-->
	<script type="text/javascript" src="https://ajax.googleapis.com/ajax/libs/jquery/1.7.2/jquery.min.js"></script>
    
	<div class="icon32 icon32-woocommerce-settings" id="icon-woocommerce"><br /></div>
	  <h2 id="wcexd-admin-menu" class="nav-tab-wrapper woo-nav-tab-wrapper">
        <a href="#" data-link="wcexd-fornitori" class="nav-tab"><?php echo __('Fornitori', 'wcexd'); ?></a>
        <a href="#" data-link="wcexd-prodotti" class="nav-tab"><?php echo __('Prodotti', 'wcexd'); ?></a>
        <div class="nav-tab not-available"><?php echo __('Clienti', 'wcexd'); ?></div>    
        <div class="nav-tab not-available"><?php echo __('Ordini', 'wcexd'); ?></div>                                        
	  </h2>
      
      
 <!-- ESPORTAZIONE ELENCO FORNITORI (WORDPRESS USERS) WOOCOMMERCE -->     
      
      <div id="wcexd-fornitori" class="wcexd-admin">

	<?php

	  //Dichiarazione variabili
	  $opt_users_role = 'wcexd-users-role';		
	  $users_field_role = 'wcexd-users-role';
	
	  //Leggo il dato se già esistente nel database
	  $users_val = get_option( $opt_users_role );
  
		echo "<h3 class=\"wcexd\">" . __( 'Esportazione elenco fornitori Woocommerce', 'wcexd' ) . "</h3>";
		echo "<p>" . __( 'L\'importazione dei fornitori in Danea avviene attraverso l\'utilizzo di un file Excel/ OpenOffice. ', 'wcexd' );
		echo "<ul class=\"wcexd\"><li>" . __('Scegli il ruolo utente Wordpress che identifica i tuoi fornitori', 'wcexd' ) . "</li>";
		echo "<li>" . __('Scarica l\'elenco aggiornato dei tuoi fornitori', 'wcexd' ) . "</li>";
		echo "<li>" . __('Apri e salva il file con uno dei programmi sopra indicati.', 'wcexd' ) . "</li>";
		echo "<li>" . __('In Danea, vai in "Fornitori/ Utilità", scegli "Importa con Excel/OpenOffice/LibreOffice" ed utilizza il file appena creato.', 'wcexd' ) . "</li></ul></p>";
		echo "<p>" . __('Per maggiori informazioni, visita questa pagina:', 'wcexd' ) . "</p>";
		echo "<a href=\"http://www.danea.it/software/domustudio/help/index.htm#html/importare_anagrafiche_e_fornitori.htm\" target=\"_blank\">http://www.danea.it/software/domustudio/help/index.htm#html/importare_anagrafiche_e_fornitori.htm</a></p>";
    ?>
    
    <?php 
     global $wp_roles;
     $roles = $wp_roles->get_names();   
	?>
  
    <p>
      
      <label><?php _e("Ruolo utente :", 'wcexd' ); ?></label> 
      <select class="wcexd-users" name="wcexd-users" form="wcexd-suppliers-submit">
          <?php
          
           if($users_val) {
                  echo '<option value=" ' .  $users_val . ' " selected="selected"> ' . $users_val . '</option>';	
                  foreach ($roles as $key => $value) {
                      if($key != $users_val) {
                        echo '<option value=" ' .  $key . ' "> ' . $key . '</option>';
                      }
                  }
                  
            } else {
                echo '<option value="Subscriber" selected="selected">Subscriber</option>';	
                foreach ($roles as $key => $value) {
                    if($key != 'Subscriber') {
                      echo '<option value=" ' .  $key . ' "> ' . $key . '</option>';
                    }
                }
            } 
          ?>
       </select>

     </p>
        
        
    <!--Form Fornitori-->
    <form name="wcexd-suppliers-submit" id="wcexd-suppliers-submit" class="wcexd-form"  method="post" action="">
      <?php wp_nonce_field( 'wcexd-suppliers-submit', 'wcexd-suppliers-nonce'); ?>
      <p class="submit">
      <input type="submit" name="download_csv" class="button-primary" value="<?php _e('Download elenco fornitori (.csv)', 'wcexd' ) ; ?>" />
      </p>
    </form>
 
</div>
    
   
    
    
 <!-- ESPORTAZIONE ELENCO PRODOTTI WOOCOMMERCE -->

   
    <div id="wcexd-prodotti" class="wcexd-admin">
    
    
    <?php   
	  echo "<h3 class=\"wcexd\">" . __( 'Esportazione elenco prodotti Woocommerce', 'wcexd' ) . "</h3>";
	  echo "<p>" . __( 'L\'importazione dei prodotti in Danea avviene attraverso l\'utilizzo di un file Excel/ OpenOffice. ', 'wcexd' );
	  echo "<ul class=\"wcexd\"><li>" . __('Scarica l\'elenco aggiornato dei tuoi prodotti Woocommerce', 'wcexd' ) . "</li>";
	  echo "<li>" . __('Apri e salva il file con uno dei programmi sopra indicati.', 'wcexd' ) . "</li>";
	  echo "<li>" . __('In Danea, vai in "Prodotti/ Utilità", scegli "Importa con Excel/OpenOffice/LibreOffice" ed utilizza il file appena creato.', 'wcexd' ) . "</li></ul></p>";
	  echo "<p>" . __('Per maggiori informazioni, visita questa pagina:', 'wcexd' ) . "</p>";
	  echo "<a href=\"http://www.danea.it/software/easyfatt/ecommerce/specifiche/ricezione_prodotti.asp\" target=\"_blank\">http://www.danea.it/software/easyfatt/ecommerce/specifiche/ricezione_prodotti.asp</a></p>";
    ?>

    <form name="wcexd-products-submit" id="wcexd-products-submit" class="wcexd-form"  method="post" action="">
    
		<?php //SENSEI OPTION
		  if ( class_exists( 'WooThemes_Sensei' ) ) { 
		  echo "<p><div class=\"update-nag\">" . __('Se utilizzi Woothemes Sensei, potresti voler abbinare ogni prodotto dello store all\'autore (Teacher) del corso ad esso associato, importandolo in Danea come fornitore.', 'wcexd' ) . "</div></p>"; 
		  echo "Usa Sensei Teacher come Fornitore  "; ?> 
		  
		  <input type="checkbox" name="sensei" value="sensei" <?php if(get_option('wcexd-sensei-option') == 1) { echo 'checked="checked"'; } ?>/>
        
		<?php } //END SENSEI OPTION?>

      <p class="submit">
      <input type="hidden" name="wcexd-products-hidden" value="1" />
	  <?php wp_nonce_field( 'wcexd-products-submit', 'wcexd-products-nonce'); ?>
      <input type="submit" name="download_csv" class="button-primary" value="<?php _e('Download elenco prodotti (.csv)', 'wcexd' ) ; ?>" />
      </p>
      
    </form>    
    
    </div>


    </div><!--WRAP-LEFT-->
	
	<div class="wrap-right">
		<iframe width="300" height="800" scrolling="no" src="http://www.ilghera.com/images/wed-iframe.html"></iframe>
	</div>
	<div class="clear"></div>
    
  </div>
    
    <?php
    
}