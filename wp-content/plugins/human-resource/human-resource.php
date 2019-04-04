<?php

/*
Plugin Name: Human Resource
Description: Human Resouce Plugin
Version: 1.0
Author: PK SOL
Author URI: https://www.pksol.com
*/

register_activation_hook( __FILE__, 'my_plugin_activation' );
function my_plugin_activation() {
	require(dirname(__FILE__). '/activation.php');
}

function enqueue_select2_jquery() {
    wp_register_style( 'select2css', '//cdnjs.cloudflare.com/ajax/libs/select2/3.4.8/select2.css', false, '1.0', 'all' );
    wp_register_script( 'select2', '//cdnjs.cloudflare.com/ajax/libs/select2/3.4.8/select2.js', array( 'jquery' ), '1.0', true );
    wp_enqueue_style( 'select2css' );
    wp_enqueue_script( 'select2' );
}
add_action( 'admin_enqueue_scripts', 'enqueue_select2_jquery' );

add_action( 'admin_footer', 'admin_plugin_script' );

function admin_plugin_script() {
	echo "<script>
		jQuery(document).ready(function($) {
			$('.select2_add').select2({
				placeholder: 'Type',
			});

			$('.select2_add').css('margin-top', '3px');
		});
	</script>";
}


$dire = scandir(dirname(__FILE__).'/pages');

foreach ($dire as $key => $files) {
	if ($files != '.' && $files != '..') {
		require(dirname(__FILE__). '/pages/'.$files);
	}
	
}

