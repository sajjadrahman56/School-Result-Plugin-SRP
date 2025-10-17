<?php
/**
 * Calculator class for GPA and position calculations
 * Implements Bangladeshi grading standards (GPA 5.0 scale)
 */

// Exit if accessed directly
if (!defined('ABSPATH')) {
    exit;
}

class SRP_Calculator {
    
    /**
     * Grade point scale for Bangladeshi grading system (GPA 5.0)
     */
    private static $grade_scale = array(
        array('min' => 80, 'max' => 100, 'grade' => 'A+', 'gpa' => 5.0),
        array('min' => 70, 'max' => 79, 'grade' => 'A', 'gpa' => 4.0),
        array('min' => 60, 'max' => 69, 'grade' => 'A-', 'gpa' => 3.5),
        array('min' => 50, 'max' => 59, 'grade' => 'B', 'gpa' => 3.0),
        array('min' => 40, 'max' => 49, 'grade' => 'C', 'gpa' => 2.0),
        array('min' => 33, 'max' => 39, 'grade' => 'D', 'gpa' => 1.0),
        array('min' => 0, 'max' => 32, 'grade' => 'F', 'gpa' => 0.0)
    );
    
    /**
     * Calculate GPA for a given marks
     *
     * @param float $marks Marks obtained
     * @param float $full_marks Full marks
     * @return array Grade and GPA
     */
    public static function calculate_gpa($marks, $full_marks = 100) {
        // Convert to percentage if not out of 100
        $percentage = ($marks / $full_marks) * 100;
        
        foreach (self::$grade_scale as $scale) {
            if ($percentage >= $scale['min'] && $percentage <= $scale['max']) {
                return array(
                    'grade' => $scale['grade'],
                    'gpa' => $scale['gpa'],
                    'percentage' => round($percentage, 2)
                );
            }
        }
        
        // Default to F if something goes wrong
        return array(
            'grade' => 'F',
            'gpa' => 0.0,
            'percentage' => round($percentage, 2)
        );
    }
    
    /**
     * Calculate average GPA for multiple subjects
     * In Bangladeshi system, if any subject has F grade, overall GPA is 0
     *
     * @param array $gpas Array of GPA values
     * @return array Average GPA and grade
     */
    public static function calculate_average_gpa($gpas) {
        if (empty($gpas)) {
            return array('average_gpa' => 0.0, 'grade' => 'F');
        }
        
        // Check if any subject has failed (GPA 0)
        if (in_array(0.0, $gpas)) {
            return array('average_gpa' => 0.0, 'grade' => 'F');
        }
        
        $total_gpa = array_sum($gpas);
        $count = count($gpas);
        $average_gpa = $total_gpa / $count;
        
        // Determine grade based on average GPA
        $grade = self::get_grade_from_gpa($average_gpa);
        
        return array(
            'average_gpa' => round($average_gpa, 2),
            'grade' => $grade
        );
    }
    
    /**
     * Get grade letter from GPA value
     *
     * @param float $gpa GPA value
     * @return string Grade letter
     */
    public static function get_grade_from_gpa($gpa) {
        if ($gpa >= 5.0) return 'A+';
        if ($gpa >= 4.0) return 'A';
        if ($gpa >= 3.5) return 'A-';
        if ($gpa >= 3.0) return 'B';
        if ($gpa >= 2.0) return 'C';
        if ($gpa >= 1.0) return 'D';
        return 'F';
    }
    
    /**
     * Calculate positions for students in a term
     *
     * @param int $term_id Term ID
     * @param string $class Class name
     * @return bool Success status
     */
    public static function calculate_positions($term_id, $class = '') {
        global $wpdb;
        
        $summary_table = $wpdb->prefix . 'srp_term_summary';
        $students_table = $wpdb->prefix . 'srp_students';
        
        // Build query
        $query = "SELECT ts.*, s.student_class 
                  FROM $summary_table ts 
                  INNER JOIN $students_table s ON ts.student_id = s.id 
                  WHERE ts.term_id = %d";
        
        $params = array($term_id);
        
        if (!empty($class)) {
            $query .= " AND s.student_class = %s";
            $params[] = $class;
        }
        
        $query .= " ORDER BY ts.average_gpa DESC, ts.total_marks DESC";
        
        $results = $wpdb->get_results($wpdb->prepare($query, $params));
        
        if (empty($results)) {
            return false;
        }
        
        // Assign positions
        $position = 1;
        foreach ($results as $result) {
            $wpdb->update(
                $summary_table,
                array('position' => $position),
                array('id' => $result->id),
                array('%d'),
                array('%d')
            );
            $position++;
        }
        
        return true;
    }
    
    /**
     * Calculate and save term summary for a student
     *
     * @param int $student_id Student ID
     * @param int $term_id Term ID
     * @return array Summary data or false
     */
    public static function calculate_term_summary($student_id, $term_id) {
        global $wpdb;
        
        $results_table = $wpdb->prefix . 'srp_results';
        $summary_table = $wpdb->prefix . 'srp_term_summary';
        
        // Get all results for this student and term
        $results = $wpdb->get_results($wpdb->prepare(
            "SELECT marks_obtained, gpa FROM $results_table 
             WHERE student_id = %d AND term_id = %d",
            $student_id,
            $term_id
        ));
        
        if (empty($results)) {
            return false;
        }
        
        $total_marks = 0;
        $gpas = array();
        
        foreach ($results as $result) {
            $total_marks += $result->marks_obtained;
            $gpas[] = $result->gpa;
        }
        
        // Calculate average GPA
        $gpa_result = self::calculate_average_gpa($gpas);
        
        $summary_data = array(
            'student_id' => $student_id,
            'term_id' => $term_id,
            'total_marks' => $total_marks,
            'total_gpa' => array_sum($gpas),
            'average_gpa' => $gpa_result['average_gpa'],
            'grade' => $gpa_result['grade']
        );
        
        // Check if summary already exists
        $existing = $wpdb->get_var($wpdb->prepare(
            "SELECT id FROM $summary_table WHERE student_id = %d AND term_id = %d",
            $student_id,
            $term_id
        ));
        
        if ($existing) {
            // Update existing
            $wpdb->update(
                $summary_table,
                $summary_data,
                array('id' => $existing),
                array('%d', '%d', '%f', '%f', '%f', '%s'),
                array('%d')
            );
        } else {
            // Insert new
            $wpdb->insert(
                $summary_table,
                $summary_data,
                array('%d', '%d', '%f', '%f', '%f', '%s')
            );
        }
        
        return $summary_data;
    }
}
