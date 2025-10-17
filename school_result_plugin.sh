#!/usr/bin/env bash
# create_school_result_plugin.sh
# Usage: ./create_school_result_plugin.sh [destination_wp_plugins_dir]
# Default destination: ./wp-content/plugins
DEST=${1:-./wp-content/plugins}
PLUGIN_SLUG=school-result-plugin
PLUGIN_DIR="$DEST/$PLUGIN_SLUG"

set -e

echo "Creating plugin at: $PLUGIN_DIR"

mkdir -p "$PLUGIN_DIR/includes"
mkdir -p "$PLUGIN_DIR/assets/css"

write_file() {
  local path="$1"
  shift
  printf "%s" "$*" > "$path"
  echo "Wrote $path"
}

# main plugin file
write_file "$PLUGIN_DIR/$PLUGIN_SLUG.php" "<?php
/*
Plugin Name: School Result Plugin
Plugin URI:  https://example.com/school-result-plugin
Description: Manage students, subjects, terms and results with GPA 5.0 scale and frontend shortcode.
Version: 0.1.0
Author: Copilot (generated)
License: GPLv2 or later
Text Domain: school-result-plugin
*/

if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

define( 'SRP_VERSION', '0.1.0' );
define( 'SRP_PLUGIN_DIR', plugin_dir_path( __FILE__ ) );
define( 'SRP_PLUGIN_URL', plugin_dir_url( __FILE__ ) );

require_once SRP_PLUGIN_DIR . 'includes/class-sr-db.php';
require_once SRP_PLUGIN_DIR . 'includes/admin.php';
require_once SRP_PLUGIN_DIR . 'includes/public.php';

// Activation hook
register_activation_hook( __FILE__, array( 'SRP_DB', 'activate' ) );

// Load admin only if in admin
if ( is_admin() ) {
    SRP_Admin::init();
}

// Load public hooks
SRP_Public::init();

// Enqueue styles
add_action( 'wp_enqueue_scripts', function() {
    wp_enqueue_style( 'srp-style', SRP_PLUGIN_URL . 'assets/css/style.css', array(), SRP_VERSION );
} );"

# class-sr-db.php
write_file "$PLUGIN_DIR/includes/class-sr-db.php" "<?php
if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

class SRP_DB {

    public static function activate() {
        global \$wpdb;
        require_once ABSPATH . 'wp-admin/includes/upgrade.php';

        \$charset_collate = \$wpdb->get_charset_collate();

        \$students_table = \$wpdb->prefix . 'srp_students';
        \$subjects_table = \$wpdb->prefix . 'srp_subjects';
        \$terms_table    = \$wpdb->prefix . 'srp_terms';
        \$results_table  = \$wpdb->prefix . 'srp_results';

        \$sql = \"CREATE TABLE {\$students_table} (
            id mediumint(9) NOT NULL AUTO_INCREMENT,
            first_name varchar(100) NOT NULL,
            last_name varchar(100) NOT NULL,
            roll varchar(100) DEFAULT '',
            photo_id bigint(20) DEFAULT NULL,
            created_at datetime DEFAULT CURRENT_TIMESTAMP,
            PRIMARY KEY  (id),
            KEY roll (roll)
        ) {\$charset_collate};\";

        \$sql .= \"CREATE TABLE {\$subjects_table} (
            id mediumint(9) NOT NULL AUTO_INCREMENT,
            name varchar(150) NOT NULL,
            code varchar(50) DEFAULT '',
            created_at datetime DEFAULT CURRENT_TIMESTAMP,
            PRIMARY KEY  (id)
        ) {\$charset_collate};\";

        \$sql .= \"CREATE TABLE {\$terms_table} (
            id mediumint(9) NOT NULL AUTO_INCREMENT,
            name varchar(150) NOT NULL,
            start_date date DEFAULT NULL,
            end_date date DEFAULT NULL,
            created_at datetime DEFAULT CURRENT_TIMESTAMP,
            PRIMARY KEY  (id)
        ) {\$charset_collate};\";

        \$sql .= \"CREATE TABLE {\$results_table} (
            id bigint(20) NOT NULL AUTO_INCREMENT,
            student_id mediumint(9) NOT NULL,
            subject_id mediumint(9) NOT NULL,
            term_id mediumint(9) NOT NULL,
            marks decimal(8,2) DEFAULT 0,
            max_marks decimal(8,2) DEFAULT 100,
            created_at datetime DEFAULT CURRENT_TIMESTAMP,
            PRIMARY KEY  (id),
            KEY student_idx (student_id),
            KEY term_idx (term_id)
        ) {\$charset_collate};\";

        dbDelta( \$sql );
    }

}
"

