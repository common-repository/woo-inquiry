<?php


/////////////////////////////////////////////// SHOP NOTIFICATION  ////////////////////////////////////////////////////////////////////////////////////////////////////////
add_action( 'woocommerce_before_single_product' , 'woo_wpinq_product_page_notic', 5 );	
function woo_wpinq_product_page_notic() {
$woo_wpinq_options = get_option('woo_wpinq_options');	
if ($woo_wpinq_options['woo_wpinq_notic_text'] != ''){
global $product;
$has_inquiry = woo_wpinq_check_product_inquiry_option($product);
if ($has_inquiry){	
echo '<div class="woo_wping_notic">'.$woo_wpinq_options['woo_wpinq_notic_text'].'</div>';
}
}
}
////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////



/////////////////////////////////////////////// ADD NEW BUTTON  ////////////////////////////////////////////////////////////////////////////////////////////////////////
add_action( 'wp' , "woo_wpinq_load_button_position");
function woo_wpinq_load_button_position(){
		if ( class_exists( 'WooCommerce' ) ) { 
		
    add_filter( 'woocommerce_get_price_html', 'woo_wpinq_price_html', 100, 2 );		
		
	if (function_exists('is_product') && is_product()){
		add_filter( 'woocommerce_get_price_html', 'woo_wpinq_price_html_single', 100, 2 );
            add_action( 'woocommerce_after_add_to_cart_button' , 'woo_wpinq_new_button' );			
	}

	if (is_shop() or is_product_category() or is_product_tag()){
		add_action('wp_footer', 'woo_wpinq_footer_scripts_and_styles');
	}	
		}
}




