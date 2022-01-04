<?php

  // Includes
  require_once ( 'inc/includes.php' );

  add_filter( 'wpcf7_support_html5_fallback', '__return_true' );
  add_filter( 'wpcf7_load_js', '__return_false' );
  add_filter( 'wpcf7_load_css', '__return_false' );

  if ( function_exists('add_theme_support' )) {
    add_theme_support('post-thumbnails');
    add_image_size('feature', 306, 210, true);

  }

  //_____________ EXCERPT MODS
  function new_excerpt_more($more) {
    global $post;
    return '';
  }
  add_filter('excerpt_more', 'new_excerpt_more');
  function custom_excerpt_length( $length ) {
    return 100;
  }
  add_filter( 'excerpt_length', 'custom_excerpt_length', 999 );

  function remove_more_jump_link($link) {
    $offset = strpos($link, '#more-');
    if ($offset) { $end = strpos($link, '"',$offset); }
    if ($end) { $link = substr_replace($link, '', $offset, $end-$offset); }
    return $link;
  }
  add_filter('the_content_more_link', 'remove_more_jump_link');

  function add_data_lightbox($content) {
    global $post;
    $pattern ="/<a(.*?)href=('|\")(.*?).(bmp|gif|jpeg|jpg|png)('|\")(.*?)>/i";
    $replacement = '<a$1href=$2$3.$4$5 data-fancybox="'.$post->post_title.'" class="img__link" $6>';
    $content = preg_replace($pattern, $replacement, $content);
    return $content;
  }
  add_filter('the_content', 'add_data_lightbox');

  function add_pdf_icon($content) {
    global $post;
    $pattern ="/<a(.*?)href=('|\")(.*?).(pdf|doc|docx|xsl|xslx)('|\")(.*?)>/i";
    $replacement = '<a$1href=$2$3.$4$5 class="pdf__link" target="_blank" $6><span class="fa fa-file-o"></span>';
    $content = preg_replace($pattern, $replacement, $content);
    return $content;
  }
  add_filter('the_content', 'add_pdf_icon');

  define( 'WP_AUTO_UPDATE_CORE', false );
  remove_action('wp_head', 'wp_generator');