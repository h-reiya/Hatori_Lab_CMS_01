<?php get_header(); ?>

<?php if (!is_user_logged_in() && !is_bot()) {
  set_post_views(get_the_ID());
} ?>

<div id="single-page" class="container-wrapper">
  <div class="container-inner">

    <?php if (have_posts()): the_post(); ?>
      <div class="breadcrumb">
        <div class="breadcrumb-inner">
          <?php
          if (function_exists('bcn_display')) {
            bcn_display();
          }
          ?>
        </div>
      </div>

      <article>
        <?php
        $single_thumbnail_onf = get_option('single_thumbnail_onf');
        ?>
        <div class="content">
          <div class="content-left">
            <div class="content-header">
              <?php if (has_category()): ?>
                <div class="category">
                  <?php echo get_the_category_list(' '); ?>
                </div>
              <?php endif; ?>
              <p class="author"><?php the_author(); ?></p>
              <h2 class="title"><?php the_title(); ?></h2>
              <div class="share-button-list">
                <ul>
                  <li>
                    <a class="x-button-link" href="https://x.com/share?url=<?php echo get_the_permalink(); ?>&amp;text=<?php echo get_the_title(); ?>" rel="nofollow" target="_blank"></a>
                  </li>
                  <li>
                    <a class="facebook-button-link" href="https://www.facebook.com/share.php?u=<?php echo get_the_permalink(); ?>" rel="nofollow noopener" target="_blank"></a>
                  </li>
                  <li>
                    <a class="line-button-link" href="http://line.me/R/msg/text/?<?php echo get_the_permalink(); ?>%0a<?php echo get_the_title(); ?>" target="_blank" rel="nofollow noopener"></a>
                  </li>
                </ul>
              </div>
              <time class="date"><?php echo get_the_date(); ?></time>
            </div>

            <div class="content-inner">
              <?php if ($single_thumbnail_onf): ?>
                <?php if (has_post_thumbnail()): ?>
                  <div class="eye-catch">
                    <div class="eye-catch-inner img-wrap">
                      <?php the_post_thumbnail('full'); ?>
                    </div>
                  </div>
                <?php endif; ?>
              <?php endif; ?>
              <?php the_content(); ?>
            </div>
          </div>

          <div class="content-right">
            <div class="content-sidebar">
              <ul>
                <?php get_sidebar(); ?>
              </ul>
            </div>
          </div>

        </div>
      </article>
    <?php endif; ?>
  </div>
</div>

<?php get_footer(); ?>