function woo_wpinq_new_button() {
global $product;
$has_inquiry = woo_wpinq_check_product_inquiry_option($product);
if ($has_inquiry){	
$woo_wpinq_options = get_option('woo_wpinq_options');	


/////////if for times set
if (!$woo_wpinq_options['woo_wpinq_store_morning'] or !$woo_wpinq_options['woo_wpinq_store_night']){
		 $current_time = 1;
         $morning = 0;
         $night = 2;		 
}else{
$current_time = current_time('timestamp' , true);
$morning = woo_wpinq_wp_strtotime($woo_wpinq_options['woo_wpinq_store_morning']);
$night = woo_wpinq_wp_strtotime($woo_wpinq_options['woo_wpinq_store_night']);
}

add_action( 'woocommerce_before_add_to_cart_button', 'woo_wpinq_before_add_to_cart_button', 10, 0 );

$current_customer = get_current_user_id();
if ($current_customer != 0){
$user_name = get_user_meta( $current_customer , 'billing_first_name', true ).' '.get_user_meta( $current_customer , 'billing_last_name', true );
$user_phone = get_user_meta( $current_customer , 'billing_phone', true );	

if ($user_name == ''){
	$user_data = get_userdata($current_customer);	
	$user_name = $user_data->first_name.' '.$user_data->last_name;
}
}else{
$user_name = '';
$user_phone = '';	
}


$contact_form = '<br><div class="woo_wpinq_contact_form"><p class="woo_wpinq_label">'.__("Name", 'woo-inquiry').'</p><input class="woo_wpinq_contact_form_field" id="woo_wpinq_username" type="text" value="'.$user_name.'" />
				<b style="font-size : 10px; color:red;" id="woo_wpinq_username_err"></b>
                <br><p class="woo_wpinq_label">'.__("Phone", 'woo-inquiry').'</p>
                <input type="tel" maxlength="11" pattern="[0-9]{4}-[0-9]{3}-[0-9]{4}" class="woo_wpinq_contact_form_field" id="woo_wpinq_phone" value="'.$user_phone.'" /><br>
				<b style="font-size : 10px; color:red;" id="woo_wpinq_phone_err"></b>
				<input class="woo_wpinq_contact_button" style="background:'.$woo_wpinq_options['woo_wpinq_clock_color'] .'!important;" id="woo_wpinq_sumbit" type="button" value="'.__("Submit", 'woo-inquiry').'" >
				</div><br>';
                

								
$maxtime = $woo_wpinq_options['woo_wpinq_maximum_time'];
$notes = do_shortcode($woo_wpinq_options['woo_wpinq_modal_notes']);
$notes = str_replace('{timer}' , '<b style="color :'.$woo_wpinq_options['woo_wpinq_clock_color'].';">'.$maxtime.' '.__("min", 'woo-inquiry').'</b>' , $notes);
$notes = str_replace('{limit}' , '<b style="color :'.$woo_wpinq_options['woo_wpinq_clock_color'].';">'.$woo_wpinq_options['woo_wpinq_shopping_limit'].' '.__("min", 'woo-inquiry').' </b>' , $notes);
$min = woo_wpinq_number_fixer($maxtime);





if ($current_time > $morning and $current_time < $night){
	$store_status = "open";
	
	$main_body = '<div class="woo_wpinq_modal-body" id="woo_wpinq_old_body">
      
	 <div class="woo_wpinq_stopwatch">
        <div class="dashicons-timedore"><div class="dashicons-aghrabe"></div></div>
		<div id="woo_wping_countdown">'.$min.' : 00</div>
      </div>
      
	  <div class="woo_wpinq_maximum_time" >
	  <ul>
	  '.$notes.'
	  </ul>
	  </div>   
	  
	</div>	  
	  
    <div class="woo_wpinq_modal-body" id="woo_wpinq_second_modal_body" style="display:none;">
    <div class="woo_wpinq_times_up_form">'.$woo_wpinq_options['woo_wpinq_timeout_message'].$contact_form.'</div>
	</div><div class="woo_wpinq_bounci_spinner" style="display:none;"><div class="woo_wpinq_double-bounce1"></div><div class="woo_wpinq_double-bounce2"></div></div>
	
    <div class="woo_wpinq_modal-body" id="woo_wpinq_timeoff_modal_body" style="display:none;"> 
    <div class="woo_wpinq_times_up_form">'.$woo_wpinq_options['woo_wpinq_timeoff_message'].$contact_form.'</div>
	</div><div class="woo_wpinq_bounci_spinner" style="display:none;"><div class="woo_wpinq_double-bounce1"></div><div class="woo_wpinq_double-bounce2"></div></div>		
	';
	
}else{
	$store_status = "closed";
	$main_body = '<div class="woo_wpinq_modal-body" id="woo_wpinq_timeoff_modal_body">
    <div class="woo_wpinq_times_up_form">'.$woo_wpinq_options['woo_wpinq_timeoff_message'].$contact_form.'</div>
	</div><div class="woo_wpinq_bounci_spinner" style="display:none;"><div class="woo_wpinq_double-bounce1"></div><div class="woo_wpinq_double-bounce2"></div></div>	
	';
	
	
}


echo '
<button class="woo_wpinq_new_button" style="display:none !important;" type="button" id="woo_wpinq_inquiry_button">'.$woo_wpinq_options['woo_wpinq_add_to_cart_text'].'<div class="woo_wpinq_spinner" style="display:none;"><div class="bounce1"></div><div class="bounce2"></div><div class="bounce3"></div></div></button><br>
<div id="woo_wpinq_modal" class="woo_wping_modal">
  <div class="woo_wpinq_modal-content">
   <div class="woo_wpinq_please_wait">'.__("Please wait ...", 'woo-inquiry').'</div> 
	  
    '.$main_body.'	  	
   
   <div style="text-align:center;">
     <span id="woo_wpinq_close" style="display:none;">'.__("Close", 'woo-inquiry').'</span>
   </div>  
  
  </div>

</div>';



wp_enqueue_style(  'woo_wpinq_animate_css', plugins_url('../assets/css/animate.css', __FILE__));
wp_register_script('woo_wpinq_interface_js', plugins_url('../assets/js/interface.js', __FILE__),array('jquery'),'5.11.13',true);	
        $values = array(
         'timer' => $maxtime,
         'wpajax' => admin_url('admin-ajax.php'),
         'product' => $product->get_id(),
		 'timesup' => __("Admin response time is over!", "woo-inquiry"),
		 'timeoff' => __("The store is closed.", "woo-inquiry"),
		 'storestatus' => $store_status , 
         'jsonfile' => 	plugins_url('../assets/json/callback.json', __FILE__),
         'aftersubmit' => $woo_wpinq_options['woo_wpinq_contact_form_msg'],
         'outofstock' => $woo_wpinq_options['woo_wpinq_outofstock_message'],
         'forceclose' => isset($woo_wpinq_options['woo_wpinq_force_contact_form']) ? $woo_wpinq_options['woo_wpinq_force_contact_form'] : ''		 
        );
        wp_localize_script( 'woo_wpinq_interface_js', 'woo_interface', $values );	   
        wp_enqueue_script('woo_wpinq_interface_js');	
        wp_enqueue_script('woo_wpinq_j_mask', plugins_url('../assets/js/jquery.mask.js', __FILE__),array('jquery'),'',true);			
        wp_register_style('woo_wpinq_dashicons',  plugins_url('../assets/css/dashicon/css/stopwatchi.css', __FILE__));
        wp_enqueue_style('woo_wpinq_dashicons');



woo_wpinq_create_styles();
add_action('wp_footer', 'woo_wpinq_footer_scripts_and_styles');
}	
}