# includes/admin.php
write_file "$PLUGIN_DIR/includes/admin.php" "<?php
if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

class SRP_Admin {

    public static function init() {
        add_action( 'admin_menu', array( __CLASS__, 'add_menus' ) );
        add_action( 'admin_post_srp_handle_form', array( __CLASS__, 'handle_form' ) );
    }

    public static function add_menus() {
        add_menu_page( __( 'School Results', 'school-result-plugin' ), __( 'School Results', 'school-result-plugin' ), 'manage_options', 'srp_main', array( __CLASS__, 'page_overview' ), 'dashicons-welcome-learn-more' );
        add_submenu_page( 'srp_main', __( 'Students', 'school-result-plugin' ), __( 'Students', 'school-result-plugin' ), 'manage_options', 'srp_students', array( __CLASS__, 'page_students' ) );
        add_submenu_page( 'srp_main', __( 'Subjects', 'school-result-plugin' ), __( 'Subjects', 'school-result-plugin' ), 'manage_options', 'srp_subjects', array( __CLASS__, 'page_subjects' ) );
        add_submenu_page( 'srp_main', __( 'Terms', 'school-result-plugin' ), __( 'Terms', 'school-result-plugin' ), 'manage_options', 'srp_terms', array( __CLASS__, 'page_terms' ) );
        add_submenu_page( 'srp_main', __( 'Results', 'school-result-plugin' ), __( 'Results', 'school-result-plugin' ), 'manage_options', 'srp_results', array( __CLASS__, 'page_results' ) );
    }

    public static function page_overview() {
        echo '<div class=\"wrap\"><h1>' . esc_html__( 'School Results', 'school-result-plugin' ) . '</h1><p>' . esc_html__( 'Use the submenus to manage Students, Subjects, Terms, and Results.', 'school-result-plugin' ) . '</p></div>';
    }

    // Simple student form
    public static function page_students() {
        if ( ! current_user_can( 'manage_options' ) ) {
            return;
        }
        ?>
        <div class=\"wrap\">
            <h1><?php echo esc_html__( 'Add Student', 'school-result-plugin' ); ?></h1>
            <form method=\"post\" action=\"<?php echo esc_url( admin_url( 'admin-post.php' ) ); ?>\" enctype=\"multipart/form-data\">
                <?php wp_nonce_field( 'srp_add_student', 'srp_nonce' ); ?>
                <input type=\"hidden\" name=\"action\" value=\"srp_handle_form\">
                <input type=\"hidden\" name=\"srp_action\" value=\"add_student\">
                <table class=\"form-table\">
                    <tr>
                        <th><label><?php echo esc_html__( 'First Name', 'school-result-plugin' ); ?></label></th>
                        <td><input type=\"text\" name=\"first_name\" required></td>
                    </tr>
                    <tr>
                        <th><label><?php echo esc_html__( 'Last Name', 'school-result-plugin' ); ?></label></th>
                        <td><input type=\"text\" name=\"last_name\" required></td>
                    </tr>
                    <tr>
                        <th><label><?php echo esc_html__( 'Roll', 'school-result-plugin' ); ?></label></th>
                        <td><input type=\"text\" name=\"roll\"></td>
                    </tr>
                    <tr>
                        <th><label><?php echo esc_html__( 'Photo', 'school-result-plugin' ); ?></label></th>
                        <td><input type=\"file\" name=\"photo\"></td>
                    </tr>
                </table>
                <?php submit_button( __( 'Add Student', 'school-result-plugin' ) ); ?>
            </form>
        </div>
        <?php
    }

