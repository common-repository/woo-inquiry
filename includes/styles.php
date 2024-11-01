<?php
function woo_wpinq_create_styles(){

$woo_wpinq_options = get_option('woo_wpinq_options');

$notification_position = $woo_wpinq_options['woo_wpinq_notification_position'];
$price_style = $woo_wpinq_options['woo_wpinq_price_style'];		
		?>	

		
<style>

.woo_wping_notic {
	
	width : 100%;
	z-index : 99998;	
	text-align: center;
	margin : 2px;
	
	<?php
	if ($notification_position === 'very-top'){
	?>
	position : fixed;
	right : 0;
	top : 0;
	<?php
	}
	?>

	<?php
	if ($notification_position === 'very-bottom'){
	?>
	position : fixed;
	right : 0;
	bottom : 0;
	<?php
	}
	?>	

    background: <?php echo $woo_wpinq_options['woo_wpinq_notification_background'];?>;
    color: <?php echo $woo_wpinq_options['woo_wpinq_notification_color'];?>;
    font-size: <?php echo $woo_wpinq_options['woo_wpinq_notif_text_size'];?>px;
    font-style: <?php echo $woo_wpinq_options['woo_wpinq_notif_font_style'];?>;
    font-width: <?php echo $woo_wpinq_options['woo_wpinq_notif_font_width'];?>;

    border-radius: <?php echo $woo_wpinq_options['woo_wpinq_notif_round'];?>px;
    padding: <?php echo $woo_wpinq_options['woo_wpinq_notif_padding'];?>px;
    border-color: <?php echo $woo_wpinq_options['woo_wpinq_notif_border_color'];?>;
    border-style: <?php echo $woo_wpinq_options['woo_wpinq_notif_border_style'];?>;	
    border-width: <?php echo $woo_wpinq_options['woo_wpinq_notif_border_width'];?>px;		
}






p.price {
	
<?php if ($price_style === 'hashur'){
?>

    background-image: repeating-linear-gradient(
        -45deg,
        transparent,
        transparent 4px,
        transparent 1px,
        <?php echo $woo_wpinq_options['woo_wpinq_hashur_color'];?> 7px
      );
	width: fit-content;

<?php }elseif($price_style === 'transpar'){ ?>
   opacity : 0.3;
<?php }elseif($price_style === 'hidden'){ ?>
   display : none !important;  
<?php }else{
      } ?>    
}


div.price {
	
<?php if ($price_style === 'hashur'){
?>

    background-image: repeating-linear-gradient(
        -45deg,
        transparent,
        transparent 4px,
        transparent 1px,
        <?php echo $woo_wpinq_options['woo_wpinq_hashur_color'];?> 7px
      );
	width: fit-content;

<?php }elseif($price_style === 'transpar'){ ?>
   opacity : 0.3;
<?php }elseif($price_style === 'hidden'){ ?>
   display : none !important;  
<?php }else{
      } ?>   
}



.dashicons-timedore {
    font-size: 40px;
    width: 40px;
    height: 40px;
    line-height: 0px;
    transform: rotateZ(180deg);
    color:  <?php echo $woo_wpinq_options['woo_wpinq_clock_color'];?>;
}


.dashicons-aghrabe {
    position: absolute;
    width: 36px;
    height: 36px;
    font-size: 36px;
    line-height: 0px !important;
    top: 0px;
    right: 2px;
    transform: rotate(180deg);
    -webkit-animation: rotate-center 0.9s linear infinite both;
    animation: rotate-center 0.9s linear infinite both;
}

div#woo_wping_countdown {
    font-size: 17px;
}

@-webkit-keyframes rotate-center {
  0% {
    -webkit-transform: rotate(0);
            transform: rotate(0);
  }
  100% {
    -webkit-transform: rotate(360deg);
            transform: rotate(360deg);
  }
}
@keyframes rotate-center {
  0% {
    -webkit-transform: rotate(0);
            transform: rotate(0);
  }
  100% {
    -webkit-transform: rotate(360deg);
            transform: rotate(360deg);
  }
}



/* Add Animation */
@-webkit-keyframes zoomIn {
  from {
    opacity: 0;
    -webkit-transform: scale3d(0.3, 0.3, 0.3);
    transform: scale3d(0.3, 0.3, 0.3);
  }

  50% {
    opacity: 1;
  }
}

@keyframes zoomIn {
  from {
    opacity: 0;
    -webkit-transform: scale3d(0.3, 0.3, 0.3);
    transform: scale3d(0.3, 0.3, 0.3);
  }

  50% {
    opacity: 1;
  }
}

.zoomIn {
  -webkit-animation-name: zoomIn;
  animation-name: zoomIn;
}



.single_add_to_cart_button{
    display: none !important;
}
.quantity {
    display: none !important;
}

#woo_wpinq_callusrightnow{
display: block !important;	
}



#woo_wpinq_inquiry_button{
	display : block !important;
}



.woo_wpinq_contact_form {
    padding: 15px!important;
    background: #f1f1f129 !important;
    border-radius: 12px !important;
    box-shadow: 1px 1px 10px #bdbcbc !important;
    margin: 25px !important;
}

.woo_wpinq_times_up_form {
    padding: 10px;
}

