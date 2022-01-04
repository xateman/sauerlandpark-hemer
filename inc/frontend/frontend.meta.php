<?php

add_action( 'wp_head', 'add_meta_to_head' );
function add_meta_to_head() {
  /*
   *  note: page title generation
   */

  global $page, $paged, $post;

  $pid = $post->ID;

  if ( $alt_page_title = get_field('alt_page_title', $pid) ) {
    $page_title  = "$alt_page_title | ";
  } else {
    $page_title  = wp_title( '|', false, 'right' );
  }
  if ( $paged >= 2 || $page >= 2 ) {
    $page_title .= sprintf( __( 'Seite %s', LOCAL), max( $paged, $page ) ).' | ';
  }
  $page_title .= get_bloginfo( 'name' );

  ?>
  <meta charset="UTF-8">
  <title><?php echo $page_title; ?></title>
  <meta name='author' content="<?php bloginfo('name'); ?>" />
  <meta name='publisher' content="aliaz werbeagentur gmbh" />
  <meta name="msapplication-config" content="none"/>

  <link rel="shortcut icon" href="<?php the_path('assets/icons'); ?>/favicon.ico" type="image/x-icon">
  <link rel="icon" href="<?php the_path('assets/icons'); ?>/favicon.ico" type="image/x-icon">

  <link href="<?php the_path('assets/icons'); ?>/apple-touch-icon-57x57.png" sizes="57x57" rel="apple-touch-icon">
  <link href="<?php the_path('assets/icons'); ?>/apple-touch-icon-72x72.png" sizes="72x72" rel="apple-touch-icon">
  <link href="<?php the_path('assets/icons'); ?>/apple-touch-icon-114x114.png" sizes="114x114" rel="apple-touch-icon">
  <link href="<?php the_path('assets/icons'); ?>/apple-touch-icon-144x144.png" sizes="144x144" rel="apple-touch-icon">

  <!--[if lt IE 9]>
  <link rel='stylesheet' type='text/css' href='<?php bloginfo('template_directory'); ?>/css/desktop.min.css' media='all' />
  <script src="https://oss.maxcdn.com/html5shiv/3.7.2/html5shiv.min.js"></script>
  <script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>
  <![endif]-->

  <?php if ( is_singular('event') || is_page_template('templates/eventmap.custom.php') || is_page_template('templates/eventmap.streetmap.php')) { ?>
    <link rel="stylesheet" href="https://unpkg.com/leaflet@1.5.1/dist/leaflet.css"
          integrity="sha512-xwE/Az9zrjBIphAcBb3F6JVqxf46+CDLwfLMHloNu6KEQCAWi6HcDUbeOfBIptF7tcCzusKFjFw2yuvEpDL9wQ=="
          crossorigin=""/>
    <script src="https://unpkg.com/leaflet@1.5.1/dist/leaflet.js"
            integrity="sha512-GffPMF3RvMeYyc1LWMHtK8EbPv0iNZ8/oTtHPx9/cc2ILxQ+u905qIwdpULaqDkyBKgOaB57QTMg7ztg8Jm2Og=="
            crossorigin=""></script>
    <script src="https://cdn.rawgit.com/ardhi/Leaflet.MousePosition/master/src/L.Control.MousePosition.js" type="text/javascript"></script>
    <?php if (is_page_template('templates/eventmap.streetmap.php')) { ?>
    <link rel="stylesheet" href="https://unpkg.com/leaflet-routing-machine@latest/dist/leaflet-routing-machine.css" />
    <script src="https://unpkg.com/leaflet-routing-machine@latest/dist/leaflet-routing-machine.js"></script>
    <?php } ?>
  <?php } ?>

  <meta name="viewport" content="width=device-width, maximum-scale=1.0" />

  <?php if ( is_page() || is_single() ) {
    /*
     *  note: page description generation
     */
    if ( $alt_page_desc = get_field('alt_page_desc', $pid) ) {

    } else if ( !empty( get_the_excerpt($pid) ) ) {
      $page_desc = get_the_excerpt($pid);
    } else if ( !empty($post->post_content) ) {
      $page_desc = wp_trim_words( get_the_content(), 40, '...' );
    } else {
      $page_desc = get_bloginfo('description');
    }
  ?>
    <meta name="description" content="<?php echo $page_desc; ?>" />
    <meta property="og:url" content="<?php echo get_permalink($pid) ?>"/>
    <meta property="og:title" content="<?php echo $page_title; ?>" />
    <meta property="og:description" content="<?php echo $page_desc; ?>" />
    <meta property="og:type" content="website" />
    <?php
      if ( $alt_feature = get_field('alt_feature', $pid) ) {
        $featured_img_url = $alt_feature['url'];
      } else if ( has_post_thumbnail( $pid ) ) {
        $featured_img_url = get_the_post_thumbnail_url( $pid, 'full');
    ?>
    <meta property="og:image" content="<?php echo $featured_img_url; ?>" />
    <?php }
  }
}
