(function ($) {
  "use strict";

  $(document).ready(function () {
    var currentGallery = {
      title: "",
      description: "",
      images: [],
    };

    // Tab functionality
    $(".nav-tab-wrapper").on("click", ".nav-tab", function (e) {
      e.preventDefault();
      $(".nav-tab-wrapper .nav-tab").removeClass("nav-tab-active");
      $(".tab-content").removeClass("active");
      $(this).addClass("nav-tab-active");
      $($(this).attr("href")).addClass("active");
    });

    // Image upload functionality
    $("#upload_image_button").on("click", function (e) {
      e.preventDefault();
      var mediaUploader = wp.media({
        title: "Choose Images",
        button: {
          text: "Use these images",
        },
        multiple: true,
      });

      mediaUploader.on("select", function () {
        var attachments = mediaUploader
          .state()
          .get("selection")
          .map(function (attachment) {
            attachment = attachment.toJSON();
            return attachment;
          });
        addImagesToGallery(attachments);
      });

      mediaUploader.open();
    });

    // Function to add images to the gallery
    function addImagesToGallery(attachments) {
      var $galleryContainer = $(".gallery-images");
      attachments.forEach(function (attachment) {
        var $imageContainer = $('<div class="gallery-image"></div>');
        var $image = $(
          '<img src="' +
            attachment.sizes.thumbnail.url +
            '" alt="' +
            attachment.alt +
            '">'
        );
        var $removeButton = $(
          '<button class="remove-image" data-id="' +
            attachment.id +
            '">×</button>'
        );

        $imageContainer.append($image).append($removeButton);
        $galleryContainer.append($imageContainer);

        currentGallery.images.push(attachment.id);
      });
    }

    // Remove image functionality
    $(".gallery-images").on("click", ".remove-image", function () {
      var imageId = $(this).data("id");
      $(this).parent(".gallery-image").remove();
      currentGallery.images = currentGallery.images.filter(function (id) {
        return id !== imageId;
      });
    });

    // Save gallery functionality
    $("#save_gallery").on("click", function (e) {
      e.preventDefault();
      currentGallery.title = $("#gallery_title").val();
      currentGallery.description = $("#gallery_description").val();

      $.ajax({
        url: wpPhotoGallery.ajaxurl,
        type: "POST",
        data: {
          action: "save_photo_gallery",
          nonce: wpPhotoGallery.nonce,
          gallery: currentGallery,
        },
        success: function (response) {
          if (response.success) {
            alert("Gallery saved successfully!");
            // Reset form and refresh gallery list
            $("#gallery_title").val("");
            $("#gallery_description").val("");
            $(".gallery-images").empty();
            currentGallery = { title: "", description: "", images: [] };
            loadGalleries();
          } else {
            alert("Error saving gallery: " + response.data);
          }
        },
        error: function () {
          alert(
            "An error occurred while saving the gallery. Please try again."
          );
        },
      });
    });

    // Load existing galleries
    function loadGalleries() {
      $.ajax({
        url: wpPhotoGallery.ajaxurl,
        type: "GET",
        data: {
          action: "get_photo_galleries",
          nonce: wpPhotoGallery.nonce,
        },
        success: function (response) {
          if (response.success) {
            displayGalleries(response.data);
          } else {
            alert("Error loading galleries: " + response.data);
          }
        },
        error: function () {
          alert("An error occurred while loading galleries. Please try again.");
        },
      });
    }

    // Display galleries in the table
    function displayGalleries(galleries) {
      var $tableBody = $(".existing-galleries tbody");
      $tableBody.empty();

      if (galleries.length === 0) {
        $tableBody.append('<tr><td colspan="4">No galleries found.</td></tr>');
      } else {
        galleries.forEach(function (gallery) {
          var $row = $("<tr></tr>");
          $row.append("<td>" + gallery.title + "</td>");
          $row.append(
            '<td><code>[wp_photo_gallery id="' + gallery.id + '"]</code></td>'
          );
          $row.append("<td>" + gallery.images.length + "</td>");
          $row.append(
            '<td><a href="#" class="edit-gallery" data-id="' +
              gallery.id +
              '">Edit</a> | <a href="#" class="delete-gallery" data-id="' +
              gallery.id +
              '">Delete</a></td>'
          );
          $tableBody.append($row);
        });
      }
    }

    // Edit gallery functionality
    $(".existing-galleries").on("click", ".edit-gallery", function (e) {
      e.preventDefault();
      var galleryId = $(this).data("id");
      // Implement edit functionality
      alert(
        "Edit functionality for gallery " + galleryId + " not yet implemented."
      );
    });

    // Delete gallery functionality
    $(".existing-galleries").on("click", ".delete-gallery", function (e) {
      e.preventDefault();
      var galleryId = $(this).data("id");
      if (confirm("Are you sure you want to delete this gallery?")) {
        $.ajax({
          url: wpPhotoGallery.ajaxurl,
          type: "POST",
          data: {
            action: "delete_photo_gallery",
            nonce: wpPhotoGallery.nonce,
            gallery_id: galleryId,
          },
          success: function (response) {
            if (response.success) {
              alert("Gallery deleted successfully!");
              loadGalleries();
            } else {
              alert("Error deleting gallery: " + response.data);
            }
          },
          error: function () {
            alert(
              "An error occurred while deleting the gallery. Please try again."
            );
          },
        });
      }
    });

    // Save settings functionality
    $("#save_settings").on("click", function (e) {
      e.preventDefault();
      var settings = {
        images_per_page: $("#images_per_page").val(),
        enable_lightbox: $("#enable_lightbox").is(":checked"),
        grid_columns: $("#grid_columns").val(),
      };

      $.ajax({
        url: wpPhotoGallery.ajaxurl,
        type: "POST",
        data: {
          action: "save_photo_gallery_settings",
          nonce: wpPhotoGallery.nonce,
          settings: settings,
        },
        success: function (response) {
          if (response.success) {
            alert("Settings saved successfully!");
          } else {
            alert("Error saving settings: " + response.data);
          }
        },
        error: function () {
          alert("An error occurred while saving settings. Please try again.");
        },
      });
    });

    // Load galleries on page load
    loadGalleries();
  });
})(jQuery);
