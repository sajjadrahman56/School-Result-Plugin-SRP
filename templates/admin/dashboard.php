<?php
/**
 * Admin dashboard template
 */

// Exit if accessed directly
if (!defined('ABSPATH')) {
    exit;
}
?>

<div class="wrap">
    <h1><?php _e('School Result Plugin Dashboard', 'school-result-plugin'); ?></h1>
    
    <div class="srp-dashboard-widgets">
        <div class="srp-widget">
            <h2><?php _e('Quick Stats', 'school-result-plugin'); ?></h2>
            <ul>
                <li><?php printf(__('Total Students: %d', 'school-result-plugin'), $student_count); ?></li>
                <li><?php printf(__('Active Terms: %d', 'school-result-plugin'), count($terms)); ?></li>
            </ul>
        </div>
        
        <div class="srp-widget">
            <h2><?php _e('Quick Links', 'school-result-plugin'); ?></h2>
            <ul>
                <li><a href="<?php echo admin_url('admin.php?page=school-result-students&action=add'); ?>"><?php _e('Add New Student', 'school-result-plugin'); ?></a></li>
                <li><a href="<?php echo admin_url('admin.php?page=school-result-terms&action=add'); ?>"><?php _e('Add New Term', 'school-result-plugin'); ?></a></li>
                <li><a href="<?php echo admin_url('admin.php?page=school-result-subjects&action=add'); ?>"><?php _e('Add New Subject', 'school-result-plugin'); ?></a></li>
                <li><a href="<?php echo admin_url('admin.php?page=school-result-results&action=add'); ?>"><?php _e('Add New Result', 'school-result-plugin'); ?></a></li>
            </ul>
        </div>
        
        <div class="srp-widget">
            <h2><?php _e('About', 'school-result-plugin'); ?></h2>
            <p><?php _e('School Result Plugin (SRP) manages school exam results with Bangladeshi grading standards (GPA 5.0 scale).', 'school-result-plugin'); ?></p>
            <p><strong><?php _e('Version:', 'school-result-plugin'); ?></strong> <?php echo SRP_VERSION; ?></p>
        </div>
    </div>
    
    <?php if (!empty($terms)): ?>
    <div class="srp-recent-terms">
        <h2><?php _e('Recent Terms', 'school-result-plugin'); ?></h2>
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
                        <a href="<?php echo admin_url('admin.php?page=school-result-results&action=view&term_id=' . $term->id); ?>"><?php _e('View Results', 'school-result-plugin'); ?></a>
                    </td>
                </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>
    <?php endif; ?>
</div>
