<?php
namespace Application_Form;

defined('ABSPATH') || exit;

use Application_Form\Core\Shortcodes\Applicant_Form;

final class Plugin {

    private static $instance;

    public static function version(){
        return '1.0.0';
    }

    public static function plugin_url()
    {
        return trailingslashit(plugin_dir_url(__FILE__));
    }

    public static function plugin_dir()
    {
        return trailingslashit(plugin_dir_path(__FILE__));
    }

    public static function public_dir(){
        return self::plugin_dir() . 'public/';
    }

    public static function public_url(){
        return self::plugin_url() . 'public/';
    }

    public static function assets_dir(){
        return self::public_url() . 'assets/';
    }

    /**
     * Initialize the plugin 
     *
     * @since 1.0.0
     * @return void
     */
    public function initialize_plugin(){
        add_action('init', [$this, 'internationalization']);

        // Global inits for both frontend and backend\
        \Application_Form\Core\REST_API\REST_API_Init::instance()->initialize_api_routes();
        
        // Separate the frontend and backend inits for saving loading time
        if(is_admin()){
            $this->backend_inits();
        } else {
            $this->frontend_inits();
        }
        
    }

    private function backend_inits(){

        // Register all backend scripts
        add_action('admin_enqueue_scripts', [$this, 'register_admin_scripts']);

    }

    private function frontend_inits(){

        // Register all backend scripts
        add_action('wp_enqueue_scripts', [$this, 'register_frontend_scripts']);

        // Intialize shortcode
        Applicant_Form::instance();
    }

    /**
     * Register the textdomain for the plugin
     * 
     * @since 1.0.0
     * @return void
     */
    public function internationalization(){
        load_plugin_textdomain( 'application-form', false, dirname( plugin_basename( __FILE__ ) ) . '/languages' );
    }

    public function register_admin_scripts(){
        // Register all admin scripts
    }

    public function register_frontend_scripts(){
        
        error_log(print_r(self::assets_dir(), true));
        

        // Styles
        wp_register_style( 'application-form-styles', self::assets_dir() . 'css/application-form-styles.css' );

        wp_register_script( 'application-form-scripts', self::assets_dir() . 'js/application-form-scripts.js', 'jquery', time(), true );

    }

    public static function instance(){
        if (!self::$instance) {
            self::$instance = new self();
        }
        return self::$instance;
    }
}