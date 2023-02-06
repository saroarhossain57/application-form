<?php
namespace Application_Form\Core\REST_API;

defined( 'ABSPATH' ) || exit;

class Form_Routes extends \WP_REST_Controller{

    private static $instance;

    public function register_routes(){
        
        $version = 'v1';
        $rest_namespace = 'application-form/' . $version;

        // Register form submission route
        register_rest_route($rest_namespace, 'submit', [
            'methods'             => \WP_REST_Server::CREATABLE,
            'callback'            => [$this, 'form_submit'],
            'permission_callback' => '__return_true',
        ]);

    }

    public function form_submit($request){

        $errors = [];

        $form_data = $request->get_params();
        $file_data = $request->get_file_params();

        $allowed_mine_types = ['image/gif', 'image/jpeg', 'image/jpg', 'image/png', 'application/pdf'];

        // Validate input fields
        if(!isset($form_data['firstname']) || empty($form_data['firstname'])){
            $errors['firstname'] = 'First name is required';
        }

        if(!isset($form_data['lastname']) || empty($form_data['lastname'])){
            $errors['lastname'] = 'Last name is required';
        }

        if(!isset($form_data['present_address']) || empty($form_data['present_address'])){
            $errors['present_address'] = 'Present address is required';
        }

        if ( !isset($form_data['email']) || empty($form_data['email']) || !filter_var($form_data['email'], FILTER_VALIDATE_EMAIL)) {
            $errors['email'] = 'Please enter a valid email address';
        }

        if ( !isset($form_data['mobile']) || empty($form_data['mobile']) || !preg_match('/(^(\+88|0088)?(01){1}[3456789]{1}(\d){8})$/', $form_data['mobile'])){
            $errors['mobile'] = 'Please enter a valid Bangladeshi mobile number';
        }

        if( !isset($form_data['postname']) || empty($form_data['postname'])){
            $errors['postname'] = 'Post name is required';
        }

        if(!isset($file_data['cv']) || !in_array($file_data['cv']['type'], $allowed_mine_types)){
            $errors['cv'] = 'Please attach a valid CV';
        } else {
            if($file_data['cv']['size'] > 10485760){
                $errors['cv'] = 'Too large file! Please use maximum 10MB file.';
            }
        }

        // Upload file to upload folder 
        if(!empty($file_data['cv']['name']) && !empty($file_data['cv']['tmp_name'])){
            $upload_result = wp_upload_bits( $file_data['cv']['name'], null, file_get_contents($file_data['cv']['tmp_name']) );
            if(isset($upload_result['error']) && !empty($upload_result['error'])){
                $errors['common'] = 'File upload failed';
            }
        } 
        
        // Check if there any error occured
        if(!empty($errors)){
            return new \WP_REST_Response([
                'status' => 400,
                'response' => [
                    'errors' => $errors
                ],
            ]);
        }

        // Insert submission into table.
        global $wpdb;
        $tablename = $wpdb->prefix . "applicant_submissions";
        $current_user = wp_get_current_user();

        $firstname       = sanitize_text_field($form_data['firstname']);
        $lastname        = sanitize_text_field($form_data['lastname']);
        $present_address = sanitize_text_field($form_data['present_address']);
        $email           = sanitize_email($form_data['email']);
        $mobile          = sanitize_text_field($form_data['mobile']);
        $postname        = sanitize_text_field($form_data['postname']);
        $cv              = isset($upload_result['url']) ? $upload_result['url'] : '';
        $submit_by   = isset($current_user->display_name) ? $current_user->display_name : 'Guest';

        $sql = $wpdb->prepare("INSERT INTO `$tablename` (`firstname`, `lastname`, `present_address`, `email`, `mobile`, `postname`, `cv`, `submit_by`) values (%s, %s, %s, %s, %s, %s, %s, %s)", $firstname, $lastname, $present_address, $email, $mobile, $postname, $cv, $submit_by);

        if($wpdb->query($sql)){
            return new \WP_REST_Response([
                'status' => 200,
                'response' => __('Form submitted successfully!', 'applicationf-form'),
            ]);
        } else {
            return new \WP_REST_Response([
                'status' => 500,
                'response' => [
                    'errors' => [
                        'common' => __('Something went wrong', 'applicationf-form')
                    ]
                ],
            ]);
        }
        
    }

