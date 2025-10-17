<?php
/**
 * Students list template
 */

// Exit if accessed directly
if (!defined('ABSPATH')) {
    exit;
}
?>

<div class="wrap">
    <h1>
        <?php _e('Students', 'school-result-plugin'); ?>
        <a href="<?php echo admin_url('admin.php?page=school-result-students&action=add'); ?>" class="page-title-action"><?php _e('Add New', 'school-result-plugin'); ?></a>
    </h1>
    
    <?php if (isset($_GET['message'])): ?>
    <div class="notice notice-success is-dismissible">
        <p>
            <?php 
            if ($_GET['message'] === 'added') {
                _e('Student added successfully.', 'school-result-plugin');
            } elseif ($_GET['message'] === 'updated') {
                _e('Student updated successfully.', 'school-result-plugin');
            }
            ?>
        </p>
    </div>
    <?php endif; ?>
    
    <?php if (empty($students)): ?>
        <p><?php _e('No students found. Add your first student!', 'school-result-plugin'); ?></p>
    <?php else: ?>
    <table class="wp-list-table widefat fixed striped">
        <thead>
            <tr>
                <th><?php _e('Photo', 'school-result-plugin'); ?></th>
                <th><?php _e('Name', 'school-result-plugin'); ?></th>
                <th><?php _e('Roll', 'school-result-plugin'); ?></th>
                <th><?php _e('Class', 'school-result-plugin'); ?></th>
                <th><?php _e('Section', 'school-result-plugin'); ?></th>
                <th><?php _e('Email', 'school-result-plugin'); ?></th>
                <th><?php _e('Actions', 'school-result-plugin'); ?></th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($students as $student): ?>
            <tr>
                <td>
                    <?php if (!empty($student->student_photo)): ?>
                        <img src="<?php echo esc_url($student->student_photo); ?>" alt="<?php echo esc_attr($student->student_name); ?>" style="width: 50px; height: 50px; object-fit: cover; border-radius: 4px;">
                    <?php else: ?>
                        <span class="dashicons dashicons-admin-users" style="font-size: 50px; color: #ccc;"></span>
                    <?php endif; ?>
                </td>
                <td><?php echo esc_html($student->student_name); ?></td>
                <td><?php echo esc_html($student->student_roll); ?></td>
                <td><?php echo esc_html($student->student_class); ?></td>
                <td><?php echo esc_html($student->student_section); ?></td>
                <td><?php echo esc_html($student->student_email); ?></td>
                <td>
                    <a href="<?php echo admin_url('admin.php?page=school-result-students&action=edit&student_id=' . $student->id); ?>"><?php _e('Edit', 'school-result-plugin'); ?></a>
                </td>
            </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
    <?php endif; ?>
</div>
