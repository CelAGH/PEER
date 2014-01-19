<?php

/*

Plugin Name: Redirect user to front page

Plugin URI: http://www.theblog.ca/wplogin-front-page

Description: When a user logs in via wp-login.php directly, redirect them to the front page.

Author: Peter

Version: 1.0

Author URI: http://www.theblog.ca

*/



function redirect_to_front_page() {

    global $redirect_to;
    
	if (!isset($_GET['redirect_to'])) {
	
		$redirect_to = get_option('http://rose.cel.agh.edu.pl/peer_en/');
		
		    }
		    
		    }
		    
		    
		    
		    add_action('login_form', 'redirect_to_front_page');
		    
		    ?>