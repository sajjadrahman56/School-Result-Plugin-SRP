<?php
/**
 * Student form template (Add/Edit)
 */

// Exit if accessed directly
if (!defined('ABSPATH')) {
    exit;
}

$is_edit = !empty($student);
$form_title = $is_edit ? __('Edit Student', 'school-result-plugin') : __('Add New Student', 'school-result-plugin');
?>

<div class="wrap">
    <h1><?php echo $form_title; ?></h1>
    
    <form method="post" action="<?php echo admin_url('admin-post.php'); ?>" enctype="multipart/form-data">
        <input type="hidden" name="action" value="srp_add_student">
        <?php wp_nonce_field('srp_add_student', 'srp_nonce'); ?>
        
        <?php if ($is_edit): ?>
            <input type="hidden" name="student_id" value="<?php echo esc_attr($student->id); ?>">
        <?php endif; ?>
        
        <table class="form-table">
            <tr>
                <th scope="row"><label for="student_name"><?php _e('Student Name', 'school-result-plugin'); ?> *</label></th>
                <td><input type="text" name="student_name" id="student_name" class="regular-text" value="<?php echo $is_edit ? esc_attr($student->student_name) : ''; ?>" required></td>
            </tr>
            <tr>
                <th scope="row"><label for="student_roll"><?php _e('Roll Number', 'school-result-plugin'); ?> *</label></th>
                <td><input type="text" name="student_roll" id="student_roll" class="regular-text" value="<?php echo $is_edit ? esc_attr($student->student_roll) : ''; ?>" required></td>
            </tr>
            <tr>
                <th scope="row"><label for="student_class"><?php _e('Class', 'school-result-plugin'); ?> *</label></th>
                <td><input type="text" name="student_class" id="student_class" class="regular-text" value="<?php echo $is_edit ? esc_attr($student->student_class) : ''; ?>" required></td>
            </tr>
            <tr>
                <th scope="row"><label for="student_section"><?php _e('Section', 'school-result-plugin'); ?></label></th>
                <td><input type="text" name="student_section" id="student_section" class="regular-text" value="<?php echo $is_edit ? esc_attr($student->student_section) : ''; ?>"></td>
            </tr>
            <tr>
                <th scope="row"><label for="student_email"><?php _e('Email', 'school-result-plugin'); ?></label></th>
                <td><input type="email" name="student_email" id="student_email" class="regular-text" value="<?php echo $is_edit ? esc_attr($student->student_email) : ''; ?>"></td>
            </tr>
            <tr>
                <th scope="row"><label for="student_phone"><?php _e('Phone', 'school-result-plugin'); ?></label></th>
                <td><input type="text" name="student_phone" id="student_phone" class="regular-text" value="<?php echo $is_edit ? esc_attr($student->student_phone) : ''; ?>"></td>
            </tr>
            <tr>
                <th scope="row"><label for="date_of_birth"><?php _e('Date of Birth', 'school-result-plugin'); ?></label></th>
                <td><input type="date" name="date_of_birth" id="date_of_birth" value="<?php echo $is_edit ? esc_attr($student->date_of_birth) : ''; ?>"></td>
            </tr>
            <tr>
                <th scope="row"><label for="student_photo"><?php _e('Student Photo', 'school-result-plugin'); ?></label></th>
                <td>
                    <?php if ($is_edit && !empty($student->student_photo)): ?>
                        <img src="<?php echo esc_url($student->student_photo); ?>" alt="<?php echo esc_attr($student->student_name); ?>" style="max-width: 150px; display: block; margin-bottom: 10px;">
                    <?php endif; ?>
                    <input type="file" name="student_photo" id="student_photo" accept="image/*">
                    <p class="description"><?php _e('Upload a photo for this student. Supported formats: JPG, PNG, GIF', 'school-result-plugin'); ?></p>
                </td>
            </tr>
        </table>
        
        <p class="submit">
            <input type="submit" name="submit" id="submit" class="button button-primary" value="<?php echo $is_edit ? __('Update Student', 'school-result-plugin') : __('Add Student', 'school-result-plugin'); ?>">
            <a href="<?php echo admin_url('admin.php?page=school-result-students'); ?>" class="button"><?php _e('Cancel', 'school-result-plugin'); ?></a>
        </p>
    </form>
</div>
