<?php
/**
 * Settings template
 */

// Exit if accessed directly
if (!defined('ABSPATH')) {
    exit;
}
?>

<div class="wrap">
    <h1><?php _e('School Result Plugin Settings', 'school-result-plugin'); ?></h1>
    
    <?php settings_errors(); ?>
    
    <form method="post" action="options.php">
        <?php
        settings_fields('srp_settings');
        do_settings_sections('srp_settings');
        ?>
        
        <table class="form-table">
            <tr>
                <th scope="row"><label for="srp_gpa_scale"><?php _e('GPA Scale', 'school-result-plugin'); ?></label></th>
                <td>
                    <input type="text" name="srp_gpa_scale" id="srp_gpa_scale" value="<?php echo esc_attr(get_option('srp_gpa_scale', '5.0')); ?>" class="regular-text" readonly>
                    <p class="description"><?php _e('Bangladeshi grading standard uses GPA 5.0 scale', 'school-result-plugin'); ?></p>
                </td>
            </tr>
            <tr>
                <th scope="row"><label for="srp_passing_marks"><?php _e('Passing Marks (%)', 'school-result-plugin'); ?></label></th>
                <td>
                    <input type="number" name="srp_passing_marks" id="srp_passing_marks" value="<?php echo esc_attr(get_option('srp_passing_marks', '33')); ?>" min="0" max="100">
                    <p class="description"><?php _e('Default passing marks percentage (usually 33%)', 'school-result-plugin'); ?></p>
                </td>
            </tr>
        </table>
        
        <h2><?php _e('Grading Scale (Bangladeshi Standard)', 'school-result-plugin'); ?></h2>
        <table class="wp-list-table widefat fixed striped">
            <thead>
                <tr>
                    <th><?php _e('Marks Range', 'school-result-plugin'); ?></th>
                    <th><?php _e('Grade', 'school-result-plugin'); ?></th>
                    <th><?php _e('GPA', 'school-result-plugin'); ?></th>
                </tr>
            </thead>
            <tbody>
                <tr>
                    <td>80-100</td>
                    <td>A+</td>
                    <td>5.0</td>
                </tr>
                <tr>
                    <td>70-79</td>
                    <td>A</td>
                    <td>4.0</td>
                </tr>
                <tr>
                    <td>60-69</td>
                    <td>A-</td>
                    <td>3.5</td>
                </tr>
                <tr>
                    <td>50-59</td>
                    <td>B</td>
                    <td>3.0</td>
                </tr>
                <tr>
                    <td>40-49</td>
                    <td>C</td>
                    <td>2.0</td>
                </tr>
                <tr>
                    <td>33-39</td>
                    <td>D</td>
                    <td>1.0</td>
                </tr>
                <tr>
                    <td>0-32</td>
                    <td>F</td>
                    <td>0.0</td>
                </tr>
            </tbody>
        </table>
        
        <?php submit_button(); ?>
    </form>
</div>
