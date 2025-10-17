<?php
/**
 * Term form template (Add/Edit)
 */

// Exit if accessed directly
if (!defined('ABSPATH')) {
    exit;
}

$is_edit = !empty($term);
$form_title = $is_edit ? __('Edit Term', 'school-result-plugin') : __('Add New Term', 'school-result-plugin');
?>

<div class="wrap">
    <h1><?php echo $form_title; ?></h1>
    
    <form method="post" action="<?php echo admin_url('admin-post.php'); ?>">
        <input type="hidden" name="action" value="srp_add_term">
        <?php wp_nonce_field('srp_add_term', 'srp_nonce'); ?>
        
        <?php if ($is_edit): ?>
            <input type="hidden" name="term_id" value="<?php echo esc_attr($term->id); ?>">
        <?php endif; ?>
        
        <table class="form-table">
            <tr>
                <th scope="row"><label for="term_name"><?php _e('Term Name', 'school-result-plugin'); ?> *</label></th>
                <td><input type="text" name="term_name" id="term_name" class="regular-text" value="<?php echo $is_edit ? esc_attr($term->term_name) : ''; ?>" required></td>
            </tr>
            <tr>
                <th scope="row"><label for="term_year"><?php _e('Year', 'school-result-plugin'); ?> *</label></th>
                <td><input type="text" name="term_year" id="term_year" class="regular-text" value="<?php echo $is_edit ? esc_attr($term->term_year) : date('Y'); ?>" required></td>
            </tr>
            <tr>
                <th scope="row"><label for="term_start_date"><?php _e('Start Date', 'school-result-plugin'); ?></label></th>
                <td><input type="date" name="term_start_date" id="term_start_date" value="<?php echo $is_edit ? esc_attr($term->term_start_date) : ''; ?>"></td>
            </tr>
            <tr>
                <th scope="row"><label for="term_end_date"><?php _e('End Date', 'school-result-plugin'); ?></label></th>
                <td><input type="date" name="term_end_date" id="term_end_date" value="<?php echo $is_edit ? esc_attr($term->term_end_date) : ''; ?>"></td>
            </tr>
            <tr>
                <th scope="row"><label for="term_status"><?php _e('Status', 'school-result-plugin'); ?></label></th>
                <td>
                    <select name="term_status" id="term_status">
                        <option value="active" <?php echo ($is_edit && $term->term_status === 'active') ? 'selected' : ''; ?>><?php _e('Active', 'school-result-plugin'); ?></option>
                        <option value="completed" <?php echo ($is_edit && $term->term_status === 'completed') ? 'selected' : ''; ?>><?php _e('Completed', 'school-result-plugin'); ?></option>
                        <option value="upcoming" <?php echo ($is_edit && $term->term_status === 'upcoming') ? 'selected' : ''; ?>><?php _e('Upcoming', 'school-result-plugin'); ?></option>
                    </select>
                </td>
            </tr>
        </table>
        
        <p class="submit">
            <input type="submit" name="submit" id="submit" class="button button-primary" value="<?php echo $is_edit ? __('Update Term', 'school-result-plugin') : __('Add Term', 'school-result-plugin'); ?>">
            <a href="<?php echo admin_url('admin.php?page=school-result-terms'); ?>" class="button"><?php _e('Cancel', 'school-result-plugin'); ?></a>
        </p>
    </form>
</div>
