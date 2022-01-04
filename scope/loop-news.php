<div class="news-wrap grid-m-3">
<?php
  $posts_per_page = 3;

  $news_args = [
    'post_type'       => 'post',
    'posts_per_page'  => $posts_per_page,
    'orderby'         => 'date',
    'order'           => 'DESC',
    'ignore_sticky_posts' => true
  ];
  $news = new WP_Query( $news_args );
  while ( $news->have_posts() ) : $news->the_post();
    ?>
    <article class="news">
      <a href="<?php the_permalink(); ?>">
        <?php
        if ( get_field('news_image') ) {
          $feature = get_field('news_image');
          the_img_set($feature, 'default', '33', ['source' => 'attachment']);
        } else {
          the_img_set(FALLBACK, 'default', '33');
        }
        $title = get_field('news_title');
        ?>
        <div class="title">
          <?php the_el($title, 'news-head', '', 'h3'); ?>
        </div>
      </a>
    </article>
  <?php endwhile;
  wp_reset_query();
  ?>
</div>
