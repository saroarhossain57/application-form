<?php
/**
 * Plugin Name: Application Form
 * Description: A simple WP Form Plugin that will render a fully functional form using shortcode [applicant_form].
 * Plugin URI:  https://github.com/saroarhossain57/wp-application-form
 * Version: 1.0.0
 * Author: Saroar Hossain
 * Author URI:  https://github.com/saroarhossain57
 * Text Domain: wp-application-form
 * Domain Path: /languages
 * License: GPL2 or later
 * License URI: https://www.gnu.org/licenses/gpl-2.0.html
 */

defined( 'ABSPATH' ) || exit;

require plugin_dir_path( __FILE__ ) .'autoload.php';

/**
 * Plugin main class
 */
final class Application_Form {

    private static $instance;

    public function __construct() {

        // Add applicant_submissions table with database version to compare later
        register_activation_hook( __FILE__, [ $this, 'run_activatation' ] );

        add_action( 'plugins_loaded', [ \Application_Form\Plugin::instance(), 'initialize_plugin' ] );

    }

    public function run_activatation(){
        \Application_Form\Core\Database\DBTables::createTables();
    }

    public static function instance(){
        if (!self::$instance) {
            self::$instance = new self();
        }
        return self::$instance;
    }
}

// Run the plugin with singletop instance.
Application_Form::instance();