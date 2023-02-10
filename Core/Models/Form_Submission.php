<?php
namespace Application_Form\Core\Models;

defined('ABSPATH') || exit;

class Form_Submission {

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
                    "SELECT * FROM {$wpdb->prefix}applicant_submissions 
                    WHERE present_address LIKE %s
                    OR concat(firstname, ' ', lastname) LIKE %s
                    OR mobile LIKE %s
                    OR postname LIKE %s
                    ORDER BY %s %s
                    LIMIT %d, %d",
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
                    "SELECT * FROM {$wpdb->prefix}applicant_submissions
                    ORDER BY %s %s
                    LIMIT %d, %d",
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
        $defaults = [
            'search'  => ''
        ];
        $args = wp_parse_args( $args, $defaults );

        if(!empty($args['search'])){
            $items = $wpdb->get_results( 
                $wpdb->prepare(
                    "SELECT * FROM {$wpdb->prefix}applicant_submissions 
                    WHERE present_address LIKE %s
                    OR concat(firstname, ' ', lastname) LIKE %s
                    OR mobile LIKE %s
                    OR postname LIKE %s",
                    '%' . $args['search'] . '%',
                    '%' . $args['search'] . '%',
                    '%' . $args['search'] . '%',
                    '%' . $args['search'] . '%',
                )
            );
        } else {
            $items = $wpdb->get_results( "SELECT * FROM {$wpdb->prefix}applicant_submissions" );
        }
        
    
        return count($items);
    }

    public static function get_by_id($id){
        global $wpdb;
        return $wpdb->get_row( $wpdb->prepare( "SELECT * FROM {$wpdb->prefix}applicant_submissions WHERE id = %d", $id ) );
    }

    public static function get_total_count() {
        global $wpdb;

        $count = (int) $wpdb->get_var( "SELECT count(id) FROM {$wpdb->prefix}applicant_submissions" );
        return $count;
    }

    public static function delete($id){
        global $wpdb;
        return $wpdb->delete($wpdb->prefix . 'applicant_submissions', [ 'id' => $id ], [ '%d' ]);
    }
    
}