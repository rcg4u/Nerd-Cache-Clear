# Nerd Cache Clear
 A powerful WordPress plugin that provides tools to clear various types of cache

## About

Nerd Cache Clear simplifies WordPress cache management by allowing you to clear page, browser, and object caches quickly. It ensures that your site always serves fresh content with minimal hassle.

## Features

- Quick clearing of various cache types.
- User-friendly dashboard integration.
- Optimized for performance and compatibility.
- Regular updates and active community support.

## Release

### Version 1.0
- Added "Release" section to document changes.
- Updated cache management info for clarity.
- Logging functions: nerd_log, nerd_log_file_change, nerd_log_cache_clear_results, nerd_get_log_contents, nerd_clear_log_file
- Generic cache clearing function: nerd_run_cache_clear
- New cache-specific clearing functions: nerd_clear_elementor_cache, nerd_clear_wp_rocket_cache, nerd_clear_filesystem_cache
- Recursive directory cleanup function: nerd_delete_directory_contents
- Updated admin page callback with new buttons (nerd_cache_clear_page)
- Updated admin menu to use the new callback (nerd_cache_clear_menu)

## GitHub Commit

Commit Message: "feat(admin): add individual cache buttons and filesystem logging for cleared files."
