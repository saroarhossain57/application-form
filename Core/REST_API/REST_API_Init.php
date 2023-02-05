<?php
namespace Application_Form\Core\REST_API;

defined( 'ABSPATH' ) || exit;

class REST_API_Init {
    private static $instance;

    public function initialize_api_routes(){
        add_action( 'rest_api_init', [Form_Routes::instance(), 'register_routes'] );
    }

    public static function instance(){
        if (!self::$instance) {
            self::$instance = new self();
        }
        return self::$instance;
    }
}