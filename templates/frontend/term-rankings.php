<?php
/**
 * Frontend term rankings template
 */

// Exit if accessed directly
if (!defined('ABSPATH')) {
    exit;
}
?>

<div class="srp-term-rankings">
    <div class="srp-rankings-header">
        <h2><?php echo esc_html($term->term_name); ?> - <?php echo esc_html($term->term_year); ?></h2>
        <?php if (!empty($class)): ?>
            <p class="srp-class-filter"><?php printf(__('Class: %s', 'school-result-plugin'), esc_html($class)); ?></p>
        <?php endif; ?>
    </div>
    
    <?php if (!empty($rankings)): ?>
    <div class="srp-rankings-table">
        <table>
            <thead>
                <tr>
                    <th class="srp-rank"><?php _e('Rank', 'school-result-plugin'); ?></th>
                    <th><?php _e('Student Name', 'school-result-plugin'); ?></th>
                    <th><?php _e('Roll', 'school-result-plugin'); ?></th>
                    <th><?php _e('Class', 'school-result-plugin'); ?></th>
                    <th><?php _e('Average GPA', 'school-result-plugin'); ?></th>
                    <th><?php _e('Grade', 'school-result-plugin'); ?></th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($rankings as $index => $ranking): ?>
                <tr class="srp-rank-<?php echo ($index + 1); ?>">
                    <td class="srp-rank">
                        <?php if ($ranking->position > 0): ?>
                            <span class="srp-position srp-position-<?php echo esc_attr($ranking->position); ?>">
                                <?php echo esc_html($ranking->position); ?>
                            </span>
                        <?php else: ?>
                            -
                        <?php endif; ?>
                    </td>
                    <td><strong><?php echo esc_html($ranking->student_name); ?></strong></td>
                    <td><?php echo esc_html($ranking->student_roll); ?></td>
                    <td><?php echo esc_html($ranking->student_class . (!empty($ranking->student_section) ? ' - ' . $ranking->student_section : '')); ?></td>
                    <td><strong><?php echo esc_html(number_format($ranking->average_gpa, 2)); ?></strong></td>
                    <td>
                        <span class="srp-grade-badge srp-grade-<?php echo esc_attr(strtolower(str_replace('+', 'plus', $ranking->grade))); ?>">
                            <?php echo esc_html($ranking->grade); ?>
                        </span>
                    </td>
                </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>
    <?php else: ?>
    <p><?php _e('No rankings available for this term.', 'school-result-plugin'); ?></p>
    <?php endif; ?>
</div>