p.woo_wpinq_label {
    font-size: 13px !important;
    color: <?php echo $woo_wpinq_options['woo_wpinq_clock_color'];?> !important;
    margin-top: 17px !important;
    margin-bottom: 2px !important;
}


.woo_wpinq_contact_form_field {
    background: #ffffffd6 !important;
    border-radius: 12px !important;
    width: 100% !important;
    height: 40px !important;
}

.woo_wpinq_contact_button {
    border-radius: 12px !important;
    background: red !important;
    width: -webkit-fill-available;
    padding: 8px !important;
    float: unset !important;
    margin: 15px !important;
    font-size: 13px !important;
    box-shadow: 1px 1px 14px 1px #adadad94;
	cursor: pointer;
}


.woo_wpinq_stopwatch {
       height: auto;
	   text-align: -webkit-center;
	   color : <?php echo $woo_wpinq_options['woo_wpinq_clock_color'];?> !important;   
}


.woo_wpinq_please_wait {
    text-align: center;
    padding: 10px;
    background: #f1f1f1;
    border-top-left-radius: 10px;
    border-top-right-radius: 10px;
	font-size : <?php echo $woo_wpinq_options['woo_wpinq_modal_header_font_size']; ?>px !important;
	color : <?php echo $woo_wpinq_options['woo_wpinq_popup_header_color']; ?>;
	background : <?php echo $woo_wpinq_options['woo_wpinq_popup_header_background']; ?>;
}


div#woo_wpinq_modal {
    display: none;
    position: fixed;
    z-index: 99999999;
    padding-top: 166px;
    left: 0;
    top: 0;
    width: 100%;
    height: 100%;
    overflow: auto;
    background-color: rgb(0,0,0);
    background-color: rgba(0,0,0,0.4);
	
}

.woo_wpinq_modal-content {
	 border-radius: 10px !important;
    position: relative;
    background-color: <?php echo $woo_wpinq_options['woo_wpinq_popup_body_background'];?>;
    margin: auto;
    padding: 0;
    border: 1px solid #888;
    width: 40%;
    box-shadow: 0 4px 8px 0 rgba(0,0,0,0.2), 0 6px 20px 0 rgba(0,0,0,0.19);
    -webkit-animation-name: zoomIn;
    -webkit-animation-duration: 0.6s;
    animation-name: zoomIn;
    animation-duration: 0.6s;
}




.woo_wpinq_maximum_time {
    width: 100%;
}

.woo_wpinq_maximum_time ul {
    padding-right: 30px !important;
}

.woo_wpinq_modal-body {
	width: -webkit-fill-available;
	border-radius: 10px !important;
	padding: 10px;
	font-size : <?php echo $woo_wpinq_options['woo_wpinq_modal_font_size']; ?>px !important;
	color : <?php echo $woo_wpinq_options['woo_wpinq_popup_body_text']; ?>;
	background : <?php echo $woo_wpinq_options['woo_wpinq_popup_body_background']; ?>;
	}



@media screen and (max-width: 782px){
.woo_wpinq_modal-content {

    width: 90%;

}
}



.woo_wpinq_new_price {
    	
    font-size: <?php echo $woo_wpinq_options['woo_wpinq_inquiry_price_size']; ?>px !important;
    background: <?php echo $woo_wpinq_options['woo_wpinq_inquiry_price_background']; ?> !important;
    padding: <?php echo $woo_wpinq_options['woo_wpinq_inquiry_price_padding']; ?>px !important;
	border-width : <?php echo $woo_wpinq_options['woo_wpinq_price_border_width']; ?>px !important;
	border-color : <?php echo $woo_wpinq_options['woo_wpinq_price_border_color']; ?> !important;
	border-style : <?php echo $woo_wpinq_options['woo_wpinq_price_border_style']; ?> !important;
    border-radius: <?php echo $woo_wpinq_options['woo_wpinq_inquiry_price_round']; ?>px !important;	
    color: <?php echo $woo_wpinq_options['woo_wpinq_inquiry_price_text']; ?> !important;
    animation: <?php echo $woo_wpinq_options['woo_wpinq_price_animation']; ?> !important;
    animation-duration: <?php echo $woo_wpinq_options['woo_wpinq_price_animation_duration']; ?>s !important;
    animation-iteration-count: <?php echo $woo_wpinq_options['woo_wpinq_price_animation_repeat']; ?> !important;
}
	

.woo_wpinq_new_price_notes{
    font-size: <?php echo $woo_wpinq_options['woo_wpinq_inquiry_price_size'] - 5; ?>px !important;
    color: <?php echo $woo_wpinq_options['woo_wpinq_inquiry_price_background']; ?> !important;
}	
	
	

span#woo_wpinq_close {
background: #ff4c4c;
    color: white;
    padding: 3px;
    position: absolute;
    cursor: pointer;
    width: 100%;
    margin-top: -5px;
    font-size: 15px;
}	

<?php echo $woo_wpinq_options['woo_wpinq_custom_styles']; ?>

</style>

<script>
<?php echo $woo_wpinq_options['woo_wpinq_custom_js']; ?>
</script>	

<?php
}












function woo_wpinq_footer_scripts_and_styles(){
$woo_wpinq_options = get_option('woo_wpinq_options');
?>
<style>
<?php echo $woo_wpinq_options['woo_wpinq_custom_styles']; ?>
</style>
<?php		
} 