    public static function instance(){
        if (!self::$instance) {
            self::$instance = new self();
        }
        return self::$instance;
    }
}


// class Slug_Custom_Route extends \WP_REST_Controller {

//   /**
//    * Register the routes for the objects of the controller.
//    */
//   public function register_routes() {
//     $version = 'v1';
//     $namespace = 'application-form/' . $version;
//     register_rest_route( $namespace, '/', array(
//       array(
//         'methods'             => WP_REST_Server::READABLE,
//         'callback'            => array( $this, 'get_items' ),
//         'permission_callback' => array( $this, 'get_items_permissions_check' ),
//         'args'                => array(

//         ),
//       ),
//       array(
//         'methods'             => WP_REST_Server::CREATABLE,
//         'callback'            => array( $this, 'create_item' ),
//         'permission_callback' => array( $this, 'create_item_permissions_check' ),
//         'args'                => $this->get_endpoint_args_for_item_schema( true ),
//       ),
//     ) );
//     register_rest_route( $namespace, '/' . $base . '/(?P<id>[\d]+)', array(
//       array(
//         'methods'             => WP_REST_Server::READABLE,
//         'callback'            => array( $this, 'get_item' ),
//         'permission_callback' => array( $this, 'get_item_permissions_check' ),
//         'args'                => array(
//           'context' => array(
//             'default' => 'view',
//           ),
//         ),
//       ),
//       array(
//         'methods'             => WP_REST_Server::EDITABLE,
//         'callback'            => array( $this, 'update_item' ),
//         'permission_callback' => array( $this, 'update_item_permissions_check' ),
//         'args'                => $this->get_endpoint_args_for_item_schema( false ),
//       ),
//       array(
//         'methods'             => WP_REST_Server::DELETABLE,
//         'callback'            => array( $this, 'delete_item' ),
//         'permission_callback' => array( $this, 'delete_item_permissions_check' ),
//         'args'                => array(
//           'force' => array(
//             'default' => false,
//           ),
//         ),
//       ),
//     ) );
//     register_rest_route( $namespace, '/' . $base . '/schema', array(
//       'methods'  => WP_REST_Server::READABLE,
//       'callback' => array( $this, 'get_public_item_schema' ),
//     ) );
//   }

//   /**
//    * Get a collection of items
//    *
//    * @param WP_REST_Request $request Full data about the request.
//    * @return WP_Error|WP_REST_Response
//    */
//   public function get_items( $request ) {
//     $items = array(); //do a query, call another class, etc
//     $data = array();
//     foreach( $items as $item ) {
//       $itemdata = $this->prepare_item_for_response( $item, $request );
//       $data[] = $this->prepare_response_for_collection( $itemdata );
//     }

//     return new WP_REST_Response( $data, 200 );
//   }

//   /**
//    * Get one item from the collection
//    *
//    * @param WP_REST_Request $request Full data about the request.
//    * @return WP_Error|WP_REST_Response
//    */
//   public function get_item( $request ) {
//     //get parameters from request
//     $params = $request->get_params();
//     $item = array();//do a query, call another class, etc
//     $data = $this->prepare_item_for_response( $item, $request );

//     //return a response or error based on some conditional
//     if ( 1 == 1 ) {
//       return new WP_REST_Response( $data, 200 );
//     } else {
//       return new WP_Error( 'code', __( 'message', 'text-domain' ) );
//     }
//   }

//   /**
//    * Create one item from the collection
//    *
//    * @param WP_REST_Request $request Full data about the request.
//    * @return WP_Error|WP_REST_Response
//    */
//   public function create_item( $request ) {
//     $item = $this->prepare_item_for_database( $request );

//     if ( function_exists( 'slug_some_function_to_create_item' ) ) {
//       $data = slug_some_function_to_create_item( $item );
//       if ( is_array( $data ) ) {
//         return new WP_REST_Response( $data, 200 );
//       }
//     }

