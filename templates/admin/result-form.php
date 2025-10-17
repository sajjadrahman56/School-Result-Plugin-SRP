<?php
/**
 * Result form template
 */

// Exit if accessed directly
if (!defined('ABSPATH')) {
    exit;
}
?>

<div class="wrap">
    <h1><?php _e('Add New Result', 'school-result-plugin'); ?></h1>
    
    <?php if (empty($students) || empty($subjects) || empty($terms)): ?>
        <div class="notice notice-error">
            <p><?php _e('Please make sure you have added students, subjects, and terms before adding results.', 'school-result-plugin'); ?></p>
        </div>
        <p>
            <a href="<?php echo admin_url('admin.php?page=school-result-students&action=add'); ?>" class="button"><?php _e('Add Student', 'school-result-plugin'); ?></a>
            <a href="<?php echo admin_url('admin.php?page=school-result-subjects&action=add'); ?>" class="button"><?php _e('Add Subject', 'school-result-plugin'); ?></a>
            <a href="<?php echo admin_url('admin.php?page=school-result-terms&action=add'); ?>" class="button"><?php _e('Add Term', 'school-result-plugin'); ?></a>
        </p>
    <?php else: ?>
    <form method="post" action="<?php echo admin_url('admin-post.php'); ?>">
        <input type="hidden" name="action" value="srp_add_result">
        <?php wp_nonce_field('srp_add_result', 'srp_nonce'); ?>
        
        <table class="form-table">
            <tr>
                <th scope="row"><label for="student_id"><?php _e('Student', 'school-result-plugin'); ?> *</label></th>
                <td>
                    <select name="student_id" id="student_id" class="regular-text" required>
                        <option value=""><?php _e('Select Student', 'school-result-plugin'); ?></option>
                        <?php foreach ($students as $student): ?>
                            <option value="<?php echo esc_attr($student->id); ?>">
                                <?php echo esc_html($student->student_name . ' - Roll: ' . $student->student_roll . ' - ' . $student->student_class); ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                </td>
            </tr>
            <tr>
                <th scope="row"><label for="term_id"><?php _e('Term', 'school-result-plugin'); ?> *</label></th>
                <td>
                    <select name="term_id" id="term_id" class="regular-text" required>
                        <option value=""><?php _e('Select Term', 'school-result-plugin'); ?></option>
                        <?php foreach ($terms as $term): ?>
                            <option value="<?php echo esc_attr($term->id); ?>">
                                <?php echo esc_html($term->term_name . ' - ' . $term->term_year); ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                </td>
            </tr>
            <tr>
                <th scope="row"><label for="subject_id"><?php _e('Subject', 'school-result-plugin'); ?> *</label></th>
                <td>
                    <select name="subject_id" id="subject_id" class="regular-text" required>
                        <option value=""><?php _e('Select Subject', 'school-result-plugin'); ?></option>
                        <?php foreach ($subjects as $subject): ?>
                            <option value="<?php echo esc_attr($subject->id); ?>" data-full-marks="<?php echo esc_attr($subject->full_marks); ?>">
                                <?php echo esc_html($subject->subject_name . ' - ' . $subject->subject_class . ' (Max: ' . $subject->full_marks . ')'); ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                </td>
            </tr>
            <tr>
                <th scope="row"><label for="marks_obtained"><?php _e('Marks Obtained', 'school-result-plugin'); ?> *</label></th>
                <td>
                    <input type="number" name="marks_obtained" id="marks_obtained" step="0.01" min="0" max="100" required>
                    <p class="description"><?php _e('Enter marks obtained by the student (GPA will be calculated automatically)', 'school-result-plugin'); ?></p>
                </td>
            </tr>
        </table>
        
        <p class="submit">
            <input type="submit" name="submit" id="submit" class="button button-primary" value="<?php _e('Add Result', 'school-result-plugin'); ?>">
            <a href="<?php echo admin_url('admin.php?page=school-result-results'); ?>" class="button"><?php _e('Cancel', 'school-result-plugin'); ?></a>
        </p>
    </form>
    <?php endif; ?>
</div>
