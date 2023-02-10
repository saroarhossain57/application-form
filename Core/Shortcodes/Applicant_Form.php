<?php
namespace Application_Form\Core\Shortcodes;

defined( 'ABSPATH' ) || exit;

class Applicant_Form {

    private static $instance; // phpcs:ignore

    public function __construct(){
        // Register shortcode
        add_shortcode('applicant_form', [$this, 'render_shortcode']);
    }

    public function render_shortcode(){
        
        wp_enqueue_style( 'application-form-styles' );
        wp_enqueue_script( 'application-form-scripts' );

        ob_start();

            include \Application_Form\Plugin::plugin_dir() . 'templates/form-template.php';

        $output = ob_get_clean();

        return $output;
    }

    public static function instance(){
        if (!self::$instance) {
            self::$instance = new self();
        }
        return self::$instance;
    }
}