//     return new WP_Error( 'cant-create', __( 'message', 'text-domain' ), array( 'status' => 500 ) );
//   }

//   /**
//    * Update one item from the collection
//    *
//    * @param WP_REST_Request $request Full data about the request.
//    * @return WP_Error|WP_REST_Response
//    */
//   public function update_item( $request ) {
//     $item = $this->prepare_item_for_database( $request );

//     if ( function_exists( 'slug_some_function_to_update_item' ) ) {
//       $data = slug_some_function_to_update_item( $item );
//       if ( is_array( $data ) ) {
//         return new WP_REST_Response( $data, 200 );
//       }
//     }

//     return new WP_Error( 'cant-update', __( 'message', 'text-domain' ), array( 'status' => 500 ) );
//   }

//   /**
//    * Delete one item from the collection
//    *
//    * @param WP_REST_Request $request Full data about the request.
//    * @return WP_Error|WP_REST_Response
//    */
//   public function delete_item( $request ) {
//     $item = $this->prepare_item_for_database( $request );

//     if ( function_exists( 'slug_some_function_to_delete_item' ) ) {
//       $deleted = slug_some_function_to_delete_item( $item );
//       if ( $deleted ) {
//         return new WP_REST_Response( true, 200 );
//       }
//     }

//     return new WP_Error( 'cant-delete', __( 'message', 'text-domain' ), array( 'status' => 500 ) );
//   }

//   /**
//    * Check if a given request has access to get items
//    *
//    * @param WP_REST_Request $request Full data about the request.
//    * @return WP_Error|bool
//    */
//   public function get_items_permissions_check( $request ) {
//     //return true; <--use to make readable by all
//     return current_user_can( 'edit_something' );
//   }

//   /**
//    * Check if a given request has access to get a specific item
//    *
//    * @param WP_REST_Request $request Full data about the request.
//    * @return WP_Error|bool
//    */
//   public function get_item_permissions_check( $request ) {
//     return $this->get_items_permissions_check( $request );
//   }

//   /**
//    * Check if a given request has access to create items
//    *
//    * @param WP_REST_Request $request Full data about the request.
//    * @return WP_Error|bool
//    */
//   public function create_item_permissions_check( $request ) {
//     return current_user_can( 'edit_something' );
//   }

//   /**
//    * Check if a given request has access to update a specific item
//    *
//    * @param WP_REST_Request $request Full data about the request.
//    * @return WP_Error|bool
//    */
//   public function update_item_permissions_check( $request ) {
//     return $this->create_item_permissions_check( $request );
//   }

//   /**
//    * Check if a given request has access to delete a specific item
//    *
//    * @param WP_REST_Request $request Full data about the request.
//    * @return WP_Error|bool
//    */
//   public function delete_item_permissions_check( $request ) {
//     return $this->create_item_permissions_check( $request );
//   }

//   /**
//    * Prepare the item for create or update operation
//    *
//    * @param WP_REST_Request $request Request object
//    * @return WP_Error|object $prepared_item
//    */
//   protected function prepare_item_for_database( $request ) {
//     return array();
//   }

//   /**
//    * Prepare the item for the REST response
//    *
//    * @param mixed $item WordPress representation of the item.
//    * @param WP_REST_Request $request Request object.
//    * @return mixed
//    */
//   public function prepare_item_for_response( $item, $request ) {
//     return array();
//   }

//   /**
//    * Get the query params for collections
//    *
//    * @return array
//    */
//   public function get_collection_params() {
//     return array(
//       'page'     => array(
//         'description'       => 'Current page of the collection.',
//         'type'              => 'integer',
//         'default'           => 1,
//         'sanitize_callback' => 'absint',
//       ),
//       'per_page' => array(
//         'description'       => 'Maximum number of items to be returned in result set.',
//         'type'              => 'integer',
//         'default'           => 10,
//         'sanitize_callback' => 'absint',
//       ),
//       'search'   => array(
//         'description'       => 'Limit results to those matching a string.',
//         'type'              => 'string',
//         'sanitize_callback' => 'sanitize_text_field',
//       ),
//     );
//   }
// }