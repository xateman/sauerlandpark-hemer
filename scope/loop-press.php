<?php

  $args = [
    'post_type'       => 'press',
    'orderby'         => 'date title',
    'order'           => 'DESC',
    'posts_per_page'  => -1
  ];
  
  $articles = new WP_Query($args);

  if ( $articles->have_posts() ) {
    echo '<div class="press-articles">';

    while ( $articles->have_posts() ) {
      $articles->the_post();
      $file = get_field('file');
      $title = get_the_title();
      $fileSize = formatBytes($file['filesize'], 2);
      $date = get_the_time('d.m.Y');
      $currentYear = get_the_time('Y');

      if (isset($prevYear) && ($prevYear != $currentYear)) {
        echo '</div>';
        echo '</div>';
        echo "<div class='year-wrap archive' data-year='$currentYear'>";
        echo "<div class='year-head toggle'>Presseartikel-Archiv: $currentYear</div>";
        echo '<div class="files closed">';
      } else if ( !isset($prevYear) ) {
        echo "<div class='year-wrap first-opened' data-year='$currentYear'>";
        echo "<div class='year-head current'>Aktuelle Presseartikel</div>";
        echo '<div class="files">';
      } //endif;

      echo "<article class='press-item'>";
      echo "<a href='{$file['url']}' target='_blank' class='file'>";
      echo "<span class='icon fal fa-file-alt'></span>";
      echo "<span class='date'>$date</span>";
      echo "<span class='title'>$title</span>";
      echo "<span class='size'>$fileSize</span>";
      echo "</a>";
      echo "</article>";

      $prevYear = $currentYear;
    }
    echo '</div>';
    echo '</div>';
    echo '</div>';
  } else {
    _e('Keine Downloads vorhanden', LOCAL);
  }
  wp_reset_query();