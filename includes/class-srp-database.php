<?php
/**
 * Database handler class
 */

// Exit if accessed directly
if (!defined('ABSPATH')) {
    exit;
}

class SRP_Database {
    
    /**
     * Create database tables
     */
    public static function create_tables() {
        global $wpdb;
        
        $charset_collate = $wpdb->get_charset_collate();
        
        // Students table
        $students_table = $wpdb->prefix . 'srp_students';
        $students_sql = "CREATE TABLE IF NOT EXISTS $students_table (
            id bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT,
            student_name varchar(255) NOT NULL,
            student_roll varchar(50) NOT NULL,
            student_class varchar(50) NOT NULL,
            student_section varchar(50) DEFAULT '',
            student_photo varchar(500) DEFAULT '',
            student_email varchar(100) DEFAULT '',
            student_phone varchar(20) DEFAULT '',
            date_of_birth date DEFAULT NULL,
            created_at datetime DEFAULT CURRENT_TIMESTAMP,
            updated_at datetime DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
            PRIMARY KEY (id),
            UNIQUE KEY student_roll_class (student_roll, student_class)
        ) $charset_collate;";
        
        // Terms table
        $terms_table = $wpdb->prefix . 'srp_terms';
        $terms_sql = "CREATE TABLE IF NOT EXISTS $terms_table (
            id bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT,
            term_name varchar(255) NOT NULL,
            term_year varchar(10) NOT NULL,
            term_start_date date DEFAULT NULL,
            term_end_date date DEFAULT NULL,
            term_status varchar(20) DEFAULT 'active',
            created_at datetime DEFAULT CURRENT_TIMESTAMP,
            PRIMARY KEY (id),
            UNIQUE KEY term_name_year (term_name, term_year)
        ) $charset_collate;";
        
        // Subjects table
        $subjects_table = $wpdb->prefix . 'srp_subjects';
        $subjects_sql = "CREATE TABLE IF NOT EXISTS $subjects_table (
            id bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT,
            subject_name varchar(255) NOT NULL,
            subject_code varchar(50) NOT NULL,
            subject_class varchar(50) NOT NULL,
            full_marks int(11) DEFAULT 100,
            pass_marks int(11) DEFAULT 33,
            is_optional tinyint(1) DEFAULT 0,
            created_at datetime DEFAULT CURRENT_TIMESTAMP,
            PRIMARY KEY (id),
            UNIQUE KEY subject_code_class (subject_code, subject_class)
        ) $charset_collate;";
        
        // Results table
        $results_table = $wpdb->prefix . 'srp_results';
        $results_sql = "CREATE TABLE IF NOT EXISTS $results_table (
            id bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT,
            student_id bigint(20) UNSIGNED NOT NULL,
            subject_id bigint(20) UNSIGNED NOT NULL,
            term_id bigint(20) UNSIGNED NOT NULL,
            marks_obtained decimal(5,2) DEFAULT 0,
            gpa decimal(3,2) DEFAULT 0,
            grade varchar(10) DEFAULT '',
            created_at datetime DEFAULT CURRENT_TIMESTAMP,
            updated_at datetime DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
            PRIMARY KEY (id),
            UNIQUE KEY student_subject_term (student_id, subject_id, term_id),
            KEY student_id (student_id),
            KEY subject_id (subject_id),
            KEY term_id (term_id)
        ) $charset_collate;";
        
        // Term results summary table
        $term_summary_table = $wpdb->prefix . 'srp_term_summary';
        $term_summary_sql = "CREATE TABLE IF NOT EXISTS $term_summary_table (
            id bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT,
            student_id bigint(20) UNSIGNED NOT NULL,
            term_id bigint(20) UNSIGNED NOT NULL,
            total_marks decimal(8,2) DEFAULT 0,
            total_gpa decimal(4,2) DEFAULT 0,
            average_gpa decimal(3,2) DEFAULT 0,
            position int(11) DEFAULT 0,
            grade varchar(10) DEFAULT '',
            created_at datetime DEFAULT CURRENT_TIMESTAMP,
            updated_at datetime DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
            PRIMARY KEY (id),
            UNIQUE KEY student_term (student_id, term_id),
            KEY student_id (student_id),
            KEY term_id (term_id)
        ) $charset_collate;";
        
        require_once(ABSPATH . 'wp-admin/includes/upgrade.php');
        
        dbDelta($students_sql);
        dbDelta($terms_sql);
        dbDelta($subjects_sql);
        dbDelta($results_sql);
        dbDelta($term_summary_sql);
    }
    
    /**
     * Drop database tables (used during uninstall)
     */
    public static function drop_tables() {
        global $wpdb;
        
        $tables = array(
            $wpdb->prefix . 'srp_results',
            $wpdb->prefix . 'srp_term_summary',
            $wpdb->prefix . 'srp_subjects',
            $wpdb->prefix . 'srp_terms',
            $wpdb->prefix . 'srp_students'
        );
        
        foreach ($tables as $table) {
            $wpdb->query("DROP TABLE IF EXISTS $table");
        }
    }
}
