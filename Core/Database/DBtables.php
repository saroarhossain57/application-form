<?php
namespace Application_Form\Core\Database;

defined('ABSPATH') || exit;

class DBTables {

    private $table_name;

    public function __construct() {
        global $wpdb;
        $this->table_name = $wpdb->prefix . 'applicant_submissions';
    }

    /**
     * Create all the database tables for the plugin
     *
     * @since 1.0.0
     * @return void
     */
    public static function createTables(){
        $self = new static;

        $sql = "CREATE TABLE IF NOT EXISTS $self->table_name (
            id int(11) unsigned NOT NULL AUTO_INCREMENT,
            firstname varchar(100) NOT NULL DEFAULT '',
            lastname varchar(100) NOT NULL DEFAULT '',
            present_address varchar(255) DEFAULT NULL,
            email varchar(150) DEFAULT NULL,
            mobile varchar(50) DEFAULT NULL,
            postname varchar(100) DEFAULT NULL,
            cv varchar(100) DEFAULT NULL,
            submission_time timestamp DEFAULT CURRENT_TIMESTAMP,
            submit_page varchar(100) DEFAULT NULL,
            submit_by varchar(100) DEFAULT NULL,
            PRIMARY KEY (id)
        );";

        require_once( ABSPATH . 'wp-admin/includes/upgrade.php' );
        dbDelta( $sql );

    }
}