<?php
/**
 * Results view template
 */

// Exit if accessed directly
if (!defined('ABSPATH')) {
    exit;
}
?>

<div class="wrap">
    <h1><?php printf(__('Results for %s - %s', 'school-result-plugin'), esc_html($term->term_name), esc_html($term->term_year)); ?></h1>
    
    <?php if (isset($_GET['message']) && $_GET['message'] === 'calculated'): ?>
    <div class="notice notice-success is-dismissible">
        <p><?php _e('Positions calculated successfully.', 'school-result-plugin'); ?></p>
    </div>
    <?php endif; ?>
    
    <div class="srp-actions" style="margin: 20px 0;">
        <form method="post" action="<?php echo admin_url('admin-post.php'); ?>" style="display: inline-block;">
            <input type="hidden" name="action" value="srp_calculate_positions">
            <input type="hidden" name="term_id" value="<?php echo esc_attr($term->id); ?>">
            <?php wp_nonce_field('srp_calculate_positions', 'srp_nonce'); ?>
            <input type="submit" class="button button-primary" value="<?php _e('Calculate Positions', 'school-result-plugin'); ?>">
        </form>
        <a href="<?php echo admin_url('admin.php?page=school-result-results'); ?>" class="button"><?php _e('Back to Terms', 'school-result-plugin'); ?></a>
    </div>
    
    <?php if (empty($rankings)): ?>
        <p><?php _e('No results found for this term. Please add results first.', 'school-result-plugin'); ?></p>
    <?php else: ?>
    <table class="wp-list-table widefat fixed striped">
        <thead>
            <tr>
                <th><?php _e('Position', 'school-result-plugin'); ?></th>
                <th><?php _e('Student Name', 'school-result-plugin'); ?></th>
                <th><?php _e('Roll', 'school-result-plugin'); ?></th>
                <th><?php _e('Class', 'school-result-plugin'); ?></th>
                <th><?php _e('Section', 'school-result-plugin'); ?></th>
                <th><?php _e('Total Marks', 'school-result-plugin'); ?></th>
                <th><?php _e('Average GPA', 'school-result-plugin'); ?></th>
                <th><?php _e('Grade', 'school-result-plugin'); ?></th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($rankings as $ranking): ?>
            <tr>
                <td><strong><?php echo esc_html($ranking->position ? $ranking->position : '-'); ?></strong></td>
                <td><?php echo esc_html($ranking->student_name); ?></td>
                <td><?php echo esc_html($ranking->student_roll); ?></td>
                <td><?php echo esc_html($ranking->student_class); ?></td>
                <td><?php echo esc_html($ranking->student_section); ?></td>
                <td><?php echo esc_html(number_format($ranking->total_marks, 2)); ?></td>
                <td><strong><?php echo esc_html(number_format($ranking->average_gpa, 2)); ?></strong></td>
                <td>
                    <span class="srp-grade srp-grade-<?php echo esc_attr(strtolower(str_replace('+', 'plus', $ranking->grade))); ?>">
                        <?php echo esc_html($ranking->grade); ?>
                    </span>
                </td>
            </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
    <?php endif; ?>
</div>
