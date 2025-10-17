<?php
/**
 * Admin interface class
 */

// Exit if accessed directly
if (!defined('ABSPATH')) {
    exit;
}

class SRP_Admin {
    
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
        add_action('admin_menu', array($this, 'add_admin_menu'));
        add_action('admin_init', array($this, 'register_settings'));
        add_action('admin_post_srp_add_student', array($this, 'handle_add_student'));
        add_action('admin_post_srp_add_term', array($this, 'handle_add_term'));
        add_action('admin_post_srp_add_subject', array($this, 'handle_add_subject'));
        add_action('admin_post_srp_add_result', array($this, 'handle_add_result'));
        add_action('admin_post_srp_calculate_positions', array($this, 'handle_calculate_positions'));
    }
    
    /**
     * Add admin menu
     */
    public function add_admin_menu() {
        add_menu_page(
            __('School Result', 'school-result-plugin'),
            __('School Result', 'school-result-plugin'),
            'manage_options',
            'school-result',
            array($this, 'dashboard_page'),
            'dashicons-welcome-learn-more',
            30
        );
        
        add_submenu_page(
            'school-result',
            __('Dashboard', 'school-result-plugin'),
            __('Dashboard', 'school-result-plugin'),
            'manage_options',
            'school-result',
            array($this, 'dashboard_page')
        );
        
        add_submenu_page(
            'school-result',
            __('Students', 'school-result-plugin'),
            __('Students', 'school-result-plugin'),
            'manage_options',
            'school-result-students',
            array($this, 'students_page')
        );
        
        add_submenu_page(
            'school-result',
            __('Terms', 'school-result-plugin'),
            __('Terms', 'school-result-plugin'),
            'manage_options',
            'school-result-terms',
            array($this, 'terms_page')
        );
        
        add_submenu_page(
            'school-result',
            __('Subjects', 'school-result-plugin'),
            __('Subjects', 'school-result-plugin'),
            'manage_options',
            'school-result-subjects',
            array($this, 'subjects_page')
        );
        
        add_submenu_page(
            'school-result',
            __('Results', 'school-result-plugin'),
            __('Results', 'school-result-plugin'),
            'manage_options',
            'school-result-results',
            array($this, 'results_page')
        );
        
        add_submenu_page(
            'school-result',
            __('Settings', 'school-result-plugin'),
            __('Settings', 'school-result-plugin'),
            'manage_options',
            'school-result-settings',
            array($this, 'settings_page')
        );
    }
    
    /**
     * Register plugin settings
     */
    public function register_settings() {
        register_setting('srp_settings', 'srp_gpa_scale');
        register_setting('srp_settings', 'srp_passing_marks');
    }
    
    /**
     * Dashboard page
     */
    public function dashboard_page() {
        $student_count = SRP_Student::get_count();
        $terms = SRP_Term::get_all(array('status' => 'active'));
        
        include SRP_PLUGIN_DIR . 'templates/admin/dashboard.php';
    }
    
    /**
     * Students page
     */
    public function students_page() {
        $action = isset($_GET['action']) ? sanitize_text_field($_GET['action']) : 'list';
        $student_id = isset($_GET['student_id']) ? intval($_GET['student_id']) : 0;
        
        if ($action === 'edit' && $student_id) {
            $student = SRP_Student::get($student_id);
            include SRP_PLUGIN_DIR . 'templates/admin/student-form.php';
        } elseif ($action === 'add') {
            $student = null;
            include SRP_PLUGIN_DIR . 'templates/admin/student-form.php';
        } else {
            $students = SRP_Student::get_all();
            include SRP_PLUGIN_DIR . 'templates/admin/students-list.php';
        }
    }
    
    /**
     * Terms page
     */
    public function terms_page() {
        $action = isset($_GET['action']) ? sanitize_text_field($_GET['action']) : 'list';
        $term_id = isset($_GET['term_id']) ? intval($_GET['term_id']) : 0;
        
        if ($action === 'edit' && $term_id) {
            $term = SRP_Term::get($term_id);
            include SRP_PLUGIN_DIR . 'templates/admin/term-form.php';
        } elseif ($action === 'add') {
            $term = null;
            include SRP_PLUGIN_DIR . 'templates/admin/term-form.php';
        } else {
            $terms = SRP_Term::get_all();
            include SRP_PLUGIN_DIR . 'templates/admin/terms-list.php';
        }
    }
    
    /**
     * Subjects page
     */
    public function subjects_page() {
        $action = isset($_GET['action']) ? sanitize_text_field($_GET['action']) : 'list';
        
        if ($action === 'add') {
            include SRP_PLUGIN_DIR . 'templates/admin/subject-form.php';
        } else {
            $subjects = SRP_Result::get_subjects();
            include SRP_PLUGIN_DIR . 'templates/admin/subjects-list.php';
        }
    }
    
    /**
     * Results page
     */
    public function results_page() {
        $action = isset($_GET['action']) ? sanitize_text_field($_GET['action']) : 'list';
        $term_id = isset($_GET['term_id']) ? intval($_GET['term_id']) : 0;
        
        if ($action === 'add') {
            $students = SRP_Student::get_all();
            $subjects = SRP_Result::get_subjects();
            $terms = SRP_Term::get_all(array('status' => 'active'));
            include SRP_PLUGIN_DIR . 'templates/admin/result-form.php';
        } elseif ($action === 'view' && $term_id) {
            $term = SRP_Term::get($term_id);
            $rankings = SRP_Result::get_term_rankings($term_id);
            include SRP_PLUGIN_DIR . 'templates/admin/results-view.php';
        } else {
            $terms = SRP_Term::get_all();
            include SRP_PLUGIN_DIR . 'templates/admin/results-list.php';
        }
    }
    
    /**
     * Settings page
     */
    public function settings_page() {
        include SRP_PLUGIN_DIR . 'templates/admin/settings.php';
    }
    
    /**
     * Handle add student form submission
     */
    public function handle_add_student() {
        if (!current_user_can('manage_options')) {
            wp_die(__('Unauthorized access', 'school-result-plugin'));
        }
        
        check_admin_referer('srp_add_student', 'srp_nonce');
        
        $data = array(
            'student_name' => sanitize_text_field($_POST['student_name']),
            'student_roll' => sanitize_text_field($_POST['student_roll']),
            'student_class' => sanitize_text_field($_POST['student_class']),
            'student_section' => sanitize_text_field($_POST['student_section']),
            'student_email' => sanitize_email($_POST['student_email']),
            'student_phone' => sanitize_text_field($_POST['student_phone']),
            'date_of_birth' => sanitize_text_field($_POST['date_of_birth'])
        );
        
        // Handle photo upload
        if (!empty($_FILES['student_photo']['name'])) {
            $photo_url = SRP_Student::upload_photo($_FILES['student_photo']);
            if (!is_wp_error($photo_url)) {
                $data['student_photo'] = $photo_url;
            }
        }
        
        $student_id = isset($_POST['student_id']) ? intval($_POST['student_id']) : 0;
        
        if ($student_id) {
            SRP_Student::update($student_id, $data);
            $message = 'updated';
        } else {
            SRP_Student::add($data);
            $message = 'added';
        }
        
        wp_redirect(add_query_arg(array('page' => 'school-result-students', 'message' => $message), admin_url('admin.php')));
        exit;
    }
    
    /**
     * Handle add term form submission
     */
    public function handle_add_term() {
        if (!current_user_can('manage_options')) {
            wp_die(__('Unauthorized access', 'school-result-plugin'));
        }
        
        check_admin_referer('srp_add_term', 'srp_nonce');
        
        $data = array(
            'term_name' => sanitize_text_field($_POST['term_name']),
            'term_year' => sanitize_text_field($_POST['term_year']),
            'term_start_date' => sanitize_text_field($_POST['term_start_date']),
            'term_end_date' => sanitize_text_field($_POST['term_end_date']),
            'term_status' => sanitize_text_field($_POST['term_status'])
        );
        
        $term_id = isset($_POST['term_id']) ? intval($_POST['term_id']) : 0;
        
        if ($term_id) {
            SRP_Term::update($term_id, $data);
            $message = 'updated';
        } else {
            SRP_Term::add($data);
            $message = 'added';
        }
        
        wp_redirect(add_query_arg(array('page' => 'school-result-terms', 'message' => $message), admin_url('admin.php')));
        exit;
    }
    
    /**
     * Handle add subject form submission
     */
    public function handle_add_subject() {
        if (!current_user_can('manage_options')) {
            wp_die(__('Unauthorized access', 'school-result-plugin'));
        }
        
        check_admin_referer('srp_add_subject', 'srp_nonce');
        
        $data = array(
            'subject_name' => sanitize_text_field($_POST['subject_name']),
            'subject_code' => sanitize_text_field($_POST['subject_code']),
            'subject_class' => sanitize_text_field($_POST['subject_class']),
            'full_marks' => intval($_POST['full_marks']),
            'pass_marks' => intval($_POST['pass_marks']),
            'is_optional' => isset($_POST['is_optional']) ? 1 : 0
        );
        
        SRP_Result::add_subject($data);
        
        wp_redirect(add_query_arg(array('page' => 'school-result-subjects', 'message' => 'added'), admin_url('admin.php')));
        exit;
    }
    
    /**
     * Handle add result form submission
     */
    public function handle_add_result() {
        if (!current_user_can('manage_options')) {
            wp_die(__('Unauthorized access', 'school-result-plugin'));
        }
        
        check_admin_referer('srp_add_result', 'srp_nonce');
        
        $data = array(
            'student_id' => intval($_POST['student_id']),
            'subject_id' => intval($_POST['subject_id']),
            'term_id' => intval($_POST['term_id']),
            'marks_obtained' => floatval($_POST['marks_obtained'])
        );
        
        SRP_Result::add_or_update($data);
        
        wp_redirect(add_query_arg(array('page' => 'school-result-results', 'message' => 'added'), admin_url('admin.php')));
        exit;
    }
    
    /**
     * Handle calculate positions action
     */
    public function handle_calculate_positions() {
        if (!current_user_can('manage_options')) {
            wp_die(__('Unauthorized access', 'school-result-plugin'));
        }
        
        check_admin_referer('srp_calculate_positions', 'srp_nonce');
        
        $term_id = intval($_POST['term_id']);
        $class = isset($_POST['class']) ? sanitize_text_field($_POST['class']) : '';
        
        SRP_Calculator::calculate_positions($term_id, $class);
        
        wp_redirect(add_query_arg(array('page' => 'school-result-results', 'action' => 'view', 'term_id' => $term_id, 'message' => 'calculated'), admin_url('admin.php')));
        exit;
    }
}
