<?php
/**
 * Admin_Notices
 *
 * Generate admin notices.
 * php version 7.4
 * 
 * @category Template_Class
 * @package  Template_Class
 * @author   Saroar Hossain <limonhossain57@gmail.com>
 * @license  https://www.gnu.org/licenses/gpl-3.0.html GPLv3
 * @link     none
 */
namespace Application_Form\Core\Admin;

defined('ABSPATH') || exit;

/**
 * Creates admin notices for delete and failed user delete.
 * 
 * @class    Admin_Notices
 * @category Template_Class
 * @package  Template_Class
 * @author   Saroar Hossain <limonhossain57@gmail.com>
 * @license  https://www.gnu.org/licenses/gpl-3.0.html GPLv3
 * @link     none
 */
class Admin_Notices
{
    private static $_instance;

    /**
     * Class constractor
     **/
    public function __construct()
    {
        add_action('admin_notices', [$this, 'show_submission_action_notices']);
    }

    public function show_submission_action_notices()
    {
        if(isset( $_GET['application-deleted'] )){ // @codingStandardsIgnoreLine WordPress.Security.NonceVerification.Recommended
            $boolean = (strtolower(sanitize_text_field(wp_unslash($_GET['application-deleted']))) === 'false') ? false : true; // @codingStandardsIgnoreLine WordPress.Security.NonceVerification.Recommended
            if($boolean === true){
                ?>
                <div class="notice notice-success is-dismissible">
                    <p><?php esc_html_e('Application deleted', 'application-form'); ?></p>
                </div>
                <?php
            }
            if($boolean === false){
                ?>
                <div class="notice notice-error is-dismissible">
                    <p><?php esc_html_e('Application is not deleted', 'application-form'); ?></p>
                </div>
                <?php
            }
        }
    }

    public static function instance(){
        if (!self::$_instance) {
            self::$_instance = new self();
        }
        return self::$_instance;
    }
}