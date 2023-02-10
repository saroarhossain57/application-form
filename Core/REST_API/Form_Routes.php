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

        $nonce = $request->get_header( 'X-WP-Nonce' );
        if ( ! wp_verify_nonce( $nonce, 'wp_rest' ) ) {
            return new \WP_Error( 'invalid_nonce', __( 'Invalid nonce.' ), [ 'status' => 400 ] );
        }

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

        $firstname       = sanitize_text_field($form_data['firstname']);
        $lastname        = sanitize_text_field($form_data['lastname']);
        $present_address = sanitize_text_field($form_data['present_address']);
        $email           = sanitize_email($form_data['email']);
        $mobile          = sanitize_text_field($form_data['mobile']);
        $postname        = sanitize_text_field($form_data['postname']);
        $cv              = isset($upload_result['url']) ? $upload_result['url'] : '';

        if($wpdb->query($wpdb->prepare("INSERT INTO {$wpdb->prefix}applicant_submissions (`firstname`, `lastname`, `present_address`, `email`, `mobile`, `postname`, `cv`) values (%s, %s, %s, %s, %s, %s, %s)", $firstname, $lastname, $present_address, $email, $mobile, $postname, $cv))){
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