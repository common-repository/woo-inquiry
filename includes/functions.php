<?php


//////////bulk_actions
add_filter( 'bulk_actions-edit-product', 'woo_wpinq_register_bulk_actions' );
function woo_wpinq_register_bulk_actions($bulk_actions) {
  $bulk_actions['addtowpinq'] = __( 'Add To Inquiry list', 'woo-inquiry');
  $bulk_actions['removewpinq'] = __( 'Remove From Inquiry list', 'woo-inquiry');  
  return $bulk_actions;
}



add_filter( 'handle_bulk_actions-edit-product', 'woo_wpinq_bulk_action_handler', 10, 3 );
 
function woo_wpinq_bulk_action_handler( $redirect_to, $doaction, $post_ids ) {
  if ( $doaction !== 'addtowpinq' and $doaction !== 'removewpinq') {
    return $redirect_to;
  }
  
  if ( $doaction === 'addtowpinq'){
  foreach ( $post_ids as $post_id ) {
  update_post_meta($post_id , "woo_wpinq_PRICE_INQUIRY", 1);
  }
  }


  if ( $doaction === 'removewpinq'){
  foreach ( $post_ids as $post_id ) {
  delete_post_meta($post_id , "woo_wpinq_PRICE_INQUIRY");
  }
  }  
  
  $redirect_to = add_query_arg( 'bulk_wpinq_posts', count( $post_ids ), $redirect_to );
  return $redirect_to;
}











     add_action('post_submitbox_misc_actions', 'woo_wpinq_createCustomField');
     add_action('save_post', 'woo_wpinq_saveCustomField');

function woo_wpinq_createCustomField()
{
    $post_id = get_the_ID();
  
    if (get_post_type($post_id) != 'product') {
        return;
    }
  
   $value0 = get_post_meta($post_id, 'woo_wpinq_PRICE_INQUIRY', true);

    wp_nonce_field('woo_wpinq_nonce_'.$post_id, 'woo_wpinq_nonce');
    ?>
    <div class="misc-pub-section misc-pub-section-last">
 <label><input type="checkbox" value="1" <?php checked($value0, true, true); ?> name="woo_wpinq_PRICE_INQUIRY" /><?php echo  __('Add To Inquiry list', 'woo-inquiry'); ?></label></br>
    </div>
    <?php
}


function woo_wpinq_saveCustomField($post_id)
{
    if (defined('DOING_AUTOSAVE') && DOING_AUTOSAVE) {
        return;
    }
    
    if (
        !isset($_POST['woo_wpinq_nonce']) ||
        !wp_verify_nonce(sanitize_text_field($_POST['woo_wpinq_nonce']), 'woo_wpinq_nonce_'.$post_id)
    ) {
        return;
    }
    
    if (!current_user_can('edit_post', $post_id)) {
        return;
    }
    if (isset($_POST['woo_wpinq_PRICE_INQUIRY'])) {
        update_post_meta($post_id, 'woo_wpinq_PRICE_INQUIRY', sanitize_text_field($_POST['woo_wpinq_PRICE_INQUIRY']));
    } else {
        delete_post_meta($post_id, 'woo_wpinq_PRICE_INQUIRY');
    }
}









/////////////////product page columns	
			  
add_action( 'manage_product_posts_custom_column', 'woo_wpinq_product_list_column', 10, 2 );

function woo_wpinq_product_list_column( $column, $postid ) {
    if ( $column == 'wpinq' ) {
		if (get_post_meta( $postid, 'woo_wpinq_PRICE_INQUIRY', true )){
        echo '<div style="color: #00c500; font-size: 30px;" class="dashicons dashicons-yes"></div>';
        }
	}
}


add_filter( 'manage_edit-product_columns', 'woo_wpinq_show_product_order',15 );
function woo_wpinq_show_product_order($columns){
   $columns['wpinq'] = 'Inquiry'; 
   return $columns;
}

add_filter( 'manage_edit-product_sortable_columns', 'woo_wpinq_product_order_sortable' );
function woo_wpinq_product_order_sortable( $columns ) {
    $columns['wpinq'] = 'wpinq';
    return $columns;
}	


