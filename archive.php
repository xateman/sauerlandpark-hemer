<?php
  get_header();
  global $wp_query;

  $maxposts = get_option('posts_per_page', '10');
  $paged = (get_query_var('paged')) ? get_query_var('paged') : 1;
?>
  <div class="page-navigation">
    <div class="elements-wrap">
      <?php get_template_part('parts/crumbs'); ?>
      <?php get_template_part('parts/post','filter'); ?>
    </div>
  </div>
  <main class="singlepage overview-page archive-page">
    <div class="content-wrap news">
      <div class="news-archive article-wrap grid-m-3">
        <?php while ( have_posts() ) { the_post(); ?>
          <article class="post">
            <a href="<?php the_permalink(); ?>">
              <?php
                if ( $feature = get_field('news_image') ) {
                  $options = [
                    'type'   => 'bg',
                    'source' => 'attachment'
                  ];
                  the_img_set($feature, 'wide', '25', $options);
                } else {
                  the_img_set(FALLBACK, 'wide', '33', ['type'=>'bg']);
                }
                $title = get_field('news_title');
              ?>
              <div class="date-title">
                <?php the_el($title, 'news-head', '', 'h3'); ?>
              </div>
            </a>
          </article>
        <?php } //endwhile ?>
      </div>
    </div>
    <?php echo get_pagination($wp_query, $maxposts, 'post'); ?>
  </main>
<?php
  get_footer();