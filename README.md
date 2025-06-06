## Nerd Cache Clear

Nerd Cache Clear is a powerful WordPress plugin to clear various types of cache including BunnyCDN.

### Features

- Clear Elementor, WP Rocket, Filesystem, Essential Addons, and BunnyCDN caches.
- **New:** Specify the order in which caches are cleared using a drag-and-drop interface.

### Usage

1. Go to the Nerd Cache Clear settings page in your WordPress admin panel.
2. Use the "Clear All Caches" button to clear all caches in the specified order.
3. **New:** Reorder the cache clearing sequence by dragging and dropping the cache types in the list. The default order is `elementor,ea_elementor,filesystem,wp_rocket,bunny_cdn`.
4. Click "Save Cache Order" to save your custom order.

### Installation

1. Upload the plugin files to the `/wp-content/plugins/nerd-cache-clear` directory, or install the plugin through the WordPress plugins screen directly.
2. Activate the plugin through the 'Plugins' screen in WordPress.
3. Use the Settings->Nerd Cache Clear screen to configure the plugin.

### Changelog

#### Version 1.4
- Added option for users to specify the order of cache clearing using a drag-and-drop interface.
- Set default cache clearing order to `elementor,ea_elementor,filesystem,wp_rocket,bunny_cdn`.

#### Version 1.3
- Enabled BunnyCDN functionality.
- Enhanced BunnyCDN cache clearing with better error handling.
- Added support for purging entire zone with `/*` path.

## Credits
Developed by narcolepticnerd - https://narcolepticnerd.com