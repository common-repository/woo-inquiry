<?php
/**
 * Plugin Name: Woo Inquiry
 * Description: Woocommerce online price inquiry
 * Author: S.J.Hossseini
 * Author URI: https://t.me/ttmga
 * Version: 0.1
 * Text Domain: woo-inquiry
 * Domain Path: /languages
 */


// Define WOO_INQUIRY_PLUGIN_FILE.
if (!defined('WOO_INQUIRY_PLUGIN_FILE'))
  {
    define('WOO_INQUIRY_PLUGIN_FILE', plugin_dir_path(__FILE__));
    define('WOO_INQUIRY_PLUGIN_URL', plugins_url('', __FILE__));
  }

require WOO_INQUIRY_PLUGIN_FILE . 'includes/settings.php';
require WOO_INQUIRY_PLUGIN_FILE . 'includes/reports.php';
require WOO_INQUIRY_PLUGIN_FILE . 'includes/functions.php';
require WOO_INQUIRY_PLUGIN_FILE . 'includes/interface.php';
require WOO_INQUIRY_PLUGIN_FILE . 'includes/styles.php';


function woo_wpinq_load_plugin_textdomain() {
    load_plugin_textdomain( 'woo-inquiry', FALSE, basename( dirname( __FILE__ ) ) . '/languages/' );
}
add_action( 'plugins_loaded', 'woo_wpinq_load_plugin_textdomain' );


function woo_wpinq_add_settings_link($links)
  {
    $settings_link = '<a href="' . admin_url('admin.php?page=woo_wpinquiry') . '">'.__("Setting",'woo-inquiry').'</a>';
    array_push($links, $settings_link);
    $settings_link = '<a href="' . admin_url('admin.php?page=woo_wpinquiry_reports') . '">'.__("Reports",'woo-inquiry').'</a>';
    array_push($links, $settings_link);
    return $links;
  }
$plugin = plugin_basename(__FILE__);
add_filter("plugin_action_links_$plugin", 'woo_wpinq_add_settings_link');



/*
@add admin menu
*/ 
add_action('admin_menu', 'woo_wpinq_admin_menu');
function woo_wpinq_admin_menu()
{
add_submenu_page( 'woocommerce', 'Price Inquiry Setting', 'Inquiry Setting' , 'manage_woocommerce', 'woo_wpinquiry', 'woo_wpinq_setting_page' ); 
add_submenu_page( 'woocommerce', 'Price Inquiry Reports', 'Inquiry Reports' , 'manage_woocommerce', 'woo_wpinquiry_reports', 'woo_wpinq_report_page' ); 
}

