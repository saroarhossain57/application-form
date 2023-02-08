<?php
namespace Application_Form\Core\Models;

defined('ABSPATH') || exit;

class Form_Submission {

    private static $table_name = 'applicant_submissions';

    public static function get_all($args = []){
        global $wpdb;
        $defaults = [
            'number'  => 10,
            'offset'  => 0,
            'orderby' => 'submission_time',
            'order'   => 'DESC'
        ];

        $args = wp_parse_args( $args, $defaults );

        $sql = $wpdb->prepare(
            "SELECT * FROM {$wpdb->prefix}" . self::$table_name . " 
            ORDER BY {$args['orderby']} {$args['order']}
            LIMIT %d, %d",
            $args['offset'], $args['number']
        );

        $items = $wpdb->get_results( $sql );

        return $items;
    }

    public static function get_by_id($id){
        global $wpdb;
        $prepared = $wpdb->prepare( "SELECT * FROM {$wpdb->prefix}". self::$table_name ." WHERE id = %d", $id );
        return $wpdb->get_row($prepared);
    }

    public static function get_total_count() {
        global $wpdb;
        $count = (int) $wpdb->get_var( "SELECT count(id) FROM {$wpdb->prefix}" . self::$table_name );
        return $count;
    }
    
}