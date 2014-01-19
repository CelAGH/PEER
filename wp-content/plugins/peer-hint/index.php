<?php

/*

Plugin Name: Peer Hint
Author: Kamil Wojtczak (kamilw7@gmail.com)
Description: Add hint on PEER page


*/

add_action( 'wp_print_styles', 'enqueue_my_styles' );

function enqueue_my_styles(){

wp_enqueue_style( 'http_css', plugins_url('peer-hint/hint.css'));

//---------------------------------------------------------------------------------------

function add_hint($atts){

extract(shortcode_atts(array(
      'text' => 1,
	'normaltext' => 1,
   ), $atts));

ob_start();
echo '<div id="hint"><p>   ' . $text . '</p><p><div id="normalhint"> ' . $normaltext . ' </div></div>';
return ob_get_clean(); 
}

add_shortcode('hint', 'add_hint');


//---------------------------------------------------------------------------------------

}


?>