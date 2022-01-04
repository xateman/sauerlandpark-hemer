<?php

  get_header();

  $allowed_html = array(
    'a' => array(
      'href' => array()
    ),
    'p' => array(),
  );
  $maxposts = 12;

?>
<div class="page-navigation">
  <div class="elements-wrap">
    <?php get_template_part('parts/crumbs'); ?>
  </div>
</div>
<main class="<?php echo element_location(); ?>">
  <div class="content-wrap">
    <div class="post-content content">
      <?php if ( have_posts() ) { ?>
        <h2><?php _e('Suchergebnisse zu', LOCAL); ?>: "<?php echo get_search_query(); ?>"</h2>
        <form role="search" method="get" class="search-form" action="<?php bloginfo('url'); ?>">
          <input class="search-field" type="search" placeholder="<?php _e('Suchbegriff', LOCAL); ?>" value="" name="s" />
          <button class="submit-form">
            <span class="fa fa-search"></span>
          </button>
        </form>

        <div class="search-results">
          <?php while ( have_posts() ) {
            the_post();
            $post_type = get_post_type();
            switch ( $post_type ) {
              case 'post' :
                $subtitle = '<b>Beitrag</b><span class="datetime">' . get_the_time('d.m.Y') . '</span>';
                $title    = get_the_title();
                break;
              case 'page' :
                $subtitle = '<b>Seite</b>';
                $title    = get_the_title();
                break;
              case 'event' :
                $dt = (object) [
                  'dateStart' => get_field('cal_date_start', $post->ID),
                  'dateEnd'   => get_field('cal_date_end', $post->ID),
                  'timeStart' => get_field('cal_time_start', $post->ID),
                  'timeEnd'   => get_field('cal_time_end', $post->ID)
                ];
                if ( $dt->dateEnd < DT['date'] ) {
                  $title_datetime = get_event_date($dt->dateStart, $dt->dateEnd, $dt->timeStart, $dt->timeEnd, 'num');
                  $concluded = __('Bereits beendet.', LOCAL);
                  $subtitle  = "<b>Event</b><abbr class='datetime' title='{$title_datetime['string']}'>$concluded</abbr>";
                } else {
                  $title_datetime = get_event_date($dt->dateStart, $dt->dateEnd, $dt->timeStart, $dt->timeEnd);
                  $subtitle = '<b>Event</b><span class="datetime">' . $title_datetime['string'] . '</span>';
                }
                $title    = get_the_title();
                break;
              case 'course' :
                $subtitle = '<b>Kurs</b>';
                $title    = get_the_title();
                break;
              default :
                $subtitle = $post_type;
                $title    = get_the_title();
                break;
            }
            ?>
            <article class="post-type-<?php echo $post_type; ?>">
              <a class="title" href="<?php the_permalink(); ?>"><?php echo $title; ?></a>
              <div class="meta">
                <div class="subtitle type-<?php echo $post_type; ?>"><?php echo $subtitle; ?></div>
                <?php //get_template_part('parts/crumbs'); ?>
              </div>
            </article>
          <?php } ?>

          <?php if ( $wp_query->found_posts > $maxposts ) {
            $big  = 999999999;
            $args = [
              'base'      => str_replace( $big, '%#%', esc_url( get_pagenum_link( $big ) ) ),
              'format'    => '/page/%#%',
              'prev_text' => '<span class="fa fa-chevron-left"></span>',
              'next_text' => '<span class="fa fa-chevron-right"></span>',
              'current'   => max( 1, get_query_var('paged') ),
              'total'     => $wp_query->max_num_pages
            ];
            $links = paginate_links($args);
            echo '<div class="pagination">' . $links . '</div>';
          } ?>
        </div>

      <?php } else { ?>

        <h2><?php _ex('Leider keine Suchergebnisse für', 'Such-Query folgt', LOCAL); ?>: "<?php echo get_search_query(); ?>"</h2>

        <form role="search" method="get" class="search-form" action="<?php bloginfo('url'); ?>">
          <input class="search-field" type="search" placeholder="<?php _e('Suchbegriff', LOCAL); ?>" value="" name="s" />
          <button class="submit-form">
            <span class="fa fa-search"></span>
          </button>
        </form>

        <div class="search-results no-results">

          <?php printf( wp_kses( __( '<p>Leider war Ihre Suche ohne Ergebnis.</p><p>Falls Sie etwas auf unserer Seite vermissen oder eine Frage haben freue wir uns auf <a href="%s/kontakt/">Ihre Nachricht</a>.</p>', LOCAL ), $allowed_html ), esc_url( get_bloginfo('url') ) ); ?>

        </div>

      <?php } ?>
    </div>
  </div>
</main>

<?php get_footer(); ?>
