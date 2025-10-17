<?php
/**
 * Shortcodes class for frontend display
 */

// Exit if accessed directly
if (!defined('ABSPATH')) {
    exit;
}

class SRP_Shortcodes {
    
    /**
     * Instance of this class
     */
    private static $instance = null;
    
    /**
     * Get the singleton instance
     */
    public static function get_instance() {
        if (null === self::$instance) {
            self::$instance = new self();
        }
        return self::$instance;
    }
    
    /**
     * Constructor
     */
    private function __construct() {
        add_shortcode('srp_student_result', array($this, 'student_result_shortcode'));
        add_shortcode('srp_term_rankings', array($this, 'term_rankings_shortcode'));
        add_shortcode('srp_student_card', array($this, 'student_card_shortcode'));
    }
    
    /**
     * Student result shortcode
     * Usage: [srp_student_result student_id="1" term_id="1"]
     */
    public function student_result_shortcode($atts) {
        $atts = shortcode_atts(array(
            'student_id' => 0,
            'term_id' => 0
        ), $atts);
        
        $student_id = intval($atts['student_id']);
        $term_id = intval($atts['term_id']);
        
        if (!$student_id || !$term_id) {
            return '<p>' . __('Invalid student or term ID.', 'school-result-plugin') . '</p>';
        }
        
        $student = SRP_Student::get($student_id);
        $term = SRP_Term::get($term_id);
        $results = SRP_Result::get_student_term_results($student_id, $term_id);
        $summary = SRP_Result::get_term_summary($student_id, $term_id);
        
        if (!$student || !$term) {
            return '<p>' . __('Student or term not found.', 'school-result-plugin') . '</p>';
        }
        
        ob_start();
        include SRP_PLUGIN_DIR . 'templates/frontend/student-result.php';
        return ob_get_clean();
    }
    
    /**
     * Term rankings shortcode
     * Usage: [srp_term_rankings term_id="1" class="Class 10"]
     */
    public function term_rankings_shortcode($atts) {
        $atts = shortcode_atts(array(
            'term_id' => 0,
            'class' => '',
            'limit' => -1
        ), $atts);
        
        $term_id = intval($atts['term_id']);
        $class = sanitize_text_field($atts['class']);
        $limit = intval($atts['limit']);
        
        if (!$term_id) {
            return '<p>' . __('Invalid term ID.', 'school-result-plugin') . '</p>';
        }
        
        $term = SRP_Term::get($term_id);
        $rankings = SRP_Result::get_term_rankings($term_id, $class);
        
        if (!$term) {
            return '<p>' . __('Term not found.', 'school-result-plugin') . '</p>';
        }
        
        if ($limit > 0) {
            $rankings = array_slice($rankings, 0, $limit);
        }
        
        ob_start();
        include SRP_PLUGIN_DIR . 'templates/frontend/term-rankings.php';
        return ob_get_clean();
    }
    
    /**
     * Student card shortcode
     * Usage: [srp_student_card student_id="1"]
     */
    public function student_card_shortcode($atts) {
        $atts = shortcode_atts(array(
            'student_id' => 0
        ), $atts);
        
        $student_id = intval($atts['student_id']);
        
        if (!$student_id) {
            return '<p>' . __('Invalid student ID.', 'school-result-plugin') . '</p>';
        }
        
        $student = SRP_Student::get($student_id);
        
        if (!$student) {
            return '<p>' . __('Student not found.', 'school-result-plugin') . '</p>';
        }
        
        ob_start();
        include SRP_PLUGIN_DIR . 'templates/frontend/student-card.php';
        return ob_get_clean();
    }
}
