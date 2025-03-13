<?php

// メディアアップローダーの読み込み
function enqueue_tag_media_script($hook) {
  if ('edit-tags.php' !== $hook || 'post_tag' !== $_GET['taxonomy']) {
      return;
  }
  wp_enqueue_media();
  wp_enqueue_script('tag-media-script', get_template_directory_uri() . '/js/tag-media.js', array('jquery'), null, true);
}
add_action('admin_enqueue_scripts', 'enqueue_tag_media_script');

// タグのカスタムフィールドを追加
function add_custom_tag_fields($tag) {
  // 画像設定
  $image_id = get_term_meta($tag->term_id, 'tag_image', true);
  $image_url = $image_id ? wp_get_attachment_image_url($image_id, 'medium') : '';

  // イメージカラー
  $tag_color = get_term_meta($tag->term_id, 'tag_color', true);
  ?>
  <tr class="form-field">
      <th scope="row" valign="top">
          <label for="tag_image"><?php _e('画像設定', 'textdomain'); ?></label>
      </th>
      <td>
          <input type="hidden" id="tag_image" name="tag_image" value="<?php echo esc_attr($image_id); ?>">
          <img id="tag_image_preview" src="<?php echo esc_url($image_url); ?>" style="max-width:100px; height:auto;">
          <button type="button" class="button" id="upload_tag_image_button"><?php _e('画像を選択', 'textdomain'); ?></button>
          <button type="button" class="button" id="remove_tag_image_button"><?php _e('画像を削除', 'textdomain'); ?></button>
      </td>
  </tr>
  <tr class="form-field">
      <th scope="row" valign="top">
          <label for="tag_color"><?php _e('イメージカラー', 'textdomain'); ?></label>
      </th>
      <td>
          <input type="color" id="tag_color" name="tag_color" value="<?php echo esc_attr($tag_color ? $tag_color : '#000000'); ?>">
      </td>
  </tr>
  <script>
      jQuery(document).ready(function($){
          var mediaUploader;
          $('#upload_tag_image_button').click(function(e) {
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
                  $('#tag_image').val(attachment.id);
                  $('#tag_image_preview').attr('src', attachment.url);
              });
              mediaUploader.open();
          });

          $('#remove_tag_image_button').click(function(e) {
              e.preventDefault();
              $('#tag_image').val('');
              $('#tag_image_preview').attr('src', '');
          });
      });
  </script>
  <?php
}

// タグのカスタムフィールドの保存処理
function save_custom_tag_fields($term_id) {
  if (isset($_POST['tag_image'])) {
      update_term_meta($term_id, 'tag_image', sanitize_text_field($_POST['tag_image']));
  }
  if (isset($_POST['tag_color'])) {
      update_term_meta($term_id, 'tag_color', sanitize_hex_color($_POST['tag_color']));
  }
}

add_action('post_tag_edit_form_fields', 'add_custom_tag_fields');
add_action('edited_post_tag', 'save_custom_tag_fields');


