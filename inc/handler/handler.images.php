<?php

  add_theme_support( 'post-thumbnails' );

  // sponsor features
  register_image_size('sponsor', 'S_SPONSOR', 140, 40, 'none');
  register_image_size('sponsor', 'M_SPONSOR', 280, 80, 'none');
  register_image_size('sponsor', 'L_SPONSOR', 320, 100, 'none');

  // basic page features / 4:3
  register_image_size('default', 'S_DEFAULT', 480, 360, 'center');
  register_image_size('default', 'M_DEFAULT', 720, 540, 'center');
  register_image_size('default', 'L_DEFAULT', 1280, 960, 'center');
  register_image_size('default', 'XL_DEFAULT', 1920, 1440, 'center');

  // feature with relative sizes and no crop
  register_image_size('relative', 'S_RELATIVE', 480, 480, 'none');
  register_image_size('relative', 'M_RELATIVE', 720, 720, 'none');
  register_image_size('relative', 'L_RELATIVE', 1280, 1280, 'none');
  register_image_size('relative', 'XL_RELATIVE', 1920, 1920, 'none');

  // quad feature
  register_image_size('quad', 'S_QUAD', 480, 480, 'center');
  register_image_size('quad', 'M_QUAD', 720, 720, 'center');
  register_image_size('quad', 'L_QUAD', 1280, 1280, 'center');
  register_image_size('quad', 'XL_QUAD', 1920, 1920, 'center');

  // special image sizes
  register_image_size('special', 'SPECIAL_HD', 1920, 300, 'center');
  register_image_size('special', 'SPECIAL_4K', 2560, 600, 'center');

  // default widescreen / 16:9 feature
  register_image_size('wide', 'S_WIDE', 480, 270, 'center');
  register_image_size('wide', 'M_WIDE', 720, 405, 'center');
  register_image_size('wide', 'L_WIDE', 1280, 720, 'center');
  register_image_size('wide', 'XL_WIDE', 1920, 1080, 'center');
  register_image_size('wide', '4K_WIDE', 2560, 1440, 'center');

  // mobile portrait features
  register_image_size('mobile','S_MOBILE',320,430,'center');
  register_image_size('mobile','M_MOBILE',640,860,'center');
  register_image_size('mobile','L_MOBILE',720,960,'center');
  register_image_size('mobile','XL_MOBILE',1080,1440,'center');

  function register_image_size($group, $id, $width, $height, $crop) {
    global $registered_images;
    $crop_arr = [
      'center' => [
        'center',
        'center'
      ],
      'top' => [
        'center',
        'top'
      ],
      'bottom' => [
        'center',
        'bottom'
      ],
      'none' => FALSE
    ];
    add_image_size($id, $width, $height, $crop_arr[$crop]);
    $registered_images[$group][] = [
      'id'    => $id,
      'width' => $width
    ];
  }

  function get_image_sizes($group)
  {
    global $registered_images;
    return $registered_images[$group];
  }

