<?php
/**
 * Plugin Name: School Result Plugin (SRP)
 * Plugin URI: https://github.com/sajjadrahman56/School-Result-Plugin-SRP-
 * Description: A free and open-source WordPress plugin that manages school exam results — built for Bangladeshi grading standards (GPA 5.0 scale). Supports multiple terms, class-wise student photos, and automatically computes final positions.
 * Version: 1.0.0
 * Author: Sajjad Rahman
 * Author URI: https://github.com/sajjadrahman56
 * License: GPL v2 or later
 * License URI: https://www.gnu.org/licenses/gpl-2.0.html
 * Text Domain: school-result-plugin
 * Domain Path: /languages
 */

// Exit if accessed directly
if (!defined('ABSPATH')) {
    exit;
}

// Define plugin constants
define('SRP_VERSION', '1.0.0');
define('SRP_PLUGIN_DIR', plugin_dir_path(__FILE__));
define('SRP_PLUGIN_URL', plugin_dir_url(__FILE__));
define('SRP_PLUGIN_BASENAME', plugin_basename(__FILE__));

/**
 * Main plugin class
 */
class School_Result_Plugin {
    
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
        $this->includes();
        $this->init_hooks();
    }
    
    /**
     * Include required files
     */
    private function includes() {
        require_once SRP_PLUGIN_DIR . 'includes/class-srp-database.php';
        require_once SRP_PLUGIN_DIR . 'includes/class-srp-admin.php';
        require_once SRP_PLUGIN_DIR . 'includes/class-srp-student.php';
        require_once SRP_PLUGIN_DIR . 'includes/class-srp-result.php';
        require_once SRP_PLUGIN_DIR . 'includes/class-srp-term.php';
        require_once SRP_PLUGIN_DIR . 'includes/class-srp-shortcodes.php';
        require_once SRP_PLUGIN_DIR . 'includes/class-srp-calculator.php';
    }
    
    /**
     * Initialize hooks
     */
    private function init_hooks() {
        register_activation_hook(__FILE__, array($this, 'activate'));
        register_deactivation_hook(__FILE__, array($this, 'deactivate'));
        
        add_action('plugins_loaded', array($this, 'init'));
        add_action('admin_enqueue_scripts', array($this, 'admin_scripts'));
        add_action('wp_enqueue_scripts', array($this, 'frontend_scripts'));
    }
    
    /**
     * Initialize plugin
     */
    public function init() {
        // Initialize components
        SRP_Admin::get_instance();
        SRP_Shortcodes::get_instance();
        
        // Load text domain
        load_plugin_textdomain('school-result-plugin', false, dirname(SRP_PLUGIN_BASENAME) . '/languages');
    }
    
    /**
     * Plugin activation
     */
    public function activate() {
        SRP_Database::create_tables();
        
        // Set default options
        if (!get_option('srp_version')) {
            add_option('srp_version', SRP_VERSION);
            add_option('srp_gpa_scale', '5.0');
            add_option('srp_passing_marks', '33');
        }
        
        flush_rewrite_rules();
    }
    
    /**
     * Plugin deactivation
     */
    public function deactivate() {
        flush_rewrite_rules();
    }
    
    /**
     * Enqueue admin scripts and styles
     */
    public function admin_scripts($hook) {
        // Only load on plugin pages
        if (strpos($hook, 'school-result') === false) {
            return;
        }
        
        wp_enqueue_style('srp-admin-style', SRP_PLUGIN_URL . 'assets/css/admin-style.css', array(), SRP_VERSION);
        wp_enqueue_script('srp-admin-script', SRP_PLUGIN_URL . 'assets/js/admin-script.js', array('jquery'), SRP_VERSION, true);
        
        wp_localize_script('srp-admin-script', 'srpAdmin', array(
            'ajaxurl' => admin_url('admin-ajax.php'),
            'nonce' => wp_create_nonce('srp-admin-nonce')
        ));
    }
    
    /**
     * Enqueue frontend scripts and styles
     */
    public function frontend_scripts() {
        wp_enqueue_style('srp-style', SRP_PLUGIN_URL . 'assets/css/style.css', array(), SRP_VERSION);
        wp_enqueue_script('srp-script', SRP_PLUGIN_URL . 'assets/js/script.js', array('jquery'), SRP_VERSION, true);
    }
}

/**
 * Initialize the plugin
 */
function srp_init() {
    return School_Result_Plugin::get_instance();
}

// Start the plugin
srp_init();
