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
    // Insert cache clear logic here.
}

// New functions for clearing specific caches.
function nerd_clear_elementor_cache() {
    // Insert Elementor cache clear logic here.
    nerd_run_cache_clear();
}

function nerd_clear_wp_rocket_cache() {
    // Insert WP-Rocket cache clear logic here.
    nerd_run_cache_clear();
}

function nerd_clear_filesystem_cache() {
    // Insert Filesystem cache clear logic here.
    nerd_run_cache_clear();
}

// Updated admin page callback that displays a button.
function nerd_cache_clear_page() {
    if ( isset( $_POST['clear_cache'] ) ) {
        if ( check_admin_referer( 'nerd_cache_clear_action' ) ) {
            nerd_clear_elementor_cache();
            nerd_clear_wp_rocket_cache();
            nerd_clear_filesystem_cache();
            echo '<div class="updated notice"><p>Caches cleared!</p></div>';
        }
    }
    ?>
    <div class="wrap">
        <h1>Nerd Cache Clear</h1>
        <form method="post">
            <?php wp_nonce_field( 'nerd_cache_clear_action' ); ?>
            <input type="submit" name="clear_cache" class="button-primary" value="Clear All Caches">
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
