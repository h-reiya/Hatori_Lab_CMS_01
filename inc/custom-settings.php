<?php

function my_custom_menu() { 
  // サイト設定の親メニューを追加
  add_menu_page(
      'サイト設定',              // ページタイトル
      'サイト設定',              // メニュータイトル
      'manage_options',          // 権限
      'site-settings',           // メニューのスラッグ
      'my_site_settings_page',   // 表示する関数
      'dashicons-flag', // アイコン
      60                         // メニューの表示位置
  );

  // top page setting
  add_submenu_page(
      'site-settings',           // 親メニューのスラッグ
      'TOPページ設定',           // ページタイトル
      'TOPページ設定',           // メニュータイトル
      'manage_options',          // 権限
      'top-page-settings',       // メニューのスラッグ
      'my_top_page_settings_page'// 表示する関数
  );

  // footer setting
  // add_submenu_page(
  //   'site-settings',          // 親メニューのスラッグを正しく設定
  //   'フッター設定',           // ページタイトル
  //   'フッター設定',           // メニュータイトル
  //   'manage_options',         // 権限
  //   'footer-settings',        // メニューのスラッグ
  //   'my_footer_settings'      // 表示する関数
  // );

  // single setting
  add_submenu_page(
    'site-settings',          // 親メニューのスラッグを正しく設定
    '記事ページ設定',           // ページタイトル
    '記事ページ設定',           // メニュータイトル
    'manage_options',         // 権限
    'single-settings',        // メニューのスラッグ
    'my_single_settings'      // 表示する関数
  );
}
add_action('admin_menu', 'my_custom_menu');

function load_wp_color_picker_script() {
  wp_enqueue_style('wp-color-picker');
  wp_enqueue_script('wp-color-picker');
}
add_action('admin_enqueue_scripts', 'load_wp_color_picker_script');

function render_color_picker_field($args) {
  $option_name = $args['option_name'];
  $value = get_option($option_name, '#ffffff');
  ?>
  <input type="text" name="<?php echo esc_attr($option_name); ?>" value="<?php echo esc_attr($value); ?>" class="color-picker" data-default-color="#ffffff" />
  <script>
      jQuery(document).ready(function($) {
          $('.color-picker').wpColorPicker();
      });
  </script>
  <?php
}

function render_checkbox_field($args) {
  $option_name = $args['option_name'];
  $value = get_option($option_name, '0');
  ?>
  <input type="checkbox" name="<?php echo esc_attr($option_name); ?>" value="1" <?php checked(1, $value); ?> />
  <?php
}

function render_hidden_checkbox_field($args) {
  $option_name = $args['option_name'];
  $value = get_option($option_name, '0');
  ?>
  <input class="hidden_checkbox" type="checkbox" name="<?php echo esc_attr($option_name); ?>" value="1" <?php checked(1, $value); ?> />
  <?php
}

function render_radio_field($args) {
  $option_name = $args['option_name'];
  $value = get_option($option_name, '0');
  ?>
  <label><input type="radio" name="<?php echo esc_attr($option_name); ?>" value="1" <?php checked(1, $value); ?> /> はい</label>
  <label><input type="radio" name="<?php echo esc_attr($option_name); ?>" value="0" <?php checked(0, $value); ?> /> いいえ</label>
  <?php
}

function render_radio_field_for_choose_image_or_text($args) {
  $option_name = $args['option_name'];
  $value = get_option($option_name, '0');
  ?>
  <label><input type="radio" name="<?php echo esc_attr($option_name); ?>" value="1" <?php checked(1, $value); ?> /> 画像</label>
  <label><input type="radio" name="<?php echo esc_attr($option_name); ?>" value="0" <?php checked(0, $value); ?> /> テキスト</label>
  <?php
}

function render_text_field($args) {
  $option_name = $args['option_name'];
  $value = get_option($option_name, '');
  ?>
  <input type="text" name="<?php echo esc_attr($option_name); ?>" value="<?php echo esc_attr($value); ?>" />
  <?php
}

