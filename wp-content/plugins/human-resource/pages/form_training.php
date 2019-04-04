<?php 

function human_recource_training_add_hander() {
    

    
    global $wpdb;
    $table_name = $wpdb->prefix . 'features_of_training'; 

    $message = '';
    $notice = '';


    $default = array(
        'id' => 0,
        'name' => '',
        'time_frame_training' => '',
        'extend_of_study_credits' => '',
        'name_of_trainer' => '',
        'provider_of_training' => '',
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

    
    add_meta_box('training_form_meta_box', __('Feature of Training data', 'wpbc'), 'wpbc_training_form_meta_box_handler', 'training_feature', 'normal', 'default'); ?>
<div class="wrap">
    <div class="icon32 icon32-posts-post" id="icon-edit"><br></div>
    <h2><?php _e('Feature of Training', 'wpbc')?> <a class="add-new-h2" href="<?php echo get_admin_url(get_current_blog_id(), 'admin.php?page=human_recource_training');?>"><?php _e('back to list', 'wpbc') ?></a>
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

                    <?php do_meta_boxes('training_feature', 'normal', $item); ?>
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


function wpbc_training_form_meta_box_handler($item) { ?>
<tbody>
    <style>
        div.postbox { width: 75%; }
    </style>	
        
    <div class="formdata">
        
    <form>
        <p>			
            <label for="name"><?php _e('Name of training:', 'wpbc')?></label>
            <br>	
            <input id="name" name="name" type="text" style="width: 60%" value="<?php echo esc_attr($item['name'])?>"
                    required>
        </p>

        <p>			
            <label for="time_frame_training"><?php _e('Interval/time frame of training:', 'wpbc')?></label>
            <br>	
            <input id="time_frame_training" name="time_frame_training" type="text" style="width: 60%" value="<?php echo esc_attr($item['time_frame_training'])?>"
                    required>
        </p>

        <p>			
            <label for="extend_of_study_credits"><?php _e('Extent of study credits:', 'wpbc')?></label>
            <br>	
            <input id="extend_of_study_credits" name="extend_of_study_credits" type="text" style="width: 60%" value="<?php echo esc_attr($item['extend_of_study_credits'])?>"
                    required>
        </p>

        <p>			
            <label for="name_of_trainer"><?php _e('Name of trainer:', 'wpbc')?></label>
            <br>	
            <input id="name_of_trainer" name="name_of_trainer" type="text" style="width: 60%" value="<?php echo esc_attr($item['name_of_trainer'])?>"
                    required>
        </p>

        <p>			
            <label for="provider_of_training"><?php _e('Provider of training:', 'wpbc')?></label>
            <br>	
            <input id="provider_of_training" name="provider_of_training" type="text" style="width: 60%" value="<?php echo esc_attr($item['provider_of_training'])?>"
                    required>
        </p>

        

        </form>
        </div>
</tbody>

<?php
}
