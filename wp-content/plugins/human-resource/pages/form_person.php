<?php

function human_recource_persons_add_hander() {

    global $wpdb;
    $table_name = $wpdb->prefix . 'features_of_person';

    $message = '';
    $notice = '';


    $default = array(
        'id' => 0,
        'name' => '',
        'location' => '',
        'department' => '',
        'phone' => '',
        'email' => '',
        'training' => '',
        'duration' => ''
    );

    if ( isset($_REQUEST['nonce']) && wp_verify_nonce($_REQUEST['nonce'], basename(__FILE__))) {

        $item = shortcode_atts($default, $_REQUEST);

        $item_valid = true; //wpbc_validate_contact($item);
        if ($item_valid === true) {
            if ($item['id'] == 0) {
                $result = $wpdb->insert($table_name, $item);
                $item['id'] = $wpdb->insert_id;
                if ($result) {
                    $message = __('Item was successfully saved', 'wpbc');
                } else {
                    $notice = __('There was an error while saving item', 'wpbc');
                }
            } else {
                $result = $wpdb->update($table_name, $item, array('id' => $item['id']));
                if ($result) {
                    $message = __('Item was successfully updated', 'wpbc');
                } else {
                    $notice = __('There was an error while updating item', 'wpbc');
                }
            }
        } else {

            $notice = $item_valid;
        }
    }
    else {

        $item = $default;
        if (isset($_REQUEST['id'])) {
            $item = $wpdb->get_row($wpdb->prepare("SELECT * FROM $table_name WHERE id = %d", $_REQUEST['id']), ARRAY_A);
            if (!$item) {
                $item = $default;
                $notice = __('Item not found', 'wpbc');
            }
        }
    }


    add_meta_box('person_form_meta_box', __('Feature of Person data', 'wpbc'), 'wpbc_person_form_meta_box_handler', 'person_feature', 'normal', 'default'); ?>


<script type="text/javascript" src="https://cdn.jsdelivr.net/jquery/latest/jquery.min.js"></script>
<script type="text/javascript" src="https://cdn.jsdelivr.net/momentjs/latest/moment.min.js"></script>
<script type="text/javascript" src="https://cdn.jsdelivr.net/npm/daterangepicker/daterangepicker.min.js"></script>
<link rel="stylesheet" type="text/css" href="https://cdn.jsdelivr.net/npm/daterangepicker/daterangepicker.css" />


<div class="wrap">
    <div class="icon32 icon32-posts-post" id="icon-edit"><br></div>
    <h2><?php _e('Feature of Training', 'wpbc')?> <a class="add-new-h2" href="<?php echo get_admin_url(get_current_blog_id(), 'admin.php?page=human_recource');?>"><?php _e('back to list', 'wpbc') ?></a>
    </h2>

    <?php if (!empty($notice)): ?>
    <div id="notice" class="error"><p><?php echo $notice ?></p></div>
    <?php endif;?>
    <?php if (!empty($message)): ?>
    <div id="message" class="updated"><p><?php echo $message ?></p></div>
    <?php endif;?>

    <form id="form" method="POST">
        <input type="hidden" name="nonce" value="<?php echo wp_create_nonce(basename(__FILE__))?>"/>

        <input type="hidden" name="id" value="<?php echo $item['id'] ?>"/>

        <div class="metabox-holder" id="poststuff">
            <div id="post-body">
                <div id="post-body-content">

                    <?php do_meta_boxes('person_feature', 'normal', $item); ?>
                    <?php
                        $btnname = 'Save';
                        if ($item['id'] != 0) {
                            $btnname = 'Update';
                        }
                    ?>
                    <input type="submit" value="<?= $btnname; ?>" id="submit" class="button-primary" name="submit">
                </div>
            </div>
        </div>
    </form>
</div>
<?php
}


