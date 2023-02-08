<?php
namespace Application_Form\Core\Admin;

use Application_Form\Core\Models\Form_Submission;

defined('ABSPATH') || exit;

class Submission_Single_Item {

    public function render($submission_id){

        $submission = Form_Submission::get_by_id($submission_id);
        $name = "{$submission->firstname} {$submission->lastname}";
        
        ?>
        <div class="wrap">
            <h2><?php _e('Application Details', 'application-form'); ?></h2> <a href="?page=<?php echo esc_attr($_REQUEST['page']); ?>" class="submission_btl_btn">< <?php _e('Back to List', 'application-form'); ?></a>
            <table class="application-submission-table">
                <tr>
                    <th><?php _e('Field Name', 'application-form'); ?></th>
                    <th><?php _e('Value', 'application-form'); ?></th>
                </tr>
                <tr>
                    <td><?php _e('ID', 'application-form'); ?></td>
                    <td><?php esc_html_e($submission->id); ?></td>
                </tr>
                <tr>
                    <td><?php _e('Name', 'application-form'); ?></td>
                    <td><?php esc_html_e($name); ?></td>
                </tr>
                <tr>
                    <td><?php _e('Present Address', 'application-form'); ?></td>
                    <td><?php esc_html_e($submission->present_address); ?></td>
                </tr>
                <tr>
                    <td><?php _e('Email', 'application-form'); ?></td>
                    <td><?php esc_html_e($submission->email); ?></td>
                </tr>
                <tr>
                    <td><?php _e('Mobile Number', 'application-form'); ?></td>
                    <td><?php esc_html_e($submission->mobile); ?></td>
                </tr>
                <tr>
                    <td><?php _e('Post Name', 'application-form'); ?></td>
                    <td><?php esc_html_e($submission->postname); ?></td>
                </tr>
                <tr>
                    <td><?php _e('Submit Date', 'application-form'); ?></td>
                    <td><?php esc_html_e(wp_date( get_option( 'date_format' ), strtotime( $submission->submission_time ) )); ?></td>
                </tr>
                <tr>
                    <td><?php _e('CV File', 'application-form'); ?></td>
                    <td>
                        <?php echo esc_url($submission->cv); ?><br />
                        <a href="<?php echo esc_url($submission->cv); ?>" download><?php _e('Download File', 'application-form'); ?></a>
                    </td>
                </tr>
            </table>
        </div>

        <?php
    }
}   