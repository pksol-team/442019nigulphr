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
        
        <div class="duraionts">


        </div> 
        <input type="hidden" name="duration" autocomplete="off" style="width: 60%" value="<?php echo esc_attr($item['duration']) ?>" >

        </form>
        </div>
</tbody>

<script type="text/javascript">

    jQuery(document).ready(function($) {


        
        if($('.select2_add').val().length > 0) {
            
            print_periods($('.select2_add').val());
            
            var old_duration_value = $('[name="duration"]').val();
            if(old_duration_value.length > 0) {
                
                var old_value = $('[name="duration"]').val().split(',');
                
                $.each(old_value, function (indexInArray, valueOfElement) { 
                    $('.duration').eq(indexInArray).val(old_value[indexInArray]);
                });
                
            }

        }

        
        $('.select2_add').change(function(e) {

            var values = $(this).val();
            var energy = values.join();
            $('[name="training"]').val(energy);

            print_periods(values);
            
            setTimeout(() => {
                update_duration_hidden();
            }, 2000);
            
        });

    });

    function update_duration_hidden() {

        var input_value = '';
        
        $('.duration').each(function (index, element) {

            var $el_val = $(element).val();

            if($el_val != "") {
                input_value += $el_val + ',';
            }
            
        }); 

        $('[name="duration"]').val( input_value.slice(0, -1) );

    }

    function print_periods(values) {

        $('.duraionts').html('');

        $.each(values, function (index, el) {

            var label_of_training = $('.trainings option[value="'+el+'"]').html();

            var html = `
                <p>
                    <label for="Period">Duration of `+label_of_training+`</label>
                    <br>
                    <input type="text" class="duration" autocomplete="off" style="width: 60%">
                </p>
            `;

            var appendedEl = $(html).appendTo('.duraionts');

            var duration_input = appendedEl.find('.duration');

            $('.duration').daterangepicker({
                autoUpdateInput: false,
                locale: {
                    cancelLabel: 'Clear'
                }
            });

            $('.duration').on('apply.daterangepicker', function(ev, picker) {

                var start_day = picker.startDate.format('D') + nth(picker.startDate.format('D'));

                var end_day = picker.endDate.format('D') + nth(picker.endDate.format('D'));

                $(this).val(start_day + picker.startDate.format(' of MMMM YYYY') + ' - ' +  end_day + picker.endDate.format(' of MMMM YYYY')  );
                update_duration_hidden();

            });

            $('.duration').on('cancel.daterangepicker', function(ev, picker) {
                $(this).val('');
                update_duration_hidden();
            });
             
        });

    }

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
