<?php
namespace Application_Form\Core\Admin;

defined('ABSPATH') || exit;

if ( ! class_exists( 'WP_List_Table' ) ) {
    require_once ABSPATH . 'wp-admin/includes/class-wp-list-table.php';
}

use Application_Form\Core\Models\Form_Submission;
class Submission_List_Table extends \WP_List_Table {

    // Define table columns
    function get_columns(){
        $columns = array(
            'cb'              => '<input type="checkbox" />',
            'name'            => __('Name', 'application-form'),
            'present_address' => __('Present Address', 'application-form'),
            'email'           => __('Email', 'application-form'),
            'mobile'          => __('Mobile', 'application-form'),
            'postname'        => __('Post Name', 'application-form'),
            'cv'              => __('CV', 'application-form'),
            'submission_time' => __('Date', 'application-form')
        );
        return $columns;
    }

    // Bind table with columns, data and all
    function prepare_items(){

        $search_string         = isset($_POST['s']) ? $_POST['s'] : '';
        $columns               = $this->get_columns();
        $sortable              = $this->get_sortable_columns();
        $primary               = 'name';
        $this->_column_headers = array($columns, [], $sortable, $primary);

        $submissions_per_page     = $this->get_items_per_page('submissions_per_page', 20);
        $current_page = $this->get_pagenum();
        $offset       = ( $current_page - 1 ) * $submissions_per_page;
        $total_items  = Form_Submission::get_total_count();

        $args = [
            'number' => $submissions_per_page,
            'offset' => $offset,
        ];

        if ( isset( $_REQUEST['orderby'] ) && isset( $_REQUEST['order'] ) ) {
            $args['orderby'] = $_REQUEST['orderby'];
            $args['order']   = $_REQUEST['order'];
        }

        $this->items = Form_Submission::get_all( $args );

        // Create pagination with data
        $this->set_pagination_args(array(
            'total_items' => $total_items, 
            'per_page'    => $submissions_per_page, 
            'total_pages' => ceil( $total_items / $submissions_per_page ) 
        ));
         
    }

    // set value for each column
    protected function column_default( $submission, $column_name ) {
        switch ( $column_name ) {
            case 'name':
                return $submission->firstname . ' ' . $submission->lastname;
            case 'cv':
                return sprintf('<a href="%s" class="cv_download_btn" download>%s</a>', $submission->cv, __('Download CV', 'application-form'));
            case 'submission_time':
                return wp_date( get_option( 'date_format' ), strtotime( $submission->submission_time ) );
            default:
                return isset( $submission->$column_name ) ? $submission->$column_name : '';
        }
    }

    // Add a checkbox in the first column
    public function column_cb($submission){
        return sprintf('<input type="checkbox" name="submission_id[]" value="%s" />', $submission->id);
    }

    // Define sortable column
    protected function get_sortable_columns(){
        $sortable_columns = array(
            'postname'        => array('postname', false),
            'submission_time' => array('submission_time', true)
        );
        return $sortable_columns;
    }

    // Adding action links to column
    public function column_name($submission){

        $name = $submission->firstname . ' ' . $submission->lastname;
        $actions = array(   
            'view' => sprintf('<a href="?page=%s&action=%s&submission=%s">' . __('view', 'application-form') . '</a>', $_REQUEST['page'], 'view', $submission->id),      
            'delete' => sprintf('<a href="?page=%s&action=%s&submission=%s">' . __('Delete', 'application-form') . '</a>', $_REQUEST['page'], 'delete', $submission->id),
        );

        return sprintf(
            '<a href="?page=%1$s&action=%2$s&submission=%3$s">%4$s</a> %5$s', 
            $_REQUEST['page'], 
            'view',
            $submission->id,
            $name, 
            $this->row_actions($actions)
        );
    }

    // To show bulk action dropdown
    public function get_bulk_actions(){
        $actions = array(
            'delete_all' => __('Delete', 'application-form'),
            'draft_all'  => __('Move to Draft', 'application-form')
        );
        return $actions;
    }
}