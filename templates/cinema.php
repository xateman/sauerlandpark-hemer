<?php

  /*
    Template Name: Kinoprogramm
  */
  get_header();

?>
  <div class="page-navigation">
    <div class="elements-wrap">
      <?php get_template_part('parts/crumbs'); ?>
    </div>
  </div>
<?php if ( ( get_field('header_select') == 'image' ) || ( get_field('header_select') == 'slider' ) ) {
  get_template_part('parts/slider');
  $extendedClass = 'has-header-image';
} else {
  $extendedClass = '';
}

  if ( have_posts() ) {
    while ( have_posts() ) {
      the_post(); ?>
      <main class="<?php echo element_location() . ' ' . $extendedClass; ?>">
        <?php get_template_part('parts/conditional.logic'); ?>
        <div class="content-part cinema-program">
          <div class="content-wrap">
            <?php
              if ( $program = get_field('cinema-programm') ) {
                $ticketUrl = get_field('general-ticket-url');
                echo "<div class='program-grid grid-m-3'>";
                foreach ( $program as $p ) {
                  //print_pre($p);
                  setlocale( LC_ALL, 'de_DE' );
                  $local_date = date("l, d.m.Y", strtotime($p['datum']));
                  $date       = strftime( '%A, %d.%m.%Y', strtotime( $local_date ) );
                  $movies     = $p['movies'];
                  $count      = count($movies);

                  $entryClass = "program-entry";
                  $movieClass = "movies";
                  if ( $count > 1 ) {
                    $entryClass .= " colspan-$count";
                    $movieClass .= " grid-m-$count";
                  }

                  echo "<div class='$entryClass'>";
                  echo "<h4 class='program-date'>$date</h4>";

                  echo "<div class='$movieClass'>";
                  foreach ( $movies as $movie ) {
                    $title    = get_field('movie-title', $movie['movie']);
                    $poster   = get_field('movie-poster', $movie['movie']);
                    $start    = substr_replace($movie['start-time'], ':', 2, 0);
                    $entrance = substr_replace( ( $movie['start-time'] - 100 ), ':', 2, 0);
                    echo "<div class='movie'>";
                    the_img_set($poster, 'relative', 25);
                    echo "<h5 class='title'>$title</h5>";

                    echo "<div class='times'>";
                    echo "<div class='begin'><span class='label'>Beginn</span><span class='time'>$start Uhr</span></div>";
                    echo "<div class='entrance'><span class='label'>Einlass</span><span class='time'>$entrance Uhr</span></div>";
                    echo "</div>";

                    if ( $movie['ticketlink'] ) {
                      $url = $movie['ticketlink'];
                    } else {
                      $url = $ticketUrl;
                    }
                    echo "<div class='movie-links'>";
                    echo "<a href='$url' target='_blank'>Tickets sichern!</a>";
                    if ( $p['snacklink'] ) {
                      echo "<a href='{$p['snacklink']}' target='_blank'>Snacks sichern!</a>";
                    }
                    echo "</div>";

                    echo "</div>";
                  }
                  echo "</div>";

                  echo "</div>";
                }
                echo "</div>";
              }
            ?>
          </div>
        </div>
      </main>
    <?php }
  }
  get_footer();