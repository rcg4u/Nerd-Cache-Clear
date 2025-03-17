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

// Core cache clearing logic.
function nerd_run_cache_clear() {
    // Generic cache clear logic (if any).
}

// New functions for clearing specific caches.
function nerd_clear_elementor_cache() {
    // Clear Elementor cache if available.
    if ( defined('ELEMENTOR_VERSION') ) {
        if ( method_exists( Elementor\Plugin::instance()->files_manager, 'clear_cache' ) ) {
            Elementor\Plugin::instance()->files_manager->clear_cache();
        }
    }
    nerd_run_cache_clear();
}

function nerd_clear_wp_rocket_cache() {
    // Clear WP-Rocket cache if function exists.
    if ( function_exists( 'rocket_clean_domain' ) ) {
        rocket_clean_domain();
    }
    nerd_run_cache_clear();
}

function nerd_clear_filesystem_cache() {
    $cache_dir = plugin_dir_path( __FILE__ ) . 'cache/';
    if ( is_dir( $cache_dir ) ) {
        $files = glob( $cache_dir . '*' );
        $deleted = [];
        if ( $files ) {
            foreach ( $files as $file ) {
                if ( is_file( $file ) && unlink( $file ) ) {
                    $deleted[] = basename( $file );
                }
            }
        }
        error_log( 'Filesystem cache cleared: ' . implode( ', ', $deleted ) );
    } else {
        error_log( 'Cache directory not found: ' . $cache_dir );
    }
    nerd_run_cache_clear();
}

// Updated admin page callback that displays a button.
function nerd_cache_clear_page() {
    if ( isset( $_POST['clear_cache_all'] ) ) {
        if ( check_admin_referer( 'nerd_cache_clear_action' ) ) {
            nerd_clear_elementor_cache();
            nerd_clear_wp_rocket_cache();
            nerd_clear_filesystem_cache();
            echo '<div class="updated notice"><p>All caches cleared!</p></div>';
        }
    } elseif ( isset( $_POST['clear_elementor'] ) ) {
        if ( check_admin_referer( 'nerd_cache_clear_action' ) ) {
            nerd_clear_elementor_cache();
            echo '<div class="updated notice"><p>Elementor cache cleared!</p></div>';
        }
    } elseif ( isset( $_POST['clear_wp_rocket'] ) ) {
        if ( check_admin_referer( 'nerd_cache_clear_action' ) ) {
            nerd_clear_wp_rocket_cache();
            echo '<div class="updated notice"><p>WP Rocket cache cleared!</p></div>';
        }
    } elseif ( isset( $_POST['clear_filesystem'] ) ) {
        if ( check_admin_referer( 'nerd_cache_clear_action' ) ) {
            nerd_clear_filesystem_cache();
            echo '<div class="updated notice"><p>Filesystem cache cleared!</p></div>';
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
        </form>
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
