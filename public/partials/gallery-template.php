<div class="wp-photo-gallery" data-gallery-id="<?php echo esc_attr($gallery_id); ?>">
    <div class="gallery-grid">
        <?php foreach ($images as $image): ?>
            <div class="gallery-item">
                <img src="<?php echo esc_url($image['thumbnail']); ?>" 
                     alt="<?php echo esc_attr($image['alt']); ?>"
                     data-full="<?php echo esc_url($image['full']); ?>"
                     data-title="<?php echo esc_attr($image['title']); ?>"
                     data-description="<?php echo esc_attr($image['description']); ?>">
            </div>
        <?php endforeach; ?>
    </div>
    <div class="gallery-pagination">
        <!-- Pagination controls will be inserted here via JavaScript -->
    </div>
</div>