function render_post_dropdown($args) {
  $option_name = $args['option_name'];
  $selected_posts = get_option($option_name, array());
  $posts = get_posts(array('post_type' => 'post', 'numberposts' => -1));
  ?>
  <select name="<?php echo esc_attr($option_name); ?>[]" multiple id="section_1_selected_posts" style="display:none;">
      <?php foreach ($posts as $post) : ?>
          <option value="<?php echo esc_attr($post->ID); ?>" <?php echo in_array($post->ID, $selected_posts) ? 'selected' : ''; ?>>
              <?php echo esc_html($post->post_title); ?>
          </option>
      <?php endforeach; ?>
  </select>
  <?php
}

function render_post_type_selector($args) {
  $option_name = $args['option_name'];
  $value = get_option($option_name, 'latest');
  ?>
  <select name="<?php echo esc_attr($option_name); ?>" class="post-type-selector" data-section="<?php echo esc_attr($args['section']); ?>">
      <option value="latest" <?php selected('latest', $value); ?>>最新の記事</option>
      <option value="popular" <?php selected('popular', $value); ?>>人気の記事</option>
      <option value="custom" <?php selected('custom', $value); ?>>任意の記事</option>
  </select>
  <?php
}

function render_custom_posts_selector($args) {
  $option_name = $args['option_name'];
  $post_type_option = $args['post_type_option'];
  $post_type_value = get_option($post_type_option, 'latest');
  $selected_posts = get_option($option_name, array());
  $section = $args['section'];
  
  // カスタム投稿選択エリア全体をdisplay:noneで制御
  $display_style = $post_type_value === 'custom' ? 'table-row' : 'none';
  ?>
  <tr class="custom-posts-field-row" id="custom_posts_<?php echo esc_attr($section); ?>" style="display: <?php echo $display_style; ?>;">
      <th scope="row">表示する記事を選択</th>
      <td>
          <div class="custom-posts-selector">
              <div class="posts-checkbox-list" style="max-height: 300px; overflow-y: auto; border: 1px solid #ddd; padding: 10px;">
                  <?php 
                  $query = new WP_Query(array(
                      'post_type' => 'post',
                      'posts_per_page' => -1,
                      'post_status' => 'publish',
                      'orderby' => 'date',
                      'order' => 'DESC',
                      'nopaging' => true,
                  ));

                  if ($query->have_posts()) :
                      while ($query->have_posts()) : $query->the_post();
                          $is_selected = in_array(get_the_ID(), (array)$selected_posts);
                          ?>
                          <div class="post-checkbox-item <?php echo $is_selected ? 'selected' : ''; ?>">
                              <label>
                                  <input type="checkbox" 
                                         name="<?php echo esc_attr($option_name); ?>[]" 
                                         value="<?php echo esc_attr(get_the_ID()); ?>"
                                         <?php checked($is_selected); ?>>
                                  <?php echo esc_html(get_the_title()); ?>
                                  <span class="post-date">（<?php echo get_the_date('Y-m-d'); ?>）</span>
                              </label>
                          </div>
                          <?php
                      endwhile;
                  endif;
                  wp_reset_postdata();
                  ?>
              </div>
              <p class="description">
                  記事数: <?php echo $query->found_posts; ?>件
              </p>
          </div>
      </td>
  </tr>

  <script>
  jQuery(document).ready(function($) {
      // 投稿タイプセレクターの変更イベント
      $('.post-type-selector[data-section="<?php echo esc_attr($section); ?>"]').on('change', function() {
          var selectedValue = $(this).val();
          var customPostsRow = $('#custom_posts_<?php echo esc_attr($section); ?>');
          
          if (selectedValue === 'custom') {
              customPostsRow.show();
          } else {
              customPostsRow.hide();
          }
      });

      // チェックボックスの状態変更時のハンドラ
      $('#custom_posts_<?php echo esc_attr($section); ?> .post-checkbox-item input[type="checkbox"]').on('change', function() {
          var $item = $(this).closest('.post-checkbox-item');
          if ($(this).is(':checked')) {
              $item.addClass('selected');
          } else {
              $item.removeClass('selected');
          }
      });
  });
  </script>
  <?php
}

