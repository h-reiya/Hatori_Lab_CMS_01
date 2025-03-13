<section class="post-list-section">
    <div class="post-list-inner">
        <div class="post-list-l">
            <div class="post-list-l-inner">
                <?php
                $paged = get_query_var('paged') ? get_query_var('paged') : 1;
                $args = array(
                    'post_type' => 'post',
                    'posts_per_page' => 8,
                    'paged' => $paged,
                    'orderby' => 'date',
                    'order' => 'DESC',
                    'ignore_sticky_posts' => 1,
                    'post__not_in' => get_option('sticky_posts'),
                );
                $query = new WP_Query($args);

                if ($query->have_posts()):
                    foreach ($query->posts as $post):
                        setup_postdata($post);
                        ?>
                        <article>
                            <div class="post-item">
                                <div class="post-image img-wrap">
                                    <a href="<?php echo get_the_permalink(); ?>">
                                        <?php the_post_thumbnail_or_noimage(); ?>
                                    </a>
                                </div>
                                <div class="post-content">
                                    <p class="post-title"><?php echo get_the_title(); ?></p>
                                    <p class="post-date"><time><?php echo get_the_date(); ?></time></p>
                                </div>
                            </div>
                        </article>
                        <?php
                    endforeach;
                else:
                    echo '<p>No posts found.</p>';
                endif;

                wp_reset_postdata();
                ?>
            </div>

            <?php if (function_exists('wp_pagenavi')): ?>
                <div class="pagination">
                    <?php wp_pagenavi(array('query' => $query)); ?>
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
