<?php
/**
 * Subject form template
 */

// Exit if accessed directly
if (!defined('ABSPATH')) {
    exit;
}
?>

<div class="wrap">
    <h1><?php _e('Add New Subject', 'school-result-plugin'); ?></h1>
    
    <form method="post" action="<?php echo admin_url('admin-post.php'); ?>">
        <input type="hidden" name="action" value="srp_add_subject">
        <?php wp_nonce_field('srp_add_subject', 'srp_nonce'); ?>
        
        <table class="form-table">
            <tr>
                <th scope="row"><label for="subject_name"><?php _e('Subject Name', 'school-result-plugin'); ?> *</label></th>
                <td><input type="text" name="subject_name" id="subject_name" class="regular-text" required></td>
            </tr>
            <tr>
                <th scope="row"><label for="subject_code"><?php _e('Subject Code', 'school-result-plugin'); ?> *</label></th>
                <td><input type="text" name="subject_code" id="subject_code" class="regular-text" required></td>
            </tr>
            <tr>
                <th scope="row"><label for="subject_class"><?php _e('Class', 'school-result-plugin'); ?> *</label></th>
                <td><input type="text" name="subject_class" id="subject_class" class="regular-text" required></td>
            </tr>
            <tr>
                <th scope="row"><label for="full_marks"><?php _e('Full Marks', 'school-result-plugin'); ?></label></th>
                <td><input type="number" name="full_marks" id="full_marks" value="100" min="1" required></td>
            </tr>
            <tr>
                <th scope="row"><label for="pass_marks"><?php _e('Pass Marks', 'school-result-plugin'); ?></label></th>
                <td><input type="number" name="pass_marks" id="pass_marks" value="33" min="1" required></td>
            </tr>
            <tr>
                <th scope="row"><label for="is_optional"><?php _e('Optional Subject', 'school-result-plugin'); ?></label></th>
                <td>
                    <input type="checkbox" name="is_optional" id="is_optional" value="1">
                    <span class="description"><?php _e('Check if this is an optional subject', 'school-result-plugin'); ?></span>
                </td>
            </tr>
        </table>
        
        <p class="submit">
            <input type="submit" name="submit" id="submit" class="button button-primary" value="<?php _e('Add Subject', 'school-result-plugin'); ?>">
            <a href="<?php echo admin_url('admin.php?page=school-result-subjects'); ?>" class="button"><?php _e('Cancel', 'school-result-plugin'); ?></a>
        </p>
    </form>
</div>