add_action( 'pre_get_posts', 'woo_wpinq_product_order_by' );
function woo_wpinq_product_order_by( $query ) {
    if( ! is_admin() )
        return;
 
    $orderby = $query->get( 'orderby');
 
    if( 'wpinq' == $orderby ) {
        $query->set('meta_key','woo_wpinq_PRICE_INQUIRY');
        $query->set('orderby','meta_value');
    }
}







 	 add_action('wp_ajax_nopriv_woo_wpinq_request_for_price', 'woo_wpinq_request_for_price');
     add_action( 'wp_ajax_woo_wpinq_request_for_price', 'woo_wpinq_request_for_price' );
    function woo_wpinq_request_for_price(){
	   global $wpdb;
	   $pid = sanitize_text_field($_POST['pid']);
	   $varid = sanitize_text_field($_POST['varid']);
	   $product  = wc_get_product($pid);
	   

	   
	   $woo_wpinq_options = get_option('woo_wpinq_options');
	   $emails= $woo_wpinq_options['woo_wpinq_notif_emails'];
	   
	   
	   ///////////check for store worktime
	   if ($woo_wpinq_options['woo_wpinq_store_morning'] and $woo_wpinq_options['woo_wpinq_store_night']){
	   ////check store status

       $current_time = current_time('timestamp' , true);
       $morning = woo_wpinq_wp_strtotime($woo_wpinq_options['woo_wpinq_store_morning']);
       $night = woo_wpinq_wp_strtotime($woo_wpinq_options['woo_wpinq_store_night']);

       if ($current_time > $morning and $current_time < $night){
	   ///nothig yet
	   }else{	 
       $response['result'] = 'closed';
	   die(json_encode($response));
	   }
	   
	   }

	 
	   /////////check is product exist in jsonfile or not
	   $item_worktimes = $wpdb->get_row("SELECT * FROM  woo_wpinq_worktime_items WHERE pid = $pid and varid = $varid");	
       $check_product = $item_worktimes->uniq;
	   
	   
	   if (!$check_product){
		   
	   if ($varid){
		   $product_var  = wc_get_product($varid);
		   $vari_price =  __("Choosed options", 'woo-inquiry').' : '.urldecode(implode('|',$product_var->get_variation_attributes( ))).'
		   💵'.__("Current price", 'woo-inquiry').' : '.number_format($product_var->get_price()).' '.get_woocommerce_currency_symbol();
		   $insert2['price'] = $product_var->get_price();
		   $pic = get_the_post_thumbnail_url($varid , 'post-thumbnail');
	   }else{
		   $vari_price = '💵'.__("Current price", 'woo-inquiry').' : '.number_format($product->get_price()).' '.get_woocommerce_currency_symbol();
		   $insert2['price'] = $product->get_price();		   
	   }
	   
        $insert2['date'] = current_time('Y:m:d');
		$insert2['time'] = current_time('timestamp' , true);
        $insert2['varid'] = $varid;
        $insert2['product'] = $pid;
        $insert2['ip'] = woo_wpinq_get_user_session_key();		
        $sql = $wpdb->insert('woo_wpinq_requests', $insert2);
		$uniq = $wpdb->insert_id;					   
		
		if (!$pic){
			$pic = get_the_post_thumbnail_url($pid , 'post-thumbnail');
		}
	

/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////send to telegram
	   $text = '<a href="'.$pic.'">🔖</a>'.__("Price Inquiry Of ", 'woo-inquiry').' <a href="'.$product->get_permalink().'" >'.$product->get_name().'</a>
	   '.$vari_price;

		
		$admins = $wpdb->get_results("SELECT * FROM  woo_wpinq_bot_admin WHERE role LIKE 'administrator' or role LIKE 'shop_manager'");
		foreach($admins as $admins)
			{	   
       $msg_id = woo_wpinq_makeHTTPRequest('sendMessage', ['chat_id' => $admins->uid,'parse_mode' => 'HTML' , 'text' => $text, 'reply_markup' => 
            json_encode(['inline_keyboard' => 
            [[['text' => "Out OF Stock", 'callback_data' => $uniq.'permpriceOutOFStock']]]
            ]) ]);
       $admin[$admins->uid] = $msg_id->result->message_id;	   
			}			
///////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////admin web report						
       $json['New'] = array(
               'id' => $uniq
                );
					 
		file_put_contents( WOO_INQUIRY_PLUGIN_FILE.'assets/json/report.json', json_encode($json));	

		
			
			           $update['msg']       = $text;
                       $update['msgid']     = json_encode( $admin);
                       $where['id']         = $uniq;
                       $sql                 = $wpdb->update('woo_wpinq_requests', $update, $where);			

					   setcookie('woo_wpinq_uniq', $uniq , time()+86400*30 , '/');
	

	
	
/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////send email to admins	   
       $message = woo_wpinq_email_template($pic , $product->get_name() , $vari_price , $product->get_permalink() , $multivendor , null);
              if (isset($multivendor) and isset($woo_wpinq_options['woo_wpinq_mail_to_vendor'])){
				  $emails .= ','.$vendor_det->user_email;
			  }
	   
	    $admin_emails = explode(',' , $emails);
        foreach ($admin_emails as $emails){
			$subject = '📣 '.__("Price Inquiry", 'woo-inquiry').' 📣';
			$headers = array('Content-Type: text/html; charset=UTF-8');
             wp_mail( $emails , $subject , $message , $headers);
		 }



////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////	   
	   
	   
	  
	   }else{
        $insert2['date'] = current_time('Y:m:d');		
		$insert2['msgid'] = 'worktime';
		$insert2['msg'] = 'worktime';
		$insert2['varid'] = $varid;
        $insert2['product'] = $pid;
        $insert2['ip'] = woo_wpinq_get_user_session_key();		
        $sql = $wpdb->insert('woo_wpinq_requests', $insert2);
		$uniq = $wpdb->insert_id;
		            
					  setcookie('woo_wpinq_uniq', $uniq , time()+86400*30 , '/');
					  
		$uniq = $check_product;	
        $msg_id	= 1;	
	   }

	   
      $response['result'] = 'true';
	  $response['dbid'] = $uniq;

      die(json_encode($response));
		
	}	




 	 add_action('wp_ajax_nopriv_woo_wpinq_times_up', 'woo_wpinq_times_up');
     add_action( 'wp_ajax_woo_wpinq_times_up', 'woo_wpinq_times_up' );
    function woo_wpinq_times_up(){
	   global $wpdb;
	   $dbid = sanitize_text_field($_POST['dbid']);

                       $update['status']     = 'Timesup';
                       $where['id']         = $dbid;
                       $sql                 = $wpdb->update('woo_wpinq_requests', $update, $where);		

					   
					   
					   
////////////admin web report
        $json = json_decode(file_get_contents( WOO_INQUIRY_PLUGIN_FILE.'assets/json/delete.json') , true);						
        array_push($json['Miss'] , $dbid);				 
		file_put_contents( WOO_INQUIRY_PLUGIN_FILE.'assets/json/delete.json', json_encode($json));
		
					   
$item_requests = $wpdb->get_row("SELECT * FROM  woo_wpinq_requests WHERE id = $dbid");					   
$new_answer = $item_requests->msg;

        $msgids = json_decode($item_requests->msgid);

		foreach($msgids as $admins => $msg_id)
			{
            woo_wpinq_makeHTTPRequest('editMessageText' , ['chat_id' => $admins , 'text' => $new_answer ,'parse_mode' => 'HTML' , 'message_id' => $msg_id  , 'reply_markup' => 
            json_encode(['inline_keyboard' => 
            [[['text' => "🚫 ".__("Missed", 'woo-inquiry')." 🚫", 'callback_data' => 'null']]]
            ]) ]);		
			} 
     die();
	}	

	
	

	
	
	
	
	
	
 	 add_action('wp_ajax_nopriv_woo_wpinq_send_user_details', 'woo_wpinq_send_user_details');
     add_action( 'wp_ajax_woo_wpinq_send_user_details', 'woo_wpinq_send_user_details' );
    function woo_wpinq_send_user_details(){
	   global $wpdb;
	   $dbid = sanitize_text_field($_POST['dbid']);
	   $name = sanitize_text_field($_POST['name']);
	   $phone = str_replace('-','',sanitize_text_field($_POST['phone']));
	   $status = sanitize_text_field($_POST['status']);
	   $req_id = $dbid;
	   
		   
	   

	   
	   
	   
	   if ($status == 'open'){
                       $update['status']    = 'Timesup';
                       $where['id']         = $dbid;
                       $sql                 = $wpdb->update('woo_wpinq_requests', $update, $where);		
				   
        $item_requests = $wpdb->get_row("SELECT * FROM  woo_wpinq_requests WHERE id = $dbid");					   
        $new_answer = $item_requests->msg;
        $msgids = json_decode($item_requests->msgid);

		foreach($msgids as $admins => $msg_id)
			{
				
			$item_roles = $wpdb->get_row("SELECT * FROM  woo_wpinq_bot_admin WHERE uid = $admins");
             

$contact = '
ا======
'.__("User info", 'woo-inquiry').'<br>
'.__("Name : ", 'woo-inquiry').''.$name.'<br>
'.__("Phone : ", 'woo-inquiry').''.$phone;	
		
			
            woo_wpinq_makeHTTPRequest('editMessageText' , ['chat_id' => $admins, 'text' => $new_answer.strip_tags($contact) ,'parse_mode' => 'HTML' , 'message_id' => $msg_id , 'reply_markup' => 
            json_encode(['inline_keyboard' => 
            [[['text' => "🚫 ".__("Missed", 'woo-inquiry')." 🚫", 'callback_data' => 'null']]]
            ]) ]);		
			} 


			
	   }else{
       
	   $woo_wpinq_options = get_option('woo_wpinq_options');
	   $emails= $woo_wpinq_options['woo_wpinq_notif_emails'];
	   
	   $pid = sanitize_text_field($_POST['pid']);
	   $varid = sanitize_text_field($_POST['varid']);
	   $product  = wc_get_product($pid);	   


	   
	   if ($varid){
		   $product_var  = wc_get_product($varid);
		   $vari_price = __("Choosed options", 'woo-inquiry').urldecode(implode('|',$product_var->get_variation_attributes( ))).'
		   💵'.__("Current Price : ", 'woo-inquiry').number_format($product_var->get_price()).' '.get_woocommerce_currency_symbol();
		   $insert2['price'] = $product_var->get_price();
		   $pic = get_the_post_thumbnail_url($varid , 'post-thumbnail');
	   }else{
		   $vari_price = '💵'.__("Current Price : ", 'woo-inquiry').number_format($product->get_price()).' '.get_woocommerce_currency_symbol();
		   $insert2['price'] = $product->get_price();		   
	   }	   
	   
        $insert2['date'] = current_time('Y:m:d');
		$insert2['time']  = current_time('timestamp' , true);
        $insert2['varid'] = $varid;
        $insert2['product'] = $pid;
        $insert2['ip'] = woo_wpinq_get_user_session_key();		
        $sql = $wpdb->insert('woo_wpinq_requests', $insert2);
		$uniq = $wpdb->insert_id;					   
		$req_id = $uniq;
		if (!$pic){
			$pic = get_the_post_thumbnail_url($pid , 'post-thumbnail');
		}
	   
	   
///////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////send to telegram	   
$contact_admins = '
ا======
'.__("User info", 'woo-inquiry').'<br>
'.__("Name : ", 'woo-inquiry').''.$name.'<br>
'.__("Phone : ", 'woo-inquiry').''.$phone;	

	   $text = '<a href="'.$pic.'">🔖</a>'.__("Price Inquiry Of ", 'woo-inquiry').'<a href="'.$product->get_permalink().'" >'.$product->get_name().'</a>
	   '.$vari_price.'
	   
	   '.strip_tags($contact_admins).'
	   
	   😴'.__("Non-working Hours", 'woo-inquiry');
	   
	   
		$admins = $wpdb->get_results("SELECT * FROM  woo_wpinq_bot_admin WHERE role LIKE 'administrator' or role LIKE 'shop_manager'");
		foreach($admins as $admins)
			{	   
       $msg_id = woo_wpinq_makeHTTPRequest('sendMessage', ['chat_id' => $admins->uid,'parse_mode' => 'HTML' , 'text' => $text ]);
       $admin[$admins->uid] = $msg_id->result->message_id;	   
			}

		

        ///send to vendors			
		if (isset($multivendor)){
	
	   $text_sellers = '<a href="'.$pic.'">🔖</a>'.__("Price Inquiry Of ", 'woo-inquiry').'<a href="'.$product->get_permalink().'" >'.$product->get_name().'</a>
	   '.$vari_price.'
	   
	   '.strip_tags($contact_sellers).'
	   
	    😴'.__("Non-working Hours", 'woo-inquiry');	
	   
		$admins = $wpdb->get_results("SELECT * FROM  woo_wpinq_bot_admin WHERE wpid = ".$vendor_id);
		foreach($admins as $admins)
			{	
			
		if (!array_key_exists($admins->uid , $admin)){	
		
       $msg_id = woo_wpinq_makeHTTPRequest('sendMessage', ['chat_id' => $admins->uid,'parse_mode' => 'HTML' , 'text' => $text_sellers ]);

       $admin[$admins->uid] = $msg_id->result->message_id;
	   
		 }	   
			}

		}			
			
            		   $update['status']     = 'Timesoff';
			           $update['msg']       = $text;
                       $update['msgid']     = json_encode( $admin);
                       $where['id']         = $uniq;
                       $sql                 = $wpdb->update('woo_wpinq_requests', $update, $where);			

					   setcookie('woo_wpinq_uniq', $uniq , time()+86400*30 , '/');
			



/////////////////////////////////////////////////////////////////////////////////////////////send email		

       $message = woo_wpinq_email_template($pic , $product->get_name() , $vari_price , $product->get_permalink() , $multivendor , true); 

              if (isset($multivendor) and isset($woo_wpinq_options['woo_wpinq_mail_to_vendor'])){
				  $emails .= ','.$vendor_det->user_email;
			  }
	   
	    $admin_emails = explode(',' , $emails);
        foreach ($admin_emails as $emails){
			$subject = '📣 استعلام قیمت 😴';
			$headers = array('Content-Type: text/html; charset=UTF-8');
             wp_mail( $emails , $subject , $message , $headers);
		}
/////////////////////////////////////////////////////////////////////////////////////////////
		
	   
		   
	   }
	   
	
	    $item_user = $wpdb->get_row("SELECT * FROM  woo_wpinq_users WHERE ip LIKE '".woo_wpinq_get_user_session_key()."'");
		
		if (!isset($item_user->id)){
        $insert['phone'] = $phone;
        $insert['name'] = $name;
        $insert['reqid'] = $req_id;	
        $insert['ip'] = woo_wpinq_get_user_session_key();		
        $sql = $wpdb->insert('woo_wpinq_users', $insert);
		}else{
                       $update2['name']     = $name;			
                       $update2['phone']     = $phone;
                       $update2['reqid']     = $req_id;					   
                       $where2['ip']         = woo_wpinq_get_user_session_key();
                       $sql                 = $wpdb->update('woo_wpinq_users', $update2, $where2);				
		}

	
     die();
	}	
	
	
	
	
	
	
	
	
 	 add_action('wp_ajax_nopriv_woo_wpinq_out_of_stock', 'woo_wpinq_out_of_stock');
     add_action( 'wp_ajax_woo_wpinq_out_of_stock', 'woo_wpinq_out_of_stock' );
    function woo_wpinq_out_of_stock(){
	   global $wpdb;
	   $dbid = sanitize_text_field($_POST['dbid']);

	
$woo_wpinq_options = get_option('woo_wpinq_options');
					   
$item_requests = $wpdb->get_row("SELECT * FROM  woo_wpinq_requests WHERE id = $dbid");					   
$new_answer = $item_requests->msg;					   
$stock_force = isset($woo_wpinq_options['woo_wpinq_force_out_of_stock']) ? 	$woo_wpinq_options['woo_wpinq_force_out_of_stock'] : '';				   
if ($stock_force == 'on'){
$new_answer = $item_requests->msg.'
'.__("New Price : ??? ", 'woo-inquiry').'
'.__("Status : Out Of Stock", 'woo-inquiry').'
'.__("Confirmed - until end of the working hours ✅", 'woo-inquiry');
$price = 'OutOFStock';
$mode = 'work';	
}else{
$new_answer = $item_requests->msg.'
'.__("New Price : ??? ", 'woo-inquiry').'
'.__("Status : Out Of Stock", 'woo-inquiry').'
'.__("Confirmed - Temporary price", 'woo-inquiry');
$price = 'OutOFStock';
$mode = 'temp';	
}					   
	

                       $update['status'] = $mode;
					   $update['price'] = $price;
                       $where['id']         = $dbid;
                       $sql                 = $wpdb->update('woo_wpinq_requests', $update, $where);	
	
////////////admin web report
        $json = json_decode(file_get_contents( WOO_INQUIRY_PLUGIN_FILE.'assets/json/delete.json') , true);						
        array_push($json['Miss'] , $dbid);				 
		file_put_contents( WOO_INQUIRY_PLUGIN_FILE.'assets/json/delete.json', json_encode($json));
		
/////////////update json file
$json1 = json_decode(file_get_contents( WOO_INQUIRY_PLUGIN_FILE.'assets/json/callback.json') , true);

$json1['uniq'.$dbid] = array(
   'price' => $price,
   'pid' => $item_requests->product,
   'varid' => $item_requests->varid,
   'mode' => $mode
);
		
file_put_contents( WOO_INQUIRY_PLUGIN_FILE.'assets/json/callback.json', json_encode($json1));
		
		
        $msgids = json_decode($item_requests->msgid);

		foreach($msgids as $admins => $msg_id)
			{
            woo_wpinq_makeHTTPRequest('editMessageText' , ['chat_id' => $admins , 'text' => $new_answer ,'parse_mode' => 'HTML' , 'message_id' => $msg_id  ]);		
			} 
     die();
	}	
	
	
	
	
	
	
	
	
	
 	 add_action('wp_ajax_nopriv_woo_wpinq_price_answer', 'woo_wpinq_price_answer');
     add_action( 'wp_ajax_woo_wpinq_price_answer', 'woo_wpinq_price_answer' );
    function woo_wpinq_price_answer(){
	   global $wpdb;
	   $dbid = sanitize_text_field($_POST['dbid']);
       $kind = sanitize_text_field($_POST['kind']);
	   $price = sanitize_text_field($_POST['price']);
	

					   
$item_requests = $wpdb->get_row("SELECT * FROM  woo_wpinq_requests WHERE id = $dbid");					   
$new_answer = $item_requests->msg;					   
					   
if ($kind === 'temp'){
$new_answer = $item_requests->msg.'
⭕️'.__("New Price : ", 'woo-inquiry').number_format($price).' '.get_woocommerce_currency_symbol().'
✅'.__("Confirmed - Temporary price", 'woo-inquiry');
$price = $price;
$mode = 'temp';
}else{
$new_answer = $item_requests->msg.'
⭕️'.__("New Price : ", 'woo-inquiry').number_format($price).' '.get_woocommerce_currency_symbol().'
✅'.__("Confirmed - until end of the working hours", 'woo-inquiry');
$price = $price;
$mode = 'work';
}				   
	

                       $update['status'] = $mode;
					   $update['price'] = $price;
                       $where['id']         = $dbid;
                       $sql                 = $wpdb->update('woo_wpinq_requests', $update, $where);	
	
////////////admin web report
        $json = json_decode(file_get_contents( WOO_INQUIRY_PLUGIN_FILE.'assets/json/delete.json') , true);						
        array_push($json['Miss'] , $dbid);				 
		file_put_contents( WOO_INQUIRY_PLUGIN_FILE.'assets/json/delete.json', json_encode($json));
		
/////////////update json file
$json1 = json_decode(file_get_contents( WOO_INQUIRY_PLUGIN_FILE.'assets/json/callback.json') , true);

$json1['uniq'.$dbid] = array(
   'price' => $price,
   'pid' => $item_requests->product,
   'varid' => $item_requests->varid,
   'mode' => $mode
);
		
file_put_contents( WOO_INQUIRY_PLUGIN_FILE.'assets/json/callback.json', json_encode($json1));
		
		
        $msgids = json_decode($item_requests->msgid);

		foreach($msgids as $admins => $msg_id)
			{
            woo_wpinq_makeHTTPRequest('editMessageText' , ['chat_id' => $admins , 'text' => $new_answer ,'parse_mode' => 'HTML' , 'message_id' => $msg_id  ]);		
			} 
     die();
	}	
	
	
	
	
	
	
	
	
	
	
	
 	 add_action('wp_ajax_nopriv_woo_wpinq_reports_table', 'woo_wpinq_reports_table');
     add_action( 'wp_ajax_woo_wpinq_reports_table', 'woo_wpinq_reports_table' );
    function woo_wpinq_reports_table(){
	   file_put_contents( WOO_INQUIRY_PLUGIN_FILE.'assets/json/report.json', '');
	   global $wpdb;
	   $table_row = '';
	   if (isset($_POST['dbid'])){
	   $dbid = sanitize_text_field($_POST['dbid']);	
	   }

       $woo_wpinq_options = get_option('woo_wpinq_options');
       $max_res_time = $woo_wpinq_options['woo_wpinq_maximum_time'] * 60;	   

        $json['Miss'] = array();	   
		file_put_contents( WOO_INQUIRY_PLUGIN_FILE.'assets/json/delete.json', json_encode($json));
		
	    $timediff = current_time('timestamp' , true) - $max_res_time;
        $item_times = $wpdb->get_results("SELECT * FROM  woo_wpinq_requests WHERE status = '' and time < ".$timediff);	
            foreach($item_times as $items){		
                       $update['status']    = 'Timesup';
                       $where['id']         = $items->id;
                       $sql                 = $wpdb->update('woo_wpinq_requests', $update, $where);		   
            }
	   
        $item_requests = $wpdb->get_results("SELECT * FROM  woo_wpinq_requests WHERE status = '' ORDER BY id DESC");					   
   
		foreach ($item_requests as $product){
           $product_simple  = new WC_Product($product->product);  	
		   $name = $product_simple->get_name();
		   
	   if ($product->varid){
		   $product_var  = wc_get_product($product->varid);
		   $name .= ' <b>'.urldecode(implode('|',$product_var->get_variation_attributes( ))).'</b>';
		   $p_price = number_format($product_var->get_price()).' '.get_woocommerce_currency_symbol();
		   $pic = $product_var->get_image(array( 150, 150 ),array(),true);
	   }else{
		   $p_price = number_format($product_simple->get_price()).' '.get_woocommerce_currency_symbol();
		   $pic = $product_simple->get_image( array( 150, 150 ) ,array(),true);		   
	   }		
		
		if (is_numeric($product->ip)){
			$user_data = get_userdata($product->ip);
		$user = '<a href="'.admin_url('user-edit.php?user_id='.$product->ip).'" target="_blank">'.$user_data->first_name.' '.$user_data->last_name.'</a>';	
		}else{
		$user = $product->ip;	
		}
		
		$table_row .= '<tr id="Row'.$product->id.'">
		                                    <td><a href="'.$product_simple->get_permalink().'" >'.$pic.'</a></td>
		                                    <td><a href="'.$product_simple->get_permalink().'" >'.$name.'</a></td>
                                            <td>'.$p_price .'</td>
                                            <td>'.$user.'</td>
                                            <td class="woo_wpinq_actions" style="width: 25%;">
							<button type="button" class="btn btn-success waves-effect" data-id="'.$product->id.'" data-action="resp"><span class="dashicons dashicons-trash"></span><span>'.__("Answer", 'woo-inquiry').'</span></button>
	                        <button type="button" class="btn btn-warning waves-effect" data-id="'.$product->id.'" data-action="stock"><span class="dashicons dashicons-pressthis"></span><span>'.__("Out Of Stock", 'woo-inquiry').'</span></button>
							</td>
		              </tr>';
					  
		}
		
		if (isset($dbid)){
         die($table_row);
		}else{
		 return $table_row;	
		}
	}		
	
	


	
	
	
	
 	 add_action('wp_ajax_nopriv_woo_wpinq_reports_table_timeoff', 'woo_wpinq_reports_table_timeoff');
     add_action( 'wp_ajax_woo_wpinq_reports_table_timeoff', 'woo_wpinq_reports_table_timeoff' );	
    function woo_wpinq_reports_table_timeoff(){
		 die('');
	}	
	






 	 add_action('wp_ajax_nopriv_woo_wpinq_reports_table_timeup', 'woo_wpinq_reports_table_timeup');
     add_action( 'wp_ajax_woo_wpinq_reports_table_timeup', 'woo_wpinq_reports_table_timeup' );
    function woo_wpinq_reports_table_timeup(){
		 die('');
	}


	

 	 add_action('wp_ajax_nopriv_woo_wpinq_reports_table_history', 'woo_wpinq_reports_table_history');
     add_action( 'wp_ajax_woo_wpinq_reports_table_history', 'woo_wpinq_reports_table_history' );
    function woo_wpinq_reports_table_history(){
		 die('');
	}
	
	
 	 add_action('wp_ajax_nopriv_woo_wpinq_users_list', 'woo_wpinq_users_list');
     add_action( 'wp_ajax_woo_wpinq_users_list', 'woo_wpinq_users_list' );	
