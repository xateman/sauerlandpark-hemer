<?php
  $headline = get_sub_field('download-headline');
  $category = get_sub_field('download-category');

  $args = [
    'post_type'       => 'download',
    'orderby'         => 'date',
    'order'           => 'DESC',
    'posts_per_page'  => -1
  ];

  if ( is_array( $category ) ) {
    $args['tax_query'] = [
      [
        'taxonomy' => 'download_tax',
        'field'    => 'term_id',
        'terms'    => $category
      ]
    ];
  }

  $files = new WP_Query($args);

  if ($files->have_posts() ) {
    echo '<div class="downloads-wrap">';

    if ( $headline ) {
      echo "<h1>$headline</h1>";
    }
    echo '<div class="files grid-m-2">';
    while ( $files->have_posts() ) {
      $files->the_post();
      $file     = get_field('file');
      $title    = get_the_title();
      $fileSize = formatBytes($file['filesize'], 2);

      echo "<a href='{$file['url']}' target='_blank' class='file'>";
      echo "<span class='icon fal fa-file-alt'></span><span class='title'>$title</span><span class='size'>$fileSize</span>";
      echo "</a>";
    }
    echo '</div>';
    echo '</div>';
  } else {
    _e('Keine Downloads vorhanden', LOCAL);
  }
  wp_reset_query();