//////////////////////////////////////////////////////////// load admin scriptS
function woo_wpinq_add_admin_scripts($hook)
  {
    
    if (isset($_GET['page']) && ($_GET['page'] == 'woo_wpinquiry'))
      {
        wp_enqueue_style('wp-color-picker');
        wp_enqueue_style('woo_wpinq-admin-ui-css', plugins_url('assets/css/jquery-ui.css', __FILE__));
        wp_enqueue_style('woo_wpinq_admin_style', plugins_url('assets/css/admin.css', __FILE__));
        wp_enqueue_style('woo_wpinq_admin_cm_style', plugins_url('codemirror/lib/codemirror.css', __FILE__));
        wp_enqueue_style('woo_wpinq_animate_css', plugins_url('assets/css/animate.css', __FILE__));
        wp_register_style('woo_wpinq_dashicons', plugins_url('assets/css/dashicon/css/stopwatchi.css', __FILE__));
        wp_enqueue_style('woo_wpinq_dashicons');
        
        wp_enqueue_script('jquery');
        wp_enqueue_media();
        wp_enqueue_script('wp-color-picker');
        wp_enqueue_script('woo_wpinq_admin_cm_js', plugins_url('codemirror/lib/codemirror.js', __FILE__), array(
            'jquery'
        ), '', true);
        wp_enqueue_script('woo_wpinq_admin_cssmode_js', plugins_url('codemirror/mode/css/css.js', __FILE__), array(
            'jquery'
        ), '', true);
        wp_enqueue_script('woo_wpinq_admin_jsmode_js', plugins_url('codemirror/mode/jsx/jsx.js', __FILE__), array(
            'jquery'
        ), '', true);
        wp_enqueue_script('woo_wpinq_admin_refresh_js', plugins_url('codemirror/display/autorefresh.js', __FILE__), array(
            'jquery'
        ), '', true);
        wp_register_script('woo_wpinq_admin_js', plugins_url('assets/js/admin.js', __FILE__), array(
            'jquery'
        ), '6.0.0', true);
        $values = array(
            'blog' => get_option('siteurl'),
            'wpajax' => admin_url('admin-ajax.php'),
            'lang' => get_bloginfo("language"),
            'phpversion' => get_option('woo_wpinq_php_version_min'),
            'email' => get_bloginfo("admin_email"),
            'access' => get_option('woo_wpinq_access_code'),
            'wrongfile' => __("File type should be mp3",'woo-inquiry'),
            'wpmedia' => __("Choose notification sound",'woo-inquiry'),
            'botuser' => __("Name",'woo-inquiry'),
            'botid' => __("Username",'woo-inquiry'),
            'status' => __("Status : ",'woo-inquiry'),			
			'botsuccess' => __("Your Bot Token has been activated successfully  ✅" , 'woo-inquiry'),
            'bottur' => __("Now go to Telegram Messenger and send this token for your bot. The bot will ask you for an email. The email you enter in this section, must be the email used to sign up on your wordpress site. Signed up user’s role must be the Admin or store manager. " ,'woo-inquiry')			
        );
        wp_localize_script('woo_wpinq_admin_js', 'woo_admin', $values);
        wp_enqueue_script('woo_wpinq_admin_js');
        
        wp_enqueue_script('jquery-ui-tabs');
        wp_enqueue_script('jquery-ui-spinner');
        
      }
    
    if (isset($_GET['page']) && ($_GET['page'] == 'woo_wpinquiry_reports'))
      {
        wp_enqueue_style('woo_wpinq_animate_css', plugins_url('assets/css/animate.css', __FILE__));
        wp_enqueue_style('woo_wpinq-admin-ui-css',  plugins_url('assets/css/jquery-ui.css', __FILE__));
        wp_enqueue_style('woo_wpinq_admin_style', plugins_url('assets/css/admin.css', __FILE__));
        wp_enqueue_style('woo_wpinq_bootstrap', plugins_url('assets/bootstrap/css/bootstrap.css', __FILE__));
        wp_enqueue_style('woo_wpinq_adminbs_style', plugins_url('assets/css/style.css', __FILE__));
        wp_enqueue_style('woo_wpinq_datatable', plugins_url('assets/jquery-datatable/skin/bootstrap/css/dataTables.bootstrap.css', __FILE__));
        
        wp_enqueue_script('jquery');
        wp_enqueue_script('jquery-ui-tabs');
        wp_register_script('woo_wpinq_admin_j_dt', plugins_url('assets/jquery-datatable/jquery.dataTables.js', __FILE__), array(
            'jquery'
        ), '', true);
        $values = array(
            'showing' => __("showing",'woo-inquiry'),
            'to' => __("to",'woo-inquiry'),
            'of' => __("of",'woo-inquiry'),
            'entries' => __("entries",'woo-inquiry'),
            'search' => __("search",'woo-inquiry'),
            'export' => __("export",'woo-inquiry'),
            'Previous' => __("Previous",'woo-inquiry'),
            'Next' => __("Next",'woo-inquiry'),
            'nodata' => __("No records to display!",'woo-inquiry')
        );
        wp_localize_script('woo_wpinq_admin_j_dt', 'woo_admin', $values);
        wp_enqueue_script('woo_wpinq_admin_j_dt');
        
        wp_enqueue_script('woo_wpinq_bootstrape_js', plugins_url('assets/bootstrap/js/bootstrap.js', __FILE__), array(
            'jquery'
        ), '', true);
        wp_enqueue_script('woo_wpinq_admin_j_dtb', plugins_url('assets/jquery-datatable/skin/bootstrap/js/dataTables.bootstrap.js', __FILE__), array(
            'jquery'
        ), '', true);
        wp_enqueue_script('woo_wpinq_admin_j_dtbu', plugins_url('assets/jquery-datatable/extensions/export/dataTables.buttons.min.js', __FILE__), array(
            'jquery'
        ), '', true);
        wp_enqueue_script('woo_wpinq_admin_j_dtf', plugins_url('assets/jquery-datatable/extensions/export/buttons.flash.min.js', __FILE__), array(
            'jquery'
        ), '', true);
        wp_enqueue_script('woo_wpinq_admin_j_dtzip', plugins_url('assets/jquery-datatable/extensions/export/jszip.min.js', __FILE__), array(
            'jquery'
        ), '', true);
        wp_enqueue_script('woo_wpinq_admin_j_dtpdf', plugins_url('assets/jquery-datatable/extensions/export/pdfmake.min.js', __FILE__), array(
            'jquery'
        ), '', true);
        wp_enqueue_script('woo_wpinq_admin_j_dtfont', plugins_url('assets/jquery-datatable/extensions/export/vfs_fonts.js', __FILE__), array(
            'jquery'
        ), '', true);
        wp_enqueue_script('woo_wpinq_admin_j_dthb', plugins_url('assets/jquery-datatable/extensions/export/buttons.html5.min.js', __FILE__), array(
            'jquery'
        ), '', true);
        wp_enqueue_script('woo_wpinq_admin_j_dthp', plugins_url('assets/jquery-datatable/extensions/export/buttons.print.min.js', __FILE__), array(
            'jquery'
        ), '', true);
        wp_enqueue_script('woo_wpinq_j_mask', plugins_url('assets/js/jquery.mask.js', __FILE__), array(
            'jquery'
        ), '', true);
        wp_register_script('woo_wpinq_admin_reports', plugins_url('assets/js/reports.js', __FILE__), array(
            'jquery'
        ), '6.0.1', true);
        $values = array(
            'wpajax' => admin_url('admin-ajax.php'),
            'jsonfile' => plugins_url('assets/json/report.json', __FILE__),
            'deletefile' => plugins_url('assets/json/delete.json', __FILE__),
            'phpversion' => get_option('woo_wpinq_php_version_min'),
            'notifnotic' => __("Your browser blocked notification sound , please play this sound",'woo-inquiry'),
            'userwarn' => __("Are you sure you want to delete all the users of this list?",'woo-inquiry'),
            'hiswarn' => __("Are you sure you want to delete history?this action will delete 'non-working hours' and 'missed' list too!",'woo-inquiry'),
            'toffwarn' => __("Are you sure?",'woo-inquiry'),
            'tupwarn' => __("Are you sure?",'woo-inquiry'),
            'confwarn' => __("Are you sure?",'woo-inquiry'),
            'pricewarn' => __("Please enter price first",'woo-inquiry')
        );
        wp_localize_script('woo_wpinq_admin_reports', 'woo_reports', $values);
        wp_enqueue_script('woo_wpinq_admin_reports');
        
      }
    
  }