function woo_wpinq_users_list(){
		 die('');
}	
	
	
	



	
	
	
	

 	 add_action('wp_ajax_nopriv_woo_wpinq_manage_admin_answer', 'woo_wpinq_manage_admin_answer');
     add_action( 'wp_ajax_woo_wpinq_manage_admin_answer', 'woo_wpinq_manage_admin_answer' );
    function woo_wpinq_manage_admin_answer(){
	   global $wpdb;
	   $dbid = sanitize_text_field($_POST['dbid']);
	   $pid = sanitize_text_field($_POST['pid']);
	   $varid = sanitize_text_field($_POST['varid']);
       $price = sanitize_text_field($_POST['price']);
       $mode = sanitize_text_field($_POST['mode']);
    
   $woo_wpinq_options = get_option('woo_wpinq_options');
 
	if ($mode === 'perm'){
		///nothing yet
	}elseif($mode === 'work'){
		
	  
	   ///////////check for store worktime
	   if ($woo_wpinq_options['woo_wpinq_store_morning'] and $woo_wpinq_options['woo_wpinq_store_night']){
		
		$item_worktimes = $wpdb->get_row("SELECT * FROM  woo_wpinq_worktime_items WHERE uniq = $dbid");	
		if (!$item_worktimes->id){
     	$insert2['varid'] = $varid;
        $insert2['pid'] = $pid;
        $insert2['uniq'] = $dbid;	
        $insert2['price'] = $price;			
        $sql = $wpdb->insert('woo_wpinq_worktime_items', $insert2);
		}
		
	   }
	   
	}else{
	//	echo "";
	}
	
	
    if ( $woo_wpinq_options['woo_wpinq_add_to_cart_allowed'] == 'on'){
	die("CanNot");   
	}else{
	die("CanBuy");	
	}
	}

	
	
	
	
	
