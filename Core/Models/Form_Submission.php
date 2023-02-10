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
            'order'   => 'DESC',
            'search'  => ''
        ];
        $args = wp_parse_args( $args, $defaults );

        if(!empty($args['search'])){
            $sql = $wpdb->prepare(
                "SELECT * FROM {$wpdb->prefix}" . self::$table_name . " 
                WHERE present_address LIKE %s
                OR concat(firstname, ' ', lastname) LIKE %s
                OR mobile LIKE %s
                OR postname LIKE %s
                ORDER BY {$args['orderby']} {$args['order']}
                LIMIT %d, %d",
                '%' . $args['search'] . '%',
                '%' . $args['search'] . '%',
                '%' . $args['search'] . '%',
                '%' . $args['search'] . '%',
                $args['offset'], 
                $args['number']
            );
        } else {
            $sql = $wpdb->prepare(
                "SELECT * FROM {$wpdb->prefix}" . self::$table_name . " 
                ORDER BY {$args['orderby']} {$args['order']}
                LIMIT %d, %d",
                $args['offset'], $args['number']
            );
        }
        
        $items = $wpdb->get_results( $sql );

        return $items;
    }

    public static function get_queried_count($args = []){
        global $wpdb;
        $defaults = [
            'search'  => ''
        ];
        $args = wp_parse_args( $args, $defaults );

        if(!empty($args['search'])){
            $sql = $wpdb->prepare(
                "SELECT * FROM {$wpdb->prefix}" . self::$table_name . " 
                WHERE present_address LIKE %s
                OR concat(firstname, ' ', lastname) LIKE %s
                OR mobile LIKE %s
                OR postname LIKE %s",
                '%' . $args['search'] . '%',
                '%' . $args['search'] . '%',
                '%' . $args['search'] . '%',
                '%' . $args['search'] . '%',
            );
        } else {
            $sql = "SELECT * FROM {$wpdb->prefix}" . self::$table_name;
        }
        
        $items = $wpdb->get_results( $sql );
        return count($items);
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

    public static function delete($id){

        error_log(print_r('delete calling', true));
        

        global $wpdb;

        $wpdb->delete($wpdb->prefix . self::$table_name, [ 'id' => $id ], [ '%d' ]);

        return false;
    }
    
}