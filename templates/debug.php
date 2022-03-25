<?php
  /*
   * Template Name: Debug
   */

  get_header();
  if ( have_posts() ) {
    while ( have_posts() ) { the_post(); ?>
      <main class="<?php echo element_location(); ?>">
        <?php

          $priceSeason = [
            'winter1' => [
              'start' => date('Y') . '-01-01',
              'end'   => date('Y') . '-03-13'
            ],
            'winter2' => [
              'start' => date('Y') . '-10-29',
              'end'   => date('Y') . '-12-31'
            ]
          ];

          if ( ( in_between(DT['date'], $priceSeason['winter2']['start'], $priceSeason['winter2']['end'])) ||
            ( in_between(DT['date'], $priceSeason['winter1']['start'], $priceSeason['winter1']['end']) ) ) {

            $prices = get_field('winter','option');

            echo "winter?";

          } else {

            $prices = get_field('summer','option');

            echo "summer?";

          }

          var_dump($prices);

          echo "ASD?";

        ?>
        <?php //get_template_part('parts/conditional.logic'); ?>
      </main>
    <?php }
  }
  get_footer();
