<?php get_header(); ?>

<div id="page" class="container-wrapper">
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
        <div class="content">
          <div class="content-header">
            <?php if (has_category()): ?>
              <div class="category">
                <?php echo get_the_category_list(' '); ?>
              </div>
            <?php endif; ?>
            <h2 class="title"><?php the_title(); ?></h2>
            <time class="date"><?php echo get_the_date(); ?></time>
          </div>

          <div class="content-inner">
            <?php if (has_post_thumbnail()): ?>
              <div class="eye-catch">
                <div class="eye-catch-inner img-wrap">
                  <?php the_post_thumbnail('full'); ?>
                </div>
              </div>
            <?php endif; ?>
            <?php the_content(); ?>
          </div>
        </div>
      </article>
    <?php endif; ?>
  </div>
</div>

<?php get_footer(); ?>