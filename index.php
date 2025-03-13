<?php get_header(); ?>
<div id="top-page" class="container-wrapper">
  <?php
  $main_visual_bg = get_option('main_visual_bg');
  $main_visual_bg_image = get_option('main_visual_bg_image');
  $main_visual_display_sp = get_option('main_visual_display_sp');
  $main_visual_display_pc = get_option('main_visual_display_pc');
  $mv_is_display = wp_is_mobile() ? $main_visual_display_sp : $main_visual_display_pc;
  ?>
  <?php if ($mv_is_display): ?>
    <section class="section-pattern-mv">
      <div class="section-inner-mv" style="background-color: <?php echo $main_visual_bg; ?>; background-image: url('<?php echo $main_visual_bg_image; ?>'); background-position: center; background-repeat: no-repeat; background-size: cover;">
        <div class="post-list">
          <ul>
            <?php
            $main_visual_post_type = get_option('main_visual_post_type', 'latest');
            $posts_per_page = 5; // メインビジュアルの表示件数

            // 記事タイプに応じてクエリ引数を設定
            $args = array(
              'post_type' => 'post',
              'post_status' => 'publish',
              'posts_per_page' => $posts_per_page,
              'ignore_sticky_posts' => 1, // 固定記事を無視
              'post__not_in' => get_option('sticky_posts'), // 固定記事を除外
            );

            // 記事タイプに応じてクエリを調整
            switch ($main_visual_post_type) {
              case 'latest':
                $args['orderby'] = 'date';
                $args['order'] = 'DESC';
                break;

              case 'popular':
                $args['meta_key'] = 'post_views_count';
                $args['orderby'] = 'meta_value_num';
                $args['order'] = 'DESC';
                break;

              case 'custom':
                $selected_posts = get_option('main_visual_custom_posts', array());
                if (!empty($selected_posts)) {
                  // 選択された記事から固定記事を除外
                  $sticky_posts = get_option('sticky_posts');
                  $filtered_posts = array_diff($selected_posts, $sticky_posts);

                  if (!empty($filtered_posts)) {
                    $args['post__in'] = $filtered_posts;
                    $args['orderby'] = 'post__in';
                    $args['posts_per_page'] = -1;
                  }
                }
                break;
            }

            $query = new WP_Query($args);
            if ($query->have_posts()) :
              while ($query->have_posts()) : $query->the_post();
            ?>
                <li>
                  <div class="post-wrap">
                    <div class="post-image">
                      <a href="<?php echo esc_url(get_the_permalink()); ?>">
                        <?php the_post_thumbnail_or_noimage(); ?>
                      </a>
                    </div>
                    <?php
                    $categories = get_the_category();
                    if (!empty($categories)):
                    ?>
                      <div class="post-info">
                        <?php foreach ($categories as $category): ?>
                          <?php
                          $category_link = get_category_link($category->term_id);
                          $category_name = $category->name;
                          ?>
                          <div class="post-category">
                            <a class="post-category-link" href="<?php echo esc_url($category_link); ?>">
                              <p class="post-cat"><?php echo esc_html($category_name); ?></p>
                            </a>
                          </div>
                        <?php endforeach; ?>
                      </div>
                    <?php endif; ?>
                    <div class="post-content">
                      <a href="<?php echo esc_url(get_the_permalink()); ?>">
                        <p class="post-title"><?php echo esc_html(get_the_title()); ?></p>
                      </a>
                      <div class="post-date">
                        <time><?php echo get_the_date(); ?></time>
                      </div>
                    </div>
                  </div>
                </li>
            <?php
              endwhile;
            endif;
            wp_reset_postdata();
            ?>
          </ul>
        </div>
      </div>
    </section>
  <?php endif; ?>

  <?php
  $section_1_bg = get_option('section_1_bg');
  $section_1_bg_image = get_option('section_1_bg_image');
  $section_1_display = get_option('section_1_display');
  ?>
  <?php if ($section_1_display): ?>
    <section class="section-pattern-a section-area" style="background-color: <?php echo $section_1_bg; ?>; background-image: url('<?php echo $section_1_bg_image; ?>'); background-position: center; background-repeat: no-repeat; background-size: cover;">
      <div class="section-inner">
        <?php
        $section_1_main_title = get_option('section_1_main_title');
        $section_1_post_type = get_option('section_1_post_type', 'latest');
        $posts_per_page = wp_is_mobile() ? 9 : 6;

        // 記事タイプに応じてクエリ引数を設定
        $args = array(
          'post_type' => 'post',
          'post_status' => 'publish',
          'posts_per_page' => $posts_per_page,
          'ignore_sticky_posts' => 1, // 固定記事を無視
          'post__not_in' => get_option('sticky_posts'), // 固定記事を除外
        );

        // 記事タイプに応じてクエリを調整
        switch ($section_1_post_type) {
          case 'latest':
            $args['orderby'] = 'date';
            $args['order'] = 'DESC';
            break;

          case 'popular':
            // 人気記事の場合（PV数などのカスタムフィールドを使用する場合）
            $args['meta_key'] = 'post_views_count'; // PV数を保存するカスタムフィールド名
            $args['orderby'] = 'meta_value_num';
            $args['order'] = 'DESC';
            break;

          case 'custom':
            // 任意の記事の場合
            $selected_posts = get_option('section_1_custom_posts', array());
            if (!empty($selected_posts)) {
              $args['post__in'] = $selected_posts;
              $args['orderby'] = 'post__in'; // 選択された順序を維持
              $args['posts_per_page'] = -1; // 選択された記事をすべて表示
            }
            break;
        }
        ?>
        <div class="section-title">
          <h2><?php echo esc_html($section_1_main_title); ?></h2>
        </div>

        <div class="post-list">
          <ul>
            <?php
            $query = new WP_Query($args);
            if ($query->have_posts()) :
              while ($query->have_posts()) : $query->the_post();
            ?>
                <li>
                  <div class="post-wrap">
                    <div class="post-image">
                      <a href="<?php echo esc_url(get_the_permalink()); ?>">
                        <?php the_post_thumbnail_or_noimage(); ?>
                      </a>
                    </div>
                    <?php
                    $categories = get_the_category();
                    if (!empty($categories)):
                    ?>
                      <div class="post-info">
                        <?php foreach ($categories as $category): ?>
                          <?php
                          $category_link = get_category_link($category->term_id);
                          $category_name = $category->name;
                          ?>
                          <div class="post-category">
                            <a class="post-category-link" href="<?php echo esc_url($category_link); ?>">
                              <p class="post-cat"><?php echo esc_html($category_name); ?></p>
                            </a>
                          </div>
                        <?php endforeach; ?>
                        <div class="post-date">
                          <time><?php echo get_the_date(); ?></time>
                        </div>
                      </div>
                    <?php endif; ?>
                    <div class="post-content">
                      <a href="<?php echo esc_url(get_the_permalink()); ?>">
                        <p class="post-title"><?php echo esc_html(get_the_title()); ?></p>
                      </a>
                    </div>
                  </div>
                </li>
            <?php
              endwhile;
            endif;
            wp_reset_postdata();
            ?>
          </ul>
        </div>

        <?php
        $section_1_link_button_text = get_option("section_1_link_button_text");
        switch ($section_1_post_type) {
          case 'latest':
            $section_1_link_button_link = '/recent-posts';
            break;

          case 'popular':
            $section_1_link_button_link = 'popular-posts';
            break;

          case 'custom':
            $section_1_link_button_link = '';
            break;
        }
        ?>
        <?php if ($section_1_link_button_text && $section_1_post_type !== 'custom'): ?>
          <div class="post-more-button">
            <div class="button-wrap">
              <a href="<?php echo esc_url(home_url($section_1_link_button_link)); ?>"><?php echo $section_1_link_button_text; ?></a>
            </div>
          </div>
        <?php endif; ?>
      </div>
    </section>
  <?php endif; ?>

  <?php
  $section_2_bg = get_option('section_2_bg');
  $section_2_bg_image = get_option('section_2_bg_image');
  $section_2_display = get_option('section_2_display');
  ?>
  <?php if ($section_2_display): ?>
    <section class="section-pattern-b section-area" style="background-color: <?php echo $section_2_bg; ?>; background-image: url('<?php echo $section_2_bg_image; ?>'); background-position: center; background-repeat: no-repeat; background-size: cover;">
      <div class="section-inner">
        <?php
        $section_2_main_title = get_option('section_2_main_title');
        $section_2_post_type = get_option('section_2_post_type', 'latest');
        $posts_per_page = 6;

        // 記事タイプに応じてクエリ引数を設定
        $args = array(
          'post_type' => 'post',
          'post_status' => 'publish',
          'posts_per_page' => $posts_per_page,
          'ignore_sticky_posts' => 1,
          'post__not_in' => get_option('sticky_posts'),
        );

        // 記事タイプに応じてクエリを調整
        switch ($section_2_post_type) {
          case 'latest':
            $args['orderby'] = 'date';
            $args['order'] = 'DESC';
            break;

          case 'popular':
            $args['meta_key'] = 'post_views_count';
            $args['orderby'] = 'meta_value_num';
            $args['order'] = 'DESC';
            break;

          case 'custom':
            $selected_posts = get_option('section_2_custom_posts', array());
            if (!empty($selected_posts)) {
              // 選択された記事から固定記事を除外
              $sticky_posts = get_option('sticky_posts');
              $filtered_posts = array_diff($selected_posts, $sticky_posts);

              if (!empty($filtered_posts)) {
                $args['post__in'] = $filtered_posts;
                $args['orderby'] = 'post__in';
                $args['posts_per_page'] = -1;
              }
            }
            break;
        }
        ?>
        <div class="section-title">
          <h2><?php echo esc_html($section_2_main_title); ?></h2>
        </div>

        <div class="post-list">
          <ul>
            <?php
            $query = new WP_Query($args);
            if ($query->have_posts()) :
              while ($query->have_posts()) : $query->the_post();
            ?>
                <li>
                  <?php if (wp_is_mobile()): ?>
                    <div class="post-wrap">
                      <div class="post-image">
                        <a href="<?php echo esc_url(get_the_permalink()); ?>">
                          <?php the_post_thumbnail_or_noimage(); ?>
                        </a>
                      </div>
                      <?php
                      $categories = get_the_category();
                      if (!empty($categories)):
                      ?>
                        <div class="post-info">
                          <?php
                          $category = $categories[0];  // 最初のカテゴリを取得
                          $category_link = get_category_link($category->term_id);
                          $category_name = $category->name;
                          ?>
                          <div class="post-category">
                            <a class="post-category-link" href="<?php echo esc_url($category_link); ?>">
                              <p class="post-cat"><?php echo esc_html($category_name); ?></p>
                            </a>
                          </div>
                          <div class="post-date">
                            <time><?php echo get_the_date(); ?></time>
                          </div>
                        </div>
                      <?php endif; ?>
                      <div class="post-content">
                        <a href="<?php echo esc_url(get_the_permalink()); ?>">
                          <p class="post-title"><?php echo esc_html(get_the_title()); ?></p>
                        </a>
                      </div>
                    </div>
                  <?php else: ?>
                    <div class="post-wrap">
                      <div class="post-image">
                        <a href="<?php echo esc_url(get_the_permalink()); ?>">
                          <?php the_post_thumbnail_or_noimage(); ?>
                        </a>
                      </div>
                      <div class="post-content">
                        <a href="<?php echo esc_url(get_the_permalink()); ?>">
                          <p class="post-title"><?php echo esc_html(get_the_title()); ?></p>
                          <div class="post-date">
                            <time><?php echo get_the_date(); ?></time>
                          </div>
                        </a>
                      </div>
                    </div>
                  <?php endif; ?>
                </li>
            <?php
              endwhile;
            endif;
            wp_reset_postdata();
            ?>
          </ul>
        </div>

        <?php
        $section_2_link_button_text = get_option("section_2_link_button_text");
        switch ($section_2_post_type) {
          case 'latest':
            $section_2_link_button_link = '/recent-posts';
            break;

          case 'popular':
            $section_2_link_button_link = 'popular-posts';
            break;

          case 'custom':
            $section_2_link_button_link = '';
            break;
        }
        ?>
        <?php if ($section_2_link_button_text && $section_2_post_type !== 'custom'): ?>
          <div class="post-more-button">
            <div class="button-wrap">
              <a href="<?php echo esc_url(home_url($section_2_link_button_link)); ?>"><?php echo $section_2_link_button_text; ?></a>
            </div>
          </div>
        <?php endif; ?>
      </div>
    </section>
  <?php endif; ?>

  <?php
  $section_3_display = get_option('section_3_display');
  ?>
  <?php if ($section_3_display): ?>
    <section class="section-pattern-tags section-area">
      <div class="section-inner">
        <?php
        $section_3_main_title = get_option('section_3_main_title');
        ?>
        <div class="section-title">
          <h2><?php echo $section_3_main_title; ?></h2>
        </div>

        <div class="post-list">
          <ul>
            <?php
            $taxonomy = "post_tag";
            $args = array(
              'hide_empty' => false,
            );
            $terms = get_terms($taxonomy, $args);
            ?>
            <?php if (!empty($terms)): foreach ($terms as $term): ?>
                <?php
                $tag_link = esc_url(get_term_link($term));
                $tag_name = esc_html($term->name);
                ?>
                <li class="section-list-item">
                  <a class="section-list-link" href="<?php echo $tag_link; ?>"><?php echo $tag_name; ?></a>
                </li>
                <? endforeach;
            endif; ?>
          </ul>
        </div>
      </div>
    </section>
  <?php endif; ?>

  <?php
  $section_4_bg = get_option('section_4_bg');
  $section_4_bg_image = get_option('section_4_bg_image');
  $section_4_display = get_option('section_4_display');
  ?>
  <?php if ($section_4_display): ?>
    <section class="section-pattern-c section-area" style="background-color: <?php echo $section_4_bg; ?>; background-image: url('<?php echo $section_4_bg_image; ?>'); background-position: center; background-repeat: no-repeat; background-size: cover;">
      <div class="section-inner">
        <?php
        $section_4_main_title = get_option('section_4_main_title');
        $section_4_post_type = get_option('section_4_post_type', 'latest');
        $posts_per_page = 16;

        // 記事タイプに応じてクエリ引数を設定
        $args = array(
          'post_type' => 'post',
          'post_status' => 'publish',
          'posts_per_page' => $posts_per_page,
          'ignore_sticky_posts' => 1,
          'post__not_in' => get_option('sticky_posts'),
          'offset' => 6 // 最初の6件をスキップ
        );

        // 記事タイプに応じてクエリを調整
        switch ($section_4_post_type) {
          case 'latest':
            $args['orderby'] = 'date';
            $args['order'] = 'DESC';
            break;

          case 'popular':
            $args['meta_key'] = 'post_views_count';
            $args['orderby'] = 'meta_value_num';
            $args['order'] = 'DESC';
            unset($args['offset']);
            break;

          case 'custom':
            $selected_posts = get_option('section_4_custom_posts', array());
            if (!empty($selected_posts)) {
              $args['post__in'] = $selected_posts;
              $args['orderby'] = 'post__in';
              $args['posts_per_page'] = -1;
              unset($args['offset']); // カスタム投稿の場合はoffsetを削除
            }
            break;
        }
        ?>
        <div class="section-title">
          <h2><?php echo esc_html($section_4_main_title); ?></h2>
        </div>

        <div class="post-list">
          <ul>
            <?php
            $query = new WP_Query($args);
            if ($query->have_posts()) :
              while ($query->have_posts()) : $query->the_post();
            ?>
                <li>
                  <div class="post-wrap">
                    <div class="post-image">
                      <a href="<?php echo esc_url(get_the_permalink()); ?>">
                        <?php the_post_thumbnail_or_noimage(); ?>
                      </a>
                    </div>
                    <?php
                    $categories = get_the_category();
                    if (!empty($categories)) {
                      // $category = $categories:reference[referenceText0] ;
                      $category = $categories[0];
                    ?>
                      <div class="post-info">
                        <div class="post-category">
                          <a class="post-category-link" href="<?php echo esc_url(get_category_link($category->term_id)); ?>">
                            <p class="post-cat"><?php echo esc_html($category->name); ?></p>
                          </a>
                        </div>
                        <div class="post-date">
                          <time><?php echo get_the_date(); ?></time>
                        </div>
                      </div>
                    <?php } ?>
                    <div class="post-content">
                      <a href="<?php echo esc_url(get_the_permalink()); ?>">
                        <p class="post-title"><?php echo esc_html(get_the_title()); ?></p>
                      </a>
                    </div>
                  </div>
                </li>
            <?php
              endwhile;
            endif;
            wp_reset_postdata();
            ?>
          </ul>
        </div>

        <?php
        $section_4_link_button_text = get_option("section_4_link_button_text");
        switch ($section_4_post_type) {
          case 'latest':
            $section_4_link_button_link = '/recent-posts';
            break;

          case 'popular':
            $section_4_link_button_link = 'popular-posts';
            break;

          case 'custom':
            $section_4_link_button_link = '';
            break;
        }
        ?>
        <?php if ($section_4_link_button_text && $section_4_post_type !== 'custom'): ?>
          <div class="post-more-button">
            <div class="button-wrap">
              <a href="<?php echo esc_url(home_url($section_4_link_button_link)); ?>"><?php echo $section_4_link_button_text; ?></a>
            </div>
          </div>
        <?php endif; ?>
      </div>
    </section>
  <?php endif; ?>

  <?php
  $section_5_bg = get_option('section_5_bg');
  $section_5_bg_image = get_option('section_5_bg_image');
  $section_5_display = get_option('section_5_display');
  ?>
  <?php if ($section_5_display): ?>
    <section class="section-pattern-d section-area" style="background-color: <?php echo $section_5_bg; ?>; background-image: url('<?php echo $section_5_bg_image; ?>'); background-position: center; background-repeat: no-repeat; background-size: cover;">
      <div class="section-inner">
        <?php
        $section_5_main_title = get_option('section_5_main_title');
        $section_5_post_type = get_option('section_5_post_type', 'latest');
        $posts_per_page = 6;

        // 記事タイプに応じてクエリ引数を設定
        $args = array(
          'post_type' => 'post',
          'post_status' => 'publish',
          'posts_per_page' => $posts_per_page,
          'ignore_sticky_posts' => 1,
          'post__not_in' => get_option('sticky_posts'),
        );

        // 記事タイプに応じてクエリを調整
        switch ($section_5_post_type) {
          case 'latest':
            $args['orderby'] = 'date';
            $args['order'] = 'DESC';
            break;

          case 'popular':
            $args['meta_key'] = 'post_views_count';
            $args['orderby'] = 'meta_value_num';
            $args['order'] = 'DESC';
            break;

          case 'custom':
            $selected_posts = get_option('section_5_custom_posts', array());
            if (!empty($selected_posts)) {
              // 選択された記事から固定記事を除外
              $sticky_posts = get_option('sticky_posts');
              $filtered_posts = array_diff($selected_posts, $sticky_posts);

              if (!empty($filtered_posts)) {
                $args['post__in'] = $filtered_posts;
                $args['orderby'] = 'post__in';
                $args['posts_per_page'] = -1;
              }
            }
            break;
        }
        ?>
        <div class="section-title">
          <h2><?php echo esc_html($section_5_main_title); ?></h2>
        </div>

        <div class="post-list swipe-section swiper">
          <ul class="swiper-wrapper">
            <?php
            $query = new WP_Query($args);
            if ($query->have_posts()) :
              while ($query->have_posts()) : $query->the_post();
            ?>
                <li class="swiper-slide">
                  <div class="post-wrap">
                    <a href="<?php echo esc_url(get_the_permalink()); ?>">
                      <div class="post-image">
                        <?php the_post_thumbnail_or_noimage(); ?>
                      </div>
                      <?php
                      $content = get_the_content();
                      $content = wp_strip_all_tags($content);
                      $content = wp_trim_words($content, 240, '...');
                      ?>
                      <div class="post-content">
                        <p class="post-title"><?php echo esc_html(get_the_title()); ?></p>
                        <p class="post-text"><?php echo esc_html($content); ?></p>
                        <span class="read-more">もっと見る</span>
                      </div>
                    </a>
                  </div>
                </li>
            <?php
              endwhile;
            endif;
            wp_reset_postdata();
            ?>
          </ul>
        </div>

      </div>
    </section>
  <?php endif; ?>
</div>

<div id="loading-overlay">
  <div class="loading-indicator">
    <div class="loading-line line-top-left"></div>
    <div class="loading-line line-top-right"></div>
    <div class="loading-line line-bottom-right"></div>
    <div class="loading-line line-bottom-left"></div>
    <div class="loading-text-wrap">
      <span class="loading-text">loading</span>
      <span class="loading-dots first blinking"></span>
      <span class="loading-dots second blinking"></span>
      <span class="loading-dots third blinking"></span>
    </div>
  </div>
</div>

<?php get_footer(); ?>