function woo_wpinq_get_user_session_key(){
$user_id = get_current_user_id();
if ($user_id === 0){	
$wc = new WC_Session_Handler();
$array = $wc->get_session_cookie( );
	return $array[0];
}else{
	return $user_id;
}
}	
	
	


	
	
function woo_wpinq_generate_user_seassion() {
	if ( class_exists( 'WooCommerce' ) ) { 
$wc = new WC_Session_Handler();
if ($wc->has_session() === false){
	$wc->init();
	$wc->set_customer_session_cookie(true);
    $wc->save_data();	
}
	}
}

add_action('init', 'woo_wpinq_generate_user_seassion');	

	
	
    function woo_wpinq_product_custom_price( $cart_item_data, $product_id ) {
	
        if( isset( $_POST['woo_wpinq_custom_price'] ) && !empty($_POST['woo_wpinq_custom_price'])) {	    
            
            $cart_item_data[ "woo_wpinq_custom_price" ] = sanitize_text_field($_POST['woo_wpinq_custom_price']);     
        }
        return $cart_item_data;
        
    }
    
    add_filter( 'woocommerce_add_cart_item_data', 'woo_wpinq_product_custom_price', 99, 2 );



function woo_wpinq_custom_price_to_cart_item( $cart_object ) {  
    if( !WC()->session->__isset( "reload_checkout" )) {
        foreach ( $cart_object->cart_contents as $key => $value ) {
            if( isset( $value["woo_wpinq_custom_price"] ) ) {
				global $wpdb;
				$item_requests = $wpdb->get_row("SELECT * FROM  woo_wpinq_requests WHERE id = ".$value["woo_wpinq_custom_price"]);
                $value['data']->set_price($item_requests->price);
            }
        }  
    }  
}
add_action( 'woocommerce_before_calculate_totals', 'woo_wpinq_custom_price_to_cart_item', 99 );
	

	
	
