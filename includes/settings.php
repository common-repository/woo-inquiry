<?php
function woo_wpinq_setting_page(){
	
	$woo_wpinq_options = get_option('woo_wpinq_options');

	if ($woo_wpinq_options['woo_wpinq_price_style'] === 'hashur'){
		$p_display = 'table-row';
	}else{
		$p_display = 'none';
	}	
	
	if ($woo_wpinq_options['woo_wpinq_price_style_shop'] === 'hashur'){
		$ps_display = 'table-row';
	}else{
		$ps_display = 'none';
	}	

	if ($woo_wpinq_options['woo_wpinq_price_style_shop'] === 'iconic'){
		$psi_display = 'table-row';
	}else{
		$psi_display = 'none';
	}

$woo_wpinq_options = get_option('woo_wpinq_options');
$audio_path = WOO_INQUIRY_PLUGIN_URL."/assets/ring/tone.mp3";
if (isset($woo_wpinq_options['woo_wpinq_web_tone'])){
$audio_path	= $woo_wpinq_options['woo_wpinq_web_tone'];
}	

	
	?> 
	

	
<style>
.custom-price {
    display : <?php echo $p_display;?>;
}
.custom-price-shop {
    display : <?php echo $ps_display;?>;
}
.custom-price-shop-icon {
    display : <?php echo $psi_display;?>;
}
.update-nag{
	display : none;
}
.notice-warning{
	display : none;
}
</style> 

<div class="woo_wpinq_setting_page"> 

 <div id="tabs">
 
   <ul>
    <li><a href="#tabs-1"><?php echo __("General Settings", 'woo-inquiry') ?></a></li>
    <li><a href="#tabs-2"><?php echo __("Notification settings", 'woo-inquiry') ?></a></li>
    <li><a href="#tabs-4"><?php echo __("Customize", 'woo-inquiry') ?></a></li>
  </ul>

  

	 <form action="options.php" method="post">  
		<?php
        	settings_fields('woo-wpinq-settings');
	        do_settings_sections('woo-wpinq-settings');
         ?> 
		 



 
   <div id="tabs-1">
 <table class="form-table">
 

 
 
     <tr>
      <th > <?php echo __("Inquiry button’s text", 'woo-inquiry'); ?> </th> 
       <td> 
       <input class="woo_wpinq_input" id="woo_wpinq_add_to_cart_text" name="woo_wpinq_options[woo_wpinq_add_to_cart_text]" type="text" value="<?php echo esc_attr($woo_wpinq_options['woo_wpinq_add_to_cart_text']); ?>" /><br>
	   </td>	  
	</tr>	

		     <tr>
            <th ><?php echo __('Disable Add to cart', 'woo-inquiry');?> </th>
            <td> 
            <input type="checkbox" name="woo_wpinq_options[woo_wpinq_add_to_cart_allowed]" id="woo_wpinq_add_to_cart_allowed" <?php echo esc_attr($woo_wpinq_options['woo_wpinq_add_to_cart_allowed']) == 'on' ? 'checked="checked"' : '';?> /><?php echo __('Yes', 'woo-inquiry');?>
            <br>
            <p><?php echo __("If this option is enabled, the user can only query the price of the product but can not add the product to the cart after the inquiry.", 'woo-inquiry'); ?></p> 
             </td>
			 </tr>	 
	 
		 	
	
	 <tr>
       <th > <?php echo __("Maximum response time(minute)", 'woo-inquiry'); ?> </th> 
       <td> 
       <input name="woo_wpinq_options[woo_wpinq_maximum_time]" id = "woo_wpinq_maximum_time" type="number" value="<?php echo esc_attr($woo_wpinq_options['woo_wpinq_maximum_time']); ?>" /><?php  echo __('min', 'woo-inquiry');?>
	   </td>	  
	</tr>	

	
	
	 <tr>
       <th > <?php echo __("Tips", 'woo-inquiry'); ?> </th> 
       <td> 
	 <?php
	 $setting = array(
	'textarea_name' => 'woo_wpinq_options[woo_wpinq_modal_notes]',
    'textarea_rows' => 10
    );
	wp_editor( $woo_wpinq_options['woo_wpinq_modal_notes'] , 'woo_wpinq_modal_notes' , $setting );
	?>	   
	<br>
	   <?php echo __("This section is the text shown to user, in waiting modal under the timer, after clicking on the inquiry button.", 'woo-inquiry'); ?>  
	   <br>
       <?php echo __("you can use : ", 'woo-inquiry'); ?> <br>
	   {timer}  =  <?php echo __("response time", 'woo-inquiry'); ?><br>
	   {limit}  =  <?php echo __("time limit for completing the order", 'woo-inquiry'); ?>
	   </td>	  
	</tr>	
	
    	<tr>
            <th ><?php  echo __('Forced contact form?', 'woo-inquiry');?> </th>
            <td> 
            <input type="checkbox" name="woo_wpinq_options[woo_wpinq_force_contact_form]" id="woo_wpinq_force_contact_form" <?php echo esc_attr($woo_wpinq_options['woo_wpinq_force_contact_form']) == 'on' ? 'checked="checked"' : '';?> /><?php echo __('Yes', 'woo-inquiry');?>
            <br>
            <p><?php echo __("If this option is enabled, the option to close the contact form disappears and the user has to fill in the contact form", 'woo-inquiry'); ?></p> 
             </td>
		 </tr>		



	 <tr>
       <th > <?php echo __("No response text", 'woo-inquiry'); ?> </th> 
       <td> 
	   
	 <?php
	 $setting = array(
	'textarea_name' => 'woo_wpinq_options[woo_wpinq_timeout_message]',
    'textarea_rows' => 10
    );
	wp_editor( $woo_wpinq_options['woo_wpinq_timeout_message'] , 'woo_wpinq_timeout_message' , $setting );
	?>	   
    <br>
	            <p><?php echo __("This text will be displayed to the user if you can not set the price for the user within the specified time period.", 'woo-inquiry'); ?></p> 
       
	   </td>	  
	</tr>	
	
	 <tr>
       <th > <?php echo __("Store’s non-working hours text", 'woo-inquiry'); ?> </th> 
       <td> 
	   
	 <?php
	 $setting = array(
	'textarea_name' => 'woo_wpinq_options[woo_wpinq_timeoff_message]',
    'textarea_rows' => 10
    );
	wp_editor( $woo_wpinq_options['woo_wpinq_timeoff_message'] , 'woo_wpinq_timeoff_message' , $setting );
	?>	   
	<br>
	            <p><?php echo __("This text will be displayed to the user during non-working hours of the store , if you set the shop hours.", 'woo-inquiry'); ?></p>	
       
	   </td>	  
	</tr>		
	

	 <tr>
       <th > <?php echo __("Displayed text after submitting contact form", 'woo-inquiry'); ?> </th> 
       <td> 
	 <?php
	 $setting = array(
	'textarea_name' => 'woo_wpinq_options[woo_wpinq_contact_form_msg]',
    'textarea_rows' => 10
    );
	wp_editor( $woo_wpinq_options['woo_wpinq_contact_form_msg'] , 'woo_wpinq_contact_form_msg' , $setting );
	?>	   
      <br>
	            <p><?php echo __("This text will be displayed to the user after the user submit contact form.", 'woo-inquiry'); ?></p>		  
	   </td>	  
	</tr>		
	

	 <tr>
       <th > <?php echo __("Out Of Stock text", 'woo-inquiry'); ?> </th> 
       <td> 
       <textarea rows="5" cols="45" class="woo_wpinq_outofstock_message" id="woo_wpinq_outofstock_message" name="woo_wpinq_options[woo_wpinq_outofstock_message]"><?php echo esc_attr($woo_wpinq_options['woo_wpinq_outofstock_message']); ?></textarea><br>
	            <p><?php echo __("This text will be displayed to the user when use choose 'out of stock' in answer of inquiry", 'woo-inquiry'); ?></p>		   
       
	   </td>	  
	</tr>	

    	<tr>
            <th ><?php  echo __('Out of stock until the end of the working hours', 'woo-inquiry');?> </th>
            <td> 
            <input type="checkbox" name="woo_wpinq_options[woo_wpinq_force_out_of_stock]" id="woo_wpinq_force_out_of_stock" <?php echo esc_attr($woo_wpinq_options['woo_wpinq_force_out_of_stock']) == 'on' ? 'checked="checked"' : '';?> /><?php echo __('Yes', 'woo-inquiry');?>
            <br>
            <p><?php echo __("If this option is enabled, when you click on 'Out of stock' option in first queries' response,The text 'out of stock' will be displayed to all users who inquiry for that product ,Until the end of the working hours", 'woo-inquiry'); ?></p> 
             </td>
		 </tr>
	
	 <tr>
       <th > <?php echo __("Price prefix", 'woo-inquiry'); ?> </th> 
       <td> 
       <input name="woo_wpinq_options[woo_wpinq_price_prefix]"  type="text" value="<?php echo (isset($woo_wpinq_options['woo_wpinq_price_prefix']) ? esc_attr($woo_wpinq_options['woo_wpinq_price_prefix']) : "New Price :"); ?>" />
	   <br>
	   </td>	  
	</tr>		
	
	
    <tr>
   <th></th>
   <td>
    <hr>
   <td>	
	</tr>	
	
	 <tr>
       <th > <?php echo __("Store notice", 'woo-inquiry'); ?> </th> 
       <td> 
       <textarea rows="5" cols="45" class="woo_wpinq_input" id="woo_wpinq_notic_text" name="woo_wpinq_options[woo_wpinq_notic_text]"><?php echo esc_attr($woo_wpinq_options['woo_wpinq_notic_text']); ?></textarea><br>
	   <?php echo __("If you do not want to display the store notification, leave this section blank", 'woo-inquiry'); ?>
	   </td>	  
	</tr>
	
	
	 <tr>
       <th > <?php echo __("Store notice position", 'woo-inquiry'); ?> </th> 
       <td> 	
	   <select id="woo_wpinq_notification_position" name="woo_wpinq_options[woo_wpinq_notification_position]">
         <option value="very-top" <?php selected($woo_wpinq_options['woo_wpinq_notification_position'], "very-top"); ?> ><?php echo __("Top", 'woo-inquiry'); ?></option>	   
         <option value="before-summery" <?php selected($woo_wpinq_options['woo_wpinq_notification_position'], "before-summery"); ?> ><?php echo __("Before Summary", 'woo-inquiry'); ?></option>
         <option value="very-bottom" <?php selected($woo_wpinq_options['woo_wpinq_notification_position'], "very-bottom"); ?> ><?php echo __("Bottom", 'woo-inquiry'); ?></option>	 
       </select>	
	   </td>	  
	</tr>	
	



   <tr>
   <th></th>
   <td>
    <hr>
   <td>	
	</tr>
	
	 <tr>
       <th > <?php echo __("Price display format in product page", 'woo-inquiry'); ?> </th> 
       <td> 	
	   <select id="woo_wpinq_price_style" name="woo_wpinq_options[woo_wpinq_price_style]">
         <option value="normal" <?php selected($woo_wpinq_options['woo_wpinq_price_style'], "normal"); ?> ><?php echo __("Display Price", 'woo-inquiry'); ?></option>	   
         <option value="hidden" <?php selected($woo_wpinq_options['woo_wpinq_price_style'], "hidden"); ?> ><?php echo __("Hide Price", 'woo-inquiry'); ?></option>	   
         <option value="transpar" <?php selected($woo_wpinq_options['woo_wpinq_price_style'], "transpar"); ?> ><?php echo __("Pale price", 'woo-inquiry'); ?></option>
         <option value="hashur" <?php selected($woo_wpinq_options['woo_wpinq_price_style'], "hashur"); ?> ><?php echo __("Hachure text", 'woo-inquiry'); ?></option>		 
       </select>	
	   </td>	  
	</tr> 
 
 	 <tr class="custom-price">
       <th > <?php echo __("Price hachuring color", 'woo-inquiry'); ?> </th> 
       <td> 
       <input name="woo_wpinq_options[woo_wpinq_hashur_color]" id = "woo_wpinq_hashur_color" type="text" value="<?php echo esc_attr($woo_wpinq_options['woo_wpinq_hashur_color']); ?>" class="my-color-field" data-default-color="#effeff" />
	   </td>	  
	</tr>
 
 
 
 	 <tr>
       <th > <?php echo __("Price display format in store page", 'woo-inquiry'); ?> </th> 
       <td> 	
	   <select id="woo_wpinq_price_style_shop" name="woo_wpinq_options[woo_wpinq_price_style_shop]">
         <option value="normal" <?php selected($woo_wpinq_options['woo_wpinq_price_style_shop'], "normal"); ?> ><?php echo __("Display Price", 'woo-inquiry'); ?></option>
         <option value="iconic" <?php selected($woo_wpinq_options['woo_wpinq_price_style_shop'], "iconic"); ?> ><?php echo __("Display Price with prefix", 'woo-inquiry'); ?></option>		 
         <option value="hidden" <?php selected($woo_wpinq_options['woo_wpinq_price_style_shop'], "hidden"); ?> ><?php echo __("Hide Price", 'woo-inquiry'); ?></option>	   
         <option value="transpar" <?php selected($woo_wpinq_options['woo_wpinq_price_style_shop'], "transpar"); ?> ><?php echo __("Pale price", 'woo-inquiry'); ?></option>
         <option value="hashur" <?php selected($woo_wpinq_options['woo_wpinq_price_style_shop'], "hashur"); ?> ><?php echo __("Hachure text", 'woo-inquiry'); ?></option>		 
       </select>	
	   </td>	  
	</tr> 
 
 	 <tr class="custom-price-shop">
       <th > <?php echo __("Price hachuring color", 'woo-inquiry'); ?> </th> 
       <td> 
       <input name="woo_wpinq_options[woo_wpinq_hashur_color_shop]" id = "woo_wpinq_hashur_color_shop" type="text" value="<?php echo esc_attr($woo_wpinq_options['woo_wpinq_hashur_color_shop']); ?>" class="my-color-field" data-default-color="#effeff" />
	   </td>	  
	</tr>
 

 	 <tr class="custom-price-shop-icon">
       <th > <?php echo __("Price prefix", 'woo-inquiry'); ?> </th> 
       <td> 
       <input name="woo_wpinq_options[woo_wpinq_price_style_shop_icon]" id = "woo_wpinq_price_style_shop_icon" type="text" value="<?php echo esc_attr($woo_wpinq_options['woo_wpinq_price_style_shop_icon']); ?>" />
	   </td>	  
	</tr> 
 
 
	


	
	
 </table>	


<?php submit_button(); ?> 
           
		   </div>
		








		
		     <div id="tabs-2">

    <table class="form-table">	
	
	
	 <tr>
       <th ><b class="woo_wpinq_th_header"> <?php echo __("General", 'woo-inquiry'); ?> </b></th> 
       <td> 
       <hr>
	   </td>	  
	</tr>	
	
			 
	
     <tr>
      <th> <?php echo __("Telegram Bot Token", 'woo-inquiry'); ?> </th> 
       <td> 
       <input class="woo_wpinq_input" id="woo_wpinq_bot_token" style="width: 400px;" name="woo_wpinq_options[woo_wpinq_bot_token]" type="text" value="<?php echo esc_attr($woo_wpinq_options['woo_wpinq_bot_token']); ?>" /><button type="button" id="woo_wpinq_active_bot" ><?php echo __('Active Bot', 'woo-inquiry'); ?></button> <p id="woo_wpinq_result_botinfo"></p> <p id="woo_wpinq_result_webhook"></p><br>
	   </td>	  
	</tr>	

	
	<tr>
       <th > <?php echo __("Inquiry request email recipients", 'woo-inquiry'); ?> </th> 
       <td> 
	   <div >
       <textarea style="text-align : left;" rows="2" cols="90" class="woo_wpinq_input" id="woo_wpinq_notif_emails" name="woo_wpinq_options[woo_wpinq_notif_emails]"><?php echo esc_attr($woo_wpinq_options['woo_wpinq_notif_emails']); ?></textarea><br>
	   </div>
	   <?php echo __("enter the recipient emails to receive inquiry request notifications. Separate each email address using a comma and enter as many emails as you want", 'woo-inquiry'); ?>
	   </td>	  
	</tr>


     <tr>
      <th> <?php echo __("Web Notification sound", 'woo-inquiry'); ?> </th> 
       <td> 
            <input class="woo_wpinq_input" id="woo_wpinq_web_tone" style="width: 400px;" name="woo_wpinq_options[woo_wpinq_web_tone]" type="text" value="<?php echo esc_attr($audio_path); ?>" /><input type="button" name="woo_wpinq_upload" id="woo_wpinq_upload" class="button-secondary" value="<?php echo __("Choose", 'woo-inquiry'); ?>">
	   </td>	  
	</tr>		
	
	</table>		
			
			<?php submit_button(); ?> 
			 </div>

			 
			 

			 
		     <div id="tabs-4">
			  
			  <div class="woo_pinq_notic_admin" > <h4>This feature is available only in the premium version.</h4>
			  <p><a href="https://www.codester.com/items/12912/woocommerce-online-price-inquiry?ref=sjafarhosseini007" style="color: black;font-size: 15px;">Buy premium version now</a></p>
			  </div>
			  
              <img style="width: 100%;" src="<?php echo WOO_INQUIRY_PLUGIN_URL."/assets/demo.png";?>" />
			 
			 <?php submit_button(); ?> 
			 </div>
			 
			 


			 
			 
		   </div>  
   
<div style="display:none;">
<input name="woo_wpinq_options[woo_wpinq_clock_color]" type="text" value="#ff3a3a" />
<input name="woo_wpinq_options[woo_wpinq_popup_header_color]" type="text" value="#ffffff" />
<input name="woo_wpinq_options[woo_wpinq_popup_header_background]" type="text" value="#ff4c4c" />
<input name="woo_wpinq_options[woo_wpinq_modal_header_font_size]" type="text" value="18" />
<input name="woo_wpinq_options[woo_wpinq_popup_body_text]" type="text" value="#0c0c0c" />
<input name="woo_wpinq_options[woo_wpinq_popup_body_background]" type="text" value="#f9f9f9" />
<input name="woo_wpinq_options[woo_wpinq_modal_font_size]" type="text" value="13" />
<input name="woo_wpinq_options[woo_wpinq_notification_color]" type="text" value="#ffffff" />
<input name="woo_wpinq_options[woo_wpinq_notification_background]" type="text" value="#2177d3" />
<input name="woo_wpinq_options[woo_wpinq_notif_text_size]" type="text" value="20" />
<input name="woo_wpinq_options[woo_wpinq_notif_font_style]" type="text" value="normal" />
<input name="woo_wpinq_options[woo_wpinq_notif_font_width]" type="text" value="normal" />
<input name="woo_wpinq_options[woo_wpinq_notif_round]" type="text" value="10" />
<input name="woo_wpinq_options[woo_wpinq_notif_padding]" type="text" value="" />
<input name="woo_wpinq_options[woo_wpinq_notif_border_width]" type="text" value="0" />
<input name="woo_wpinq_options[woo_wpinq_notif_border_color]" type="text" value="" />
<input name="woo_wpinq_options[woo_wpinq_notif_border_style]" type="text" value="solid" />
<input name="woo_wpinq_options[woo_wpinq_inquiry_price_text]" type="text" value="#ffffff" />
<input name="woo_wpinq_options[woo_wpinq_inquiry_price_background]" type="text" value="#004cdb" />
<input name="woo_wpinq_options[woo_wpinq_inquiry_price_size]" type="text" value="20" />
<input name="woo_wpinq_options[woo_wpinq_inquiry_price_round]" type="text" value="29" />
<input name="woo_wpinq_options[woo_wpinq_inquiry_price_padding]" type="text" value="10" />
<input name="woo_wpinq_options[woo_wpinq_price_animation]" type="text" value="pulse" />
<input name="woo_wpinq_options[woo_wpinq_price_animation_repeat]" type="text" value="infinite" />
<input name="woo_wpinq_options[woo_wpinq_price_animation_duration]" type="text" value="1" />
<input name="woo_wpinq_options[woo_wpinq_price_border_width]" type="text" value="2" />
<input name="woo_wpinq_options[woo_wpinq_price_border_color]" type="text" value="#ffffff" />
<input name="woo_wpinq_options[woo_wpinq_price_border_style]" type="text" value="solid" />
<input name="woo_wpinq_options[woo_wpinq_custom_styles]" type="text" value="" />
<input name="woo_wpinq_options[woo_wpinq_store_morning]" type="text" value="" />
<input name="woo_wpinq_options[woo_wpinq_store_night]" type="text" value="" />
<input name="woo_wpinq_options[woo_wpinq_shopping_limit]" type="text" value="60" />
</div>   
		   
      </form>  		   
</div>


  <script>
 var hash = window.location.hash;
var chosenmethod = hash.substring(1); 
  jQuery( function() {
    jQuery( "#tabs" ).tabs({
  active: chosenmethod
});
  } );
  
  </script>
  <style>
a#ui-id-5 {
    color: white;
    background: #ff6161;
}  
  </style>
		   
<?php		   
}


          add_action('admin_init', function()
              {
              register_setting('woo-wpinq-settings', 'woo_wpinq_options');  

if (is_admin() && current_user_can('activate_plugins') && !is_plugin_active('woocommerce/woocommerce.php'))
{
          add_action('admin_notices', 'woo_inq_child_plugin_notice');
}

			  
              });
			  
			  
			  
          function woo_inq_child_plugin_notice()
              {
              echo '<div class="notice notice-error is-dismissible"><p>' . __("To use the woo inquiry Plugin, please first install and activate Woocommerce", 'woo-inquiry') . '<a href="https://wordpress.org/plugins/woocommerce/" target="_blank" >Install Woocommerce</a></p></div>';
              }	
			  