/////////////////shop loop buttons
add_filter( 'woocommerce_loop_add_to_cart_link', 'woo_wpinq_replacing_add_to_cart_button', 10, 2 );
function woo_wpinq_replacing_add_to_cart_button( $button, $product  ) {
$has_inquiry = woo_wpinq_check_product_inquiry_option($product);
if ($has_inquiry){		
    $fclass = explode('class="' , $button);
	$pclass = explode('"' , $fclass[1]);
	$class = $pclass[0];
    $button_text = __("View product", "woo-inquiry");
    $button = '<a class="'.$class.'" href="' . $product->get_permalink() . '">' . $button_text . '</a>';
}
    return $button;
}



/////////////////shop loop price

function woo_wpinq_price_html( $price, $product ){
$has_inquiry = woo_wpinq_check_product_inquiry_option($product);
if ($has_inquiry){		
$woo_wpinq_options = get_option('woo_wpinq_options');		
	$price_style = $woo_wpinq_options['woo_wpinq_price_style_shop'];
	if ($price_style === 'hashur'){
    return '<div class="woo_wpinq_hashuri" style="background-image: repeating-linear-gradient(
        -45deg,
        transparent,
        transparent 4px,
        transparent 1px,
        '.$woo_wpinq_options['woo_wpinq_hashur_color_shop'].' 7px
      ); width: fit-content;">'.$price.'</div>';
	}elseif($price_style === 'transpar'){
	return '<div class="woo_wpinq_transpar" style="opacity : 0.3;">'.$price.'</div>';
	}elseif($price_style === 'normal'){
	return '<div class="woo_wpinq_transpar">'.$price.'</div>';
	}elseif($price_style === 'iconic'){
	return '<div class="woo_wpinq_iconic" style="display: inline-flex;">'.$woo_wpinq_options['woo_wpinq_price_style_shop_icon'].' '.$price.'</div>';	
	}else{
	return '';	
	}
	
}else{
	return $price;
}

}



////////////////////product page price
function woo_wpinq_price_html_single( $price, $product ){
$has_inquiry = woo_wpinq_check_product_inquiry_option($product);
if ($has_inquiry){		
$woo_wpinq_options = get_option('woo_wpinq_options');
	
	return $price.'
	<div id="woo_wpinq_scrollable"></div>
	<div id="woo_wpinq_new_price" style="display:none;">
	<div class="woo_wpinq_new_price" style="display: inline-flex;">'.(isset($woo_wpinq_options['woo_wpinq_price_prefix']) ? esc_attr($woo_wpinq_options['woo_wpinq_price_prefix']) : __("New Price : ", "woo-inquiry")).' 
	<div id="woo_wpinq_price_amount" style="direction: ltr;"></div>
	<div id="woo_wpinq_currency_symbol" style="padding-right: 5px; padding-left: 5px;">'.get_woocommerce_currency_symbol().'</div>
	</div>
    <br><p class="woo_wpinq_new_price_notes">'.(isset($woo_wpinq_options['woo_wpinq_price_inquiry_notics']) ? esc_attr($woo_wpinq_options['woo_wpinq_price_inquiry_notics']) : "").'</p>	
	</div>';
	
}else{
	return $price;
}

}