function woo_wpinq_cart_items_price(  $wc, $cart_object, $cart_item_key ) {
   if (isset($cart_object['woo_wpinq_custom_price'])){
			    global $wpdb;
				$item_requests = $wpdb->get_row("SELECT * FROM  woo_wpinq_requests WHERE id = ".$cart_object["woo_wpinq_custom_price"]); 

	$currency_pos = get_option( 'woocommerce_currency_pos' );
	if ( $currency_pos == 'right') {
			$currency = get_woocommerce_currency_symbol();
			$price = number_format($item_requests->price).''.get_woocommerce_currency_symbol();
	}elseif( $currency_pos == 'right_space'){
			$currency = get_woocommerce_currency_symbol();
			$price = number_format($item_requests->price).' '.get_woocommerce_currency_symbol();
	}elseif( $currency_pos == 'left'){
			$currency = get_woocommerce_currency_symbol();
			$price = get_woocommerce_currency_symbol().''.number_format($item_requests->price);		
	}else{
			$currency = get_woocommerce_currency_symbol();
			$price = get_woocommerce_currency_symbol().' '.number_format($item_requests->price);			
	}

				
  return $price;  
  
   }else{
	 return $wc;  
   }
  }
  add_filter( 'woocommerce_cart_item_price', 'woo_wpinq_cart_items_price' , 10 , 3);	
  
 
 
 
 
 
 

