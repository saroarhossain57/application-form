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
            $items = $wpdb->get_results( 
                $wpdb->prepare(
                    "SELECT * FROM %s 
                    WHERE present_address LIKE %s
                    OR concat(firstname, ' ', lastname) LIKE %s
                    OR mobile LIKE %s
                    OR postname LIKE %s
                    ORDER BY %s %s
                    LIMIT %d, %d",
                    $wpdb->prefix . self::$table_name,
                    '%' . $args['search'] . '%',
                    '%' . $args['search'] . '%',
                    '%' . $args['search'] . '%',
                    '%' . $args['search'] . '%',
                    $args['orderby'],
                    $args['order'],
                    $args['offset'], 
                    $args['number']
                )
            );
        } else {
            $items = $wpdb->get_results( 
                $wpdb->prepare(
                    "SELECT * FROM %s 
                    ORDER BY %s %s
                    LIMIT %d, %d",
                    $wpdb->prefix . self::$table_name,
                    $args['orderby'],
                    $args['order'],
                    $args['offset'], $args['number']
                )
            );
        }
        
        return $items;
    }

    public static function get_queried_count($args = []){
        global $wpdb;
        $table_name = $wpdb->prefix . self::$table_name;
        $defaults = [
            'search'  => ''
        ];
        $args = wp_parse_args( $args, $defaults );

        if(!empty($args['search'])){
            $items = $wpdb->get_results( 
                $wpdb->prepare(
                    "SELECT * FROM %s 
                    WHERE present_address LIKE %s
                    OR concat(firstname, ' ', lastname) LIKE %s
                    OR mobile LIKE %s
                    OR postname LIKE %s",
                    $wpdb->prefix . self::$table_name,
                    '%' . $args['search'] . '%',
                    '%' . $args['search'] . '%',
                    '%' . $args['search'] . '%',
                    '%' . $args['search'] . '%',
                )
            );
        } else {
            $items = $wpdb->get_results( "SELECT * FROM {$table_name}" );
        }
        
    
        return count($items);
    }

    public static function get_by_id($id){
        global $wpdb;
        return $wpdb->get_row( $wpdb->prepare( "SELECT * FROM %s WHERE id = %d", $wpdb->prefix . self::$table_name, $id ) );
    }

    public static function get_total_count() {
        global $wpdb;

        $count = (int) $wpdb->get_var( $wpdb->prepare("SELECT count(id) FROM %s", $wpdb->prefix . self::$table_name) );
        return $count;
    }

    public static function delete($id){
        global $wpdb;
        return $wpdb->delete($wpdb->prefix . self::$table_name, [ 'id' => $id ], [ '%d' ]);
    }
    
}