add_action('admin_enqueue_scripts', 'woo_wpinq_add_admin_scripts', 10, 1);



function woo_wpinq_scripts_method()
  {
    wp_enqueue_script('jquery');
    wp_enqueue_script('jquery-ui-dialog');
    wp_enqueue_script('jquery-ui-tabs');
  }
add_action('wp_enqueue_scripts', 'woo_wpinq_scripts_method');




///////////////////////////////////////////////////////////////// set options on activation

function woo_wpinq_plugin_activation()
  {
    if (!get_option('woo_wpinq_options'))
      {
        $default = array(
            'woo_wpinq_add_to_cart_text' => __('price inquiry','woo-inquiry'),
            'woo_wpinq_force_contact_form' => '',
            'woo_wpinq_force_contact_form_message' => '',
            'woo_wpinq_outofstock_message' => __('Out Of Stock','woo-inquiry'),
            'woo_wpinq_store_morning' => '',
            'woo_wpinq_web_tone' => WOO_INQUIRY_PLUGIN_URL . "/assets/ring/tone.mp3",
            'woo_wpinq_store_night' => '',
            'woo_wpinq_maximum_time' => '6',
            'woo_wpinq_shopping_limit' => '60',
            'woo_wpinq_modal_notes' => __('maximum response time of the administrator is {timer}<br>
                                        please do not reload the page until the inquiry’s status is determined<br>
                                        after inquiry, you have only {limit} to complete your purchase, otherwise the item will be removed from your cart and you must inquire it again<br>','woo-inquiry'),
            'woo_wpinq_timeout_message' => __('Our colleagues are currently unable to respond. Please enter your contact information in the form below. In a few minutes, one of our colleagues will call you.','woo-inquiry'),
            'woo_wpinq_timeoff_message' => __('Our store is closed now. Please enter your contact information in the form below to contact you at the next working day.','woo-inquiry'),
            'woo_wpinq_notic_text' => __('The price of this product is not updated ... Please click on the price inquiry button','woo-inquiry'),
            'woo_wpinq_contact_form_msg' => __("Thank you for being in touch with us. Soon, one of our colleagues will contact you",'woo-inquiry'),
            'woo_wpinq_notification_position' => 'very-bottom',
            'woo_wpinq_price_style' => 'normal',
            'woo_wpinq_hashur_color' => 'black',
            'woo_wpinq_price_style_shop' => 'normal',
            'woo_wpinq_hashur_color_shop' => 'black',
            'woo_wpinq_price_style_shop_icon' => '',
            'woo_wpinq_Bot_iranian' => '',
            'woo_wpinq_bot_token' => '',
            'woo_wpinq_notif_emails' => get_bloginfo('admin_email'),
            'woo_wpinq_clock_color' => '#ff3a3a',
            'woo_wpinq_popup_header_color' => '#ffffff',
            'woo_wpinq_popup_header_background' => '#ff4c4c',
            'woo_wpinq_modal_header_font_size' => '18',
            'woo_wpinq_popup_body_text' => '#0c0c0c',
            'woo_wpinq_popup_body_background' => '#f9f9f9',
            'woo_wpinq_modal_font_size' => '13',
            'woo_wpinq_notification_color' => '#ffffff',
            'woo_wpinq_notification_background' => '#2177d3',
            'woo_wpinq_notif_text_size' => '20',
            'woo_wpinq_notif_font_style' => 'normal',
            'woo_wpinq_notif_font_width' => 'normal',
            'woo_wpinq_notif_round' => '0',
            'woo_wpinq_notif_padding' => '10',
            'woo_wpinq_notif_border_width' => '0',
            'woo_wpinq_notif_border_color' => '',
            'woo_wpinq_notif_border_style' => 'solid',
            'woo_wpinq_inquiry_price_text' => '#ffffff',
            'woo_wpinq_inquiry_price_background' => '#004cdb',
            'woo_wpinq_inquiry_price_size' => '20',
            'woo_wpinq_inquiry_price_round' => '29',
            'woo_wpinq_inquiry_price_padding' => '10',
            'woo_wpinq_price_animation' => 'pulse',
            'woo_wpinq_price_animation_repeat' => 'infinite',
            'woo_wpinq_price_animation_duration' => '1',
            'woo_wpinq_price_border_width' => '2',
            'woo_wpinq_price_border_color' => '#ffffff',
            'woo_wpinq_price_border_style' => 'solid',
            'woo_wpinq_custom_styles' => ''
        );
        update_option('woo_wpinq_options', $default);
      }
    
    
    global $wpdb;
    
    $woo_wpinq_db = 'woo_wpinq_requests';
    if ($wpdb->get_var("show tables like '$woo_wpinq_db'") != $woo_wpinq_db)
      {
        $sql = "CREATE TABLE " . $woo_wpinq_db . " ( 
        `id` int NOT NULL AUTO_INCREMENT, 
        `ip` TEXT CHARACTER SET utf8mb4 COLLATE utf8mb4_bin NOT NULL, 
        `product` TEXT CHARACTER SET utf8mb4 COLLATE utf8mb4_bin NOT NULL,	
        `varid` TEXT CHARACTER SET utf8mb4 COLLATE utf8mb4_bin NOT NULL, 
        `price` TEXT CHARACTER SET utf8mb4 COLLATE utf8mb4_bin NOT NULL,	
        `msgid` TEXT CHARACTER SET utf8mb4 COLLATE utf8mb4_bin NOT NULL,
        `msg` TEXT CHARACTER SET utf8mb4 COLLATE utf8mb4_bin NOT NULL,	
        `status` TEXT CHARACTER SET utf8mb4 COLLATE utf8mb4_bin NOT NULL,
        `cart_key` TEXT CHARACTER SET utf8mb4 COLLATE utf8mb4_bin NOT NULL,		
        `time` TEXT CHARACTER SET utf8mb4 COLLATE utf8mb4_bin NOT NULL,	
        `date` DATE NOT NULL,			
        UNIQUE KEY id (id) 
        );";
        
        require_once(ABSPATH . 'wp-admin/includes/upgrade.php');
        dbDelta($sql);
      }
    
    
    $woo_wpinq_db = 'woo_wpinq_bot_admin';
    if ($wpdb->get_var("show tables like '$woo_wpinq_db'") != $woo_wpinq_db)
      {
        $sql = "CREATE TABLE " . $woo_wpinq_db . " ( 
        `id` int NOT NULL AUTO_INCREMENT, 
        `uid` TEXT CHARACTER SET utf8mb4 COLLATE utf8mb4_bin NOT NULL, 
        `price` TEXT CHARACTER SET utf8mb4 COLLATE utf8mb4_bin NOT NULL,	
        `msgid` TEXT CHARACTER SET utf8mb4 COLLATE utf8mb4_bin NOT NULL,
        `uniqid` TEXT CHARACTER SET utf8mb4 COLLATE utf8mb4_bin NOT NULL,
        `wpid` TEXT CHARACTER SET utf8mb4 COLLATE utf8mb4_bin NOT NULL,
        `role` TEXT CHARACTER SET utf8mb4 COLLATE utf8mb4_bin NOT NULL,		
        `step` int NOT NULL,
        `status` int NOT NULL,		
        UNIQUE KEY id (id) 
        );";
        
        require_once(ABSPATH . 'wp-admin/includes/upgrade.php');
        dbDelta($sql);
      }
    
    
    
    $woo_wpinq_db = 'woo_wpinq_worktime_items';
    if ($wpdb->get_var("show tables like '$woo_wpinq_db'") != $woo_wpinq_db)
      {
        $sql = "CREATE TABLE " . $woo_wpinq_db . " ( 
        `id` int NOT NULL AUTO_INCREMENT, 
        `pid` TEXT CHARACTER SET utf8mb4 COLLATE utf8mb4_bin NOT NULL, 
        `varid` TEXT CHARACTER SET utf8mb4 COLLATE utf8mb4_bin NOT NULL,	
        `price` TEXT CHARACTER SET utf8mb4 COLLATE utf8mb4_bin NOT NULL, 
        `uniq` TEXT CHARACTER SET utf8mb4 COLLATE utf8mb4_bin NOT NULL,	
        UNIQUE KEY id (id) 
        );";
        
        require_once(ABSPATH . 'wp-admin/includes/upgrade.php');
        dbDelta($sql);
      }
    
    
    $woo_wpinq_db = 'woo_wpinq_sms_history';
    if ($wpdb->get_var("show tables like '$woo_wpinq_db'") != $woo_wpinq_db)
      {
        $sql = "CREATE TABLE " . $woo_wpinq_db . " ( 
        `id` int NOT NULL AUTO_INCREMENT, 
        `uid` TEXT CHARACTER SET utf8mb4 COLLATE utf8mb4_bin NOT NULL, 
        `admin` TEXT CHARACTER SET utf8mb4 COLLATE utf8mb4_bin NOT NULL,	
        `msgbody` TEXT CHARACTER SET utf8mb4 COLLATE utf8mb4_bin NOT NULL, 
        `reqid` TEXT CHARACTER SET utf8mb4 COLLATE utf8mb4_bin NOT NULL,	
        `status` TEXT CHARACTER SET utf8mb4 COLLATE utf8mb4_bin NOT NULL,			
        UNIQUE KEY id (id) 
        );";
        
        require_once(ABSPATH . 'wp-admin/includes/upgrade.php');
        dbDelta($sql);
      }
    
    $woo_wpinq_db = 'woo_wpinq_users';
    if ($wpdb->get_var("show tables like '$woo_wpinq_db'") != $woo_wpinq_db)
      {
        $sql = "CREATE TABLE " . $woo_wpinq_db . " ( 
        `id` int NOT NULL AUTO_INCREMENT, 
        `reqid` TEXT CHARACTER SET utf8mb4 COLLATE utf8mb4_bin NOT NULL, 
        `ip` TEXT CHARACTER SET utf8mb4 COLLATE utf8mb4_bin NOT NULL, 		
        `name` TEXT CHARACTER SET utf8mb4 COLLATE utf8mb4_bin NOT NULL,	
        `phone` TEXT CHARACTER SET utf8mb4 COLLATE utf8mb4_bin NOT NULL,
        UNIQUE KEY id (id) 
        );";
        
        require_once(ABSPATH . 'wp-admin/includes/upgrade.php');
        dbDelta($sql);
      }
    
    
    
    
    if (!get_option('woo_wpinq_access_code'))
      {
        update_option('woo_wpinq_access_code', wp_generate_password(16, false));
      }
    
    if (!get_option('woo_wpinq_vendors_access_code'))
      {
        update_option('woo_wpinq_vendors_access_code', wp_generate_password(50, false));
      }
    
    if (!get_option('woo_wpinq_current_date'))
      {
        update_option('woo_wpinq_current_date', date('Y-m-d'));
      }
  }