function woo_wpinq_cart_items_line_subtotal(  $wc, $cart_object, $cart_item_key ) {
   if (isset($cart_object['woo_wpinq_custom_price'])){
			    global $wpdb;
				$item_requests = $wpdb->get_row("SELECT * FROM  woo_wpinq_requests WHERE id = ".$cart_object["woo_wpinq_custom_price"]); 


	$currency_pos = get_option( 'woocommerce_currency_pos' );
	if ( $currency_pos == 'right') {
			$currency = get_woocommerce_currency_symbol();
			$price = number_format((int)$item_requests->price * $cart_object['quantity']).''.get_woocommerce_currency_symbol();
	}elseif( $currency_pos == 'right_space'){
			$currency = get_woocommerce_currency_symbol();
			$price = number_format((int)$item_requests->price * $cart_object['quantity']).' '.get_woocommerce_currency_symbol();
	}elseif( $currency_pos == 'left'){
			$currency = get_woocommerce_currency_symbol();
			$price = get_woocommerce_currency_symbol().''.number_format((int)$item_requests->price * $cart_object['quantity']);		
	}else{
			$currency = get_woocommerce_currency_symbol();
			$price = get_woocommerce_currency_symbol().' '.number_format((int)$item_requests->price * $cart_object['quantity']);			
	}

				
  return $price;    
   }else{
	 return $wc;  
   }
  } 
  
  add_filter( 'woocommerce_cart_item_subtotal', 'woo_wpinq_cart_items_line_subtotal' , 10 , 3);	
	




	
	
	