function render_post_type_dropdown($args) {
  $option_name = $args['option_name'];
  $value = get_option($option_name, 'latest');
  ?>
  <select name="<?php echo esc_attr($option_name); ?>">
  <option value="latest" <?php selected('latest', $value); ?>>最新の記事</option>
  <option value="popular" <?php selected('popular', $value); ?>>人気の記事</option>
  </select>
  <?php
}

function render_image_field($args) {
  $option_name = $args['option_name'];
  $value = get_option($option_name, '');

  // 入力フィールドと選択・クリアボタンを表示
  ?>
  <div>
      <img id="image_preview_<?php echo esc_attr($option_name); ?>" src="<?php echo esc_url($value); ?>" style="max-width: 300px; max-height: 200px; <?php echo empty($value) ? 'display:none;' : ''; ?>" />
      <input type="button" class="button-secondary" value="画像を選択" id="upload_image_button_<?php echo esc_attr($option_name); ?>" />
      <input type="button" class="button-secondary" value="画像をクリア" id="clear_image_button_<?php echo esc_attr($option_name); ?>" style="margin-left: 5px;" />
      <input type="hidden" name="<?php echo esc_attr($option_name); ?>" id="<?php echo esc_attr($option_name); ?>" value="<?php echo esc_attr($value); ?>" />
  </div>
  <script>
  jQuery(document).ready(function($) {
      var custom_uploader;

      $('#upload_image_button_<?php echo esc_attr($option_name); ?>').click(function(e) {
          e.preventDefault();

          // If the uploader object has already been created, reopen the dialog
          if (custom_uploader) {
              custom_uploader.open();
              return;
          }

          // Extend the wp.media object
          custom_uploader = wp.media({
              title: '画像を選択',
              button: { text: '画像を選択' },
              multiple: false
          })
          .on('select', function() {
              var attachment = custom_uploader.state().get('selection').first().toJSON();
              $('#<?php echo esc_attr($option_name); ?>').val(attachment.url);
              $('#image_preview_<?php echo esc_attr($option_name); ?>').attr('src', attachment.url).show();
          })
          .open();
      });

      $('#clear_image_button_<?php echo esc_attr($option_name); ?>').click(function(e) {
          e.preventDefault();
          $('#<?php echo esc_attr($option_name); ?>').val('');
          $('#image_preview_<?php echo esc_attr($option_name); ?>').attr('src', '').hide();
      });
  });
  </script>
  <?php
}
  
function my_site_settings_page() {
  ?>
  <div class="wrap custom-setting">
      <h1>サイト設定</h1>
      <form method="post" action="options.php">
          <?php
          settings_fields('my_general_settings_group');
          do_settings_sections('general-settings');
          submit_button();
          ?>
      </form>
  </div>
  <?php
}

function my_top_page_settings_page() {
  ?>
  <div class="wrap custom-setting">
      <h1>TOPページ設定</h1>
      <form method="post" action="options.php">
          <?php
          settings_fields('my_top_page_settings_group');
          do_settings_sections('top-page-settings');
          submit_button();
          ?>
      </form>
  </div>
  <?php
}

// function my_footer_settings() {
  //  <div class="wrap custom-setting">
      //  <h1>フッター設定</h1>
      //  <form method="post" action="options.php">
          //  settings_fields('my_footer_settings_group');
          //  do_settings_sections('footer-settings');
          //  submit_button();
      //  </form>
  //  </div> 
// }

function my_single_settings() {
  ?>
  <div class="wrap custom-setting">
      <h1>記事ページ設定</h1>
      <form method="post" action="options.php">
          <?php
          settings_fields('my_single_settings_group');
          do_settings_sections('single-settings');
          submit_button();
          ?>
      </form>
  </div>
  <?php
}

