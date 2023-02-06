<?php
namespace Application_Form\Core\Admin;

defined('ABSPATH') || exit;

class Menu {
    
    private static $instance;

    public function __construct(){
        add_action('admin_menu', [$this, 'application_form_admin_menu']);
    }

    public function application_form_admin_menu(){
        add_menu_page(__('Application Form Submissions', 'application-form'), __('Submissions', 'application-form'), 'manage_options', 'application_submissions', [$this, 'render_page_content'], 'dashicons-list-view');
    }

    public function render_page_content(){
        ?>
        <div class="wrap">
            <h2><?php _e('Application Submissions', 'application-form') ?></h2>
            
        </div>

        <?php
    }

    public static function instance(){
        if (!self::$instance) {
            self::$instance = new self();
        }
        return self::$instance;
    }
}