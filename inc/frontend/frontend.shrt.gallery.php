<?php

function my_post_gallery($output, $attr) {
  global $post;

  if (isset($attr['orderby'])) {
    $attr['orderby'] = sanitize_sql_orderby($attr['orderby']);
    if (!$attr['orderby'])
      unset($attr['orderby']);
  }

  extract(shortcode_atts(array(
    'order'      => 'ASC',
    'orderby'    => 'menu_order ID',
    'id'         => $post->ID,
    'itemtag'    => 'dl',
    'icontag'    => 'dt',
    'captiontag' => 'dd',
    'columns'    => 3,
    'size'       => 'thumbnail',
    'include'    => '',
    'exclude'    => ''
  ), $attr));

  $id = intval($id);
  if ('RAND' == $order) $orderby = 'none';

  if (!empty($include)) {
    $include = preg_replace('/[^0-9,]+/', '', $include);
    $args = [
      'include'        => $include,
      'post_status'    => 'inherit',
      'post_type'      => 'attachment',
      'post_mime_type' => 'image',
      'order'          => $order,
      'orderby'        => $orderby
    ];
    $_attachments = get_posts($args);

    $attachments = array();
    foreach ($_attachments as $key => $val) {
      $attachments[$val->ID] = $_attachments[$key];
    }
  }

  if ( empty($attachments) ) return '';

  $output = '<div class="page-gallery grid-s-2 grid-m-4">';

  foreach ($attachments as $id => $attachment) {
    $img   = wp_get_attachment_image_src($id, 'full');
    $thumb = wp_get_attachment_image_src($id, 'frontpage-small');
    $meta  = wp_get_attachment_metadata($id);
    $lightbox_name = $post->post_name;

    $caption = $meta['image_meta']['caption'];
    if ( $meta['image_meta']['copyright'] ) {
      $caption .= ' | ' . $meta['image_meta']['copyright'];
    }

    $output .= '<a href="'.$img[0].'" data-fancybox="'.$lightbox_name.'" data-caption="'.$caption.'">';
    $output .= get_img_set($id, 'feat', '25', 'wp');
    $output .= '</a>';

  }
  $output .= '</div>';
  return $output;
}
add_filter('post_gallery', 'my_post_gallery', 10, 2);