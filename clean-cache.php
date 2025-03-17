<?php
/**
 * Plugin Name: Nerd Cache Clear
 * Plugin URI: https://narcolepticnerd.com/wordpress-plugins/NerdCacheClear
 * Description: A basic WordPress plugin to clear cache.
 * Version: 1.0
 * Author: narcolepticnerd
 * Author URI: https://narcolepticnerd.com
 */

if ( ! defined( 'ABSPATH' ) ) {
    exit; // Exit if accessed directly.
}

global $nerd_log;
function nerd_log($message) {
    global $nerd_log;
    $nerd_log[] = $message;
    error_log($message);
}

function nerd_log_file_change($file, $action) {
    $log_file = WP_CONTENT_DIR . '/cache-clearer.log';
    $log_entry = date('Y-m-d H:i:s') . " - {$action}: {$file}\n";
    file_put_contents($log_file, $log_entry, FILE_APPEND);
}

function nerd_log_cache_clear_results($results, $action) {
    $log_file = WP_CONTENT_DIR . '/cache-clearer.log';
    $log_entry = date('Y-m-d H:i:s') . " - Action: {$action}\n";
    foreach ($results as $cache_type => $status) {
        $log_entry .= ucfirst($cache_type) . ": " . ($status ? 'Success' : 'Failed') . "\n";
    }
    $log_entry .= "-------------------------\n";
    file_put_contents($log_file, $log_entry, FILE_APPEND);
}

function nerd_get_log_contents() {
    $log_file = WP_CONTENT_DIR . '/cache-clearer.log';
    if (file_exists($log_file)) {
        return file_get_contents($log_file);
    }
    return 'Log file is empty or does not exist.';
}

function nerd_clear_log_file() {
    $log_file = WP_CONTENT_DIR . '/cache-clearer.log';
    if (file_exists($log_file)) {
        file_put_contents($log_file, '');
        return true;
    }
    return false;
}

// Core cache clearing logic.
function nerd_run_cache_clear() {
    // Generic cache clear logic (if any).
}

// New functions for clearing specific caches.
function nerd_clear_elementor_cache() {
    nerd_log('Starting clear_elementor_cache.');
    nerd_log('Checking if ELEMENTOR_VERSION is defined.');
    if ( defined('ELEMENTOR_VERSION') ) {
        nerd_log('ELEMENTOR_VERSION is defined.');
        if ( method_exists( Elementor\Plugin::instance()->files_manager, 'clear_cache' ) ) {
            nerd_log('Method clear_cache exists. Calling Elementor cache clear.');
            Elementor\Plugin::instance()->files_manager->clear_cache();
            nerd_log('Elementor cache cleared successfully via clear_cache().');
        } else {
            nerd_log('Elementor cache clear method not available.');
        }
    } else {
        nerd_log('ELEMENTOR_VERSION not defined. Skipping Elementor cache clear.');
    }
    nerd_run_cache_clear();
}

function nerd_clear_wp_rocket_cache() {
    nerd_log('Starting clear_wp_rocket_cache.');
    nerd_log('Checking if function rocket_clean_domain exists.');
    if ( function_exists( 'rocket_clean_domain' ) ) {
        nerd_log('rocket_clean_domain function exists. Calling it.');
        rocket_clean_domain();
        nerd_log('WP Rocket cache cleared using rocket_clean_domain().');
    } else {
        nerd_log('rocket_clean_domain() function not found. Skipping WP Rocket cache clear.');
    }
    nerd_run_cache_clear();
}

function nerd_clear_filesystem_cache() {
    nerd_log('Starting clear_filesystem_cache.');
    $cache_dir = WP_CONTENT_DIR . '/cache/';
    nerd_log('Determined cache directory: ' . $cache_dir);
    if ( is_dir( $cache_dir ) ) {
        nerd_log('Cache directory exists. Scanning for files.');
        $files = glob( $cache_dir . '*' );
        nerd_log('Found ' . count((array)$files) . ' file(s) in cache directory.');
        $deleted = [];
        if ( $files ) {
            foreach ( $files as $file ) {
                nerd_log('Processing file: ' . $file);
                if ( is_file( $file ) ) {
                    if ( unlink( $file ) ) {
                        nerd_log('Successfully deleted file: ' . $file);
                        $deleted[] = $file;
                    } else {
                        nerd_log('Failed to delete file: ' . $file);
                    }
                } else {
                    nerd_log('Skipped non-file: ' . $file);
                }
            }
        }
        if ( ! empty($deleted) ) {
            nerd_log( 'Filesystem cache cleared. Deleted files: ' . implode( ', ', $deleted ) );
        } else {
            nerd_log( 'Filesystem cache clear invoked, but no files were deleted from: ' . $cache_dir );
        }
        nerd_delete_directory_contents($cache_dir);
    } else {
        nerd_log( 'Cache directory not found: ' . $cache_dir );
    }
    nerd_run_cache_clear();
}

function nerd_delete_directory_contents($path) {
    // Recursively remove all files and subdirectories.
    $items = glob($path . '*', GLOB_MARK);
    foreach ($items as $item) {
        if (is_dir($item)) {
            nerd_delete_directory_contents($item);
            rmdir($item);
            nerd_log('Deleted directory: ' . $item);
        } else {
            unlink($item);
            nerd_log('Deleted file: ' . $item);
        }
    }
}

