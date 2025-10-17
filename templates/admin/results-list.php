<?php
/**
 * Results list template
 */

// Exit if accessed directly
if (!defined('ABSPATH')) {
    exit;
}
?>

<div class="wrap">
    <h1>
        <?php _e('Results', 'school-result-plugin'); ?>
        <a href="<?php echo admin_url('admin.php?page=school-result-results&action=add'); ?>" class="page-title-action"><?php _e('Add New', 'school-result-plugin'); ?></a>
    </h1>
    
    <?php if (isset($_GET['message']) && $_GET['message'] === 'added'): ?>
    <div class="notice notice-success is-dismissible">
        <p><?php _e('Result added successfully.', 'school-result-plugin'); ?></p>
    </div>
    <?php endif; ?>
    
    <h2><?php _e('View Results by Term', 'school-result-plugin'); ?></h2>
    
    <?php if (empty($terms)): ?>
        <p><?php _e('No terms found. Please add a term first.', 'school-result-plugin'); ?></p>
    <?php else: ?>
    <table class="wp-list-table widefat fixed striped">
        <thead>
            <tr>
                <th><?php _e('Term Name', 'school-result-plugin'); ?></th>
                <th><?php _e('Year', 'school-result-plugin'); ?></th>
                <th><?php _e('Status', 'school-result-plugin'); ?></th>
                <th><?php _e('Actions', 'school-result-plugin'); ?></th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($terms as $term): ?>
            <tr>
                <td><?php echo esc_html($term->term_name); ?></td>
                <td><?php echo esc_html($term->term_year); ?></td>
                <td><?php echo esc_html($term->term_status); ?></td>
                <td>
                    <a href="<?php echo admin_url('admin.php?page=school-result-results&action=view&term_id=' . $term->id); ?>" class="button button-primary"><?php _e('View Results', 'school-result-plugin'); ?></a>
                </td>
            </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
    <?php endif; ?>
</div>
