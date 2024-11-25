<?php

class WP_Photo_Gallery_Public {
    private $plugin_name;
    private $version;

    public function __construct($plugin_name, $version) {
        $this->plugin_name = $plugin_name;
        $this->version = $version;
    }

    public function enqueue_styles() {
        wp_enqueue_style($this->plugin_name, WP_PHOTO_GALLERY_PLUGIN_URL . 'public/css/wp-photo-gallery-public.css', array(), $this->version, 'all');
    }

    public function enqueue_scripts() {
        wp_enqueue_script($this->plugin_name, WP_PHOTO_GALLERY_PLUGIN_URL . 'public/js/wp-photo-gallery-public.js', array('jquery'), $this->version, false);
    }

    public function display_gallery($atts) {
        $gallery_id = isset($atts['id']) ? intval($atts['id']) : 0;
        
        if ($gallery_id === 0) {
            return 'Invalid gallery ID';
        }

        // Fetch gallery images and settings
        $images = $this->get_gallery_images($gallery_id);
        $settings = $this->get_gallery_settings($gallery_id);

        // Start output buffering
        ob_start();

        // Include the gallery template
        include(WP_PHOTO_GALLERY_PLUGIN_DIR . 'public/partials/gallery-template.php');

        // Return the buffered content
        return ob_get_clean();
    }

    private function get_gallery_images($gallery_id) {
        // Implement logic to fetch images for the given gallery ID
        // This should return an array of image data (URL, title, description, etc.)
    }

    private function get_gallery_settings($gallery_id) {
        // Implement logic to fetch settings for the given gallery ID
        // This should return an array of settings (layout, pagination, etc.)
    }
}