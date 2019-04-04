<?php 

function feature_shortcode_styles() {
	
    $post = get_post(get_the_ID());

    if (strpos($post->post_content,'[feature_widget]') != false or $post->post_content == '[feature_widget]' ) {
		
        $plugin_url = plugins_url('/Inquiry-form');
        
        echo "<script>
            alert('working');
        </script>";

        // echo '<link rel="stylesheet" href="'.$plugin_url.'/assets/css/register.css?time='.time().'">';

    }

}

add_action('wp_head', 'feature_shortcode_styles', 0);