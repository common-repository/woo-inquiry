<?php
function woo_wpinq_report_page(){

	
$woo_wpinq_options = get_option('woo_wpinq_options');
$audio_path = WOO_INQUIRY_PLUGIN_URL."/assets/ring/tone.mp3";
if (isset($woo_wpinq_options['woo_wpinq_web_tone'])){
$audio_path	= $woo_wpinq_options['woo_wpinq_web_tone'];
}

?>
<audio controls muted id="woo_wpinq_notification" style="display:none;">
  <source src="<?php echo $audio_path;?>" type="audio/mpeg">
</audio>
<div class="woo_wpinq_preload">
<div class="spinner2">
  <div class="double-bounce1"></div>
  <div class="double-bounce2"></div>
</div>
</div>





<div class="woo_wpinq_report_page"> 
	

 <div id="tabs">
 
   <ul>
    <li><a href="#tabs-1"><?php echo __("Current inquiries", 'woo-inquiry') ?></a></li>
    <li><a href="#tabs-2"><?php echo __("Missed inquiries", 'woo-inquiry') ?></a></li>	
    <li><a href="#tabs-3"><?php echo __("Non-working hours inquiries", 'woo-inquiry') ?></a></li>
    <li><a href="#tabs-4"><?php echo __("History", 'woo-inquiry') ?></a></li>	
    <li><a href="#tabs-5"><?php echo __("Users", 'woo-inquiry') ?></a></li>
	
  </ul>

     <div id="tabs-1">	 
	 
            <div class="modal fade" id="mdModal" tabindex="-1" role="dialog">
                <div class="modal-dialog" role="document">
                    <div class="modal-content">
					
					<div class="woo_wpinq_waiting" id="woo_wpinq_price_waiting">
                       <div class="spinner2">
                       <div class="double-bounce1"></div>
                       <div class="double-bounce2"></div>
                       </div>
                    </div>
					
                        <div class="modal-header">
                            <h4 class="modal-title" id="defaultModalLabel"><?php echo __("Answer to user inquiry", 'woo-inquiry') ?></h4>
                        </div>
                        <div class="modal-body">
                        <input type="text" name="woo_wping_price_answer" id="woo_wping_price_answer" value="" class="regular-text" />
                        </div>
                        <div class="modal-footer">
                            <button type="button" data-kind="temp" style="background:#05c75c !important" class="btn btn-link waves-effect woo_wping_price_answer_confirm"><?php echo __("Confirm(Temporary)", 'woo-inquiry') ?></button>
                            <button type="button" data-kind="work" style="background:#2626ff !important" class="btn btn-link waves-effect woo_wping_price_answer_confirm" ><?php echo __("Confirm(in working hours)", 'woo-inquiry') ?></button>
                            <button type="button" style="background:#ff3636 !important" class="btn btn-link waves-effect" data-dismiss="modal"><?php echo __("Cancel", 'woo-inquiry') ?></button>							
                        </div>
                    </div>
                </div>
            </div>	 
			
			
			
	 
                      <div class="woo_wpinq_reports" >
                        <div class="body">
                            <div class="table-responsive">
                                <table class="table table-bordered table-striped table-hover js-basic-example dataTable" id="woo_wpinq_current_inquiries">
                                    <thead>
                                        <tr>
                                            <th><?php echo __("Product", 'woo-inquiry') ?></th>
                                            <th><?php echo __("Product Name", 'woo-inquiry') ?></th>
                                            <th><?php echo __("Current Price", 'woo-inquiry') ?></th>
                                            <th><?php echo __("User / Session", 'woo-inquiry') ?></th>
                                            <th><?php echo __("Actions", 'woo-inquiry') ?></th>                                            
                                        </tr>
                                    </thead>
                                    <tbody>
<?php
                                       echo woo_wpinq_reports_table();
?>								
                                    </tbody>
                                </table>
                            </div>
                        </div>
					</div>	

      </div>


     <div id="tabs-2">
	 
	 
                      <div class="woo_wpinq_reports" >
					  <button type="button" class="btn bg-red waves-effect woo_wping_deleter" data-kind="timeup"><span class="dashicons dashicons-trash"></span><span>  <?php echo __("Delete All Records", 'woo-inquiry') ?> </span></button><br>
                        <div class="body">
                            <div class="table-responsive">
                                <table id="woo_wpinq_timeup_table_js" class="table table-bordered table-striped table-hover js-basic-example dataTable">
                                    <thead>
                                        <tr>
                                            <th><?php echo __("Product", 'woo-inquiry') ?></th>
                                            <th><?php echo __("Product Name", 'woo-inquiry') ?></th>
                                            <th><?php echo __("Current Price", 'woo-inquiry') ?></th>
											<th><?php echo __("User info", 'woo-inquiry') ?></th>
                                            <th><?php echo __("Date", 'woo-inquiry') ?></th>

                                             											
                                        </tr>
                                    </thead>
                                    <tbody id="woo_wpinq_timeup_table">
                                         			  <div class="woo_pinq_notic_admin" > <h4>This feature is available only in the premium version.</h4>
			  <p><a href="https://www.codester.com/items/12912/woocommerce-online-price-inquiry?ref=sjafarhosseini007" style="color: black;font-size: 15px;">Buy premium version now</a></p>
			  </div>
                                       								
                                    </tbody>
                                </table>
                            </div>
                        </div>
					</div>	

      </div>	  
	  
	  
	  
     <div id="tabs-3">
                      <div class="woo_wpinq_reports" >
					  <button type="button" class="btn bg-red waves-effect woo_wping_deleter" data-kind="timeoff"><span class="dashicons dashicons-trash"></span>  <?php echo __("Delete All Records", 'woo-inquiry') ?> </span></button><br>
                        <div class="body">
                            <div class="table-responsive">
                                <table id="woo_wpinq_timeoff_table_js" class="table table-bordered table-striped table-hover js-basic-example dataTable">
                                    <thead>
                                        <tr>
                                            <th><?php echo __("Product", 'woo-inquiry') ?></th>
                                            <th><?php echo __("Product Name", 'woo-inquiry') ?></th>
                                            <th><?php echo __("Current Price", 'woo-inquiry') ?></th>
											<th><?php echo __("User info", 'woo-inquiry') ?></th>
                                            <th><?php echo __("Date", 'woo-inquiry') ?></th>
                                             											
                                        </tr>
                                    </thead>
                                    <tbody id="woo_wpinq_timeoff_table">
                                      			  <div class="woo_pinq_notic_admin" > <h4>This feature is available only in the premium version.</h4>
			  <p><a href="https://www.codester.com/items/12912/woocommerce-online-price-inquiry?ref=sjafarhosseini007" style="color: black;font-size: 15px;">Buy premium version now</a></p>
			  </div>
                                       								
                                    </tbody>
                                </table>
                            </div>
                        </div>
					</div>	

      </div>
	  
	  
   <div id="tabs-4">

                     <div class="woo_wpinq_reports" >
					  <button type="button" class="btn bg-red waves-effect woo_wping_deleter" data-kind="history"><span class="dashicons dashicons-trash"></span><span>  <?php echo __("Delete All Records", 'woo-inquiry') ?> </span></button><br>					 
                        <div class="body">
                            <div class="table-responsive">
                                <table id="woo_wpinq_history_table_js" class="table table-bordered table-striped table-hover dataTable js-exportable">
                                    <thead>
                                        <tr>
                                            <th><?php echo __("Product", 'woo-inquiry') ?></th>
                                            <th><?php echo __("Product Name", 'woo-inquiry') ?></th>
                                            <th><?php echo __("Regular Price", 'woo-inquiry') ?></th>
                                            <th><?php echo __("New Price", 'woo-inquiry') ?></th>
                                            <th><?php echo __("Status", 'woo-inquiry') ?></th>  
                                            <th><?php echo __("User info", 'woo-inquiry') ?></th>
                                            <th><?php echo __("Date", 'woo-inquiry') ?></th>											
                                        </tr>
                                    </thead>
                                    <tbody id="woo_wpinq_history_table">
			  <div class="woo_pinq_notic_admin" > <h4>This feature is available only in the premium version.</h4>
			  <p><a href="https://www.codester.com/items/12912/woocommerce-online-price-inquiry?ref=sjafarhosseini007" style="color: black;font-size: 15px;">Buy premium version now</a></p>
			  </div>									
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>

            <!-- #END# Exportable Table -->   
   
   </div>   
   
   
   
   <div id="tabs-5">

                     <div class="woo_wpinq_reports" >
					  <button type="button" class="btn bg-red waves-effect woo_wping_deleter" data-kind="users"><span class="dashicons dashicons-trash"></span><span>  <?php echo __("Delete All Records", 'woo-inquiry') ?> </span></button><br>					 
                        <div class="body">
                            <div class="table-responsive">
                                <table id="woo_wpinq_users_table_js" class="table table-bordered table-striped table-hover dataTable js-exportable">
                                    <thead>
                                        <tr>
                                            <th><?php echo __("Name", 'woo-inquiry') ?></th>
                                            <th><?php echo __("Phone", 'woo-inquiry') ?></th>
                                            <th><?php echo __("Role", 'woo-inquiry') ?></th>                                           
                                        </tr>
                                    </thead>
                                    <tbody id="woo_wpinq_users_table">
			  <div class="woo_pinq_notic_admin" > <h4>This feature is available only in the premium version.</h4>
			  <p><a href="https://www.codester.com/items/12912/woocommerce-online-price-inquiry?ref=sjafarhosseini007" style="color: black;font-size: 15px;">Buy premium version now</a></p>
			  </div>							
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>

            <!-- #END# Exportable Table -->      
   
   
   </div> 




</div> 

</div> 
<?php

}
?>