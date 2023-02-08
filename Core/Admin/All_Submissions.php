<?php
namespace Application_Form\Core\Admin;

defined('ABSPATH') || exit;

class All_Submissions {
    
    private $submission_list_table;

    public function __construct(){
        $this->submission_list_table = new Submission_List_Table();
    }

    public function render(){
        ?>
        <div class="wrap">
            <h2><?php _e('Application Submissions', 'application-form') ?></h2>
            <form method="post">
            <?php
                $this->submission_list_table->prepare_items();
                $this->submission_list_table->search_box('search', 'search_id');
                $this->submission_list_table->display();   
            ?>
            </form>
        </div>

        <?php
    }
}