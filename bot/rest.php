<?php

$updater = json_decode(file_get_contents('php://input'));
global $wpdb;

$user_id = $updater->message->from->id;
$uname = $updater->message->from->username;
$fname = $updater->message->from->first_name;
$lname = $updater->message->from->last_name;
$base_text = $updater->message->text;
$msg_id = $updater->message->message_id;
$chat_id = $updater->callback_query->message->chat->id;
$replay_to = $updater->message->reply_to_message->message_id;
$c_data = $updater->callback_query->data;
$msgid = $updater->callback_query->message->message_id;
$named = $fname . ' ' . $lname;


///convert all input numbers to english
$text = woo_wpinq_english_Numbers($base_text);
$text = woo_wpinq_arabic_Numbers($text);

		$woo_wpinq_options = get_option('woo_wpinq_options');
        $vendor_access = get_option('woo_wpinq_vendors_access_code'); 	

if ($user_id){
$user = $user_id;
}else{
$user = $chat_id;
}	

$item_user = $wpdb->get_row("SELECT * FROM  woo_wpinq_bot_admin where uid = '$user' ", ARRAY_A);




if ($item_user['uid']){



///say to signed-in user : i know u
	if ($text === $vendor_access or $text === $woo_wpinq_options['woo_wpinq_bot_token']){

$info =__("You are currently in the list of bot managers",'woo-inquiry');     
		var_dump(woo_wpinq_makeHTTPRequest('sendMessage' , ['chat_id' => $user , 'text' => $info, 'parse_mode' => 'HTML' ]));	
		
	}


////////////callback mode
if(isset($updater->callback_query)){

$temp = explode("tempprice" , $c_data);
$work = explode("workprice" , $c_data);
$perimary = explode("permprice" , $c_data);
$sms_to_user = explode("SMSTOUSER" , $c_data);

if (is_numeric($temp[0])){
$uniqid = $temp[0];	
}elseif(is_numeric($work[0])){
$uniqid = $work[0];	
}else{
$uniqid = $perimary[0];	
}

if (is_numeric($sms_to_user[1])){
$uniqid = $sms_to_user[0];	
}

$item_requests = $wpdb->get_row("SELECT * FROM  woo_wpinq_requests WHERE id = ".$uniqid);

if ($item_requests->msg != '' and $item_requests->status != 'Timesup'){	



if (is_numeric($temp[0])){
$new_answer = $item_requests->msg.'
⭕️'.__("New Price : ", 'woo-inquiry').number_format($temp[1]).' '.get_woocommerce_currency_symbol().'
✅'.__("Confirmed - Temporary price", 'woo-inquiry');
$price = $temp[1];
$mode = 'temp';
}elseif(is_numeric($work[0])){
$new_answer = $item_requests->msg.'
⭕️'.__("New Price : ", 'woo-inquiry').number_format($work[1]).' '.get_woocommerce_currency_symbol().'
✅'.__("Confirmed - until end of the working hours", 'woo-inquiry');
$price = $work[1];
$mode = 'work';
}else{
	
if ($perimary[1] == 'OutOFStock'){
$stock_force = isset($woo_wpinq_options['woo_wpinq_force_out_of_stock']) ? 	$woo_wpinq_options['woo_wpinq_force_out_of_stock'] : '';				   
if ($stock_force == 'on'){
$new_answer = $item_requests->msg.'
⭕️'.__("New Price : ??", 'woo-inquiry').'
'.__("Status : Out Of Stock", 'woo-inquiry').'
✅'.__("Confirmed - until end of the working hours", 'woo-inquiry');
$price = $perimary[1];
$mode = 'work';	
}else{
$new_answer = $item_requests->msg.'
⭕️'.__("New Price : ??", 'woo-inquiry').'
'.__("Status : Out Of Stock", 'woo-inquiry').'
✅'.__("Confirmed - Temporary price", 'woo-inquiry');
$price = $perimary[1];
$mode = 'temp';	
}
}else{	
	
$new_answer = $item_requests->msg.'
⭕️'.__("New Price : ??", 'woo-inquiry').number_format($perimary[1]).' '.get_woocommerce_currency_symbol().'
✅'.__("Confirmed - permanently" , 'woo-inquiry');
$price = $perimary[1];
$mode = 'perm';

}
}





$json = json_decode(file_get_contents( WOO_INQUIRY_PLUGIN_FILE.'assets/json/callback.json') , true);

$json['uniq'.$uniqid] = array(
   'price' => $price,
   'pid' => $item_requests->product,
   'varid' => $item_requests->varid,
   'mode' => $mode
);

                       $update['status'] = $mode;
					   $update['price'] = $price;
                       $where['id']     = $uniqid;
                       $sql             = $wpdb->update('woo_wpinq_requests', $update, $where);

        $msgids = json_decode($item_requests->msgid);
		$admins = $wpdb->get_results("SELECT * FROM  woo_wpinq_bot_admin");
		foreach($admins as $admins)
			{
	        $adid = $admins->uid;
            woo_wpinq_makeHTTPRequest('editMessageText' , ['chat_id' => $admins->uid, 'text' => $new_answer ,'parse_mode' => 'HTML' , 'message_id' => $msgids->$adid ]);
            }

        $json0 = json_decode(file_get_contents( WOO_INQUIRY_PLUGIN_FILE.'assets/json/delete.json') , true);						
        array_push($json0['Miss'] , $uniqid);				 
		file_put_contents( WOO_INQUIRY_PLUGIN_FILE.'assets/json/delete.json', json_encode($json0));
			
		file_put_contents( WOO_INQUIRY_PLUGIN_FILE.'assets/json/callback.json', json_encode($json));

 
}






//////////string mode
}else{
$search = '"'.$user.'":'.$replay_to;
$con = $wpdb->prepare('msgid LIKE %s' , '%'.$search.'%');	 		
$item_requests = $wpdb->get_row("SELECT * FROM  woo_wpinq_requests WHERE ".$con);

if ($item_requests->msg != '' and $item_requests->status == ''){	
$new_answer = $item_requests->msg.'

⭕️'.__("New Price : ", 'woo-inquiry').number_format($text).' '.get_woocommerce_currency_symbol();
	   if ($woo_wpinq_options['woo_wpinq_store_morning'] and $woo_wpinq_options['woo_wpinq_store_night']){
woo_wpinq_makeHTTPRequest('editMessageText' , ['chat_id' => $user, 'text' => $new_answer ,'parse_mode' => 'HTML' , 'message_id' => $replay_to, 'reply_markup' => 
json_encode(['inline_keyboard' => 
[
[['text' => __("Confirm - Temporary price", 'woo-inquiry') , 'callback_data' => $item_requests->id.'tempprice'.$text]],
[['text' =>__("Confirm - until end of the working hours", 'woo-inquiry'), 'callback_data' => $item_requests->id.'workprice'.$text]]
]
]) ]);

	   }else{
woo_wpinq_makeHTTPRequest('editMessageText' , ['chat_id' => $user, 'text' => $new_answer ,'parse_mode' => 'HTML' , 'message_id' => $replay_to, 'reply_markup' => 
json_encode(['inline_keyboard' => 
[
[['text' =>__("Confirmed - Temporary price", 'woo-inquiry'), 'callback_data' => $item_requests->id.'tempprice'.$text]]
]
]) ]);		   
		   
	   }
}




}



































}else{
	
	////admin part
	
	if (get_option('temp_admin_id_login') == $user){
		$wp_user = get_user_by('email' , $text);
		
		if ($wp_user != false){
		if (implode( ' ' , $wp_user->roles) === 'administrator' or implode( ' ' ,$wp_user->roles) === 'shop_manager'){
		$info =__("Done! You added to the list of admins.",'woo-inquiry');
        delete_option('temp_admin_id_login');       
		var_dump(woo_wpinq_makeHTTPRequest('sendMessage' , ['chat_id' => $user , 'text' => $info, 'parse_mode' => 'HTML' ]));	

        $insert2['uid'] = $user;
        $insert2['wpid'] = $wp_user->ID;		
        $insert2['role'] = implode( ' ' , $wp_user->roles);		
        $sql = $wpdb->insert('woo_wpinq_bot_admin', $insert2);
		$uniq = $wpdb->insert_id;		
		}else{
		$info =__("You do not have permission to access this section. Your user account should have the role of Admin or store manager.",'woo-inquiry');
        delete_option('temp_admin_id_login');       
		var_dump(woo_wpinq_makeHTTPRequest('sendMessage' , ['chat_id' => $user , 'text' => $info, 'parse_mode' => 'HTML' ]));				
		}
		
		
		}else{
		$info = __("This email address does not exist!",'woo-inquiry');
        delete_option('temp_admin_id_login');       
		var_dump(woo_wpinq_makeHTTPRequest('sendMessage' , ['chat_id' => $user , 'text' => $info, 'parse_mode' => 'HTML' ]));			
		}
		
	}	
	
	
		$token = $woo_wpinq_options['woo_wpinq_bot_token'];
	if ($text === $token){
		$info =__("OK , thats correct , Please enter your email address : ",'woo-inquiry');
        update_option('temp_admin_id_login' , $user);       
		var_dump(woo_wpinq_makeHTTPRequest('sendMessage' , ['chat_id' => $user , 'text' => $info, 'parse_mode' => 'HTML' ]));	
	}	


if ($text === '/start'){
$info =__("Hi , If you want to be my manager , Please enter my token :",'woo-inquiry');     
		var_dump(woo_wpinq_makeHTTPRequest('sendMessage' , ['chat_id' => $user , 'text' => $info, 'parse_mode' => 'HTML' ]));	
}	
	
}





?>