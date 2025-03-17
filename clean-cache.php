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

// Core cache clearing function.
function nerd_cache_clear() {
    // Insert cache clear logic here.
}

// Add admin menu to access cache clear functionality.
function nerd_cache_clear_menu() {
    add_menu_page(
        'Nerd Cache Clear',   // Page title.
        'Cache Clear',        // Menu title.
        'manage_options',     // Capability.
        'nerd-cache-clear',   // Menu slug.
        'nerd_cache_clear'    // Function to display page content.
    );
}
add_action( 'admin_menu', 'nerd_cache_clear_menu' );
