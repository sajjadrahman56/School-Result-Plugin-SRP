<?php
/**
 * Result management class
 */

// Exit if accessed directly
if (!defined('ABSPATH')) {
    exit;
}

class SRP_Result {
    
    /**
     * Add or update a result
     *
     * @param array $data Result data
     * @return int|false Result ID or false on failure
     */
    public static function add_or_update($data) {
        global $wpdb;
        
        $table = $wpdb->prefix . 'srp_results';
        $subjects_table = $wpdb->prefix . 'srp_subjects';
        
        // Get subject details for full marks
        $subject = $wpdb->get_row($wpdb->prepare(
            "SELECT full_marks FROM $subjects_table WHERE id = %d",
            $data['subject_id']
        ));
        
        if (!$subject) {
            return false;
        }
        
        // Calculate GPA and grade
        $gpa_data = SRP_Calculator::calculate_gpa($data['marks_obtained'], $subject->full_marks);
        
        $result_data = array(
            'student_id' => intval($data['student_id']),
            'subject_id' => intval($data['subject_id']),
            'term_id' => intval($data['term_id']),
            'marks_obtained' => floatval($data['marks_obtained']),
            'gpa' => $gpa_data['gpa'],
            'grade' => $gpa_data['grade']
        );
        
        // Check if result already exists
        $existing = $wpdb->get_var($wpdb->prepare(
            "SELECT id FROM $table WHERE student_id = %d AND subject_id = %d AND term_id = %d",
            $result_data['student_id'],
            $result_data['subject_id'],
            $result_data['term_id']
        ));
        
        if ($existing) {
            // Update existing
            $wpdb->update(
                $table,
                $result_data,
                array('id' => $existing),
                array('%d', '%d', '%d', '%f', '%f', '%s'),
                array('%d')
            );
            $result_id = $existing;
        } else {
            // Insert new
            $wpdb->insert(
                $table,
                $result_data,
                array('%d', '%d', '%d', '%f', '%f', '%s')
            );
            $result_id = $wpdb->insert_id;
        }
        
        // Update term summary
        if ($result_id) {
            SRP_Calculator::calculate_term_summary($result_data['student_id'], $result_data['term_id']);
        }
        
        return $result_id;
    }
    
    /**
     * Delete a result
     *
     * @param int $result_id Result ID
     * @return bool Success status
     */
    public static function delete($result_id) {
        global $wpdb;
        
        $table = $wpdb->prefix . 'srp_results';
        
        // Get result details before deleting
        $result = $wpdb->get_row($wpdb->prepare(
            "SELECT student_id, term_id FROM $table WHERE id = %d",
            $result_id
        ));
        
        if (!$result) {
            return false;
        }
        
        $deleted = $wpdb->delete($table, array('id' => $result_id), array('%d'));
        
        // Recalculate term summary
        if ($deleted) {
            SRP_Calculator::calculate_term_summary($result->student_id, $result->term_id);
        }
        
        return $deleted !== false;
    }
    
    /**
     * Get result by ID
     *
     * @param int $result_id Result ID
     * @return object|null Result object or null
     */
    public static function get($result_id) {
        global $wpdb;
        
        $table = $wpdb->prefix . 'srp_results';
        
        return $wpdb->get_row($wpdb->prepare(
            "SELECT * FROM $table WHERE id = %d",
            $result_id
        ));
    }
    
    /**
     * Get student results for a term
     *
     * @param int $student_id Student ID
     * @param int $term_id Term ID
     * @return array Results array
     */
    public static function get_student_term_results($student_id, $term_id) {
        global $wpdb;
        
        $results_table = $wpdb->prefix . 'srp_results';
        $subjects_table = $wpdb->prefix . 'srp_subjects';
        
        return $wpdb->get_results($wpdb->prepare(
            "SELECT r.*, s.subject_name, s.subject_code, s.full_marks 
             FROM $results_table r
             INNER JOIN $subjects_table s ON r.subject_id = s.id
             WHERE r.student_id = %d AND r.term_id = %d
             ORDER BY s.subject_name",
            $student_id,
            $term_id
        ));
    }
    
    /**
     * Get term summary for a student
     *
     * @param int $student_id Student ID
     * @param int $term_id Term ID
     * @return object|null Summary object or null
     */
    public static function get_term_summary($student_id, $term_id) {
        global $wpdb;
        
        $table = $wpdb->prefix . 'srp_term_summary';
        
        return $wpdb->get_row($wpdb->prepare(
            "SELECT * FROM $table WHERE student_id = %d AND term_id = %d",
            $student_id,
            $term_id
        ));
    }
    
    /**
     * Get all results for a term with rankings
     *
     * @param int $term_id Term ID
     * @param string $class Class filter
     * @return array Results array
     */
    public static function get_term_rankings($term_id, $class = '') {
        global $wpdb;
        
        $summary_table = $wpdb->prefix . 'srp_term_summary';
        $students_table = $wpdb->prefix . 'srp_students';
        
        $query = "SELECT ts.*, s.student_name, s.student_roll, s.student_class, s.student_section
                  FROM $summary_table ts
                  INNER JOIN $students_table s ON ts.student_id = s.id
                  WHERE ts.term_id = %d";
        
        $params = array($term_id);
        
        if (!empty($class)) {
            $query .= " AND s.student_class = %s";
            $params[] = $class;
        }
        
        $query .= " ORDER BY ts.position ASC";
        
        return $wpdb->get_results($wpdb->prepare($query, $params));
    }
    
    /**
     * Add a new subject
     *
     * @param array $data Subject data
     * @return int|false Subject ID or false on failure
     */
    public static function add_subject($data) {
        global $wpdb;
        
        $table = $wpdb->prefix . 'srp_subjects';
        
        $insert_data = array(
            'subject_name' => sanitize_text_field($data['subject_name']),
            'subject_code' => sanitize_text_field($data['subject_code']),
            'subject_class' => sanitize_text_field($data['subject_class']),
            'full_marks' => isset($data['full_marks']) ? intval($data['full_marks']) : 100,
            'pass_marks' => isset($data['pass_marks']) ? intval($data['pass_marks']) : 33,
            'is_optional' => isset($data['is_optional']) ? intval($data['is_optional']) : 0
        );
        
        $result = $wpdb->insert($table, $insert_data);
        
        if ($result) {
            return $wpdb->insert_id;
        }
        
        return false;
    }
    
    /**
     * Get all subjects with optional filters
     *
     * @param array $args Query arguments
     * @return array Subjects array
     */
    public static function get_subjects($args = array()) {
        global $wpdb;
        
        $table = $wpdb->prefix . 'srp_subjects';
        
        $defaults = array(
            'class' => '',
            'orderby' => 'subject_name',
            'order' => 'ASC'
        );
        
        $args = wp_parse_args($args, $defaults);
        
        $query = "SELECT * FROM $table WHERE 1=1";
        $params = array();
        
        if (!empty($args['class'])) {
            $query .= " AND subject_class = %s";
            $params[] = $args['class'];
        }
        
        $query .= " ORDER BY {$args['orderby']} {$args['order']}";
        
        if (!empty($params)) {
            $query = $wpdb->prepare($query, $params);
        }
        
        return $wpdb->get_results($query);
    }
}
