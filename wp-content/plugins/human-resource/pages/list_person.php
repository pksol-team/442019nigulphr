<?php 

function human_recource_persons_hander() {
    
    
class List_person extends WP_List_Table { 
    function __construct()
    {
        global $status, $page;

        parent::__construct(array(
            'singular' => 'person',
            'plural' => 'persons',
        ));
    }

    function column_training($item)
    {
        
        global $wpdb;
        $table_name = $wpdb->prefix . 'features_of_training';

        $training = $item['training'];

        $querystr = "SELECT * FROM $table_name WHERE id IN ($training) ";
        $pageposts = $wpdb->get_results($querystr, OBJECT);

        $string_trains = '';
        foreach ($pageposts as $key => $single_train) {
            $string_trains .= $single_train->name.', ';
        }

        return substr($string_trains, 0, -2 );
        
    }

    function column_default($item, $column_name)
    {
        return $item[$column_name];
    }

    function column_name($item)
    {

        $actions = array(
            'edit' => sprintf('<a href="?page=human_recource_add_person&id=%s">%s</a>', $item['id'], __('Edit', 'wpbc')),
            'delete' => sprintf('<a href="?page=%s&action=delete&id=%s" onclick="return confirm(\'Are you sure?\')" >%s</a>', $_REQUEST['page'], $item['id'], __('Delete', 'wpbc')),
        );

        return sprintf('%s %s',
            $item['name'],
            $this->row_actions($actions)
        );
    }

    function column_cb($item)
    {
        return sprintf(
            '<input type="checkbox" name="id[]" value="%s" />',
            $item['id']
        );
    }

    function get_columns()
    {
        $columns = array(
            'cb' => '<input type="checkbox" />',
            'name' => __('Name', 'wpbc'),
            'location' => __('Location', 'wpbc'),
            'department' => __('Department', 'wpbc'),
            'phone' => __('Phone', 'wpbc'),
            'email' => __('Email', 'wpbc'),
            'training' => __('Trainings', 'wpbc'),
        );
        return $columns;
    }

    function get_sortable_columns()
    {
        $sortable_columns = false;
        return $sortable_columns;
    }

    function get_bulk_actions()
    {
        $actions = array(
            'delete' => 'Delete'
        );
        return $actions;
    }

    function process_bulk_action()
    {
        global $wpdb;
        $table_name = $wpdb->prefix . 'features_of_person'; 

        if ('delete' === $this->current_action()) {
            $ids = isset($_REQUEST['id']) ? $_REQUEST['id'] : array();
            if (is_array($ids)) $ids = implode(',', $ids);

            if (!empty($ids)) {
                $wpdb->query("DELETE FROM $table_name WHERE id IN($ids)");
            }
        }
    }

    function prepare_items()
    {
        global $wpdb;
        $table_name = $wpdb->prefix . 'features_of_person'; 

        $per_page = 10; 

        $columns = $this->get_columns();
        $hidden = array();
        $sortable = $this->get_sortable_columns();
        
        $this->_column_headers = array($columns, $hidden, $sortable);
       
        $this->process_bulk_action();

        $total_items = $wpdb->get_var("SELECT COUNT(id) FROM $table_name");


        $paged = isset($_REQUEST['paged']) ? max(0, intval($_REQUEST['paged']) - 1) : 0;
        $orderby = (isset($_REQUEST['orderby']) && in_array($_REQUEST['orderby'], array_keys($this->get_sortable_columns()))) ? $_REQUEST['orderby'] : 'name';
        $order = (isset($_REQUEST['order']) && in_array($_REQUEST['order'], array('asc', 'desc'))) ? $_REQUEST['order'] : 'asc';


        $this->items = $wpdb->get_results($wpdb->prepare("SELECT * FROM $table_name ORDER BY $orderby $order LIMIT %d OFFSET %d", $per_page, $paged), ARRAY_A);


        $this->set_pagination_args(array(
            'total_items' => $total_items, 
            'per_page' => $per_page,
            'total_pages' => ceil($total_items / $per_page) 
        ));


    }
}

	    global $wpdb;

	    $table = new List_person();
	    $table->prepare_items();

	    $message = '';
	    if ('delete' === $table->current_action()) {
	        $message = '<div class="updated below-h2" id="message"><p>' . sprintf(__('Items deleted: %d', 'wpbc'), count($_REQUEST['id'])) . '</p></div>';
	    }
	    ?>
	<div class="wrap">

	    <div class="icon32 icon32-posts-post" id="icon-edit"><br></div>
	    <h2><?php _e('Features of Person', 'wpbc')?> <a class="add-new-h2"
	                                 href="<?php echo get_admin_url(get_current_blog_id(), 'admin.php?page=human_recource_add_person');?>"><?php _e('Add new', 'wpbc') ?></a>
	    </h2>
	    <?php echo $message; ?>

	    <form id="contacts-table" method="POST">
	        <input type="hidden" name="page" value="<?php echo $_REQUEST['page'] ?>"/>
	        <?php $table->display() ?>
	    </form>

	</div>

<?php } ?>

