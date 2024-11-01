
	 jQuery(function($){

 $('#woo_wpinq_phone').mask('0000-000-0000');



$('.variations :input').bind('change', function() {
								$('#woo_wpinq_new_price').css({"display" : "none"});
                                $('button[name=add-to-cart]').attr('style', 'display : none !important');
								$('.single_add_to_cart_button').attr('style', 'display : none !important');
                                $('.quantity').attr('style', 'display : none !important');	
                                $('#woo_wpinq_inquiry_button').attr('style', 'display : block !important');								
});




var classname = $('.single_add_to_cart_button').attr('class');
if (classname.length){
$('#woo_wpinq_inquiry_button').attr('class' , classname);
$('#woo_wpinq_inquiry_button').show();
}else{
classname = $('button[name=add-to-cart]').attr('class');	
$('#woo_wpinq_inquiry_button').attr('class' , classname);
$('#woo_wpinq_inquiry_button').show();
}


$( "#woo_wpinq_close" ).on( "click", function() {
$('#woo_wpinq_modal').css({"display" : "none"});
});




$( "#woo_wpinq_inquiry_button" ).on( "click", function() {




if ($(this).hasClass("wc-variation-selection-needed")){
	return;
}
	
$('.woo_wpinq_spinner').css({"display" : "inline-block"});

	var varid = 0;
	var isvariable = $("input[name='variation_id']").val(); 
    if (isvariable > 0){
		varid = isvariable;
	}

 $.ajax({  
    type: "POST",  
    url: woo_interface.wpajax,  
    data: { 'action' : 'woo_wpinq_request_for_price' , 'pid' : woo_interface.product , 'varid' : varid },
    success: function(textStatus){
	var response = JSON.parse(textStatus);

if (response.result == 'true'){
  var dbid = response.dbid;
	$('#woo_wpinq_modal').css({"display" : "block"});
	$('.woo_wpinq_spinner').css({"display" : "none"});
  var maxtime = parseInt(woo_interface.timer);
  var maxtimesec = maxtime * 60;
  var counter = 59;
  var min = (maxtimesec - 60 ) / 60;
  window.WooVerficountdown = setInterval(function(){
  var sec = counter-- ;   
  document.getElementById("woo_wping_countdown").innerHTML = woo_wpinq_number_style(min.toString()) + " : " + woo_wpinq_number_style(sec.toString()); 
	
                        var ms = new Date().getTime();
                        $.get(woo_interface.jsonfile + "?dummy=" + ms, function(data) {
							var property = 'uniq'+dbid;
							if(data.hasOwnProperty(property)){
								var parsed = data;
								var newprice = parsed[property].price;
								
                        jQuery.ajax({
                                type: "POST"
                                , url: woo_interface.wpajax
                                , data: { 'action': 'woo_wpinq_manage_admin_answer', 'dbid': dbid , 'pid' : parsed[property].pid , 'varid' : parsed[property].varid  , 'price' :  parsed[property].price , 'mode' : parsed[property].mode}
                                , success: function(textStatus) {

                                $('#woo_wpinq_custom_price').val(dbid);								
                               	$('#woo_wpinq_modal').css({"display" : "none"});
                                $('#woo_wpinq_new_price').css({"display" : "block"});
                                $('#woo_wpinq_inquiry_button').attr('style', 'display : none !important');	
								
                                if (newprice == 'OutOFStock'){
								$('.woo_wpinq_new_price').html('<p style="font-size : 13px; margin: 0px">'+woo_interface.outofstock+'</p>');
								}else{	
                                
								if (textStatus === 'CanBuy'){
								$('#woo_wpinq_second_modal_body').remove();	
								$('#woo_wpinq_timeoff_modal_body').remove();	
								$('.single_add_to_cart_button').attr('style', 'display : block !important');								
                                $('button[name=add-to-cart]').attr('style', 'display : block !important');
                                $('.quantity').attr('style', 'display : block !important');
								}
								
								

                                $.event.trigger({
	                              type: "AfterAnswer",
	                              message: "price_fire",
	                              time: new Date()
                                });							
								
                                $('#woo_wpinq_inquiry_button').attr('style', 'display : none !important');											
								$('#woo_wpinq_price_amount').html(newprice.replace(/(\d)(?=(\d\d\d)+(?!\d))/g, "$1,"));
								}
								
								clearInterval(window.WooVerficountdown);
	
                                   $('html, body').animate({
                                     scrollTop: $("#woo_wpinq_scrollable").offset().top - 200
                                   }, 1000) 	
	 
                                }
                                , error: function(MLHttpRequest, textStatus, errorThrown) {
                                        alert(errorThrown);
                                }
                        });									
								
								
								
								
							}
                        }, 'json');
				
	
  if (counter == 0 && min != 0) {
     counter = 59;
	 min = min - 1;
  }  
  
  if (counter == 0 && min == 0) {

     counter = 0;
	 sec = 0;
	 min = 0;
	 clearInterval(window.WooVerficountdown);
	 document.getElementById("woo_wping_countdown").innerHTML = woo_wpinq_number_style(min.toString()) + " : " + woo_wpinq_number_style(sec.toString());
  
                        jQuery.ajax({
                                type: "POST"
                                , url: woo_interface.wpajax
                                , data: { 'action': 'woo_wpinq_times_up', 'dbid': dbid }
                                , success: function(textStatus) {

	 if (woo_interface.forceclose != 'on'){
	 $('#woo_wpinq_close').css({"display" : "block"});								
     }
	 
     $('.dashicons-aghrabe').css({"animation" : "unset"});
	 $('.woo_wpinq_modal-body').css({"font-size" : "15px" , "color" : "black" , "display" : "block"});
	 $('#woo_wpinq_old_body').css({"display" : "none"});
     $('#woo_wpinq_timeoff_modal_body').remove();	 
	 $('#woo_wpinq_second_modal_body').css({"display" : "block"});
	 $('.woo_wpinq_please_wait').css({"animation-name" : "flash" , "animation-duration" : "1s" , "animation-iteration-count" : "2"});
     $('.woo_wpinq_please_wait').html(woo_interface.timesup);
	 $('#woo_wpinq_sumbit').attr( 'data-dbid' , dbid);

                                }
                                , error: function(MLHttpRequest, textStatus, errorThrown) {
                                        alert(errorThrown);
                                }
                        });	 
  }  
  
}, 1000);


}else if(response.result == 'closed'){
	 if (woo_interface.forceclose != 'on'){
	 $('#woo_wpinq_close').css({"display" : "block"});								
     }	
$('.woo_wpinq_spinner').css({"display" : "none"});	
$('#woo_wpinq_second_modal_body').remove();	
$('#woo_wpinq_old_body').remove();
$('.woo_wpinq_please_wait').html(woo_interface.timeoff);		 
$('#woo_wpinq_timeoff_modal_body').css({"display" : "block"});
$('#woo_wpinq_modal').css({"display" : "block"});		
}else{
	
alert('به دلیل مشکلات فنی در حال حاضر بخش استعلام قیمت غیرفعال است. لطفا با مدیریت سایت تماس بگیرید');	
}
	
    },  
    error: function(MLHttpRequest, textStatus, errorThrown){  
        alert(errorThrown);  
    }  
  });		 


  
  
  

  
  
  
  
});







$( ".woo_wpinq_contact_button" ).on( "click", function() {
	var dbid = $(this).attr('data-dbid');
	var name = $('#woo_wpinq_username').val();
	var phone = $('#woo_wpinq_phone').val();
	var err = 0;
    var statusv = 'open';
	
if ($(this).attr('data-dbid')){

  statusv = woo_interface.storestatus;
  
}else{

  statusv = 'close';
}	

	
	var varid = 0;
	var isvariable = $("input[name='variation_id']").val(); 
    if (isvariable > 0){
		varid = isvariable;
	}	
	
	if (name.length == 0){
    err = 1;	
    $('#woo_wpinq_username_err').html('* پرکردن این فیلد الزامیست')

	}

	if (phone.length == 0){
    err = 1;	
    $('#woo_wpinq_phone_err').html('* پرکردن این فیلد الزامیست')
	}	
	
	if (phone.length < 11){
    err = 1;	
    $('#woo_wpinq_phone_err').html('* شماره تماس باید 11 رقمی باشد')

	}	
	
if (err == 0){
$('.woo_wpinq_bounci_spinner').css({"display" : "block"});  

                         jQuery.ajax({
                                type: "POST"
                                , url: woo_interface.wpajax
                                , data: { 'action': 'woo_wpinq_send_user_details', 'dbid': dbid , 'name' : name , 'phone' : phone , 'status' : statusv  , 'pid' : woo_interface.product , 'varid' : varid}
                                , success: function(textStatus) {
								$('.woo_wpinq_bounci_spinner').css({"display" : "none"});	
                                $('.woo_wpinq_modal-body').html('<b style="padding: 8px;display: inline-block;margin-top: 20px;margin-bottom: 20px;" >' + woo_interface.aftersubmit + '</b>');
					            $('#woo_wpinq_close').css({"display" : "block"});
                                }
                                , error: function(MLHttpRequest, textStatus, errorThrown) {
                                        alert(errorThrown);
                                }
                        });
}else{
	return;	
}	
	
	
	
});





});














function woo_wpinq_number_style(number){
	if (number.length === 1){
		return '0' + number;
	}else{
		return number;
	}
}


