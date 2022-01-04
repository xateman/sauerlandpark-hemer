<?php

  get_header();

  $maxposts = get_option('posts_per_page', 8);
  $paged = (get_query_var('paged')) ? get_query_var('paged') : 1;
  $term = get_queried_object();
?>
  <div class="page-navigation">
    <div class="elements-wrap">
      <?php get_template_part('parts/crumbs'); ?>
      <?php get_template_part('parts/event','categories'); ?>
      <?php get_template_part('parts/event','filter'); ?>
    </div>
  </div>
  <main class="singlepage overview-page archive-page">
    <?php if ( $taxDesc = get_field('content', $term) ) { ?>
      <div class="content-wrap taxonomy-description">
        <div class="post-content content">
          <?php echo $taxDesc; ?>
        </div>
      </div>
    <?php } ?>
    <div class="content-wrap events">
      <div class="events-archive article-wrap grid-m-2 grid-l-3">
        <?php $index = 1;
          if ( have_posts() ) {
            while ( have_posts() ) { the_post();
              $start_date = get_field('cal_date_start');
              $date       = date("d.m.Y", strtotime("$start_date"));
              $terms      = wp_get_post_terms( $post->ID, 'event_tax', array('fields' => 'all') );

              $dateOut  = $date;
              $dateProp = $start_date;
              if ( $start_time = get_field('cal_time_start') ) {
                $dateOut  .= " | $start_time Uhr";
                $dateProp .= "T$start_time";
              }

              if ($terms) {
                $category_list = '<div class="categorie-list">';
                foreach ($terms as $term) {
                  $term_id = 'term_' . $term->term_id;
                  $category_list .= '<div class="category">';
                  $category_list .= '<a href="'.get_bloginfo('url').'/events/'.$term->slug.'" title="'.$term->name.'">';
                  $category_list .= get_category_icon($term_id);
                  $category_list .= '</a>';
                  $category_list .= '</div>';
                }
                $category_list .= '</div>';
              }
              ?>
              <article class="event" itemscope itemtype="https://schema.org/Event">
                <?php if (isset($category_list)) {
                  echo $category_list;
                } ?>
                <a href="<?php the_permalink(); ?>">
                  <?php if ( $feature = get_field('cal_image') ) {
                    the_img_set($feature, 'wide', '33', ['type' => 'bg']);
                  } ?>
                  <div class="date-title">
                    <div class="date" itemprop="startDate" content="<?php echo $dateProp; ?>"><?php echo $dateOut; ?></div>
                    <h3 itemprop="name"><?php the_field('cal_title'); ?></h3>
                  </div>
                </a>
              </article>
            <?php } //endwhile; ?>
          <?php } else { ?>
            <div class="no-events">
              <p><?php _e('Aktuell sind keine Veranstaltungen geplant.',LOCAL); ?></p>
            </div>
          <?php } //endif; ?>
      </div>
    </div>
    <?php echo get_pagination($wp_query, $maxposts, 'event'); ?>
  </main>
<?php get_footer();