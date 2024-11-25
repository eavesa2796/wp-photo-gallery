<div class="wrap wp-photo-gallery-admin">
    <h1><?php echo esc_html(get_admin_page_title()); ?></h1>

    <div class="nav-tab-wrapper">
        <a href="#galleries" class="nav-tab nav-tab-active">Galleries</a>
        <a href="#settings" class="nav-tab">Settings</a>
    </div>

    <div id="galleries" class="tab-content active">
        <div class="gallery-form">
            <h2>Create New Gallery</h2>
            <form method="post" action="">
                <?php wp_nonce_field('wp_photo_gallery_save', 'wp_photo_gallery_nonce'); ?>
                
                <table class="form-table">
                    <tr>
                        <th scope="row">
                            <label for="gallery_title">Gallery Title</label>
                        </th>
                        <td>
                            <input type="text" id="gallery_title" name="gallery_title" class="regular-text" required>
                        </td>
                    </tr>
                    <tr>
                        <th scope="row">
                            <label for="gallery_description">Description</label>
                        </th>
                        <td>
                            <textarea id="gallery_description" name="gallery_description" class="large-text" rows="5"></textarea>
                        </td>
                    </tr>
                </table>

                <h3>Gallery Images</h3>
                <div class="gallery-images"></div>
                
                <p class="submit">
                    <button type="button" id="upload_image_button" class="button button-secondary">
                        Add Images
                    </button>
                    <input type="submit" name="submit" id="save_gallery" class="button button-primary" value="Save Gallery">
                </p>
            </form>
        </div>

        <div class="existing-galleries">
            <h2>Existing Galleries</h2>
            <table class="wp-list-table widefat fixed striped">
                <thead>
                    <tr>
                        <th>Title</th>
                        <th>Shortcode</th>
                        <th>Images</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    $galleries = get_option('wp_photo_gallery_galleries', array());
                    if (!empty($galleries)) {
                        foreach ($galleries as $id => $gallery) {
                            ?>
                            <tr>
                                <td><?php echo esc_html($gallery['title']); ?></td>
                                <td><code>[wp_photo_gallery id="<?php echo esc_attr($id); ?>"]</code></td>
                                <td><?php echo count($gallery['images']); ?></td>
                                <td>
                                    <a href="#" class="edit-gallery" data-id="<?php echo esc_attr($id); ?>">Edit</a> |
                                    <a href="#" class="delete-gallery" data-id="<?php echo esc_attr($id); ?>">Delete</a>
                                </td>
                            </tr>
                            <?php
                        }
                    } else {
                        ?>
                        <tr>
                            <td colspan="4">No galleries found.</td>
                        </tr>
                        <?php
                    }
                    ?>
                </tbody>
            </table>
        </div>
    </div>

    <div id="settings" class="tab-content">
        <form method="post" action="">
            <?php wp_nonce_field('wp_photo_gallery_settings', 'wp_photo_gallery_settings_nonce'); ?>
            
            <table class="form-table">
                <tr>
                    <th scope="row">
                        <label for="images_per_page">Images Per Page</label>
                    </th>
                    <td>
                        <input type="number" id="images_per_page" name="images_per_page" 
                               value="<?php echo esc_attr(get_option('wp_photo_gallery_images_per_page', 12)); ?>" 
                               min="1" max="100">
                    </td>
                </tr>
                <tr>
                    <th scope="row">
                        <label for="enable_lightbox">Enable Lightbox</label>
                    </th>
                    <td>
                        <input type="checkbox" id="enable_lightbox" name="enable_lightbox" 
                               <?php checked(get_option('wp_photo_gallery_enable_lightbox', true)); ?>>
                    </td>
                </tr>
                <tr>
                    <th scope="row">
                        <label for="grid_columns">Grid Columns</label>
                    </th>
                    <td>
                        <select id="grid_columns" name="grid_columns">
                            <?php
                            $current_columns = get_option('wp_photo_gallery_grid_columns', 3);
                            for ($i = 2; $i <= 6; $i++) {
                                printf(
                                    '<option value="%1$d" %2$s>%1$d Columns</option>',
                                    $i,
                                    selected($current_columns, $i, false)
                                );
                            }
                            ?>
                        </select>
                    </td>
                </tr>
            </table>

            <p class="submit">
                <input type="submit" name="submit" id="save_settings" class="button button-primary" value="Save Settings">
            </p>
        </form>
    </div>
</div>