<?php

class WP_Photo_Gallery_Admin {
    private $plugin_name;
    private $version;

    public function __construct($plugin_name, $version) {
        $this->plugin_name = $plugin_name;
        $this->version = $version;

        add_action('wp_ajax_save_photo_gallery', array($this, 'handle_ajax_save'));
        add_action('wp_ajax_get_photo_galleries', array($this, 'handle_ajax_get_galleries'));
        add_action('wp_ajax_delete_photo_gallery', array($this, 'handle_ajax_delete_gallery'));
        add_action('wp_ajax_save_photo_gallery_settings', array($this, 'handle_ajax_save_settings'));
    }

    public function enqueue_styles() {
        wp_enqueue_style($this->plugin_name, plugin_dir_url(__FILE__) . 'css/wp-photo-gallery-admin.css', array(), $this->version, 'all');
    }

    public function enqueue_scripts() {
        wp_enqueue_media();
        wp_enqueue_script($this->plugin_name, plugin_dir_url(__FILE__) . 'js/wp-photo-gallery-admin.js', array('jquery'), $this->version, false);
        wp_localize_script($this->plugin_name, 'wpPhotoGallery', array(
            'ajaxurl' => admin_url('admin-ajax.php'),
            'nonce' => wp_create_nonce('wp_photo_gallery_ajax_nonce')
        ));
    }

    public function add_plugin_admin_menu() {
        add_menu_page(
            'WP Photo Gallery', 
            'Photo Gallery', 
            'manage_options', 
            $this->plugin_name, 
            array($this, 'display_plugin_setup_page'),
            'dashicons-format-gallery',
            20
        );
    }

    public function display_plugin_setup_page() {
        include_once('partials/wp-photo-gallery-admin-display.php');
    }

    public function handle_ajax_save() {
        check_ajax_referer('wp_photo_gallery_ajax_nonce', 'nonce');

        if (!current_user_can('manage_options')) {
            wp_send_json_error('Unauthorized');
        }

        $gallery_data = isset($_POST['gallery']) ? $_POST['gallery'] : null;
        if (!$gallery_data) {
            wp_send_json_error('No gallery data received');
        }

        $galleries = get_option('wp_photo_gallery_galleries', array());
        $gallery_id = uniqid();
        $galleries[$gallery_id] = array(
            'id' => $gallery_id,
            'title' => sanitize_text_field($gallery_data['title']),
            'description' => sanitize_textarea_field($gallery_data['description']),
            'images' => array_map('absint', $gallery_data['images'])
        );

        update_option('wp_photo_gallery_galleries', $galleries);

        wp_send_json_success(array(
            'message' => 'Gallery saved successfully',
            'gallery_id' => $gallery_id
        ));
    }

    public function handle_ajax_get_galleries() {
        check_ajax_referer('wp_photo_gallery_ajax_nonce', 'nonce');

        if (!current_user_can('manage_options')) {
            wp_send_json_error('Unauthorized');
        }

        $galleries = get_option('wp_photo_gallery_galleries', array());
        wp_send_json_success(array_values($galleries));
    }

    public function handle_ajax_delete_gallery() {
        check_ajax_referer('wp_photo_gallery_ajax_nonce', 'nonce');

        if (!current_user_can('manage_options')) {
            wp_send_json_error('Unauthorized');
        }

        $gallery_id = isset($_POST['gallery_id']) ? sanitize_text_field($_POST['gallery_id']) : '';
        if (!$gallery_id) {
            wp_send_json_error('No gallery ID provided');
        }

        $galleries = get_option('wp_photo_gallery_galleries', array());
        if (isset($galleries[$gallery_id])) {
            unset($galleries[$gallery_id]);
            update_option('wp_photo_gallery_galleries', $galleries);
            wp_send_json_success('Gallery deleted successfully');
        } else {
            wp_send_json_error('Gallery not found');
        }
    }

    public function handle_ajax_save_settings() {
        check_ajax_referer('wp_photo_gallery_ajax_nonce', 'nonce');

        if (!current_user_can('manage_options')) {
            wp_send_json_error('Unauthorized');
        }

        $settings = isset($_POST['settings']) ? $_POST['settings'] : null;
        if (!$settings) {
            wp_send_json_error('No settings data received');
        }

        $sanitized_settings = array(
            'images_per_page' => absint($settings['images_per_page']),
            'enable_lightbox' => (bool)$settings['enable_lightbox'],
            'grid_columns' => absint($settings['grid_columns'])
        );

        update_option('wp_photo_gallery_settings', $sanitized_settings);

        wp_send_json_success('Settings saved successfully');
    }
}