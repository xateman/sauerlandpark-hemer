<?php
function script_init() {

  wp_deregister_script( 'jquery' );
  wp_enqueue_script('jquery', 'https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js', [], null, false);
  wp_enqueue_script('jquery-ui', 'https://ajax.googleapis.com/ajax/libs/jqueryui/1.12.1/jquery-ui.min.js', [], null, true);
  //wp_enqueue_script('modernizr', get_path('libs').'/presets/modernizr.js', 'jquery', null, true);

  wp_enqueue_script('swiper#ad', 'https://cdnjs.cloudflare.com/ajax/libs/Swiper/4.3.5/js/swiper.min.js', 'jquery', null, true);

  wp_enqueue_script('fancybox#ad', 'https://cdnjs.cloudflare.com/ajax/libs/fancybox/3.3.5/jquery.fancybox.min.js', 'jquery', null, true);

  wp_enqueue_script('custom-scrollbar#ad', get_path('libs').'/presets/custom.scrollbar.min.js', 'jquery', null, true);

  //wp_enqueue_script('jscookie', 'https://cdnjs.cloudflare.com/ajax/libs/js-cookie/2.1.4/js.cookie.min.js', 'jquery', null, true);

  wp_enqueue_script('init#ad', get_path('libs').'/init.js', array(), null, true);

  if ( _DEVICE == 'PHONE' ) {
    wp_enqueue_script('phone-js#ad', get_path('libs').'/phone.min.js', array(), null, true);
  } else if ( _DEVICE == 'TABLET' ) {
    wp_enqueue_script('tablet-js#ad', get_path('libs').'/tablet.min.js', array(), null, true);
  } else {
    wp_enqueue_script('desktop-js#ad', get_path('libs').'/desktop.min.js', array(), null, true);
  }

  if ( is_singular('event') || is_page_template('templates/eventmap.custom.php') ) {
    wp_enqueue_script('eventmap#ad', get_path('libs').'/eventmap.custom.js', array(), null, true);
  }
  if ( is_page_template('templates/eventmap.streetmap.php') ) {
    wp_enqueue_script('eventmap-osm#ad', get_path('libs').'/eventmap.streetmap.js', array(), null, true);
  }

}
if ( !is_admin() ) {
  add_action('wp_enqueue_scripts', 'script_init');
}

function load_styles() {

  $styles = [
    'dashicons' => [
      'url'   => includes_url().'css/dashicons.min.css',
      'media' => 'all'
    ],
    'fonts' => [
      'url'   => 'https://fonts.googleapis.com/css2?family=Open+Sans:wght@300;400;600;700&display=swap',
      'media' => 'all'
    ],
    'fontawesome' => [
      'url'         => 'https://pro.fontawesome.com/releases/v5.10.1/css/all.css',
      'media'       => 'all',
      'integrity'   => 'sha384-y++enYq9sdV7msNmXr08kJdkX4zEI1gMjjkw0l9ttOepH7fMdhb7CePwuRQCfwCr',
      'crossorigin' => 'anonymous'
    ],
    'swiper' => [
      'url'   => 'https://cdnjs.cloudflare.com/ajax/libs/Swiper/4.3.5/css/swiper.min.css',
      'media' => 'all'
    ],
    'fancybox' => [
      'url'   => 'https://cdnjs.cloudflare.com/ajax/libs/fancybox/3.3.5/jquery.fancybox.min.css',
      'media' => 'all'
    ],
    'print' => [
      'url'   => get_path('css') . '/print.min.css',
      'media' => 'print'
    ]
  ];

  /*
  if ( _DEVICE == 'DESKTOP' ) {



  } else if ( _DEVICE == 'TABLET' ) {

    $styles['tablet'] = [
      'url'   => get_path('css') . '/tablet.min.css',
      'media' => 'all',
      'ver'   => [
        'type' => 'date',
        'path' => get_path('css', 'dir') . '/tablet.min.css',
      ]
    ];

  } else if ( _DEVICE == 'PHONE' ) {

    $styles['mobile'] = [
      'url'   => get_path('css') . '/mobile.min.css',
      'media' => 'all',
      'ver'   => [
        'type' => 'date',
        'path' => get_path('css', 'dir') . '/mobile.min.css',
      ]
    ];

  }
  */

  $desktop = [
    'mobile' => [
      'url'   => get_path('css') . '/mobile.min.css',
      'media' => 'screen and (max-width: 768px)',
      'ver'   => [
        'type' => 'date',
        'path' => get_path('css', 'dir') . '/mobile.min.css',
      ]
    ],
    'tablet' => [
      'url'   => get_path('css') . '/tablet.min.css',
      'media' => 'screen and (min-width: 768px) and (max-width: 1024px)',
      'ver'   => [
        'type' => 'date',
        'path' => get_path('css', 'dir') . '/tablet.min.css',
      ]
    ],
    'desktop' => [
      'url'   => get_path('css') . '/desktop.min.css',
      'media' => 'screen and (min-width: 1025px)',
      'ver'   => [
        'type' => 'date',
        'path' => get_path('css', 'dir') . '/desktop.min.css',
      ]
    ],
  ];

  $styles = array_merge($styles, $desktop);

  if ( is_singular('event') || is_page_template('templates/eventmap.custom.php') ) {
    $styles['mousePosition'] = [
      'url'   => 'https://cdn.rawgit.com/ardhi/Leaflet.MousePosition/master/src/L.Control.MousePosition.css',
      'media' => 'all'
    ];
  }

  foreach ($styles as $style) {
    $url = $style['url'];
    if ( isset($style['ver']) && is_array($style['ver']) ) {
      switch ( $style['ver']['type'] ) {
        case 'date':
          $version = filemtime($style['ver']['path']);
          break;
        case 'size':
          $version = filesize($style['ver']['path']);
          break;
      }
      $url .= '?v=' . $version;
    } else if ( isset ($style['ver']) ) {
      $url .= '?v=' . $style['ver'];
    }
    $attr = '';
    if ( isset($style['integrity']) && $style['integrity'] != '' ) {
      $attr .= " integrity='{$style['integrity']}'";
    }
    if ( isset($style['crossorigin']) && $style['crossorigin'] != '' ) {
      $attr .= " crossorigin='{$style['crossorigin']}'";
    }
    echo "<link rel='stylesheet' href='$url' media='{$style['media']}' $attr />";
  }

}