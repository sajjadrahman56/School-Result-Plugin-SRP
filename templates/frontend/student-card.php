<?php
/**
 * Frontend student card template
 */

// Exit if accessed directly
if (!defined('ABSPATH')) {
    exit;
}
?>

<div class="srp-student-card">
    <?php if (!empty($student->student_photo)): ?>
        <div class="srp-card-photo">
            <img src="<?php echo esc_url($student->student_photo); ?>" alt="<?php echo esc_attr($student->student_name); ?>">
        </div>
    <?php endif; ?>
    
    <div class="srp-card-info">
        <h3 class="srp-card-name"><?php echo esc_html($student->student_name); ?></h3>
        
        <div class="srp-card-details">
            <p><strong><?php _e('Roll Number:', 'school-result-plugin'); ?></strong> <?php echo esc_html($student->student_roll); ?></p>
            <p><strong><?php _e('Class:', 'school-result-plugin'); ?></strong> <?php echo esc_html($student->student_class); ?>
            <?php if (!empty($student->student_section)): ?>
                - <?php echo esc_html($student->student_section); ?>
            <?php endif; ?>
            </p>
            
            <?php if (!empty($student->student_email)): ?>
                <p><strong><?php _e('Email:', 'school-result-plugin'); ?></strong> <?php echo esc_html($student->student_email); ?></p>
            <?php endif; ?>
            
            <?php if (!empty($student->student_phone)): ?>
                <p><strong><?php _e('Phone:', 'school-result-plugin'); ?></strong> <?php echo esc_html($student->student_phone); ?></p>
            <?php endif; ?>
            
            <?php if (!empty($student->date_of_birth) && $student->date_of_birth !== '0000-00-00'): ?>
                <p><strong><?php _e('Date of Birth:', 'school-result-plugin'); ?></strong> <?php echo esc_html(date_i18n(get_option('date_format'), strtotime($student->date_of_birth))); ?></p>
            <?php endif; ?>
        </div>
    </div>
</div>
