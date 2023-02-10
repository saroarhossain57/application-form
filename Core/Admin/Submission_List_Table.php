<?php
namespace Application_Form\Core\Admin;

defined('ABSPATH') || exit;

if ( ! class_exists( 'WP_List_Table' ) ) {
    require_once ABSPATH . 'wp-admin/includes/class-wp-list-table.php';
}

use Application_Form\Core\Models\Form_Submission;

class Submission_List_Table extends \WP_List_Table {

    function __construct() {
        parent::__construct([
            'singular' => __('Application', 'application-form'),
            'plural'   => __('Applications', 'application-form'),
            'ajax'     => false
        ]);
    }

    function prepare_items(){

        $search_string         = isset($_GET['s']) ? sanitize_text_field( wp_unslash($_GET['s']) ) : ''; // @codingStandardsIgnoreLine WordPress.Security.NonceVerification.Recommended // The search string don't come all the time
        $columns               = $this->get_columns();
        $sortable              = $this->get_sortable_columns();
        $primary               = 'name';
        $this->_column_headers = [ $columns, [], $sortable, $primary ];

        $submissions_per_page     = $this->get_items_per_page('submissions_per_page', 10);
        $current_page = $this->get_pagenum();
        $offset       = ( $current_page - 1 ) * $submissions_per_page;
        
        // Preparing arguments for search
        $args = [
            'number' => $submissions_per_page,
            'offset' => $offset,
        ];

        if ( isset( $_REQUEST['orderby'] ) && isset( $_REQUEST['order'] ) ) { // @codingStandardsIgnoreLine WordPress.Security.NonceVerification.Recommended // This data handled by WordPress
            $args['orderby'] = sanitize_text_field( wp_unslash( $_REQUEST['orderby'] ) ); // @codingStandardsIgnoreLine WordPress.Security.NonceVerification.Recommended // This data handled by WordPress
            $args['order']   = sanitize_text_field(wp_unslash($_REQUEST['order'])); // @codingStandardsIgnoreLine WordPress.Security.NonceVerification.Recommended // This data handled by WordPress
        }

        if( !empty($search_string) ){
            $args['search'] = $search_string;
        }

        $this->items = Form_Submission::get_all( $args );

        $total_items = Form_Submission::get_queried_count( $args );

        // Create pagination with data
        $this->set_pagination_args([
            'total_items' => $total_items, 
            'per_page'    => $submissions_per_page, 
            'total_pages' => ceil( $total_items / $submissions_per_page ) 
        ]);
    }

    // Define table columns
    function get_columns(){
        $columns = [
            'cb'              => '<input type="checkbox" />',
            'name'            => __('Name', 'application-form'),
            'present_address' => __('Present Address', 'application-form'),
            'email'           => __('Email', 'application-form'),
            'mobile'          => __('Mobile', 'application-form'),
            'postname'        => __('Post Name', 'application-form'),
            'cv'              => __('CV', 'application-form'),
            'submission_time' => __('Date', 'application-form')
        ];
        return $columns;
    }

    protected function get_sortable_columns(){
        $sortable_columns = [
            'postname'        => [ 'postname', false ],
            'submission_time' => [ 'submission_time', true ]
        ];

        return $sortable_columns;
    }

    // Add a checkbox in the first column
    public function column_cb($submission){

        return sprintf('<input type="checkbox" name="submission_id[]" value="%s" />', $submission->id);
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

    // Adding action links to column
    public function column_name($submission){

        $requested_page = isset($_REQUEST['page']) ? sanitize_text_field( wp_unslash($_REQUEST['page']) ) : ''; // @codingStandardsIgnoreLine WordPress.Security.NonceVerification.Recommended // This data handled by WordPress
        $name = $submission->firstname . ' ' . $submission->lastname;
        $view_url = wp_nonce_url("page={$requested_page}&action=view&submission={$submission->id}", 'view_single_application');
        $actions = array(   
            'view' => sprintf('<a href="?%s">' . __('view', 'application-form') . '</a>', $view_url ),      
            'delete' => sprintf('<a href="?page=%s&action=%s&submission=%s">' . __('Delete', 'application-form') . '</a>', $requested_page, 'delete', $submission->id),
        );

        return sprintf(
            '<a href="?page=%1$s&action=%2$s&submission=%3$s">%4$s</a> %5$s', 
            $requested_page, 
            'view',
            $submission->id,
            $name, 
            $this->row_actions($actions)
        );
    }

    // To show bulk action dropdown
    public function get_bulk_actions(){
        return [
            'delete_all' => __('Delete', 'application-form'),
        ];
    }

    function search_box( $text, $input_id ) {
        $input_id = $input_id . '-search-input';
        $search_string = ( isset( $_GET['s'] ) ) ? sanitize_text_field( wp_unslash($_GET['s']) ) : ''; // @codingStandardsIgnoreLine WordPress.Security.NonceVerification.Recommended // This data handled by WordPress
        ?>
        <form method="get">
            <input type="hidden" name="page" value="application_submissions">
            <input type="hidden" name="s" value="<?php esc_attr_e($search_string); ?>">
            <?php wp_nonce_field( 'application_form_search', 'application_form_search_nonce' ); ?>
            <p class="search-box">
                <label class="screen-reader-text" for="<?php echo esc_attr( $input_id ); ?>"><?php echo esc_html($text); ?>:</label>
                <input type="search" id="<?php echo esc_attr( $input_id ); ?>" name="s" value="<?php _admin_search_query(); ?>">
                <input type="submit" id="search-submit" class="button" value="<?php echo esc_attr_x( 'Search', 'submit button' ); ?>">
            </p>
        </form>
        <?php
    }

    function no_items() {
        esc_html_e( 'No application found', 'applicationf-form' );
    }
}