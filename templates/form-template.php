<div class="appli-form-container">
    <h2><?php _e('WeDevs Application Form', 'application-form') ?></h2>
    <form action="<?php echo esc_url(get_rest_url( null, 'application-form/v1/submit' )); ?>" method="post" enctype="multipart/form-data" id="appli-application-form">
        <?php wp_nonce_field('wp_rest'); ?>
        <div class="appli-form-fields">
            <div class="appli-form-row appli-firstname">
                <label for="firstname">First Name <span>*</span></label>
                <input type="text" id="firstname" name="firstname" value="" placeholder="Enter First Name">
                <p class="appli-error"></p>
            </div>
            <div class="appli-form-row appli-lastname">
                <label for="lastname">Last Name <span>*</span></label>
                <input type="text" id="lastname" name="lastname" value="" placeholder="Enter Last Name">
                <p class="appli-error"></p>
            </div>
            <div class="appli-form-row appli-present-address">
                <label for="present_address">Present Address <span>*</span></label>
                <input type="text" id="present_address" name="present_address" value="" placeholder="Enter Present Address">
                <p class="appli-error"></p>
            </div>
            <div class="appli-form-row appli-email">
                <label for="email">Email <span>*</span></label>
                <input type="email" id="email" name="email" value="" placeholder="Enter Email">
                <p class="appli-error"></p>
            </div>
            <div class="appli-form-row appli-mobile">
                <label for="mobile">Mobile Number <span>*</span> (<small>Only Bangladeshi Numbers Are Allowed</small>)</label>
                <input type="text" id="mobile" name="mobile" value="" placeholder="Enter Mobile Number">
                <p class="appli-error"></p>
            </div>
            <div class="appli-form-row appli-postname">
                <label for="postname">Post Name <span>*</span></label>
                <input type="text" id="postname" name="postname" value="" placeholder="Enter Post Name">
                <p class="appli-error"></p>
            </div>
            <div class="appli-form-row appli-cv">
                <label for="cv">Your CV <span>*</span> (<small>Only images and PDF files are allowed. Maximum File Size Is 10MB</small>)</label>
                <input type="file" id="cv" name="cv" value="" placeholder="Attach Your CV" accept="image/png, image/jpeg, application/pdf">
                <p class="appli-error"></p>
            </div>
        </div>
        <div class="appli-form-submit">
            <button type="submit" id="appli-submit-button">Submit Application</button>
            <p class="appli-global-notice"></p>
        </div>
    </form>
</div>