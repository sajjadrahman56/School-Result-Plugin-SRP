<?php
/**
 * Term management class
 */

// Exit if accessed directly
if (!defined('ABSPATH')) {
    exit;
}

class SRP_Term {
    
    /**
     * Add a new term
     *
     * @param array $data Term data
     * @return int|false Term ID or false on failure
     */
    public static function add($data) {
        global $wpdb;
        
        $table = $wpdb->prefix . 'srp_terms';
        
        $insert_data = array(
            'term_name' => sanitize_text_field($data['term_name']),
            'term_year' => sanitize_text_field($data['term_year']),
            'term_status' => isset($data['term_status']) ? sanitize_text_field($data['term_status']) : 'active'
        );
        
        if (isset($data['term_start_date'])) {
            $insert_data['term_start_date'] = sanitize_text_field($data['term_start_date']);
        }
        
        if (isset($data['term_end_date'])) {
            $insert_data['term_end_date'] = sanitize_text_field($data['term_end_date']);
        }
        
        $result = $wpdb->insert($table, $insert_data);
        
        if ($result) {
            return $wpdb->insert_id;
        }
        
        return false;
    }
    
    /**
     * Update an existing term
     *
     * @param int $term_id Term ID
     * @param array $data Term data
     * @return bool Success status
     */
    public static function update($term_id, $data) {
        global $wpdb;
        
        $table = $wpdb->prefix . 'srp_terms';
        
        $update_data = array();
        
        if (isset($data['term_name'])) {
            $update_data['term_name'] = sanitize_text_field($data['term_name']);
        }
        if (isset($data['term_year'])) {
            $update_data['term_year'] = sanitize_text_field($data['term_year']);
        }
        if (isset($data['term_status'])) {
            $update_data['term_status'] = sanitize_text_field($data['term_status']);
        }
        if (isset($data['term_start_date'])) {
            $update_data['term_start_date'] = sanitize_text_field($data['term_start_date']);
        }
        if (isset($data['term_end_date'])) {
            $update_data['term_end_date'] = sanitize_text_field($data['term_end_date']);
        }
        
        $result = $wpdb->update(
            $table,
            $update_data,
            array('id' => $term_id),
            null,
            array('%d')
        );
        
        return $result !== false;
    }
    
    /**
     * Delete a term
     *
     * @param int $term_id Term ID
     * @return bool Success status
     */
    public static function delete($term_id) {
        global $wpdb;
        
        $table = $wpdb->prefix . 'srp_terms';
        
        // Also delete associated results
        $results_table = $wpdb->prefix . 'srp_results';
        $summary_table = $wpdb->prefix . 'srp_term_summary';
        
        $wpdb->delete($results_table, array('term_id' => $term_id), array('%d'));
        $wpdb->delete($summary_table, array('term_id' => $term_id), array('%d'));
        
        $result = $wpdb->delete($table, array('id' => $term_id), array('%d'));
        
        return $result !== false;
    }
    
    /**
     * Get term by ID
     *
     * @param int $term_id Term ID
     * @return object|null Term object or null
     */
    public static function get($term_id) {
        global $wpdb;
        
        $table = $wpdb->prefix . 'srp_terms';
        
        return $wpdb->get_row($wpdb->prepare(
            "SELECT * FROM $table WHERE id = %d",
            $term_id
        ));
    }
    
    /**
     * Get all terms with optional filters
     *
     * @param array $args Query arguments
     * @return array Terms array
     */
    public static function get_all($args = array()) {
        global $wpdb;
        
        $table = $wpdb->prefix . 'srp_terms';
        
        $defaults = array(
            'year' => '',
            'status' => '',
            'orderby' => 'term_start_date',
            'order' => 'DESC'
        );
        
        $args = wp_parse_args($args, $defaults);
        
        $query = "SELECT * FROM $table WHERE 1=1";
        $params = array();
        
        if (!empty($args['year'])) {
            $query .= " AND term_year = %s";
            $params[] = $args['year'];
        }
        
        if (!empty($args['status'])) {
            $query .= " AND term_status = %s";
            $params[] = $args['status'];
        }
        
        $query .= " ORDER BY {$args['orderby']} {$args['order']}";
        
        if (!empty($params)) {
            $query = $wpdb->prepare($query, $params);
        }
        
        return $wpdb->get_results($query);
    }
}
