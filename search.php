<?php get_header(); ?>

<div id="main-eya-catch-area" class="main-eya-catch-area">
  <div class="main-eye-catch-area-content">
    <h2 class="main-title">
      <p><?php esc_html_e('Search Results'); ?></p>
      <p>"<?php echo get_search_query(); ?>"</p>
    </h2>
    <div class="main-eye-catch-cover"></div>
  </div>
</div>

<section class="post-list-section">
  <div class="post-list-inner">
    <div class="post-list-l">
      <div class="post-list-l-inner">
        <?php
          $args = array(
            's' => get_search_query(), 
            'posts_per_page' => -1,    
          );
          $posts = get_posts($args);
        ?>
        <?php if ($posts): foreach ($posts as $post) : setup_postdata($post); ?>
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
                <?php if(!empty($categories)): ?>
                  <div class="post-categories">
                    <?php foreach ($categories as $index => $category): ?>
                    <?php
                      $category_link = get_category_link($category->term_id);
                      $category_name = $category->name;
                    ?>
                    <?php if ($index < 2): ?>
                    <a class="post-category-link" href="<?php echo esc_url($category_link); ?>">
                      <p class="post-cat"><?php echo esc_html($category_name); ?></p>
                    </a>
                    <?php endif; ?>
                    <?php endforeach; ?>
                  </div>
                <?php endif; ?>
                <p class="post-title"><?php echo esc_html(get_the_title()); ?></p>
                <p class="post-date"><time><?php echo esc_html(get_the_date()); ?></time></p>
              </div>
            </div>
          </article>
        <?php endforeach; wp_reset_postdata(); ?>
        <?php else: ?>
          <p><?php esc_html_e('No results found for your search.'); ?></p>
        <?php endif; ?>
      </div>

      <?php if(function_exists('wp_pagenavi')): ?>
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

<?php get_footer(); ?>