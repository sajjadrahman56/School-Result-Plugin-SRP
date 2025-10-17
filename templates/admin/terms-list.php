<?php
/**
 * Terms list template
 */

// Exit if accessed directly
if (!defined('ABSPATH')) {
    exit;
}
?>

<div class="wrap">
    <h1>
        <?php _e('Terms', 'school-result-plugin'); ?>
        <a href="<?php echo admin_url('admin.php?page=school-result-terms&action=add'); ?>" class="page-title-action"><?php _e('Add New', 'school-result-plugin'); ?></a>
    </h1>
    
    <?php if (isset($_GET['message'])): ?>
    <div class="notice notice-success is-dismissible">
        <p>
            <?php 
            if ($_GET['message'] === 'added') {
                _e('Term added successfully.', 'school-result-plugin');
            } elseif ($_GET['message'] === 'updated') {
                _e('Term updated successfully.', 'school-result-plugin');
            }
            ?>
        </p>
    </div>
    <?php endif; ?>
    
    <?php if (empty($terms)): ?>
        <p><?php _e('No terms found. Add your first term!', 'school-result-plugin'); ?></p>
    <?php else: ?>
    <table class="wp-list-table widefat fixed striped">
        <thead>
            <tr>
                <th><?php _e('Term Name', 'school-result-plugin'); ?></th>
                <th><?php _e('Year', 'school-result-plugin'); ?></th>
                <th><?php _e('Start Date', 'school-result-plugin'); ?></th>
                <th><?php _e('End Date', 'school-result-plugin'); ?></th>
                <th><?php _e('Status', 'school-result-plugin'); ?></th>
                <th><?php _e('Actions', 'school-result-plugin'); ?></th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($terms as $term): ?>
            <tr>
                <td><?php echo esc_html($term->term_name); ?></td>
                <td><?php echo esc_html($term->term_year); ?></td>
                <td><?php echo esc_html($term->term_start_date); ?></td>
                <td><?php echo esc_html($term->term_end_date); ?></td>
                <td>
                    <span class="srp-status srp-status-<?php echo esc_attr($term->term_status); ?>">
                        <?php echo esc_html($term->term_status); ?>
                    </span>
                </td>
                <td>
                    <a href="<?php echo admin_url('admin.php?page=school-result-terms&action=edit&term_id=' . $term->id); ?>"><?php _e('Edit', 'school-result-plugin'); ?></a> |
                    <a href="<?php echo admin_url('admin.php?page=school-result-results&action=view&term_id=' . $term->id); ?>"><?php _e('View Results', 'school-result-plugin'); ?></a>
                </td>
            </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
    <?php endif; ?>
</div>
