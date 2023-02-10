<div class="appli-form-container">
    <h2><?php esc_html_e('WeDevs Application Form', 'application-form') ?></h2>
    <form action="<?php echo esc_url(get_rest_url( null, 'application-form/v1/submit' )); ?>" method="post" enctype="multipart/form-data" id="appli-application-form">
        <?php wp_nonce_field('wp_rest'); ?>
        <div class="appli-form-fields">
            <div class="appli-form-row appli-firstname">
                <label for="firstname"><?php esc_html_e('First Name', 'td'); ?> <span>*</span></label>
                <input type="text" id="firstname" name="firstname" value="" placeholder="<?php esc_attr_e('Enter First Name', 'td'); ?>">
                <p class="appli-error"></p>
            </div>
            <div class="appli-form-row appli-lastname">
                <label for="lastname"><?php esc_html_e('Last Name', 'td'); ?> <span>*</span></label>
                <input type="text" id="lastname" name="lastname" value="" placeholder="<?php esc_attr_e('Enter Last Name', 'td'); ?>">
                <p class="appli-error"></p>
            </div>
            <div class="appli-form-row appli-present-address">
                <label for="present_address"><?php esc_html_e('Present Address', 'td'); ?> <span>*</span></label>
                <input type="text" id="present_address" name="present_address" value="" placeholder="<?php esc_attr_e('Enter Present Address', 'td'); ?>">
                <p class="appli-error"></p>
            </div>
            <div class="appli-form-row appli-email">
                <label for="email"><?php esc_html_e('Email', 'td'); ?> <span>*</span></label>
                <input type="email" id="email" name="email" value="" placeholder="<?php esc_attr_e('Enter Email', 'td'); ?>">
                <p class="appli-error"></p>
            </div>
            <div class="appli-form-row appli-mobile">
                <label for="mobile"><?php esc_html_e('Mobile Number', 'td'); ?> <span>*</span> (<small><?php esc_html_e('Only Bangladeshi Numbers Are Allowed', 'td'); ?></small>)</label>
                <input type="text" id="mobile" name="mobile" value="" placeholder="<?php esc_attr_e('Enter Mobile Number', 'td'); ?>">
                <p class="appli-error"></p>
            </div>
            <div class="appli-form-row appli-postname">
                <label for="postname"><?php esc_html_e('Post Name', 'td'); ?> <span>*</span></label>
                <input type="text" id="postname" name="postname" value="" placeholder="<?php esc_attr_e('Enter Post Name', 'td'); ?>">
                <p class="appli-error"></p>
            </div>
            <div class="appli-form-row appli-cv">
                <label for="cv"><?php esc_html_e('Your CV', 'td'); ?> <span>*</span> (<small><?php esc_html_e('Only images and PDF files are allowed. Maximum File Size Is 10MB', 'td'); ?></small>)</label>
                <input type="file" id="cv" name="cv" value="" placeholder="<?php esc_attr_e('Attach Your CV', 'td'); ?>" accept="image/png, image/jpeg, application/pdf">
                <p class="appli-error"></p>
            </div>
        </div>
        <div class="appli-form-submit">
            <button type="submit" id="appli-submit-button"><?php esc_html_e('Enter First Name', 'td'); ?></button>
            <p class="appli-global-notice"></p>
        </div>
    </form>
</div>