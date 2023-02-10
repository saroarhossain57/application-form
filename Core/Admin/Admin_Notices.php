<?php
namespace Application_Form\Core\Admin;

defined('ABSPATH') || exit;

class Admin_Notices {
    private static $instance;

    public function __construct(){
        add_action( 'admin_notices', [$this, 'show_submission_action_notices'] );
    }

    public function show_submission_action_notices(){

        if(isset( $_GET['application-deleted'] )){
            $boolean = (strtolower($_GET['application-deleted']) === 'false') ? false : true;
            if($boolean === true){
                ?>
                <div class="notice notice-success is-dismissible">
                    <p><?php _e('Application deleted', 'application-form'); ?></p>
                </div>
                <?php
            }
            if($boolean === false){
                ?>
                <div class="notice notice-error is-dismissible">
                    <p><?php _e('Application is not deleted', 'application-form'); ?></p>
                </div>
                <?php
            }
        }

    }

    public static function instance(){
        if (!self::$instance) {
            self::$instance = new self();
        }
        return self::$instance;
    }
}