/*
* image sourceset function
*
* @param $id     - Identifier for image source. Depends on $source value. acf = get_field (type: image) / wp = post->ID
* @param $type   - "img" (image) or "wp" (wordpress post thumbnail).
* @return string - Output of the formatted image source set.
*/

  function get_img_set($id, $size_group = 'wide', $image_width = 50, $options = []) {
    /*
     * $o['source'] ACF expects an ACF image array
     * if $o['source'] is wp, the $id should be the wordpress post->ID
     */

    $default_options = [
      'source'       => 'acf', // possible acf, wp, attachment
      'type'         => 'img', // possible img, bg
      'lightbox'     => false,
      'lightboxName' => 'gallery',
      'title'        => get_the_title(),
      'permalink'    => false,
      'class'     => 'default'
    ];

    $o = array_merge($default_options, $options);

    $o['class'] .= " src-{$o['source']} type-{$o['type']} size-$size_group";

    $sizes = get_image_sizes($size_group);

    $set = '';

    if ( $o['source'] == 'acf' ) {

      $img = $id['sizes'];
      $src = $id['url'];
      foreach ($sizes as $size) {
        $set_arr[$size['width']] = $img[ $size['id'] ];
      }

    } else if ( $o['source'] == 'wp' ) {

      $src = get_the_post_thumbnail_url($id, $sizes[0]['id'] );
      foreach ( $sizes as $size ) {
        $set_arr[$size['width']] = get_the_post_thumbnail_url($id, $size['id'] );
      }

    } else if ( $o['source'] == 'attachment' ) {

      $src = wp_get_attachment_image_url($id, $sizes[0]['id'] );
      foreach ( $sizes as $size ) {
        $set_arr[$size['width']] = wp_get_attachment_image_url($id, $size['id'] );
      }

    }

    if ( $o['type'] == 'img' ) {
      foreach ( $set_arr as $width => $url ) {
        $set .= $url . ' ' . $width . 'w,';
      }

      $control = '(max-width: 768px) 100vw, ' . $image_width . 'vw';

      if ( $o['permalink'] == true ) {
        $permaLink = get_permalink();
        $output  = "<a href=\"$permaLink\">";
        $output .= "<img src=\"$src\" class=\"{$o['class']}\" srcset=\"$set\" sizes=\"$control\" />";
        $output .= '</a>';
      } else {
        $output = "<img src=\"$src\" class=\"{$o['class']}\" srcset=\"$set\" sizes=\"$control\" />";
      }
    } else if ( $o['type'] == 'bg' ) {

      $output  = "<div class=\"image-box dynamic--image-bg {$o['class']}\" ";
      foreach ( $set_arr as $width => $url ) {
        /*
        if ( $url != $id['url'] ) {

        }
        */
        $output .= 'data-img-' . $width . '="' . $url . '"';
      }
      $output .= 'data-width="' . $image_width . '"';
      $output .= 'style="background-image:url('.current($set_arr).')">';
      $output .= '</div>';
    } else if ( $o['type'] == 'parallax' ) {
      $output  = "<div class=\"image-box dynamic--image-bg parallax {$o['class']}\" ";
      foreach ( $set_arr as $width => $url ) {
        $output .= 'data-img-' . $width . '="' . $url . '"';
      }
      $output .= 'data-width="' . $image_width . '"';
      $output .= 'style="background-image:url('.current($set_arr).')">';
      $output .= "</div>";
    }

    if ( $o['lightbox'] ) {
      return '<a href="'.$src.'" data-fancybox="'.$o['lightboxName'].'" data-caption="'.$o['title'].'" data-srcset="'.substr($set, 0, -1).'">'.$output.'</a>';
    } else {
      return $output;
    }
  }

  function the_img_set($id, $size_group = 'wide', $image_width = 50, $options = []) {
    $output = get_img_set($id, $size_group, $image_width, $options);
    echo $output;
  }

  if ( $fallback = get_field('fallback_image', 'option') ) {
    define('FALLBACK', $fallback);
  }

  // add image sizes to the media library and gallery options
  add_filter( 'image_size_names_choose', 'add_size_to_media_lib' );
  function add_size_to_media_lib( $sizes ) {
    return array_merge( $sizes, array(
      'S_WIDE'     => 'Feature 16:9',
      'S_DEFAULT'  => 'Feature 4:3',
      'S_PORTRAIT' => 'Portrait',
    ) );
  }


  // add fancybox handler to all images in the_content
  add_filter('the_content', 'fancybox_images');
  function fancybox_images($content) {
    global $post;
    $pattern ="/<a(.*?)href=('|\")(.*?).(bmp|gif|jpeg|jpg|png)('|\")(.*?)>/i";
    $replacement = '<a$1href=$2$3.$4$5 data-fancybox data-fancybox-group="'.get_the_title().'" $6>';
    $content = preg_replace($pattern, $replacement, $content);
    return $content;
  }