function wpbc_person_form_meta_box_handler($item) { ?>
<tbody>
    <style>
        div.postbox { width: 75%; }
    </style>

    <div class="formdata">

    <form>

        <p>
            <label for="name"><?php _e('Name:', 'wpbc')?></label>
            <br>
            <input id="name" name="name" type="text" style="width: 60%" value="<?php echo esc_attr($item['name'])?>"
                    required>
        </p>
        <p>
            <label for="location"><?php _e('Location:', 'wpbc')?></label>
            <br>
            <input id="location" name="location" type="text" style="width: 60%" value="<?php echo esc_attr($item['location'])?>"
                    required>
        </p>
        <p>
            <label for="department"><?php _e('Department:', 'wpbc')?></label>
            <br>
            <input id="department" name="department" type="text" style="width: 60%" value="<?php echo esc_attr($item['department'])?>"
                    required>
        </p>
        <p>
            <label for="phone"><?php _e('Phone:', 'wpbc')?></label>
            <br>
            <input id="phone" name="phone" type="text" style="width: 60%" value="<?php echo esc_attr($item['phone'])?>"
                    required>
        </p>
        <p>
            <label for="email"><?php _e('Email:', 'wpbc')?></label>
            <br>
            <input id="email" name="email" type="text" style="width: 60%" value="<?php echo esc_attr($item['email'])?>"
                    required>
        </p>
        <p>
            <label for="training"><?php _e('Trainings:', 'wpbc') ?></label>
            <br>

            <?php
			 	global $wpdb;
				$features = $wpdb->get_results( "SELECT * FROM {$wpdb->prefix}features_of_training", OBJECT );

				$featuress = array();

                if (esc_attr($item['training']) != '') {
					$array = explode(',', $item['training']);
					foreach ($array as $key => $value) {
						array_push($featuress, $value);
					}
				}

            ?>

            <select class='trainings select2_add' multiple="multiple" style="width: 60%" required>

                <?php if (count($features) > 0): ?>
                    <?php foreach ($features as $key => $feat): ?>
                        <option value="<?= $feat->id; ?>" <?php if(in_array($feat->id, $featuress)) echo 'selected'; ?> ><?= $feat->name; ?></option>
                    <?php endforeach ?>
                <?php endif; ?>

            </select>

            <input id="training" name="training" type="hidden" value="<?php echo esc_attr($item['training'])?>">


        </p>

        <div class="duraionts">&nbsp;</div>

		<?php
			$data_values = [];

			if (count($features) > 0) {
				$exploded_duration = explode(',', $item['duration']);
				foreach ($features as $key => $feat) {

					if(in_array($feat->id, $featuress)) {

						array_push( $data_values, [$feat->id => $exploded_duration[$key]] );

					}
				}
			}

		?>

        <input type="text" name="duration" autocomplete="off" style="width: 60%" value="<?php echo esc_attr($item['duration']) ?>" data-values='<?= json_encode($data_values); ?>' >

        </form>
        </div>
</tbody>

<script type="text/javascript">

    jQuery(document).ready(function($) {

		const update_data = () => {

			let hidden_data = '';
			$('.duration').each(  (index, element) => {

				let $el = $(element);
				hidden_data += $el.val()+',';

			});

			$('[name="duration"]').val(hidden_data.substring(0, hidden_data.length - 1));

		}


		// get data attriubte values decode it and loop thought it and creaet dynamic inputs of duration
		const fill_values_by_data_attr = () => {

			// get the data value attr and decode it to json
			let data_values =  jQuery.parseJSON($('[name="duration"]').attr('data-values'));


			if(data_values.length != 0) {

				// make sure whenever this function call it will remove all existing inputs then process
				$('.duraionts').html('');

				// loop throught the json
				$.each(data_values, (index, el) => {

					let training_id = Object.keys(el)[0];
					let training_duration = el[training_id];

					// get the label of selected value
					let label_of_training = $('.trainings option[value="'+training_id+'"]').html();

					// html of inputs with lable and value
					let html = `
						<p>
							<label for="Period">Duration of `+label_of_training+`</label>
							<br>
							<input type="text" class="duration" autocomplete="off" style="width: 60%" data-object-id="`+training_id+`">
						</p>
					`;

					// append all inputs to the box
					let appendedEl = $(html).appendTo('.duraionts');

					// select the appended input for apply date picker
					let duration_input = appendedEl.find('.duration');

					if(training_duration != '') {
						let start_date = (training_duration.split('-')[0]).trim();
						let end_date = (training_duration.split('-')[1]).trim();

						let start_month_year = start_date.substr(8);
						let start_date_day = start_date.substr(0, 2);

						let end_month_year = end_date.substr(8);
						let end_date_day = end_date.substr(0, 2);

						var start_calender = new Date(start_month_year+', '+start_date_day);
						var end_calender = new Date(end_month_year+', '+end_date_day);
					} else {
						let start_calender = new Date();
						let end_calender = new Date();
					}

					// date picker basic initializing
					duration_input.daterangepicker({
						autoUpdateInput: false,
						opens: 'top',
						startDate: start_calender,
						endDate: end_calender,
						locale: {
							cancelLabel: 'Clear'
						}
					}).val(training_duration);

					// date picker event for custom date format on start and end
					duration_input.on('apply.daterangepicker', function(ev, picker) {

						var start_day = picker.startDate.format('D') + nth(picker.startDate.format('D'));
						var end_day = picker.endDate.format('D') + nth(picker.endDate.format('D'));

						$(this).val(start_day + picker.startDate.format(' of MMMM YYYY') + ' - ' +  end_day + picker.endDate.format(' of MMMM YYYY')  );
						update_data();


					});

					// make input empty when click on cancel
					duration_input.on('cancel.daterangepicker', function(ev, picker) {

						$(this).val('');
						update_data();

					});

				});

			}

			update_data();

		}


		fill_values_by_data_attr();

        $('.select2_add').change(e => {

			let $this = $(e.currentTarget);
			let array_values =  $this.val();

			let duration_hidden_input = $('[name="duration"]');
			let data_values = jQuery.parseJSON(duration_hidden_input.attr('data-values'));

			let data = '';

			$.each(array_values, (index_of_ids, ids) => {

				if($('[data-object-id="'+ids+'"]').length == 0) {

					data += JSON.stringify( {[ids]: ""})+',';

				} else {

					data += JSON.stringify( {[ids]: $('[data-object-id="'+ids+'"]').val()})+',';

				}

			});

			$('[name="duration"]').attr('data-values', '['+ data.substring(0, data.length-1) +']');

			fill_values_by_data_attr();

		});


    });


</script>

<script type="text/javascript">

function nth(d) {
    if (d > 3 && d < 21) return 'th';
    switch (d % 10) {
        case 1:  return "st";
        case 2:  return "nd";
        case 3:  return "rd";
        default: return "th";
    }
}

</script>


<?php
}
