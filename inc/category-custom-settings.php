<?php

function enqueue_category_media_script($hook) {
  if ('edit-tags.php' !== $hook || 'category' !== $_GET['taxonomy']) {
      return;
  }
  wp_enqueue_media();
  wp_enqueue_script('category-media-script', get_template_directory_uri() . '/js/category-media.js', array('jquery'), null, true);
}
add_action('admin_enqueue_scripts', 'enqueue_category_media_script');

// カテゴリにカスタムフィールドを追加
function add_custom_category_fields($tag) {
  // 画像設定
  $image_id = get_term_meta($tag->term_id, 'category_image', true);
  $image_url = $image_id ? wp_get_attachment_image_url($image_id, 'medium') : '';

  // イメージカラー
  $category_color = get_term_meta($tag->term_id, 'category_color', true);
  ?>
  <tr class="form-field">
      <th scope="row" valign="top">
          <label for="category_image"><?php _e('画像設定', 'textdomain'); ?></label>
      </th>
      <td>
          <input type="hidden" id="category_image" name="category_image" value="<?php echo esc_attr($image_id); ?>">
          <img id="category_image_preview" src="<?php echo esc_url($image_url); ?>" style="max-width:100px; height:auto;">
          <button type="button" class="button" id="upload_image_button"><?php _e('画像を選択', 'textdomain'); ?></button>
          <button type="button" class="button" id="remove_image_button"><?php _e('画像を削除', 'textdomain'); ?></button>
      </td>
  </tr>
  <tr class="form-field">
      <th scope="row" valign="top">
          <label for="category_color"><?php _e('イメージカラー', 'textdomain'); ?></label>
      </th>
      <td>
          <input type="color" id="category_color" name="category_color" value="<?php echo esc_attr($category_color ? $category_color : '#000000'); ?>">
      </td>
  </tr>
  <script>
      jQuery(document).ready(function($){
          var mediaUploader;
          $('#upload_image_button').click(function(e) {
              e.preventDefault();
              if (mediaUploader) {
                  mediaUploader.open();
                  return;
              }
              mediaUploader = wp.media.frames.file_frame = wp.media({
                  title: '<?php _e('画像を選択', 'textdomain'); ?>',
                  button: {
                      text: '<?php _e('画像を選択', 'textdomain'); ?>'
                  },
                  multiple: false
              });
              mediaUploader.on('select', function() {
                  var attachment = mediaUploader.state().get('selection').first().toJSON();
                  $('#category_image').val(attachment.id);
                  $('#category_image_preview').attr('src', attachment.url);
              });
              mediaUploader.open();
          });

          $('#remove_image_button').click(function(e) {
              e.preventDefault();
              $('#category_image').val('');
              $('#category_image_preview').attr('src', '');
          });
      });
  </script>
  <?php
}

// カスタムフィールドの保存処理
function save_custom_category_fields($term_id) {
  if (isset($_POST['category_image'])) {
      update_term_meta($term_id, 'category_image', sanitize_text_field($_POST['category_image']));
  }
  if (isset($_POST['category_color'])) {
      update_term_meta($term_id, 'category_color', sanitize_hex_color($_POST['category_color']));
  }
}

add_action('category_edit_form_fields', 'add_custom_category_fields');
add_action('edited_category', 'save_custom_category_fields');
