<?php
/**
 * Student management class
 */

// Exit if accessed directly
if (!defined('ABSPATH')) {
    exit;
}

class SRP_Student {
    
    /**
     * Add a new student
     *
     * @param array $data Student data
     * @return int|false Student ID or false on failure
     */
    public static function add($data) {
        global $wpdb;
        
        $table = $wpdb->prefix . 'srp_students';
        
        $insert_data = array(
            'student_name' => sanitize_text_field($data['student_name']),
            'student_roll' => sanitize_text_field($data['student_roll']),
            'student_class' => sanitize_text_field($data['student_class']),
            'student_section' => isset($data['student_section']) ? sanitize_text_field($data['student_section']) : '',
            'student_email' => isset($data['student_email']) ? sanitize_email($data['student_email']) : '',
            'student_phone' => isset($data['student_phone']) ? sanitize_text_field($data['student_phone']) : '',
            'date_of_birth' => isset($data['date_of_birth']) ? sanitize_text_field($data['date_of_birth']) : null
        );
        
        // Handle photo upload
        if (isset($data['student_photo']) && !empty($data['student_photo'])) {
            $insert_data['student_photo'] = esc_url_raw($data['student_photo']);
        }
        
        $result = $wpdb->insert($table, $insert_data);
        
        if ($result) {
            return $wpdb->insert_id;
        }
        
        return false;
    }
    
    /**
     * Update an existing student
     *
     * @param int $student_id Student ID
     * @param array $data Student data
     * @return bool Success status
     */
    public static function update($student_id, $data) {
        global $wpdb;
        
        $table = $wpdb->prefix . 'srp_students';
        
        $update_data = array();
        
        if (isset($data['student_name'])) {
            $update_data['student_name'] = sanitize_text_field($data['student_name']);
        }
        if (isset($data['student_roll'])) {
            $update_data['student_roll'] = sanitize_text_field($data['student_roll']);
        }
        if (isset($data['student_class'])) {
            $update_data['student_class'] = sanitize_text_field($data['student_class']);
        }
        if (isset($data['student_section'])) {
            $update_data['student_section'] = sanitize_text_field($data['student_section']);
        }
        if (isset($data['student_email'])) {
            $update_data['student_email'] = sanitize_email($data['student_email']);
        }
        if (isset($data['student_phone'])) {
            $update_data['student_phone'] = sanitize_text_field($data['student_phone']);
        }
        if (isset($data['date_of_birth'])) {
            $update_data['date_of_birth'] = sanitize_text_field($data['date_of_birth']);
        }
        if (isset($data['student_photo'])) {
            $update_data['student_photo'] = esc_url_raw($data['student_photo']);
        }
        
        $result = $wpdb->update(
            $table,
            $update_data,
            array('id' => $student_id),
            null,
            array('%d')
        );
        
        return $result !== false;
    }
    
    /**
     * Delete a student
     *
     * @param int $student_id Student ID
     * @return bool Success status
     */
    public static function delete($student_id) {
        global $wpdb;
        
        $table = $wpdb->prefix . 'srp_students';
        
        // Also delete associated results
        $results_table = $wpdb->prefix . 'srp_results';
        $summary_table = $wpdb->prefix . 'srp_term_summary';
        
        $wpdb->delete($results_table, array('student_id' => $student_id), array('%d'));
        $wpdb->delete($summary_table, array('student_id' => $student_id), array('%d'));
        
        $result = $wpdb->delete($table, array('id' => $student_id), array('%d'));
        
        return $result !== false;
    }
    
    /**
     * Get student by ID
     *
     * @param int $student_id Student ID
     * @return object|null Student object or null
     */
    public static function get($student_id) {
        global $wpdb;
        
        $table = $wpdb->prefix . 'srp_students';
        
        return $wpdb->get_row($wpdb->prepare(
            "SELECT * FROM $table WHERE id = %d",
            $student_id
        ));
    }
    
    /**
     * Get all students with optional filters
     *
     * @param array $args Query arguments
     * @return array Students array
     */
    public static function get_all($args = array()) {
        global $wpdb;
        
        $table = $wpdb->prefix . 'srp_students';
        
        $defaults = array(
            'class' => '',
            'section' => '',
            'orderby' => 'student_roll',
            'order' => 'ASC',
            'limit' => -1,
            'offset' => 0
        );
        
        $args = wp_parse_args($args, $defaults);
        
        $query = "SELECT * FROM $table WHERE 1=1";
        $params = array();
        
        if (!empty($args['class'])) {
            $query .= " AND student_class = %s";
            $params[] = $args['class'];
        }
        
        if (!empty($args['section'])) {
            $query .= " AND student_section = %s";
            $params[] = $args['section'];
        }
        
        $query .= " ORDER BY {$args['orderby']} {$args['order']}";
        
        if ($args['limit'] > 0) {
            $query .= " LIMIT %d OFFSET %d";
            $params[] = $args['limit'];
            $params[] = $args['offset'];
        }
        
        if (!empty($params)) {
            $query = $wpdb->prepare($query, $params);
        }
        
        return $wpdb->get_results($query);
    }
    
    /**
     * Get student count
     *
     * @param array $args Query arguments
     * @return int Student count
     */
    public static function get_count($args = array()) {
        global $wpdb;
        
        $table = $wpdb->prefix . 'srp_students';
        
        $query = "SELECT COUNT(*) FROM $table WHERE 1=1";
        $params = array();
        
        if (!empty($args['class'])) {
            $query .= " AND student_class = %s";
            $params[] = $args['class'];
        }
        
        if (!empty($args['section'])) {
            $query .= " AND student_section = %s";
            $params[] = $args['section'];
        }
        
        if (!empty($params)) {
            $query = $wpdb->prepare($query, $params);
        }
        
        return (int) $wpdb->get_var($query);
    }
    
    /**
     * Upload student photo
     *
     * @param array $file File data from $_FILES
     * @return string|WP_Error Photo URL or error
     */
    public static function upload_photo($file) {
        if (!function_exists('wp_handle_upload')) {
            require_once(ABSPATH . 'wp-admin/includes/file.php');
        }
        
        $uploadedfile = $file;
        
        $upload_overrides = array(
            'test_form' => false,
            'mimes' => array(
                'jpg|jpeg|jpe' => 'image/jpeg',
                'png' => 'image/png',
                'gif' => 'image/gif'
            )
        );
        
        $movefile = wp_handle_upload($uploadedfile, $upload_overrides);
        
        if ($movefile && !isset($movefile['error'])) {
            return $movefile['url'];
        } else {
            return new WP_Error('upload_error', $movefile['error']);
        }
    }
}