function my_custom_settings_init() {
  // サイト設定
  register_setting('my_general_settings_group', 'my_general_option');
  add_settings_section('my_general_section', '全体設定セクション', null, 'general-settings');
  add_settings_field('my_general_section_setting_toggle', '', 'render_hidden_checkbox_field', 'general-settings', 'my_general_section', array('option_name' => 'my_general_section_setting_toggle'));
  add_settings_field('my_general_section_setting_site_logo', 'サイトロゴ設定', 'render_radio_field_for_choose_image_or_text', 'general-settings', 'my_general_section', array('option_name' => 'my_general_section_setting_site_logo'));
  add_settings_field('my_general_section_setting_site_logo_image', 'サイトロゴ画像（ヘッダー）', 'render_image_field', 'general-settings', 'my_general_section', array('option_name' => 'my_general_section_setting_site_logo_image'));
  add_settings_field('my_general_section_setting_site_logo_image2', 'サイトロゴ画像（フッター）', 'render_image_field', 'general-settings', 'my_general_section', array('option_name' => 'my_general_section_setting_site_logo_image2'));

  add_settings_field('my_general_section_site_color', 'サイトカラー', 'render_color_picker_field', 'general-settings', 'my_general_section', array('option_name' => 'my_general_section_site_color'));

  // TOPページ設定のセクションとフィールド

  // メインビジュアル
  add_settings_section('main_visual', 'メインビジュアル', '', 'top-page-settings');
  add_settings_field('main_visual_setting_toggle', '', 'render_hidden_checkbox_field', 'top-page-settings', 'main_visual', array('option_name' => 'main_visual_setting_toggle'));
  add_settings_field('main_visual_display_pc', 'メインビジュアルを表示（PC）', 'render_radio_field', 'top-page-settings', 'main_visual', array('option_name' => 'main_visual_display_pc'));
  add_settings_field('main_visual_display_sp', 'メインビジュアルを表示（SP）', 'render_radio_field', 'top-page-settings', 'main_visual', array('option_name' => 'main_visual_display_sp'));
  add_settings_field('main_visual_post_type', '表示する記事タイプ', 'render_post_type_selector', 'top-page-settings', 'main_visual', array(
    'option_name' => 'main_visual_post_type',
    'section' => 'main_visual'
));
add_settings_field('main_visual_custom_posts', '', 'render_custom_posts_selector', 'top-page-settings', 'main_visual', array(
    'option_name' => 'main_visual_custom_posts',
    'post_type_option' => 'main_visual_post_type',
    'section' => 'main_visual'
));
add_settings_field('main_visual_bg', '背景色', 'render_color_picker_field', 'top-page-settings', 'main_visual', array('option_name' => 'main_visual_bg'));
add_settings_field('main_visual_bg_image', '背景画像', 'render_image_field', 'top-page-settings', 'main_visual', array('option_name' => 'main_visual_bg_image'));
  
  // セクション1
  add_settings_section('section_1', '【セクション1】　' . get_option('section_1_main_title'), '', 'top-page-settings');
  add_settings_field('section_1_setting_toggle', '', 'render_hidden_checkbox_field', 'top-page-settings', 'section_1', array('option_name' => 'section_1_setting_toggle'));
  add_settings_field('section_1_display', 'セクションを表示', 'render_radio_field', 'top-page-settings', 'section_1', array('option_name' => 'section_1_display'));
  add_settings_field('section_1_main_title', 'メインタイトル', 'render_text_field', 'top-page-settings', 'section_1', array('option_name' => 'section_1_main_title'));
  // add_settings_field('section_1_sub_title', 'サブタイトル', 'render_text_field', 'top-page-settings', 'section_1', array('option_name' => 'section_1_sub_title'));
  add_settings_field('section_1_link_button_text', 'リンクボタンテキスト', 'render_text_field', 'top-page-settings', 'section_1', array('option_name' => 'section_1_link_button_text'));
  add_settings_field('section_1_post_type', '表示する記事タイプ', 'render_post_type_selector', 'top-page-settings', 'section_1', array(
    'option_name' => 'section_1_post_type',
    'section' => 'section_1'
));
add_settings_field('section_1_custom_posts', '', 'render_custom_posts_selector', 'top-page-settings', 'section_1', array(
    'option_name' => 'section_1_custom_posts',
    'post_type_option' => 'section_1_post_type',
    'section' => 'section_1'
));
add_settings_field('section_1_bg', '背景色', 'render_color_picker_field', 'top-page-settings', 'section_1', array('option_name' => 'section_1_bg'));
add_settings_field('section_1_bg_image', '背景画像', 'render_image_field', 'top-page-settings', 'section_1', array('option_name' => 'section_1_bg_image'));

  // セクション2
  add_settings_section('section_2', '【セクション2】　' . get_option('section_2_main_title'), '', 'top-page-settings');
  add_settings_field('section_2_setting_toggle', '', 'render_hidden_checkbox_field', 'top-page-settings', 'section_2', array('option_name' => 'section_2_setting_toggle'));
  add_settings_field('section_2_display', 'セクションを表示', 'render_radio_field', 'top-page-settings', 'section_2', array('option_name' => 'section_2_display'));
  // add_settings_field('section_2_image', '背景画像', 'render_image_field', 'top-page-settings', 'section_2', array('option_name' => 'section_2_image'));
  // add_settings_field('section_2_show_ad', '広告を表示', 'render_radio_field', 'top-page-settings', 'section_2', array('option_name' => 'section_2_show_ad'));
  add_settings_field('section_2_main_title', 'メインタイトル', 'render_text_field', 'top-page-settings', 'section_2', array('option_name' => 'section_2_main_title'));
  add_settings_field('section_2_link_button_text', 'リンクボタンテキスト', 'render_text_field', 'top-page-settings', 'section_2', array('option_name' => 'section_2_link_button_text'));
  // add_settings_field('section_2_sub_title', 'サブタイトル', 'render_text_field', 'top-page-settings', 'section_2', array('option_name' => 'section_2_sub_title'));
  add_settings_field('section_2_post_type', '表示する記事タイプ', 'render_post_type_selector', 'top-page-settings', 'section_2', array(
    'option_name' => 'section_2_post_type',
    'section' => 'section_2'
));
  add_settings_field('section_2_custom_posts', '', 'render_custom_posts_selector', 'top-page-settings', 'section_2', array(
    'option_name' => 'section_2_custom_posts',
    'post_type_option' => 'section_2_post_type',
    'section' => 'section_2'
));
add_settings_field('section_2_bg', '背景色', 'render_color_picker_field', 'top-page-settings', 'section_2', array('option_name' => 'section_2_bg'));
add_settings_field('section_2_bg_image', '背景画像', 'render_image_field', 'top-page-settings', 'section_2', array('option_name' => 'section_2_bg_image'));

    // セクション3
    add_settings_section('section_3', '【セクション3】　' . get_option('section_3_main_title'), '', 'top-page-settings');
  add_settings_field('section_3_setting_toggle', '', 'render_hidden_checkbox_field', 'top-page-settings', 'section_3', array('option_name' => 'section_3_setting_toggle'));
    add_settings_field('section_3_display', 'セクションを表示', 'render_radio_field', 'top-page-settings', 'section_3', array('option_name' => 'section_3_display'));
    add_settings_field('section_3_main_title', 'メインタイトル', 'render_text_field', 'top-page-settings', 'section_3', array('option_name' => 'section_3_main_title'));
    // add_settings_field('section_3_sub_title', 'サブタイトル', 'render_text_field', 'top-page-settings', 'section_3', array('option_name' => 'section_3_sub_title'));
    // add_settings_field('section_3_post_type', '表示する記事タイプ', 'render_post_type_dropdown', 'top-page-settings', 'section_3', array('option_name' => 'section_3_post_type'));

  // セクション4
  add_settings_section('section_4', '【セクション4】　' . get_option('section_4_main_title'), '', 'top-page-settings');
  add_settings_field('section_4_setting_toggle', '', 'render_hidden_checkbox_field', 'top-page-settings', 'section_4', array('option_name' => 'section_4_setting_toggle'));
  add_settings_field('section_4_display', 'セクションを表示', 'render_radio_field', 'top-page-settings', 'section_4', array('option_name' => 'section_4_display'));
  add_settings_field('section_4_main_title', 'メインタイトル', 'render_text_field', 'top-page-settings', 'section_4', array('option_name' => 'section_4_main_title'));
  add_settings_field('section_4_link_button_text', 'リンクボタンテキスト', 'render_text_field', 'top-page-settings', 'section_4', array('option_name' => 'section_4_link_button_text'));
  // add_settings_field('section_4_sub_title', 'サブタイトル', 'render_text_field', 'top-page-settings', 'section_4', array('option_name' => 'section_4_sub_title'));
  add_settings_field('section_4_post_type', '表示する記事タイプ', 'render_post_type_selector', 'top-page-settings', 'section_4', array(
    'option_name' => 'section_4_post_type',
    'section' => 'section_4'
));
  add_settings_field('section_4_custom_posts', '', 'render_custom_posts_selector', 'top-page-settings', 'section_4', array(
    'option_name' => 'section_4_custom_posts',
    'post_type_option' => 'section_4_post_type',
    'section' => 'section_4'
));
add_settings_field('section_4_bg', '背景色', 'render_color_picker_field', 'top-page-settings', 'section_4', array('option_name' => 'section_4_bg'));
add_settings_field('section_4_bg_image', '背景画像', 'render_image_field', 'top-page-settings', 'section_4', array('option_name' => 'section_4_bg_image'));

  // セクション5
  add_settings_section('section_5', '【セクション5】　' . get_option('section_5_main_title'), '', 'top-page-settings');
  add_settings_field('section_5_setting_toggle', '', 'render_hidden_checkbox_field', 'top-page-settings', 'section_5', array('option_name' => 'section_5_setting_toggle'));
  add_settings_field('section_5_display', 'セクションを表示', 'render_radio_field', 'top-page-settings', 'section_5', array('option_name' => 'section_5_display'));
  add_settings_field('section_5_main_title', 'メインタイトル', 'render_text_field', 'top-page-settings', 'section_5', array('option_name' => 'section_5_main_title'));
  // add_settings_field('section_5_sub_title', 'サブタイトル', 'render_text_field', 'top-page-settings', 'section_5', array('option_name' => 'section_5_sub_title'));
  add_settings_field('section_5_post_type', '表示する記事タイプ', 'render_post_type_selector', 'top-page-settings', 'section_5', array(
    'option_name' => 'section_5_post_type',
    'section' => 'section_5'
));
  add_settings_field('section_5_custom_posts', '', 'render_custom_posts_selector', 'top-page-settings', 'section_5', array(
    'option_name' => 'section_5_custom_posts',
    'post_type_option' => 'section_5_post_type',
    'section' => 'section_5'
));
add_settings_field('section_5_bg', '背景色', 'render_color_picker_field', 'top-page-settings', 'section_5', array('option_name' => 'section_5_bg'));
add_settings_field('section_5_bg_image', '背景画像', 'render_image_field', 'top-page-settings', 'section_5', array('option_name' => 'section_5_bg_image'));
  

  // セクション6
  // add_settings_section('section_6', get_option('section_6_main_title') ? get_option(('section_6_main_title')) : 'セクション6', '', 'top-page-settings');
  // add_settings_field('section_6_setting_toggle', '', 'render_hidden_checkbox_field', 'top-page-settings', 'section_6', array('option_name' => 'section_6_setting_toggle'));
  // add_settings_field('section_6_display', 'セクションを表示', 'render_radio_field', 'top-page-settings', 'section_6', array('option_name' => 'section_6_display'));
  // add_settings_field('section_6_main_title', 'メインタイトル', 'render_text_field', 'top-page-settings', 'section_6', array('option_name' => 'section_6_main_title'));
  // add_settings_field('section_6_sub_title', 'サブタイトル', 'render_text_field', 'top-page-settings', 'section_6', array('option_name' => 'section_6_sub_title'));
  // add_settings_field('section_6_post_type', '表示する記事タイプ', 'render_post_type_dropdown', 'top-page-settings', 'section_6', array('option_name' => 'section_6_post_type'));

  // フッター設定
  // add_settings_section('footer_section', 'フッター設定セクション', null, 'footer-settings');
  // add_settings_field('footer_setting_toggle', '', 'render_hidden_checkbox_field', 'footer-settings', 'footer_section', array('option_name' => 'footer_setting_toggle'));
  // add_settings_field('footer_left_onf', 'フッターメニュー1を表示', 'render_radio_field', 'footer-settings', 'footer_section', array('option_name' => 'footer_left_onf'));
  // add_settings_field('footer_left_title', 'フッターメニュー1 タイトル', 'render_text_field', 'footer-settings', 'footer_section', array('option_name' => 'footer_left_title'));
  // add_settings_field('footer_center_onf', 'フッターメニュー2を表示', 'render_radio_field', 'footer-settings', 'footer_section', array('option_name' => 'footer_center_onf'));
  // add_settings_field('footer_center_title', 'フッターメニュー2 タイトル', 'render_text_field', 'footer-settings', 'footer_section', array('option_name' => 'footer_center_title'));
  // add_settings_field('footer_right_onf', 'フッターメニュー3を表示', 'render_radio_field', 'footer-settings', 'footer_section', array('option_name' => 'footer_right_onf'));
  // add_settings_field('footer_right_title', 'フッターメニュー3 タイトル', 'render_text_field', 'footer-settings', 'footer_section', array('option_name' => 'footer_right_title'));

  add_settings_section('single_section', '記事ページ設定セクション', null, 'single-settings');
  add_settings_field('single_setting_toggle', '', 'render_hidden_checkbox_field', 'single-settings', 'single_section', array('option_name' => 'single_setting_toggle'));
  add_settings_field('single_thumbnail_onf', '記事ページアイキャッチを表示', 'render_radio_field', 'single-settings', 'single_section', array('option_name' => 'single_thumbnail_onf'));

  // 設定の登録
  register_setting('my_general_settings_group', 'my_general_section_setting_toggle');
  register_setting('my_general_settings_group', 'my_general_section_setting_site_logo');
  register_setting('my_general_settings_group', 'my_general_section_setting_site_logo_image');
  register_setting('my_general_settings_group', 'my_general_section_setting_site_logo_image2');
  register_setting('my_general_settings_group', 'my_general_section_site_color');

  register_setting('my_top_page_settings_group', 'main_visual_setting_toggle');
  register_setting('my_top_page_settings_group', 'main_visual_display_pc');
  register_setting('my_top_page_settings_group', 'main_visual_text_display_pc');
  register_setting('my_top_page_settings_group', 'main_visual_display_sp');
  register_setting('my_top_page_settings_group', 'main_visual_text_display_sp');
  register_setting('my_top_page_settings_group', 'main_visual_image');
  register_setting('my_top_page_settings_group', 'main_visual_post_type');
register_setting('my_top_page_settings_group', 'main_visual_custom_posts');
register_setting('my_top_page_settings_group', 'main_visual_bg');
register_setting('my_top_page_settings_group', 'main_visual_bg_image');

  register_setting('my_top_page_settings_group', 'section_1_setting_toggle');
  register_setting('my_top_page_settings_group', 'section_1_display');
  register_setting('my_top_page_settings_group', 'section_1_main_title');
  register_setting('my_top_page_settings_group', 'section_1_sub_title');
  register_setting('my_top_page_settings_group', 'section_1_link_button_text');
  register_setting('my_top_page_settings_group', 'section_1_post_type');
  register_setting('my_top_page_settings_group', 'section_1_custom_posts');
  register_setting('my_top_page_settings_group', 'section_1_bg');
register_setting('my_top_page_settings_group', 'section_1_bg_image');

  register_setting('my_top_page_settings_group', 'section_2_setting_toggle');
  register_setting('my_top_page_settings_group', 'section_2_display');
  register_setting('my_top_page_settings_group', 'section_2_image');
  register_setting('my_top_page_settings_group', 'section_2_show_ad');
  register_setting('my_top_page_settings_group', 'section_2_main_title');
  register_setting('my_top_page_settings_group', 'section_2_sub_title');
  register_setting('my_top_page_settings_group', 'section_2_link_button_text');
  register_setting('my_top_page_settings_group', 'section_2_post_type');
  register_setting('my_top_page_settings_group', 'section_2_custom_posts');
  register_setting('my_top_page_settings_group', 'section_2_bg');
  register_setting('my_top_page_settings_group', 'section_2_bg_image');

  register_setting('my_top_page_settings_group', 'section_3_setting_toggle');
  register_setting('my_top_page_settings_group', 'section_3_display');
  register_setting('my_top_page_settings_group', 'section_3_main_title');
  register_setting('my_top_page_settings_group', 'section_3_sub_title');
  register_setting('my_top_page_settings_group', 'section_3_post_type');

  register_setting('my_top_page_settings_group', 'section_4_setting_toggle');
  register_setting('my_top_page_settings_group', 'section_4_display');
  register_setting('my_top_page_settings_group', 'section_4_main_title');
  register_setting('my_top_page_settings_group', 'section_4_sub_title');
  register_setting('my_top_page_settings_group', 'section_4_link_button_text');
  register_setting('my_top_page_settings_group', 'section_4_post_type');
  register_setting('my_top_page_settings_group', 'section_4_custom_posts');
  register_setting('my_top_page_settings_group', 'section_4_bg');
  register_setting('my_top_page_settings_group', 'section_4_bg_image');

  register_setting('my_top_page_settings_group', 'section_5_setting_toggle');
  register_setting('my_top_page_settings_group', 'section_5_display');
  register_setting('my_top_page_settings_group', 'section_5_main_title');
  register_setting('my_top_page_settings_group', 'section_5_sub_title');
  register_setting('my_top_page_settings_group', 'section_5_post_type');
  register_setting('my_top_page_settings_group', 'section_5_custom_posts');
  register_setting('my_top_page_settings_group', 'section_5_bg');
  register_setting('my_top_page_settings_group', 'section_5_bg_image');

  // register_setting('my_top_page_settings_group', 'section_6_setting_toggle');
  // register_setting('my_top_page_settings_group', 'section_6_display');
  // register_setting('my_top_page_settings_group', 'section_6_main_title');
  // register_setting('my_top_page_settings_group', 'section_6_sub_title');
  // register_setting('my_top_page_settings_group', 'section_6_post_type');

  // footer setting
  // register_setting('my_footer_settings_group', 'footer_setting_toggle');
  // register_setting('my_footer_settings_group', 'footer_left_onf');
  // register_setting('my_footer_settings_group', 'footer_left_title');
  // register_setting('my_footer_settings_group', 'footer_center_onf');
  // register_setting('my_footer_settings_group', 'footer_center_title');
  // register_setting('my_footer_settings_group', 'footer_right_onf');
  // register_setting('my_footer_settings_group', 'footer_right_title');

  // single setting
  register_setting('my_single_settings_group', 'single_setting_toggle');
  register_setting('my_single_settings_group', 'single_thumbnail_onf');
}
add_action('admin_init', 'my_custom_settings_init');

function my_top_page_field_render() {
  $value = get_option('my_top_page_option', '');
  echo '<input type="text" name="my_top_page_option" value="' . esc_attr($value) . '" />';
}

function my_footer_field_render() {
  $value = get_option('my_footer_option', '');
  echo '<input type="text" name="my_footer_option" value="' . esc_attr($value) . '" />';
}

function my_custom_admin_notice() {
  if (isset($_GET['settings-updated']) && $_GET['settings-updated']) {
      ?>
      <div class="notice notice-success is-dismissible">
          <p><?php _e('設定が保存されました。', 'text-domain'); ?></p>
      </div>
      <?php
  }
  if (isset($_GET['error']) && $_GET['error']) {
      ?>
      <div class="notice notice-error is-dismissible">
          <p><?php _e('エラーが発生しました。', 'text-domain'); ?></p>
      </div>
      <?php
  }
}
add_action('admin_notices', 'my_custom_admin_notice');