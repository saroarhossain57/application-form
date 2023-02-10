<?php
namespace Application_Form\Core\Admin;

defined('ABSPATH') || exit;

class Menu {
    
    private static $instance;
    private $application_submissions_page;

    public function __construct(){
        add_action('admin_menu', [$this, 'application_form_admin_menu']);
        add_filter('set-screen-option', [$this, 'application_form_table_set_option'], 10, 3);
    }

    public function application_form_table_set_option($status, $option, $value){
        return $value;
    }

    public function application_form_admin_menu(){

        $this->application_submissions_page = add_menu_page(__('Application Form Submissions', 'application-form'), __('Submissions', 'application-form'), 'manage_options', 'application_submissions', [$this, 'render_page_content'], 'dashicons-list-view');

        add_action("load-{$this->application_submissions_page}", [$this, 'application_form_screen_options']);        
        add_action("admin_head-{$this->application_submissions_page}", [$this, 'enqueue_scripts']);

        if (isset($_REQUEST['action']) && !empty($_REQUEST['submission']) && $_REQUEST['action'] == 'delete'){ // @codingStandardsIgnoreLine WordPress.Security.NonceVerification.Recommended // Admin Menu should not verify nonce
            $id = isset( $_REQUEST['submission'] ) ? intval( sanitize_text_field( wp_unslash($_REQUEST['submission']) ) ) : 0; //@codingStandardsIgnoreLine WordPress.Security.NonceVerification.Recommended // Admin Menu should not verify nonce
            if ( \Application_Form\Core\Models\Form_Submission::delete( $id ) ) {
                $redirected_to = admin_url( 'admin.php?page=application_submissions&application-deleted=true' );
            } else {
                $redirected_to = admin_url( 'admin.php?page=application_submissions&application-deleted=false' );
            }

            wp_safe_redirect( $redirected_to );
            exit;
        }
    }

    public function enqueue_scripts(){
        wp_enqueue_style('application-form-admin-styles');
    }

    public function application_form_screen_options(){
    
        $screen = get_current_screen();
    
        // get out of here if we are not on our settings page
        if(!is_object($screen) || $screen->id != $this->application_submissions_page)
            return;
    
        $args = array(
            'label' => __('Submissions per page', 'application-form'),
            'default' => 10,
            'option' => 'submissions_per_page'
        );
        add_screen_option( 'per_page', $args );
    }

    public function render_page_content(){

        if ( ! current_user_can( 'manage_options' ) ) {
            wp_die( 'You are not allowed to view the page.' );
        }

        if (isset($_REQUEST['action']) && !empty($_REQUEST['submission']) && $_REQUEST['action'] == 'view'){ //@codingStandardsIgnoreLine WordPress.Security.NonceVerification.Recommended // Admin Menu should not verify nonce
            $single_item = new Submission_Single_Item();
            $single_item->render( sanitize_text_field( wp_unslash( $_GET['submission'] ) ) ); //@codingStandardsIgnoreLine WordPress.Security.NonceVerification.Recommended // Admin Menu should not verify nonce

        } else {
            $all_submissions = new All_Submissions();
            $all_submissions->render();
        }
    }

    public static function instance(){
        if (!self::$instance) {
            self::$instance = new self();
        }
        return self::$instance;
    }
}