    public static function page_subjects() {
        if ( ! current_user_can( 'manage_options' ) ) {
            return;
        }
        ?>
        <div class=\"wrap\">
            <h1><?php echo esc_html__( 'Add Subject', 'school-result-plugin' ); ?></h1>
            <form method=\"post\" action=\"<?php echo esc_url( admin_url( 'admin-post.php' ) ); ?>\">
                <?php wp_nonce_field( 'srp_add_subject', 'srp_nonce' ); ?>
                <input type=\"hidden\" name=\"action\" value=\"srp_handle_form\">
                <input type=\"hidden\" name=\"srp_action\" value=\"add_subject\">
                <table class=\"form-table\">
                    <tr>
                        <th><label><?php echo esc_html__( 'Name', 'school-result-plugin' ); ?></label></th>
                        <td><input type=\"text\" name=\"name\" required></td>
                    </tr>
                    <tr>
                        <th><label><?php echo esc_html__( 'Code', 'school-result-plugin' ); ?></label></th>
                        <td><input type=\"text\" name=\"code\"></td>
                    </tr>
                </table>
                <?php submit_button( __( 'Add Subject', 'school-result-plugin' ) ); ?>
            </form>
        </div>
        <?php
    }

    public static function page_terms() {
        if ( ! current_user_can( 'manage_options' ) ) {
            return;
        }
        ?>
        <div class=\"wrap\">
            <h1><?php echo esc_html__( 'Add Term', 'school-result-plugin' ); ?></h1>
            <form method=\"post\" action=\"<?php echo esc_url( admin_url( 'admin-post.php' ) ); ?>\">
                <?php wp_nonce_field( 'srp_add_term', 'srp_nonce' ); ?>
                <input type=\"hidden\" name=\"action\" value=\"srp_handle_form\">
                <input type=\"hidden\" name=\"srp_action\" value=\"add_term\">
                <table class=\"form-table\">
                    <tr>
                        <th><label><?php echo esc_html__( 'Name', 'school-result-plugin' ); ?></label></th>
                        <td><input type=\"text\" name=\"name\" required></td>
                    </tr>
                    <tr>
                        <th><label><?php echo esc_html__( 'Start Date', 'school-result-plugin' ); ?></label></th>
                        <td><input type=\"date\" name=\"start_date\"></td>
                    </tr>
                    <tr>
                        <th><label><?php echo esc_html__( 'End Date', 'school-result-plugin' ); ?></label></th>
                        <td><input type=\"date\" name=\"end_date\"></td>
                    </tr>
                </table>
                <?php submit_button( __( 'Add Term', 'school-result-plugin' ) ); ?>
            </form>
        </div>
        <?php
    }