function woo_wpinq_before_add_to_cart_button(  ) { 
global $product;
$has_inquiry = woo_wpinq_check_product_inquiry_option($product);
if ($has_inquiry){
    echo '<input type="hidden" name="woo_wpinq_custom_price" id="woo_wpinq_custom_price" value="0" />';
}
}	
add_action( 'woocommerce_before_add_to_cart_button', 'woo_wpinq_before_add_to_cart_button', 10, 0 ); 	







function woo_wpinq_woocommerce_check_cart_items(  ) { 
    global $wpdb;
    global $woocommerce;

	$current_session = woo_wpinq_get_user_session_key();
	
	$item_requests = $wpdb->get_results("SELECT * FROM  woo_wpinq_requests WHERE ip = '$current_session'");
    
	$woo_wpinq_options = get_option('woo_wpinq_options');
	
	$timeup = $woo_wpinq_options['woo_wpinq_shopping_limit'];
	$user_limit = $timeup * 60;
	$current_time = current_time('timestamp' , true);	
	
	foreach($item_requests as $items){
	$request_time = $items->time;
	$diffrence = $current_time - (int)$request_time;
	if ( $diffrence > $user_limit){
    $woocommerce->cart->remove_cart_item($items->cart_key);
	}
	}
} 
add_action( 'woocommerce_check_cart_items', 'woo_wpinq_woocommerce_check_cart_items', 10, 0 ); 






function woo_wpinq_woocommerce_add_to_cart( $cart_item_key, $product_id, $quantity, $variation_id, $variation, $cart_item_data ) { 
 if ($cart_item_key){
	$has_inquiry = get_post_meta($product_id , 'woo_wpinq_PRICE_INQUIRY', true);

    if ($has_inquiry){	 
                       global $wpdb;
	                   $update['cart_key']  = $cart_item_key;
					   $update['time']  = current_time('timestamp' , true);
                       $where['id']     = $_COOKIE['woo_wpinq_uniq'];
                       $sql             = $wpdb->update('woo_wpinq_requests', $update, $where);
    }
 }
}
          
add_action( 'woocommerce_add_to_cart', 'woo_wpinq_woocommerce_add_to_cart', 10, 6 ); 




/////delete controler
 	 add_action('wp_ajax_nopriv_woo_wpinq_delete_db', 'woo_wpinq_delete_db');
     add_action( 'wp_ajax_woo_wpinq_delete_db', 'woo_wpinq_delete_db' );
    function woo_wpinq_delete_db(){
	   global $wpdb;
	   $kind = sanitize_text_field($_POST['kind']);

	   if ($kind === 'users'){
		   	   	          $wpdb->query('TRUNCATE TABLE woo_wpinq_users');	
	   
	   }elseif($kind === 'history'){
		   	   	          $wpdb->query('TRUNCATE TABLE woo_wpinq_requests');		   
	   }elseif($kind === 'timeoff'){
		   	   	          $wpdb->query("DELETE FROM woo_wpinq_requests WHERE status LIKE 'Timesoff'");
	   }else{
		   	   	          $wpdb->query("DELETE FROM woo_wpinq_requests WHERE status LIKE 'Timesup'");
	   }	   
	   

	die("down");   
	}         



    function woo_wpinq_number_fixer($number){
		if (strlen($number) > 1){
			return $number;
		}else{
			return '0'.$number;
		}
	}

	function woo_wpinq_persian_Numbers($srting)
{
    $en_num = array('0','1','2','3','4','5','6','7','8','9');
    $fa_num = array('۰','۱','۲','۳','۴','۵','۶','۷','۸','۹');
    return str_replace($en_num, $fa_num, $srting);

}


	function woo_wpinq_english_Numbers($srting)
{
    $en_num = array('0','1','2','3','4','5','6','7','8','9');
    $fa_num = array('۰','۱','۲','۳','۴','۵','۶','۷','۸','۹');
    return str_replace($fa_num, $en_num, $srting);

}


	function woo_wpinq_arabic_Numbers($srting)
{
    $en_num = array('0','1','2','3','4','5','6','7','8','9');
    $fa_num = array('٠' , '١'  , '٢' , '٣' , '٤' , '٥' , '٦' , '٧' , '٨' , '٩');
    return str_replace($fa_num, $en_num, $srting);

}	


	
   function woo_wpinq_check_product_inquiry_option($product){

$has_cat = 0;
$categ = $product->get_category_ids();
foreach($categ as $cat){
	$has_cat_tick = get_term_meta($cat , 'woo_wpinq_PRICE_INQUIRY', true);
	if ($has_cat_tick){
		$has_cat = 1;
	}
}

$has_tag = 0;
$tags = $product->get_tag_ids();
foreach($tags as $tag){
	$has_tag_tick = get_term_meta($tag , 'woo_wpinq_PRICE_INQUIRY', true);
	if ($has_tag_tick){
		$has_tag = 1;
	}
}

$has_inquiry = get_post_meta($product->get_id(), 'woo_wpinq_PRICE_INQUIRY', true);   
	
if ($has_inquiry or $has_tag or $has_cat){	
  return true;
}else{
  return '';	
}
	
   }
   
   
   
   


 

 

 
