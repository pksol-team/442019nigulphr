<?php


function frontend_hr_plugin_scripts_handler() {

	$post = get_post(get_the_ID());

	if (strpos($post->post_content,'[feature_widget]') != false or $post->post_content == '[feature_widget]' or (strpos($_SERVER['REQUEST_URI'], 'human_recource_listings') !== FALSE) ) {

		$plugin_url = plugins_url('human-resource');

		wp_register_style( 'custom_hr_wp_global_css', $plugin_url . '/assets/css/global.css', false );
		wp_enqueue_style( 'custom_hr_wp_global_css' );


		wp_register_script( 'hr_frontend_javascript', $plugin_url . '/assets/js/global.js', false, '1.0', 'in_footer' );
		wp_enqueue_script( 'hr_frontend_javascript' );

    }

}

add_action( 'wp_enqueue_scripts', 'frontend_hr_plugin_scripts_handler' );


function load_custom_hr_wp_admin_style() {

	if((strpos($_SERVER['REQUEST_URI'], 'human_recource_listings') !== FALSE)) {

		$plugin_url = plugins_url('human-resource');

		wp_register_style( 'custom_hr_wp_global_css', $plugin_url . '/assets/css/global.css', false );
		wp_enqueue_style( 'custom_hr_wp_global_css' );

		wp_register_style( 'custom_hr_wp_admin_css', $plugin_url . '/assets/css/admin.css', ['custom_hr_wp_global_css'] );
		wp_enqueue_style( 'custom_hr_wp_admin_css' );

		wp_register_script( 'hr_frontend_javascript', $plugin_url . '/assets/js/global.js', false, '1.0', 'in_footer' );
		wp_enqueue_script( 'hr_frontend_javascript' );

	}

}

add_action( 'admin_enqueue_scripts', 'load_custom_hr_wp_admin_style' );


add_shortcode( 'feature_widget', 'Frontned_data_handler' );

function Frontned_data_handler() {

	$plugin_url = plugins_url('/human-resource');

	global $wpdb;
	$prefix = $wpdb->prefix;
	$person_row = $wpdb->get_results("SELECT * FROM {$prefix}features_of_person", OBJECT);


	$rows = '';

	if(count($person_row) > 0) {

		foreach ($person_row as $key1 => $person_single) {

			$querystr = "SELECT * FROM {$prefix}features_of_training WHERE id IN ($person_single->training) ";
			$training_row = $wpdb->get_results($querystr, OBJECT);

			$Training = '';
			$Time = '';
			$Extend = '';
			$Name = '';
			$Provider = '';
			$duration_training = '';

			$duration_training = explode(',', $person_single->duration);

			if(count($training_row) > 0) {

				foreach ($training_row as $key => $train_row) {


					$Training .= $train_row->name.' ('.$duration_training[$key].') <br>';
					$Time .= $train_row->time_frame_training.'<br>';
					$Extend .= $train_row->extend_of_study_credits.'<br>';
					$Name .= $train_row->name_of_trainer.'<br>';
					$Provider .= $train_row->provider_of_training.'<br>';

				}

			}

			$rows .= '<tr>
				<td>'.($key1+1).'</td>
				<td>'.ucwords($person_single->name).'</td>
				<td>'.ucwords($person_single->location).'</td>
				<td>'.ucwords($person_single->department).'</td>
				<td>'.ucwords($person_single->phone).'</td>
				<td>'.ucwords($person_single->email).'</td>
				<td>'.$Training.'</td>
				<td>'.$Time.'</td>
				<td>'.$Extend.'</td>
				<td>'.$Name.'</td>
				<td>'.$Provider.'</td>
			</tr>';
		}

	}

	echo '
		<link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/1.10.19/css/jquery.dataTables.min.css">

		<div class="loadingss">
			<img src="'.$plugin_url.'/assets/img/loader.gif" width="100">
		</div>

		<div class="s003">




		<h2 class="product-search">Search Product</h2>

			<form class="search-submit-form">
				<div class="inner-form">
					<div class="input-field first-wrap">
						<label>Person\'s Name</label>
						<input type="text" name="person-name">
					</div>

					<div class="input-field first-wrap">
						<label>Training\'s name</label>
						<input type="text" name="training-name">
					</div>

					<div class="input-field first-wrap">
						<label>Location</label>
						<input type="text" name="location">
					</div>
					<button type="submit" class="input-field first-wrap">Search</button>
				</div>
			</form>

			<div style="overflow-x:auto; clear: both;" id="talbe_data">
				<table id="table-data" style="width: 100%;">
					<thead>
						<tr>
							<th>#</th>
							<th>Name</th>
							<th>Location</th>
							<th>Department</th>
							<th>Phone</th>
							<th>Email</th>
							<th>Training (Period/Duration)</th>
							<th>Time Frame</th>
							<th>Extend of Study Credits</th>
							<th>Name of Trainer</th>
							<th>Provider of Trainer</th>
						</tr>
					</thead>
					<tbody>
						'.$rows.'
					</tbody>
				</table>
			</div>
		</div>


	';

}


