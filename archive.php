<?php get_header(); ?>

<div id="archive-page" class="container-wrapper">
  <div class="breadcrumb">
    <div class="breadcrumb-inner">
      <?php
      if (function_exists('bcn_display')) {
        bcn_display();
      }
      ?>
    </div>
  </div>

  <?php if (is_category()): ?>
    <?php get_template_part('components/category/category'); ?>
  <?php elseif (is_tag()): ?>
    <?php get_template_part('components/tag/tag'); ?>
  <?php elseif (is_search()): ?>
    <?php get_template_part('components/search/search'); ?>
  <?php endif; ?>

</div>

<?php get_footer(); ?>