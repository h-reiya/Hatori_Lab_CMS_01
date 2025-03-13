<?php
$tag_id = get_queried_object_id();
$tag = get_tag($tag_id);
$tag_name = $tag->name;
$tag_description = $tag->description;
$image_id = get_term_meta($tag_id, 'tag_image', true);
$image_url = $image_id ? wp_get_attachment_image_url($image_id, 'full') : '';
?>
<section id="main-eya-catch-area" class="main-eya-catch-area <?php if (!$image_url): ?>no-eye-catch<?php endif ?>">
  <div class="main-eye-catch-area-content">
    <h2 class="main-title"><?php echo esc_html($tag_name); ?></h2>
    <p class="main-description"><?php echo esc_html($tag_description); ?></p>
  </div>
  <?php if ($image_url): ?>
    <div class="main-eye-catch" style="background-image: url(<?php echo $image_url; ?>);"></div>
  <?php endif; ?>
  <div class="main-eye-catch-cover"></div>
</section>

<section class="post-list-section">
  <div class="post-list-inner">
    <div class="post-list-l">
      <div class="post-list-l-inner">
        <?php
        $paged = get_query_var('paged') ? get_query_var('paged') : 1;
        $args = array(
          'tag_id' => $tag_id,
          'posts_per_page' => 8,
          'paged' => $paged,
          'orderby' => 'date',
          'order' => 'DESC',
        );
        $query = new WP_Query($args);
        ?>
        <?php if ($query->have_posts()): foreach ($query->posts as $post): setup_postdata($post); ?>
            <article>
              <div class="post-item">
                <div class="ranking"></div>
                <div class="post-image img-wrap">
                  <a href="<?php echo get_the_permalink(); ?>">
                    <?php the_post_thumbnail_or_noimage(); ?>
                  </a>
                </div>
                <div class="post-content">
                  <?php
                  $categories = get_the_category();
                  ?>
                  <?php if (!empty($categories)): ?>
                    <div class="post-categories">
                      <?php foreach ($categories as $index => $category): ?>
                        <?php
                        $category_link = get_category_link($category->term_id);
                        $category_name = $category->name;
                        ?>
                        <?php if ($index < 2): ?>
                          <a class="post-category-link" href="<?php echo $category_link; ?>">
                            <p class="post-cat"><?php echo $category_name; ?></p>
                          </a>
                        <?php endif; ?>
                      <?php endforeach; ?>
                    </div>
                  <?php endif; ?>
                  <p class="post-title"><?php echo get_the_title(); ?></p>
                  <p class="post-date"><time><?php echo get_the_date(); ?></time></p>
                </div>
              </div>
            </article>
        <?php endforeach;
        endif; ?>
      </div>

      <?php if (function_exists('wp_pagenavi')): ?>
        <div class="pagination">
          <?php wp_pagenavi(); ?>
        </div>
      <?php endif; ?>
    </div>

    <?php
    $my_general_section_site_color = get_option('my_general_section_site_color');
    ?>
    <div class="post-list-r">
      <div class="post-list-r-inner">
        <ul style="border-color: <?php echo $my_general_section_site_color; ?>;">
          <?php get_sidebar(); ?>
        </ul>
      </div>
    </div>
  </div>
</section>

<?php wp_reset_postdata(); ?>