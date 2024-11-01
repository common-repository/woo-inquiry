jQuery(document).ready(function($){
	

    $('#woo_wpinq_upload').click(function(e) {
        e.preventDefault();
        var audio = wp.media({ 
            title: woo_admin.wpmedia,
			library: { type: "audio"},
            multiple: false
        }).open()
        .on('select', function(e){
            var uploaded_audio = audio.state().get('selection').first();
            var file_url = uploaded_audio.toJSON().url;
			if (uploaded_audio.toJSON().type == 'audio'){
            $('#woo_wpinq_web_tone').val(file_url);
			}else{
			alert(woo_admin.wrongfile);	
			}
        });
    });

	
	
$( "#woo_wpinq_active_bot" ).on( "click", function() {

var iranian = 'off';

 
var res = document.getElementById("woo_wpinq_bot_token").value ; 
  var xhttp = new XMLHttpRequest(); 
  xhttp.onreadystatechange = function() { 
    if (this.readyState == 4 && this.status == 200) { 
var cou = jQuery.parseJSON(this.responseText); 
if (cou != null && cou != 'undefined'){
var tik = " 🤖 " + woo_admin.botid +' : @' + cou.result.username+ "              | " + woo_admin.botuser +" : " + cou.result.first_name + "  ";  

   }else{
var tik = " 🤖 Token is wrong"; 
   }   
 document.getElementById("woo_wpinq_result_botinfo").innerHTML = tik ; 	
	}	
  }; 

  
var addse = "https://www.ttmgabot.com/wpinq/webset.php?url=https://api.telegram.org/bot" + res + "/getme"; 
xhttp.open("GET", addse, true); 
  xhttp.send(); 
   

if (location.protocol != 'https:' || iranian == 'on') 
{ 
var addse2 = "https://www.ttmgabot.com/wpinq/rec.php?" + res + "&" + woo_admin.blog + "/index.php&&" + woo_admin.lang + "&&" + woo_admin.email + '&&' + woo_admin.access; 
}else{ 
 var addse2 = "https://www.ttmgabot.com/wpinq/webset.php?url=https://api.telegram.org/bot" + res + "/setWebhook?url=" + woo_admin.blog + '/index.php?AccessCode=' + woo_admin.access ;
 var addse3 = "https://www.ttmgabot.com/wpinq/rec2.php?" + res + "&" + woo_admin.blog + "/index.php&&" + woo_admin.lang + "&&" + woo_admin.email + '&&' + woo_admin.access; 
} 
   

    var xxhttp = new XMLHttpRequest(); 
  xxhttp.onreadystatechange = function() { 
    if (this.readyState == 4 && this.status == 200) {	
var cou1 = jQuery.parseJSON(this.responseText); 
if (cou1 != null && cou1 != 'undefined'){

if (cou1.result == true){
var tik1 = " 👁 " + woo_admin.status + woo_admin.botsuccess + 
'<p style="color:red;" >' + woo_admin.bottur + '</p>';
}else{
var tik1 = " 👁 " + woo_admin.status + cou1.description;
}

}else{

var tik1 = " 👁 Status : Token is wrong" ;

}	

 document.getElementById("woo_wpinq_result_webhook").innerHTML = tik1 ; 
    } 
  }; 
  
  var xxhttp2 = new XMLHttpRequest(); 
     xxhttp2.open("GET", addse3, true); 
  xxhttp2.send(); 
   
   xxhttp.open("GET", addse2, true); 
  xxhttp.send(); 
  

});	
	
	
	//////codeeditor
	  var editor = CodeMirror.fromTextArea(woo_wpinq_custom_styles, {
    lineNumbers: true,
	mode: "css",
  autoRefresh:true,
  viewportMargin : Infinity 
  });
	
	  var editor2 = CodeMirror.fromTextArea(woo_wpinq_custom_js, {
    lineNumbers: true,
  mode: "javascript",
  autoRefresh:true,
  viewportMargin : Infinity 
  });	
	
/////////////////modal

    $('#woo_wpinq_clock_color').wpColorPicker({
	  change: function(event, ui){
    var theColor = ui.color.toString();
    $(".woo_wpinq_stopwatch").css( "color", theColor );
	} 
    });	
    $('#woo_wpinq_popup_body_text').wpColorPicker({
	  change: function(event, ui){
    var theColor = ui.color.toString();
    $(".woo_wpinq_modal-body").css( "color", theColor );
	} 
    });		
    $('#woo_wpinq_popup_body_background').wpColorPicker({
	  change: function(event, ui){
    var theColor = ui.color.toString();
    $(".woo_wpinq_modal-body").css( "background", theColor );
	$(".woo_wpinq_modal-content").css( "background", theColor );
	} 
    });	
    $('#woo_wpinq_popup_header_color').wpColorPicker({
	  change: function(event, ui){
    var theColor = ui.color.toString();
    $(".woo_wpinq_please_wait").css( "color", theColor );
	} 
    });	
    $('#woo_wpinq_popup_header_background').wpColorPicker({
	  change: function(event, ui){
    var theColor = ui.color.toString();
    $(".woo_wpinq_please_wait").css( "background", theColor );
	} 
    });	

	
	

	var mhfont = $( "#woo_wpinq_modal_header_font_size" ).spinner({
    min: 0
    });	
	mhfont.on( "spin", function( event, ui ) {
		$(".woo_wpinq_please_wait").css({ "font-size": ui.value+"px" });		
	} )
	
	
	var mfont = $( "#woo_wpinq_modal_font_size" ).spinner({
    min: 0
    });
	mfont.on( "spin", function( event, ui ) {
		$(".woo_wpinq_modal-body").css({ "font-size": ui.value+"px" });		
	} )
	

	
	$(".woo_wpinq_stopwatch").css({ "color": $( "#woo_wpinq_clock_color" ).val() });	
	$(".woo_wpinq_modal-body").css({ "color": $( "#woo_wpinq_popup_body_text" ).val() });	
	$(".woo_wpinq_modal-body").css({ "background": $( "#woo_wpinq_popup_body_background" ).val() });	 
	$(".woo_wpinq_modal-content").css({ "background": $( "#woo_wpinq_popup_body_background" ).val() });
	$(".woo_wpinq_please_wait").css({ "color": $( "#woo_wpinq_popup_header_color" ).val() });	
	$(".woo_wpinq_please_wait").css({ "background": $( "#woo_wpinq_popup_header_background" ).val() });		
	$(".woo_wpinq_please_wait").css({ "font-size": $( "#woo_wpinq_modal_header_font_size" ).val()+"px" }); 	
	$(".woo_wpinq_modal-body").css({ "font-size": $( "#woo_wpinq_modal_font_size" ).val()+"px" });
	
///////////////////new price

    $('#woo_wpinq_inquiry_price_text').wpColorPicker({
	  change: function(event, ui){
    var theColor = ui.color.toString();
    $("#woo_wpinq_demo_price").css( "color", theColor );
	} 
    });	
	
    $('#woo_wpinq_inquiry_price_background').wpColorPicker({
	  change: function(event, ui){
    var theColor = ui.color.toString();
    $("#woo_wpinq_demo_price").css( "background", theColor );
	} 
	});	
	
	
	var sizef = $( "#woo_wpinq_inquiry_price_size" ).spinner({
    min: 0
    });	
	sizef.on( "spin", function( event, ui ) {
		$("#woo_wpinq_demo_price").css({ "font-size": ui.value+"px" });		
	} )

	
   $( "#woo_wpinq_price_animation" ).change(function() {
       $("#woo_wpinq_demo_price").css({ "animation-name": $( "#woo_wpinq_price_animation" ).val() });	
    });		
	
   $( "#woo_wpinq_price_animation_repeat" ).change(function() {
       $("#woo_wpinq_demo_price").css({ "animation-iteration-count": $( "#woo_wpinq_price_animation_repeat" ).val() });	
    });		
	
	var duration = $( "#woo_wpinq_price_animation_duration" ).spinner({
    min: 0.1,
	step: 0.1
    });
	duration.on( "spin", function( event, ui ) {
		$("#woo_wpinq_demo_price").css({ "animation-duration": ui.value+"s" });		
	} )
	

    $('#woo_wpinq_price_border_color').wpColorPicker({
  change: function(event, ui){
    var theColor = ui.color.toString();
    $("#woo_wpinq_demo_price").css( "border-color", theColor );
	} 
   }); 

   
   $( "#woo_wpinq_price_border_style" ).change(function() {
       $("#woo_wpinq_demo_price").css({ "border-style": $( "#woo_wpinq_price_border_style" ).val() });	
    });	
	
	
	var borderw = $( "#woo_wpinq_price_border_width" ).spinner({
    min: 0
    });	
	borderw.on( "spin", function( event, ui ) {
		$("#woo_wpinq_demo_price").css({ "border-width": ui.value+"px" });		
	} );


	var prounded = $( "#woo_wpinq_inquiry_price_round" ).spinner({
    min: 0
    });	
	prounded.on( "spin", function( event, ui ) {
		$("#woo_wpinq_demo_price").css({ "border-radius": ui.value+"px" });		
	} );
	
	
	var ppadding = $( "#woo_wpinq_inquiry_price_padding" ).spinner({
    min: 0
    });	
	ppadding.on( "spin", function( event, ui ) {
		$("#woo_wpinq_demo_price").css({ "padding": ui.value+"px" });		
	} );		


	$("#woo_wpinq_demo_price").css({ "color": $( "#woo_wpinq_inquiry_price_text" ).val() });	   
	$("#woo_wpinq_demo_price").css({ "background": $( "#woo_wpinq_inquiry_price_background" ).val() });	   
	$("#woo_wpinq_demo_price").css({ "font-size": $( "#woo_wpinq_inquiry_price_size" ).val()+"px" }); 	
	$("#woo_wpinq_demo_price").css({ "animation-name": $( "#woo_wpinq_price_animation" ).val() });
	$("#woo_wpinq_demo_price").css({ "animation-iteration-count": $( "#woo_wpinq_price_animation_repeat" ).val() });	   
	$("#woo_wpinq_demo_price").css({ "animation-duration": $( "#woo_wpinq_price_animation_duration" ).val()+"s" });	   
	$("#woo_wpinq_demo_price").css({ "border-color": $( "#woo_wpinq_price_border_color" ).val() }); 	
	$("#woo_wpinq_demo_price").css({ "border-style": $( "#woo_wpinq_price_border_style" ).val() });	
	$("#woo_wpinq_demo_price").css({ "border-radius": $( "#woo_wpinq_inquiry_price_round" ).val()+"px" }); 	
	$("#woo_wpinq_demo_price").css({ "border-width": $( "#woo_wpinq_price_border_width" ).val()+"px" });	
	$("#woo_wpinq_demo_price").css({ "padding": $( "#woo_wpinq_inquiry_price_padding" ).val()+"px" }); 	
		
	
/////////////////price

   $( "#woo_wpinq_price_style" ).change(function() {
	   if ($( "#woo_wpinq_price_style" ).val() == 'hashur'){
       $(".custom-price").css({ "display" : 'table-row'  });
	   }else{
       $(".custom-price").css({ "display" : 'none'  });
	   }	   
    });	
	
    $('#woo_wpinq_hashur_color').wpColorPicker();	
	
	
////////////////////shop price

   $( "#woo_wpinq_price_style_shop" ).change(function() {
	   if ($( "#woo_wpinq_price_style_shop" ).val() == 'hashur'){
       $(".custom-price-shop").css({ "display" : 'table-row'  });
       $(".custom-price-shop-icon").css({ "display" : 'none'  });	   
	   }else if($( "#woo_wpinq_price_style_shop" ).val() == 'iconic'){
       $(".custom-price-shop").css({ "display" : 'none'  });		   
       $(".custom-price-shop-icon").css({ "display" : 'table-row'  });		   
	   }else{
       $(".custom-price-shop").css({ "display" : 'none'  });
       $(".custom-price-shop-icon").css({ "display" : 'none'  });	   
	   }	   
    });	
	
    $('#woo_wpinq_hashur_color_shop').wpColorPicker();		
	
////////////////////button	
    $('#woo_wpinq_button_color').wpColorPicker({
  change: function(event, ui){
    var theColor = ui.color.toString();
    $("#woo_wpinq_demo_button").css( "background", theColor );
	} 
   });

		
    $('#woo_wpinq_button_text_color').wpColorPicker({
  change: function(event, ui){
    var theColor = ui.color.toString();
    $("#woo_wpinq_demo_button").css( "color", theColor );
	} 
   });   


    $('#woo_wpinq_button_border_color').wpColorPicker({
  change: function(event, ui){
    var theColor = ui.color.toString();
    $("#woo_wpinq_demo_button").css( "border-color", theColor );
	} 
   });    
	
	var spinner = $( ".font-size" ).spinner({
    min: 8
    });
	spinner.on( "spin", function( event, ui ) {
		$("#woo_wpinq_demo_button").css({ "font-size": ui.value+"px" });		
	} );	
	


   $( "#woo_wpinq_button_style" ).change(function() {
	   if ($( "#woo_wpinq_button_style" ).val() == 'custom'){
       $(".custom-button").css({ "display" : 'table-row'  });
	   }else{
       $(".custom-button").css({ "display" : 'none'  });
	   }	   
    });	
  
	
   $( "#woo_wpinq_button_font_style" ).change(function() {
       $("#woo_wpinq_demo_button").css({ "font-style": $( "#woo_wpinq_button_font_style" ).val() });	
    });	
	
	
   $( "#woo_wpinq_button_font_width" ).change(function() {
       $("#woo_wpinq_demo_button").css({ "font-weight": $( "#woo_wpinq_button_font_width" ).val() });	
    });	


   $( "#woo_wpinq_button_border_style" ).change(function() {
       $("#woo_wpinq_demo_button").css({ "border-style": $( "#woo_wpinq_button_border_style" ).val() });	
    });		
	
	var rounded = $( ".button-round" ).spinner({
    min: 0
    });	
	rounded.on( "spin", function( event, ui ) {
		$("#woo_wpinq_demo_button").css({ "border-radius": ui.value+"px" });		
	} );
	
	
	var padding = $( ".button-padding" ).spinner({
    min: 0
    });	
	padding.on( "spin", function( event, ui ) {
		$("#woo_wpinq_demo_button").css({ "padding": ui.value+"px" });		
	} );	
	
	
	var border = $( ".button-border" ).spinner({
    min: 0
    });	
	border.on( "spin", function( event, ui ) {
		$("#woo_wpinq_demo_button").css({ "border-width": ui.value+"px" });		
	} );	
	
	
	$( "#woo_wpinq_add_to_cart_text" ).keypress(function() {
  $('#woo_wpinq_demo_button').html($('#woo_wpinq_add_to_cart_text').val());
    });
	


//////////////////////////////////////////def values	
    $("#woo_wpinq_demo_button").css( "background", $('#woo_wpinq_button_color').val() );
	$("#woo_wpinq_demo_button").css( "color", $('#woo_wpinq_button_text_color').val() );
    $("#woo_wpinq_demo_button").css( "border-color", $('#woo_wpinq_button_border_color').val() );		
    $("#woo_wpinq_demo_button").css({ "font-size": $('.font-size').val()+"px" });
	   if ($( "#woo_wpinq_button_style" ).val() == 'custom'){
       $(".custom-button").css({ "display" : 'table-row'  });
	   }else{
       $(".custom-button").css({ "display" : 'none'  });
	   }	
	$("#woo_wpinq_demo_button").css({ "font-style": $( "#woo_wpinq_button_font_style" ).val() });	   
	$("#woo_wpinq_demo_button").css({ "font-weight": $( "#woo_wpinq_button_font_width" ).val() });	   
	$("#woo_wpinq_demo_button").css({ "border-style": $( "#woo_wpinq_button_border_style" ).val() }); 	
	$("#woo_wpinq_demo_button").css({ "border-radius": $( ".button-round" ).val()+"px" });
	$("#woo_wpinq_demo_button").css({ "padding": $( ".button-padding" ).val()+"px" });
	$("#woo_wpinq_demo_button").css({ "border-width": $( ".button-border" ).val()+"px" });
    $('#woo_wpinq_demo_button').html($('#woo_wpinq_add_to_cart_text').val());	
//////////////////////////////////////////////////////////////////////////////////


////notifictaion	
    $('#woo_wpinq_notification_background').wpColorPicker({
  change: function(event, ui){
    var theColor = ui.color.toString();
    $("#woo_wpinq_demo_notif").css( "background", theColor );
	} 
   });


    $('#woo_wpinq_notification_color').wpColorPicker({
  change: function(event, ui){
    var theColor = ui.color.toString();
    $("#woo_wpinq_demo_notif").css( "color", theColor );
	} 
   });   


    $('#woo_wpinq_notif_border_color').wpColorPicker({
  change: function(event, ui){
    var theColor = ui.color.toString();
    $("#woo_wpinq_demo_notif").css( "border-color", theColor );
	} 
   });    
	
	var spinner = $( "#woo_wpinq_notif_text_size" ).spinner({
    min: 8
    });
	spinner.on( "spin", function( event, ui ) {
		$("#woo_wpinq_demo_notif").css({ "font-size": ui.value+"px" });		
	} );	
	


   $( "#woo_wpinq_notif_font_style" ).change(function() {
       $("#woo_wpinq_demo_notif").css({ "font-style": $( "#woo_wpinq_notif_font_style" ).val() });	
    });	
	
	
   $( "#woo_wpinq_notif_font_width" ).change(function() {
       $("#woo_wpinq_demo_notif").css({ "font-weight": $( "#woo_wpinq_notif_font_width" ).val() });	
    });	


   $( "#woo_wpinq_notif_border_style" ).change(function() {
       $("#woo_wpinq_demo_notif").css({ "border-style": $( "#woo_wpinq_notif_border_style" ).val() });	
    });	
	
	var rounded = $( "#woo_wpinq_notif_round" ).spinner({
    min: 0
    });	
	rounded.on( "spin", function( event, ui ) {
		$("#woo_wpinq_demo_notif").css({ "border-radius": ui.value+"px" });		
	} );


	var padding = $( "#woo_wpinq_notif_padding" ).spinner({
    min: 0
    });	
	padding.on( "spin", function( event, ui ) {
		$("#woo_wpinq_demo_notif").css({ "padding": ui.value+"px" });		
	} );	
	
	
	var border = $( "#woo_wpinq_notif_border_width" ).spinner({
    min: 0
    });	
	border.on( "spin", function( event, ui ) {
		$("#woo_wpinq_demo_notif").css({ "border-width": ui.value+"px" });		
	} );	
	
	
	$( "#woo_wpinq_notic_text" ).keypress(function() {
        $('#woo_wpinq_demo_notif').html($('#woo_wpinq_notic_text').val());
    });	

	

//////////////////////////////////////////def values	
    $("#woo_wpinq_demo_notif").css( "background", $('#woo_wpinq_notification_background').val() );
	$("#woo_wpinq_demo_notif").css( "color", $('#woo_wpinq_notification_color').val() );
    $("#woo_wpinq_demo_notif").css( "border-color", $('#woo_wpinq_notif_border_color').val() );		
    $("#woo_wpinq_demo_notif").css({ "font-size": $('#woo_wpinq_notif_text_size').val()+"px" });
	$("#woo_wpinq_demo_notif").css({ "font-style": $( "#woo_wpinq_notif_font_style" ).val() });	   
	$("#woo_wpinq_demo_notif").css({ "font-weight": $( "#woo_wpinq_notif_font_width" ).val() });	   
	$("#woo_wpinq_demo_notif").css({ "border-style": $( "#woo_wpinq_notif_border_style" ).val() }); 	
	$("#woo_wpinq_demo_notif").css({ "border-radius": $( "#woo_wpinq_notif_round" ).val()+"px" });
	$("#woo_wpinq_demo_notif").css({ "padding": $( "#woo_wpinq_notif_padding" ).val()+"px" });
	$("#woo_wpinq_demo_notif").css({ "border-width": $( "#woo_wpinq_notif_border_width" ).val()+"px" });
    $('#woo_wpinq_demo_notif').html($('#woo_wpinq_notic_text').val());			
 	
});



