<?php
namespace Application_Form\Core\Admin;

use Application_Form\Core\Models\Form_Submission;

defined('ABSPATH') || exit;

class Submission_Single_Item {

    public function render($submission_id){

        if ( !isset( $_GET['_wpnonce'] ) || !wp_verify_nonce( sanitize_text_field(wp_unslash($_GET['_wpnonce'])), 'view_single_application' ) ) {
            wp_die('You are not allowed to view this page');
        }

        $submission = Form_Submission::get_by_id($submission_id);
        $name = "{$submission->firstname} {$submission->lastname}";
        $list_page = isset($_REQUEST['page']) ? sanitize_text_field( wp_unslash($_REQUEST['page']) ) : '';
        ?>
        <div class="wrap">
            <h2><?php esc_html_e('Application Details', 'application-form'); ?></h2> <a href="?page=<?php echo esc_attr($list_page); ?>" class="submission_btl_btn">< <?php esc_html_e('Back to List', 'application-form'); ?></a>
            <table class="application-submission-table">
                <tr>
                    <th><?php esc_html_e('Field Name', 'application-form'); ?></th>
                    <th><?php esc_html_e('Value', 'application-form'); ?></th>
                </tr>
                <tr>
                    <td><?php esc_html_e('ID', 'application-form'); ?></td>
                    <td><?php esc_html_e($submission->id); ?></td>
                </tr>
                <tr>
                    <td><?php esc_html_e('Name', 'application-form'); ?></td>
                    <td><?php esc_html_e($name); ?></td>
                </tr>
                <tr>
                    <td><?php esc_html_e('Present Address', 'application-form'); ?></td>
                    <td><?php esc_html_e($submission->present_address); ?></td>
                </tr>
                <tr>
                    <td><?php esc_html_e('Email', 'application-form'); ?></td>
                    <td><?php esc_html_e($submission->email); ?></td>
                </tr>
                <tr>
                    <td><?php esc_html_e('Mobile Number', 'application-form'); ?></td>
                    <td><?php esc_html_e($submission->mobile); ?></td>
                </tr>
                <tr>
                    <td><?php esc_html_e('Post Name', 'application-form'); ?></td>
                    <td><?php esc_html_e($submission->postname); ?></td>
                </tr>
                <tr>
                    <td><?php esc_html_e('Submit Date', 'application-form'); ?></td>
                    <td><?php esc_html_e(wp_date( get_option( 'date_format' ), strtotime( $submission->submission_time ) )); ?></td>
                </tr>
                <tr>
                    <td><?php esc_html_e('CV File', 'application-form'); ?></td>
                    <td>
                        <?php echo esc_url($submission->cv); ?><br />
                        <a href="<?php echo esc_url($submission->cv); ?>" download><?php esc_html_e('Download File', 'application-form'); ?></a>
                    </td>
                </tr>
            </table>
        </div>

        <?php
    }
}   