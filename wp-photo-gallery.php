<?php
/*
Plugin Name: WP Photo Gallery
Plugin URI: http://example.com/wp-photo-gallery
Description: A responsive photo gallery plugin with lightbox and admin management features.
Version: 1.0
Author: Your Name
Author URI: http://example.com
License: GPL2
*/

// Exit if accessed directly
if (!defined('ABSPATH')) {
    exit;
}

// Define plugin constants
define('WP_PHOTO_GALLERY_VERSION', '1.0');
define('WP_PHOTO_GALLERY_PLUGIN_DIR', plugin_dir_path(__FILE__));
define('WP_PHOTO_GALLERY_PLUGIN_URL', plugin_dir_url(__FILE__));

// Include necessary files
require_once(WP_PHOTO_GALLERY_PLUGIN_DIR . 'includes/class-wp-photo-gallery-loader.php');
require_once(WP_PHOTO_GALLERY_PLUGIN_DIR . 'includes/class-wp-photo-gallery.php');
require_once(WP_PHOTO_GALLERY_PLUGIN_DIR . 'admin/class-wp-photo-gallery-admin.php');
require_once(WP_PHOTO_GALLERY_PLUGIN_DIR . 'public/class-wp-photo-gallery-public.php');

// Initialize the plugin
function run_wp_photo_gallery() {
    $plugin = new WP_Photo_Gallery();
    $plugin->run();
}

run_wp_photo_gallery();