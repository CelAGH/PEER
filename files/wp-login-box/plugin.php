<?php
/*
Plugin Name: WP Login Box
Plugin URI: http://en.itzoovim.com/plugins/wordpress-login-form/
Description: WP Login Box lets you add a wordpress login form to your website.
Version: 1.06
Author: Ziv, Itzoovim
Author URI: en.itzoovim.com
*/
$wplb_style = "light";
$data = get_option('wplb_options');

if ($data['style'] != "") {
	$wplb_style = $data['style'];
}

//include the main class file
require_once("admin/admin-page-class.php");

 $config = array(
    'menu'=> 'settings',                 //sub page to settings page
    'page_title' => 'WPLB Options',   //The name of this page
    'capability' => 'edit_themes',       // The capability needed to view the page
    'option_group' => 'wplb_options',    //the name of the option to create in the database
    'id' => 'admin_page',                // Page id, unique per page
    'fields' => array(),                 // list of fields (can be added by field arrays)
    'local_images' => false,             // Use local or hosted images (meta box images for add/remove)
    'use_with_theme' => false            //change path if used with theme set to true, false for a plugin or anything else for a custom path(default false).
); 
 
 /**
 * Initiate your admin page
 */
 
$options_panel = new BF_Admin_Page_Class($config);


/**
 * define your admin page tabs listing
 */
$options_panel->OpenTabs_container('');
$options_panel->TabsListing(array(
   'links' => array(
   'options_1' =>  __('')
   )
));

// Open admin page first tab with the id options_1
$options_panel->OpenTab('options_1');

/**
* Add fields to your admin page first tab
*
* Simple options:
* input text, checbox, select, radio
* textarea
*/

//title
$options_panel->Title('Thank you for downloading and installing this plugin. <br /> On this page you will be able to customize it to your liking. <br /> To include the login box in your site, just paste the following function to any area you desire:<br /> <strong>&lt;?php
if&nbsp;
(function_exists (&#39;wplb_login&#39;))&nbsp;&nbsp;&nbsp;{
wplb_login();
}
?&gt;</strong> <br />If this plugin helped you giving it a good rating would be great!');
//text field
$options_panel->addText('greeting',array('name'=> 'Greeting Text'));
$options_panel->addText('inred',array('name'=> 'Redirect after login to this url (full url): '));
$options_panel->addText('outred',array('name'=> 'Redirect after logout to this url (Must be from this site. Only the part after your site'."'s".' name. (For example, example.com/<strong>yourlink</strong>): '));
$options_panel->addText('reglink',array('name'=> 'Custom Registration Form link (leave empty if you do not have one, enter a full URL): '));
$options_panel->addText('uremembermetext',array('name'=> 'Custom remember me checkbox text (leave empty if you do not have one): '));
$options_panel->addText('ulogintext',array('name'=> 'Custom login button text (leave empty if you do not have one): '));
$options_panel->addText('uregistertext',array('name'=> 'Custom register link text (leave empty if you do not have one): '));
$options_panel->addText('uforgottext',array('name'=> 'Custom forgot your password link text (leave empty if you do not have one, enter a full URL): '));
$options_panel->addText('uprofilelink',array('name'=> 'Custom your profile link (leave empty if you do not have one, enter a full URL): '));
$options_panel->addText('uprofiletext',array('name'=> 'Custom your profile link text (leave empty if you do not have one): '));
$options_panel->addText('ulogouttext',array('name'=> 'Custom logout link text (leave empty if you do not have one): '));
$options_panel->addCheckbox('wplogin',array('name'=> ' <br><strong>*****</strong> Disable WP-Login redirects? '));
$options_panel->addCheckbox('register',array('name'=> ' <br>Enable the Register link? '));
$options_panel->addCheckbox('forgot',array('name'=> ' <br>Enable the Forgot Your Password link? '));
$options_panel->addCheckbox('profile',array('name'=> ' <br> Enable the Profile link? <strong>(Only appears when logged in)</strong>   '));
$options_panel->addCheckbox('logout',array('name'=> ' <br>Enable the Logout link? <strong>(Only appears when logged in)</strong>     '));
//select field
$options_panel->addSelect('style',array('blue'=>'Blue','green'=>'Green','dark'=>'Dark','light'=>'Light'),array('name'=> ' <br>Select a style', 'std'=> array('selectkey2')));
//radio field
$options_panel->addRadio('float',array('left'=>'Left            ','right'=>'Right'),array('name'=> ' <br>Do you want the login box to float (align) to the left, or to the rigth?<br>', 'std'=> array('radionkey2')));

$options_panel->addParagraph("<br><br><h3>*** This option will make sure your users are not redirect to wp-login.php after a failed login attempty. This option will affect your entire theme. ****</h3>");
// Close first tab
$options_panel->CloseTab();

function wplb_login() {
    if(is_user_logged_in()) {
	    include "in.php";		
	} else {
        include "out.php";
	}
}
function my_front_end_login_fail() {
    // Get the reffering page, where did the post submission come from?
    $referrer = $_SERVER['HTTP_REFERER'];
 
    // if there's a valid referrer, and it's not the default log-in screen
    if(!empty($referrer) && !strstr($referrer,'wp-login') && !strstr($referrer,'wp-admin')){
        // let's append a query string to the URL for the plugin to use
        wp_redirect($referrer . '?failed_login&failed_login'); 
    exit;
    }
}

if ($data['wplogin'] == "1") {
    // hook failed login
    add_action('wp_login_failed', 'my_front_end_login_fail'); 
}

// Add settings link on plugin page
function your_plugin_settings_link($links) { 
    $settings_link = '<a href="options-general.php?page=options-general.php_wplb_options">Settings</a>'; 
    array_unshift($links, $settings_link); 
    return $links; 
}
 
$plugin = plugin_basename(__FILE__); 
add_filter("plugin_action_links_$plugin", 'your_plugin_settings_link' );
wp_register_style( 'wplbstyle', plugins_url("styles/".$wplb_style.".css", __FILE__) );

if (!is_admin()) {
    wp_enqueue_style( 'wplbstyle' );
}
?>