function woo_wpinq_email_template($pic , $pname , $price , $plink , $multivendor , $night){
	
	if (!isset($multivendor)){
		
		$linkinquiry = '<a class ="woo_wpinq_link_button" style=" background: #00ff7e; padding: 10px; font-size: 15px; color: black; text-decoration: unset; font-weight: 900; border-radius: 50px; margin: 20px; border: unset;" href="'.admin_url('admin.php?page=woo_wpinquiry_reports').'" target="_blank">'.__("Answer Now", 'woo-inquiry').'</a><br><br><br> ';
		
	}else{
		
		$linkinquiry = '';
		
	}
	
    if (isset($night)){
       $time = '<p style="color:red; font-weight:900;">'.__("😴 non-working hours request", 'woo-inquiry').'</p>';
	}else{
       $time = '<p style="color:red; font-weight:900;">'.__("Please follow the user's request as soon as possible", 'woo-inquiry').'</p>';
	}	
	
       $html = '
	   <html>
	   <header>
	   </header>
	   <body style="text-align:center;">
	   <img width = "250" src="'.$pic.'" height= "250">
	   <br><b>'.__("Product : ", 'woo-inquiry').$pname.'<b>
	   <br><b>'.$price.'
	   <b><br><br><br>
        '.$linkinquiry.'
	   <a class ="woo_wpinq_link_button" style=" background: #3300ff; padding: 10px; font-size: 15px; color: white; text-decoration: unset; font-weight: 900; border-radius: 50px; margin: 20px; border: unset;" href="'.$plink.'">'.__("View Product", 'woo-inquiry').'</a>
	   
	   <br><br>
	   '.$time.'
	   </body>
	   <footer>
	   </footer>
	   </html>';
    return $html;

} 
 

 
 
function woo_wpinq_shortcode_replacer($msg , $product , $product_var){

		   $product_id = $product->get_id();
		   
	   if ($product_var){
		   $msg = str_replace('{Product_sale_price}' , number_format($product_var->get_price()).' '.html_entity_decode(get_woocommerce_currency_symbol()) , $msg); 
		   $msg = str_replace('{Product_name}' , $product->get_name().' '.urldecode(implode('|', $product_var->get_variation_attributes( ))) , $msg); 		   
	   }else{	
           $msg = str_replace('{Product_sale_price}' , number_format($product->get_price()).' '.html_entity_decode(get_woocommerce_currency_symbol()) , $msg);		   
		   $msg = str_replace('{Product_name}' , $product->get_name() , $msg); 
		   }

		 $msg = str_replace('{Product_id}' , $product_id , $msg);		   
		 $msg = str_replace('{Product_shorturl}' , wp_get_shortlink($product_id) , $msg); 		   
		 $msg = str_replace('{Product_permalink}' , $product->get_permalink() , $msg); 		   

		 
		 return $msg;
}	





	

function woo_wpinq_create_message_before_send($text , $product_id , $product_var_id , $username){
    $product  = wc_get_product($product_id);	
	if ($product_var_id){
    $product_var = wc_get_product($product_var_id);
	}else{
	$product_var = '';	
	}
	$woo_wpinq_options = get_option('woo_wpinq_options');
	
	$msg = $woo_wpinq_options['woo_wpinq_user_sms_body'];

    $new_msg = woo_wpinq_shortcode_replacer($msg , $product , $product_var);

    $new_msg = str_replace('{Admin_answer}' , $text , $new_msg);	
    $new_msg = str_replace('{user_name}' , $username , $new_msg);	   
    
	return $new_msg;
}





function woo_wpinq_wp_strtotime($str) {
  // This function behaves a bit like PHP's StrToTime() function, but taking into account the Wordpress site's timezone
  // CAUTION: It will throw an exception when it receives invalid input - please catch it accordingly
  // From https://mediarealm.com.au/
  
  $tz_string = get_option('timezone_string');
  $tz_offset = get_option('gmt_offset', 0);
  
  if (!empty($tz_string)) {
      // If site timezone option string exists, use it
      $timezone = $tz_string;
  
  } elseif ($tz_offset == 0) {
      // get UTC offset, if it isn’t set then return UTC
      $timezone = 'UTC';
  
  } else {
      $timezone = $tz_offset;
      
      if(substr($tz_offset, 0, 1) != "-" && substr($tz_offset, 0, 1) != "+" && substr($tz_offset, 0, 1) != "U") {
          $timezone = "+" . $tz_offset;
      }
  }
  
  $datetime = new DateTime($str, new DateTimeZone($timezone));
  return $datetime->format('U');
}



function woo_wpinq_makeHTTPRequest($method, $datas = [])
	{
		$woo_wpinq_options = get_option('woo_wpinq_options');
		
		$token = $woo_wpinq_options['woo_wpinq_bot_token'];

		$url = "https://api.telegram.org/bot" . $token . "/" . $method;	

		$ch = curl_init();
		curl_setopt($ch, CURLOPT_URL, $url);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
		curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query($datas));
		$res = curl_exec($ch);
		if (curl_error($ch))
			{
				var_dump(curl_error($ch));
			}
		else
			{
				return json_decode($res);
			}
	}	
