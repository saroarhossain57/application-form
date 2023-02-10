<?php
namespace Application_Form\Core\Admin;

defined('ABSPATH') || exit;

class All_Submissions {

    public function render(){

        $submission_list_table = new Submission_List_Table();
        $submission_list_table->prepare_items();
        ?>
        <div class="wrap">
            <h2><?php _e('Application Submissions', 'application-form') ?></h2>
            <?php
                $submission_list_table->search_box('search', 'search_id');
                $submission_list_table->display();   
            ?>
        </div>

        <?php
    }
}