    public static function page_results() {
        if ( ! current_user_can( 'manage_options' ) ) {
            return;
        }
        global \$wpdb;
        \$students_table = \$wpdb->prefix . 'srp_students';
        \$subjects_table = \$wpdb->prefix . 'srp_subjects';
        \$terms_table    = \$wpdb->prefix . 'srp_terms';

        \$students = \$wpdb->get_results( \"SELECT id, first_name, last_name FROM {\$students_table} ORDER BY first_name\" );
        \$subjects = \$wpdb->get_results( \"SELECT id, name FROM {\$subjects_table} ORDER BY name\" );
        \$terms    = \$wpdb->get_results( \"SELECT id, name FROM {\$terms_table} ORDER BY id DESC\" );
        ?>
        <div class=\"wrap\">
            <h1><?php echo esc_html__( 'Add Result', 'school-result-plugin' ); ?></h1>
            <form method=\"post\" action=\"<?php echo esc_url( admin_url( 'admin-post.php' ) ); ?>\">
                <?php wp_nonce_field( 'srp_add_result', 'srp_nonce' ); ?>
                <input type=\"hidden\" name=\"action\" value=\"srp_handle_form\">
                <input type=\"hidden\" name=\"srp_action\" value=\"add_result\">
                <table class=\"form-table\">
                    <tr>
                        <th><label><?php echo esc_html__( 'Student', 'school-result-plugin' ); ?></label></th>
                        <td>
                            <select name=\"student_id\" required>
                                <option value=\"\"><?php echo esc_html__( 'Select Student', 'school-result-plugin' ); ?></option>
                                <?php foreach ( \$students as \$s ) : ?>
                                    <option value=\"<?php echo esc_attr( \$s->id ); ?>\"><?php echo esc_html( \$s->first_name . ' ' . \$s->last_name ); ?></option>
                                <?php endforeach; ?>
                            </select>
                        </td>
                    </tr>
                    <tr>
                        <th><label><?php echo esc_html__( 'Subject', 'school-result-plugin' ); ?></label></th>
                        <td>
                            <select name=\"subject_id\" required>
                                <option value=\"\"><?php echo esc_html__( 'Select Subject', 'school-result-plugin' ); ?></option>
                                <?php foreach ( \$subjects as \$sub ) : ?>
                                    <option value=\"<?php echo esc_attr( \$sub->id ); ?>\"><?php echo esc_html( \$sub->name ); ?></option>
                                <?php endforeach; ?>
                            </select>
                        </td>
                    </tr>
                    <tr>
                        <th><label><?php echo esc_html__( 'Term', 'school-result-plugin' ); ?></label></th>
                        <td>
                            <select name=\"term_id\" required>
                                <option value=\"\"><?php echo esc_html__( 'Select Term', 'school-result-plugin' ); ?></option>
                                <?php foreach ( \$terms as \$t ) : ?>
                                    <option value=\"<?php echo esc_attr( \$t->id ); ?>\"><?php echo esc_html( \$t->name ); ?></option>
                                <?php endforeach; ?>
                            </select>
                        </td>
                    </tr>
                    <tr>
                        <th><label><?php echo esc_html__( 'Marks', 'school-result-plugin' ); ?></label></th>
                        <td><input type=\"number\" step=\"0.01\" name=\"marks\" required></td>
                    </tr>
                    <tr>
                        <th><label><?php echo esc_html__( 'Max Marks', 'school-result-plugin' ); ?></label></th>
                        <td><input type=\"number\" step=\"0.01\" name=\"max_marks\" value=\"100\"></td>
                    </tr>
                </table>
                <?php submit_button( __( 'Add Result', 'school-result-plugin' ) ); ?>
            </form>
        </div>
        <?php
    }

    public static function handle_form() {
        if ( ! isset( \$_POST['srp_action'] ) ) {
            wp_die( __( 'Invalid request', 'school-result-plugin' ) );
        }
        if ( ! current_user_can( 'manage_options' ) ) {
            wp_die( __( 'Permission denied', 'school-result-plugin' ) );
        }
        \$action = sanitize_text_field( \$_POST['srp_action'] );

        if ( ! isset( \$_POST['srp_nonce'] ) || ! wp_verify_nonce( \$_POST['srp_nonce'], 'srp_' . str_replace( 'add_', '', \$action ) ) ) {
            wp_die( __( 'Nonce verification failed', 'school-result-plugin' ) );
        }

        global \$wpdb;
        \$students_table = \$wpdb->prefix . 'srp_students';
        \$subjects_table = \$wpdb->prefix . 'srp_subjects';
        \$terms_table    = \$wpdb->prefix . 'srp_terms';
        \$results_table  = \$wpdb->prefix . 'srp_results';

        if ( \$action === 'add_student' ) {
            \$first = sanitize_text_field( \$_POST['first_name'] );
            \$last  = sanitize_text_field( \$_POST['last_name'] );
            \$roll  = sanitize_text_field( \$_POST['roll'] );

            // Handle photo upload
            \$photo_id = null;
            if ( ! empty( \$_FILES['photo']['name'] ) ) {
                require_once ABSPATH . 'wp-admin/includes/file.php';
                require_once ABSPATH . 'wp-admin/includes/media.php';
                require_once ABSPATH . 'wp-admin/includes/image.php';

                \$file = \$_FILES['photo'];
                // basic mime/size checks
                \$allowed = array( 'image/jpeg', 'image/png', 'image/gif' );
                if ( in_array( \$file['type'], \$allowed, true ) ) {
                    \$attach_id = media_handle_upload( 'photo', 0 );
                    if ( is_wp_error( \$attach_id ) ) {
                        // ignore and proceed without photo
                        \$photo_id = null;
                    } else {
                        \$photo_id = intval( \$attach_id );
                    }
                }
            }

            \$wpdb->insert(
                \$students_table,
                array(
                    'first_name' => \$first,
                    'last_name'  => \$last,
                    'roll'       => \$roll,
                    'photo_id'   => \$photo_id,
                ),
                array( '%s', '%s', '%s', '%d' )
            );

            wp_safe_redirect( admin_url( 'admin.php?page=srp_students' ) );
            exit;
        }

        if ( \$action === 'add_subject' ) {
            \$name = sanitize_text_field( \$_POST['name'] );
            \$code = sanitize_text_field( \$_POST['code'] );
            \$wpdb->insert( \$subjects_table, array( 'name' => \$name, 'code' => \$code ), array( '%s', '%s' ) );
            wp_safe_redirect( admin_url( 'admin.php?page=srp_subjects' ) );
            exit;
        }

        if ( \$action === 'add_term' ) {
            \$name = sanitize_text_field( \$_POST['name'] );
            \$start = ! empty( \$_POST['start_date'] ) ? sanitize_text_field( \$_POST['start_date'] ) : null;
            \$end = ! empty( \$_POST['end_date'] ) ? sanitize_text_field( \$_POST['end_date'] ) : null;
            \$wpdb->insert( \$terms_table, array( 'name' => \$name, 'start_date' => \$start, 'end_date' => \$end ), array( '%s', '%s', '%s' ) );
            wp_safe_redirect( admin_url( 'admin.php?page=srp_terms' ) );
            exit;
        }

        if ( \$action === 'add_result' ) {
            \$student_id = intval( \$_POST['student_id'] );
            \$subject_id = intval( \$_POST['subject_id'] );
            \$term_id    = intval( \$_POST['term_id'] );
            \$marks      = floatval( \$_POST['marks'] );
            \$max_marks  = floatval( \$_POST['max_marks'] );
            \$wpdb->insert( \$results_table, array(
                'student_id' => \$student_id,
                'subject_id' => \$subject_id,
                'term_id'    => \$term_id,
                'marks'      => \$marks,
                'max_marks'  => \$max_marks,
            ), array( '%d', '%d', '%d', '%f', '%f' ) );
            wp_safe_redirect( admin_url( 'admin.php?page=srp_results' ) );
            exit;
        }

        wp_die( __( 'Unknown action', 'school-result-plugin' ) );
    }

}
"

# includes/public.php
write_file "$PLUGIN_DIR/includes/public.php" "<?php
if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

class SRP_Public {

    public static function init() {
        add_shortcode( 'school_result', array( __CLASS__, 'shortcode_result' ) );
    }

    // Grade-to-point mapping for GPA 5.0 (simple mapping)
    public static function marks_to_point( \$percent ) {
        if ( \$percent >= 80 ) return 5.0;
        if ( \$percent >= 70 ) return 4.0;
        if ( \$percent >= 60 ) return 3.5;
        if ( \$percent >= 50 ) return 3.0;
        if ( \$percent >= 40 ) return 2.0;
        return 0.0;
    }

    public static function shortcode_result( \$atts ) {
        \$atts = shortcode_atts( array(
            'student_id' => 0,
            'term_id'    => 0,
        ), \$atts, 'school_result' );

        \$student_id = intval( \$atts['student_id'] );
        \$term_id    = intval( \$atts['term_id'] );

        if ( ! \$student_id || ! \$term_id ) {
            return '<div class=\"srp-result\">' . esc_html__( 'Please provide student_id and term_id in the shortcode.', 'school-result-plugin' ) . '</div>';
        }

        global \$wpdb;
        \$students_table = \$wpdb->prefix . 'srp_students';
        \$subjects_table = \$wpdb->prefix . 'srp_subjects';
        \$results_table  = \$wpdb->prefix . 'srp_results';

        \$student = \$wpdb->get_row( \$wpdb->prepare( \"SELECT * FROM {\$students_table} WHERE id = %d\", \$student_id ) );
        if ( ! \$student ) {
            return '<div class=\"srp-result\">' . esc_html__( 'Student not found.', 'school-result-plugin' ) . '</div>';
        }

        \$results = \$wpdb->get_results( \$wpdb->prepare( \"SELECT r.*, s.name as subject_name, s.code as subject_code FROM {\$results_table} r LEFT JOIN {\$subjects_table} s ON r.subject_id = s.id WHERE r.student_id = %d AND r.term_id = %d\", \$student_id, \$term_id ) );

        if ( empty( \$results ) ) {
            return '<div class=\"srp-result\">' . esc_html__( 'No results found for this student/term.', 'school-result-plugin' ) . '</div>';
        }

        // Compute GPA: convert each subject percent to point, average points.
        \$total_points = 0;
        \$count = 0;
        \$rows = array();

        foreach ( \$results as \$r ) {
            \$percent = 0;
            if ( floatval( \$r->max_marks ) > 0 ) {
                \$percent = ( floatval( \$r->marks ) / floatval( \$r->max_marks ) ) * 100;
            }
            \$point = self::marks_to_point( \$percent );
            \$total_points += \$point;
            \$count++;
            \$rows[] = array(
                'subject' => esc_html( \$r->subject_name ),
                'marks'   => esc_html( number_format_i18n( floatval( \$r->marks ), 2 ) ),
                'max'     => esc_html( number_format_i18n( floatval( \$r->max_marks ), 2 ) ),
                'percent' => esc_html( number_format_i18n( round( \$percent, 2 ), 2 ) ) . '%',
                'point'   => esc_html( number_format_i18n( \$point, 2 ) ),
            );
        }

        \$gpa = \$count ? round( \$total_points / \$count, 2 ) : 0;

        ob_start();
        ?>
        <div class=\"srp-result\">
            <h3><?php echo esc_html( sprintf( '%s %s', \$student->first_name, \$student->last_name ) ); ?></h3>
            <table class=\"srp-table\">
                <thead><tr><th><?php echo esc_html__( 'Subject', 'school-result-plugin' ); ?></th><th><?php echo esc_html__( 'Marks', 'school-result-plugin' ); ?></th><th><?php echo esc_html__( 'Max', 'school-result-plugin' ); ?></th><th><?php echo esc_html__( 'Percent', 'school-result-plugin' ); ?></th><th><?php echo esc_html__( 'Point', 'school-result-plugin' ); ?></th></tr></thead>
                <tbody>
                <?php foreach ( \$rows as \$row ) : ?>
                    <tr>
                        <td><?php echo \$row['subject']; ?></td>
                        <td><?php echo \$row['marks']; ?></td>
                        <td><?php echo \$row['max']; ?></td>
                        <td><?php echo \$row['percent']; ?></td>
                        <td><?php echo \$row['point']; ?></td>
                    </tr>
                <?php endforeach; ?>
                </tbody>
                <tfoot><tr><td colspan=\"4\" style=\"text-align:right;\"><strong><?php echo esc_html__( 'GPA', 'school-result-plugin' ); ?>:</strong></td><td><strong><?php echo esc_html( number_format_i18n( \$gpa, 2 ) ); ?></strong></td></tr></tfoot>
            </table>
        </div>
        <?php
        return ob_get_clean();
    }

}
"

# uninstall.php
write_file "$PLUGIN_DIR/uninstall.php" "<?php
if ( ! defined( 'WP_UNINSTALL_PLUGIN' ) ) {
    exit;
}

global \$wpdb;

\$wpdb->query( 'DROP TABLE IF EXISTS ' . \$wpdb->prefix . 'srp_results' );
\$wpdb->query( 'DROP TABLE IF EXISTS ' . \$wpdb->prefix . 'srp_students' );
\$wpdb->query( 'DROP TABLE IF EXISTS ' . \$wpdb->prefix . 'srp_subjects' );
\$wpdb->query( 'DROP TABLE IF EXISTS ' . \$wpdb->prefix . 'srp_terms' );
"

# CSS
write_file "$PLUGIN_DIR/assets/css/style.css" ".srp-result { border:1px solid #ddd; padding:12px; max-width:800px; background:#fff; }
.srp-table { width:100%; border-collapse:collapse; }
.srp-table th, .srp-table td { border:1px solid #eee; padding:8px; text-align:left; }
.srp-table thead { background:#f9f9f9; }
"

# readme.txt (simple)
write_file "$PLUGIN_DIR/readme.txt" "=== School Result Plugin ===
Contributors: copilot
Tags: school, results, gpa
Requires at least: 5.0
Tested up to: 6.0
Stable tag: 0.1.0
License: GPLv2 or later

This plugin provides a minimal system to manage students, subjects, terms and results and display a student's result with GPA using a shortcode.

Shortcode:
[school_result student_id=\"X\" term_id=\"Y\"]
"

chmod -R 755 "$PLUGIN_DIR"
echo "Plugin scaffold created. To install, move the plugin directory into your site's wp-content/plugins and activate in WP admin."
