<?php
namespace Application_Form;

// Autoload all the classes in our plugin
spl_autoload_register( function($class_name){

    if ( 0 === strpos( $class_name, 'Application_Form' ) ) {
        $file_name = strtolower(preg_replace(['/\b'.__NAMESPACE__.'\\\/', '/\\\/' ], ['', DIRECTORY_SEPARATOR], $class_name));

        $file = plugin_dir_path(__FILE__) . $file_name . '.php';

        if ( file_exists( $file ) ) {
            require_once( $file );
        }
    }
} );