<?php 

if (!class_exists('WP_List_Table')) {
    require_once(ABSPATH . 'wp-admin/includes/class-wp-list-table.php');
}


add_action('admin_menu', 'hr_create_menu');
function hr_create_menu() {
    
    add_menu_page("HR", "Human Resource", "administrator", "human_recource", "human_recource_persons_hander",  'dashicons-admin-users');
    add_submenu_page("human_recource", "Feature of Person", "Feature of Person", "administrator", "human_recource", "human_recource_persons_hander");
    add_submenu_page("human_recource", "Add Feature of Person", "Add Feature of Person", "administrator", "human_recource_add_person", "human_recource_persons_add_hander");
    
    add_submenu_page("human_recource", "Feature of Training", "Feature of Training", "administrator", "human_recource_training", "human_recource_training_hander");
    add_submenu_page("human_recource", "Add Feature of Training", "Add Feature of Training", "administrator", "human_recource_training_add", "human_recource_training_add_hander");
    

}