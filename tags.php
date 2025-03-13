<?php
/* Template Name: tags */
?>

<?php get_header(); ?>

<div id="tags-page" class="container-wrapper">

  <?php
  $section_3_main_title = get_option('section_3_main_title');
  ?>
  <section class="tags-section">
    <div class="section-inner">
      <div class="section-header">
        <h2>
          <span class="main-title"><?php echo $section_3_main_title; ?></span>
        </h2>
      </div>

      <div class="section-content-lists">
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

</div>

<?php get_footer(); ?>