register_activation_hook(__FILE__, 'woo_wpinq_plugin_activation');




function woo_wpinq_add_query_vars_filter($vars)
  {
    $vars[] = "AccessCode";
    return $vars;
  }
add_filter('query_vars', 'woo_wpinq_add_query_vars_filter');



add_action('wp', 'woo_wpinq_load_after_wp');
function woo_wpinq_load_after_wp()
  {
   

    
    $woo_wpinq_options = get_option('woo_wpinq_options');
    
    ///////////check for store worktime
    if ($woo_wpinq_options['woo_wpinq_store_morning'] and $woo_wpinq_options['woo_wpinq_store_night'])
      {
        $woo_history_deleter = get_option('woo_wpinq_remove_day_history');
        $current_time        = current_time('timestamp', true);
        $morning             = woo_wpinq_wp_strtotime($woo_wpinq_options['woo_wpinq_store_morning']);
        $night               = woo_wpinq_wp_strtotime($woo_wpinq_options['woo_wpinq_store_night']);
        
        
        if ($current_time > $morning and $current_time < $night)
          {
            //open
            if ($woo_history_deleter)
              {
                delete_option('woo_wpinq_remove_day_history');
              }
            
          }
        else
          {
            
            //close
            if (!$woo_history_deleter)
              {
                global $wpdb;
                update_option('woo_wpinq_remove_day_history', 'on');
                $wpdb->query('TRUNCATE TABLE woo_wpinq_worktime_items');
                
                $json['uniq0'] = array(
                    'price' => 0,
                    'pid' => 0,
                    'varid' => 0,
                    'mode' => ''
                );
                
                file_put_contents(WOO_INQUIRY_PLUGIN_FILE . 'assets/json/callback.json', json_encode($json));
                
                
              }
            
          }
      }
    
    
    ////delete jsonfile
    $sql_day   = date_create(get_option('woo_wpinq_current_date'));
    $today_now = date_create(date('Y-m-d'));
    $diff_days = date_diff($sql_day, $today_now);
    
    if ($diff_days->d > 0)
      {
        $json['uniq0'] = array(
            'price' => 0,
            'pid' => 0,
            'varid' => 0,
            'mode' => ''
        );
        
        file_put_contents(WOO_INQUIRY_PLUGIN_FILE . 'assets/json/callback.json', json_encode($json));
        update_option('woo_wpinq_current_date', date('Y-m-d'));
        woo_wpinq_check_min_php_version();
      }
    
    if (get_query_var('AccessCode'))
      {
        if (get_query_var('AccessCode') == get_option('woo_wpinq_access_code'))
          {
            
            include 'bot/rest.php';
            
            
          }
      }
    
  }
  