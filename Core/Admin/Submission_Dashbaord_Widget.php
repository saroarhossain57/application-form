<?php
namespace Application_Form\Core\Admin;

defined('ABSPATH') || exit;

use Application_Form\Core\Models\Form_Submission;
class Submission_Dashbaord_Widget {

    private static $instance;

    public function __construct(){
        add_action( 'wp_dashboard_setup', [$this, 'setup_widgets'] );
    }
  
    public function setup_widgets(){
        wp_add_dashboard_widget('application_submissions_widget', __('Latest Applications', 'application-form'), [$this, 'render_applications_widget']);
    }
    public function render_applications_widget(){
        $args = [
            'number' => 5,
            'orderby' => 'id',
            'order'   => 'DESC'
        ];

        $submissions = Form_Submission::get_all( $args );

        if(is_array($submissions)){
            ?>

            <div class="appli-widgets-container">
            <?php 
            foreach($submissions as $submission) :
                $appplication_url = get_admin_url() . 'admin.php?page=application_submissions&action=view&submission=' . $submission->id;
                $name = sprintf('<a href="%s" title="%s">%s</a>', esc_url($appplication_url), __('Click to view details', 'application-form'), $submission->firstname . ' ' . $submission->lastname);
            ?>

                <div class="appli-latest-application">
                    <?php echo $name; ?> - <?php _e('Post: ', 'application-form'); ?><?php echo esc_html($submission->postname); ?> - <?php echo wp_date( 'd M Y', strtotime( $submission->submission_time ) ); ?> - <a href="<?php echo esc_url($appplication_url); ?>"><?php echo __('View', 'application-form') ?></a>
                </div><br>
            <?php endforeach; ?>
            </div>
            <?php
        }
    }


    public static function instance(){
        if (!self::$instance) {
            self::$instance = new self();
        }
        return self::$instance;
    }
}