function nerd_clear_ea_elementor_cache() {
    nerd_log('Starting clear_ea_elementor_cache.');
    if ( class_exists('Essential_Addons_Elementor\Classes\Cache') ) {
        nerd_log('Essential Addons class found. Attempting to clear EA cache...');
        if ( method_exists('Essential_Addons_Elementor\Classes\Cache', 'clear_all_caches') ) {
            Essential_Addons_Elementor\Classes\Cache::clear_all_caches();
            nerd_log('Essential Addons cache cleared via clear_all_caches().');
        } else {
            nerd_log('Essential Addons cache method not found.');
        }
    } else {
        nerd_log('Essential Addons for Elementor plugin not detected. Skipping...');
    }
    nerd_run_cache_clear();
}

// Updated admin page callback that displays a button.
function nerd_cache_clear_page() {
    nerd_log('Admin settings page loaded' . print_r($_POST, true));
    if ( isset( $_POST['clear_cache_all'] ) ) {
        nerd_log('Button "Clear All Caches" pressed.');
        if ( check_admin_referer( 'nerd_cache_clear_action' ) ) {
            nerd_log('Nonce verified for "Clear All Caches". Clearing all caches: starting.');
            nerd_clear_elementor_cache();
            nerd_clear_wp_rocket_cache();
            nerd_clear_filesystem_cache();
            nerd_log('Clearing all caches completed.');
            echo '<div class="updated notice"><p>All caches cleared!</p></div>';
        } else {
            nerd_log('Nonce verification failed for "Clear All Caches". Operation aborted.');
        }
    } elseif ( isset( $_POST['clear_elementor'] ) ) {
        nerd_log('Button "Clear Elementor Cache" pressed.');
        if ( check_admin_referer( 'nerd_cache_clear_action' ) ) {
            nerd_log('Nonce verified for "Clear Elementor Cache". Clearing Elementor cache: starting.');
            nerd_clear_elementor_cache();
            nerd_log('Clearing Elementor cache completed.');
            echo '<div class="updated notice"><p>Elementor cache cleared!</p></div>';
        } else {
            nerd_log('Nonce verification failed for "Clear Elementor Cache". Operation aborted.');
        }
    } elseif ( isset( $_POST['clear_wp_rocket'] ) ) {
        nerd_log('Button "Clear WP Rocket Cache" pressed.');
        if ( check_admin_referer( 'nerd_cache_clear_action' ) ) {
            nerd_log('Nonce verified for "Clear WP Rocket Cache". Clearing WP Rocket cache: starting.');
            nerd_clear_wp_rocket_cache();
            nerd_log('Clearing WP Rocket cache completed.');
            echo '<div class="updated notice"><p>WP Rocket cache cleared!</p></div>';
        } else {
            nerd_log('Nonce verification failed for "Clear WP Rocket Cache". Operation aborted.');
        }
    } elseif ( isset( $_POST['clear_filesystem'] ) ) {
        nerd_log('Button "Clear Filesystem Cache" pressed.');
        if ( check_admin_referer( 'nerd_cache_clear_action' ) ) {
            nerd_log('Nonce verified for "Clear Filesystem Cache". Clearing Filesystem cache: starting.');
            nerd_clear_filesystem_cache();
            nerd_log('Clearing Filesystem cache completed.');
            echo '<div class="updated notice"><p>Filesystem cache cleared!</p></div>';
        } else {
            nerd_log('Nonce verification failed for "Clear Filesystem Cache". Operation aborted.');
        }
    } elseif ( isset( $_POST['clear_ea_elementor'] ) ) {
        nerd_log('Button "Clear Essential Addons Cache" pressed.');
        if ( check_admin_referer( 'nerd_cache_clear_action' ) ) {
            nerd_log('Nonce verified for "Clear Essential Addons Cache".');
            nerd_clear_ea_elementor_cache();
            echo '<div class="updated notice"><p>Essential Addons cache cleared!</p></div>';
        } else {
            nerd_log('Nonce verification failed for "Clear Essential Addons Cache".');
        }
    }
    ?>
    <div class="wrap">
        <h1>Nerd Cache Clear</h1>
        <form method="post">
            <?php wp_nonce_field( 'nerd_cache_clear_action' ); ?>
            <input type="submit" name="clear_cache_all" class="button-primary" value="Clear All Caches">
            <input type="submit" name="clear_elementor" class="button-secondary" value="Clear Elementor Cache">
            <input type="submit" name="clear_wp_rocket" class="button-secondary" value="Clear WP Rocket Cache">
            <input type="submit" name="clear_filesystem" class="button-secondary" value="Clear Filesystem Cache">
            <input type="submit" name="clear_ea_elementor" class="button-secondary" value="Clear Essential Addons Cache">
        </form>
        <div style="width:100%;height:200px;border:1px solid #ccc;overflow:auto;">
            <?php
            global $nerd_log;
            echo '<ul>';
            foreach ( (array) $nerd_log as $log_msg ) {
                echo '<li>' . esc_html($log_msg) . '</li>';
            }
            echo '</ul>';
            ?>
        </div>
    </div>
    <?php
}

// Update admin menu to use the new callback.
function nerd_cache_clear_menu() {
    add_menu_page(
        'Nerd Cache Clear',  // Page title.
        'Cache Clear',       // Menu title.
        'manage_options',    // Capability.
        'nerd-cache-clear',  // Menu slug.
        'nerd_cache_clear_page' // New function to display page content.
    );
}
add_action( 'admin_menu', 'nerd_cache_clear_menu' );