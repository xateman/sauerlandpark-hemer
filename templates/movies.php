<?php

  /*
    Template Name: Filmübersicht
  */
  get_header();

?>
  <div class="page-navigation">
    <div class="elements-wrap">
      <?php get_template_part('parts/crumbs'); ?>
    </div>
  </div>
<?php
  if ( have_posts() ) {
    while ( have_posts() ) {
      the_post(); ?>
      <main class="<?php echo element_location(); ?>">
        <?php get_template_part('parts/conditional.logic'); ?>
        <?php
          if ( $movie_list = get_field('movie-list') ) {
            echo "<div class='content-part movie-list'>";
            $args = [
              'post_type'      => 'movie',
              'posts_per_page' => -1,
              'orderby'        => 'title',
              'order'          => 'ASC',
              'post__in'       => $movie_list
            ];
            $movies = new WP_Query( $args );
            if ( $movies->have_posts() ) {
              while ( $movies->have_posts() ) {
                echo "<div class='movie'>";
                $movies->the_post();
                $title    = get_field('movie-title');
                $poster   = get_field('movie-poster');
                $duration = get_field('movie-duration');
                $fsk      = get_field('fsk');
                $text     = get_field('description');
                $poster   = get_img_set($poster, 'relative', 33, ['lightbox'=>true]);
                echo "<div class='poster'>$poster</div>";
                echo "<div class='details'>";
                echo "<h3 class='title'>$title</h3>";
                echo "<div class='meta'>";
                echo "<div class='meta-set fsk'><span class='label'>FSK</span><span class='value'>$fsk</span></div>";
                echo "<div class='meta-set time'><span class='label'>Laufzeit</span><span class='value'>$duration min.</span></div>";
                echo "</div>";
                echo "<div class='text'>$text</div>";
                echo "</div>";
                echo "</div>";
              }
            }
            echo "</div>";
          }
        ?>
      </main>
    <?php }
  }
  get_footer();