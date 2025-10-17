<?php
/**
 * Frontend student result template
 */

// Exit if accessed directly
if (!defined('ABSPATH')) {
    exit;
}
?>

<div class="srp-student-result">
    <div class="srp-result-header">
        <div class="srp-student-info">
            <?php if (!empty($student->student_photo)): ?>
                <img src="<?php echo esc_url($student->student_photo); ?>" alt="<?php echo esc_attr($student->student_name); ?>" class="srp-student-photo">
            <?php endif; ?>
            <div class="srp-student-details">
                <h2><?php echo esc_html($student->student_name); ?></h2>
                <p><strong><?php _e('Roll:', 'school-result-plugin'); ?></strong> <?php echo esc_html($student->student_roll); ?></p>
                <p><strong><?php _e('Class:', 'school-result-plugin'); ?></strong> <?php echo esc_html($student->student_class); ?> 
                <?php if (!empty($student->student_section)): ?>
                    - <?php echo esc_html($student->student_section); ?>
                <?php endif; ?>
                </p>
            </div>
        </div>
        <div class="srp-term-info">
            <h3><?php echo esc_html($term->term_name); ?> - <?php echo esc_html($term->term_year); ?></h3>
        </div>
    </div>
    
    <?php if (!empty($results)): ?>
    <div class="srp-results-table">
        <table>
            <thead>
                <tr>
                    <th><?php _e('Subject', 'school-result-plugin'); ?></th>
                    <th><?php _e('Full Marks', 'school-result-plugin'); ?></th>
                    <th><?php _e('Marks Obtained', 'school-result-plugin'); ?></th>
                    <th><?php _e('Grade', 'school-result-plugin'); ?></th>
                    <th><?php _e('GPA', 'school-result-plugin'); ?></th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($results as $result): ?>
                <tr>
                    <td><?php echo esc_html($result->subject_name); ?></td>
                    <td><?php echo esc_html($result->full_marks); ?></td>
                    <td><?php echo esc_html(number_format($result->marks_obtained, 2)); ?></td>
                    <td><span class="srp-grade-badge srp-grade-<?php echo esc_attr(strtolower(str_replace('+', 'plus', $result->grade))); ?>"><?php echo esc_html($result->grade); ?></span></td>
                    <td><?php echo esc_html(number_format($result->gpa, 2)); ?></td>
                </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>
    <?php endif; ?>
    
    <?php if ($summary): ?>
    <div class="srp-result-summary">
        <div class="srp-summary-item">
            <span class="srp-summary-label"><?php _e('Total Marks:', 'school-result-plugin'); ?></span>
            <span class="srp-summary-value"><?php echo esc_html(number_format($summary->total_marks, 2)); ?></span>
        </div>
        <div class="srp-summary-item">
            <span class="srp-summary-label"><?php _e('Average GPA:', 'school-result-plugin'); ?></span>
            <span class="srp-summary-value"><strong><?php echo esc_html(number_format($summary->average_gpa, 2)); ?></strong></span>
        </div>
        <div class="srp-summary-item">
            <span class="srp-summary-label"><?php _e('Grade:', 'school-result-plugin'); ?></span>
            <span class="srp-summary-value"><strong><?php echo esc_html($summary->grade); ?></strong></span>
        </div>
        <?php if ($summary->position > 0): ?>
        <div class="srp-summary-item">
            <span class="srp-summary-label"><?php _e('Position:', 'school-result-plugin'); ?></span>
            <span class="srp-summary-value"><strong><?php echo esc_html($summary->position); ?></strong></span>
        </div>
        <?php endif; ?>
    </div>
    <?php else: ?>
    <p><?php _e('No results available.', 'school-result-plugin'); ?></p>
    <?php endif; ?>
</div>
