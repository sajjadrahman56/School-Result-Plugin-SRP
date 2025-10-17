<?php
/**
 * Subjects list template
 */

// Exit if accessed directly
if (!defined('ABSPATH')) {
    exit;
}
?>

<div class="wrap">
    <h1>
        <?php _e('Subjects', 'school-result-plugin'); ?>
        <a href="<?php echo admin_url('admin.php?page=school-result-subjects&action=add'); ?>" class="page-title-action"><?php _e('Add New', 'school-result-plugin'); ?></a>
    </h1>
    
    <?php if (isset($_GET['message']) && $_GET['message'] === 'added'): ?>
    <div class="notice notice-success is-dismissible">
        <p><?php _e('Subject added successfully.', 'school-result-plugin'); ?></p>
    </div>
    <?php endif; ?>
    
    <?php if (empty($subjects)): ?>
        <p><?php _e('No subjects found. Add your first subject!', 'school-result-plugin'); ?></p>
    <?php else: ?>
    <table class="wp-list-table widefat fixed striped">
        <thead>
            <tr>
                <th><?php _e('Subject Name', 'school-result-plugin'); ?></th>
                <th><?php _e('Subject Code', 'school-result-plugin'); ?></th>
                <th><?php _e('Class', 'school-result-plugin'); ?></th>
                <th><?php _e('Full Marks', 'school-result-plugin'); ?></th>
                <th><?php _e('Pass Marks', 'school-result-plugin'); ?></th>
                <th><?php _e('Optional', 'school-result-plugin'); ?></th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($subjects as $subject): ?>
            <tr>
                <td><?php echo esc_html($subject->subject_name); ?></td>
                <td><?php echo esc_html($subject->subject_code); ?></td>
                <td><?php echo esc_html($subject->subject_class); ?></td>
                <td><?php echo esc_html($subject->full_marks); ?></td>
                <td><?php echo esc_html($subject->pass_marks); ?></td>
                <td><?php echo $subject->is_optional ? __('Yes', 'school-result-plugin') : __('No', 'school-result-plugin'); ?></td>
            </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
    <?php endif; ?>
</div>
