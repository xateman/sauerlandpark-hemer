<?php

  //_____________THEME TEXTDOMAIN SETUP
  define('LOCAL', 'sph-theme');
  function language_theme_setup() {
    load_theme_textdomain(LOCAL, get_template_directory() . '/lang');
  }
  add_action('after_setup_theme', 'language_theme_setup');

  if (class_exists('Mobile_Detect')) {

    $detect = new Mobile_Detect();

    if ( $detect->isTablet() ) {
      define('_DEVICE', 'TABLET');
    } else if ( $detect->isMobile() ) {
      define('_DEVICE', 'PHONE');
    } else {
      define('_DEVICE', 'DESKTOP');
    }

  }

  // DEFINE DT as DATETIME array
  $timestamp = current_time( 'timestamp', 1 );
  $datetime = [
    'date'  => date('Y-m-d', $timestamp),
    'time'  => date('H:i', $timestamp),
    'dnice' => date('d.m.Y', $timestamp),
  ];
  define('DT', $datetime);

  // DEFINE contact and social infos as META
  $contact = [];
  if ( $phone = get_field('default-phone', 'option') ) {
    $contact['phone'] = [
      'value' => $phone,
      'icon'  => '<span class="fad fa-phone"></span>'
    ];
  }
  if ( $email = get_field('default-email', 'option') ) {
    $contact['email'] = [
      'value' => $email,
      'icon'  => '<span class="fad fa-envelope"></span>'
    ];
  }
  if ( $facebook = get_field('social-facebook', 'option') ) {
    $contact['facebook'] = [
      'value' => $facebook,
      'icon'  => '<span class="fab fa-facebook-f"></span>'
    ];
  }
  if ( $instagram = get_field('social-instagram', 'option') ) {
    $contact['instagram'] = [
      'value' => $instagram,
      'icon'  => '<span class="fab fa-instagram"></span>'
    ];
  }
  if ( $youtube = get_field('social-youtube', 'option') ) {
    $contact['youtube'] = [
      'value' => $youtube,
      'icon'  => '<span class="fab fa-youtube"></span>'
    ];
  }
  define('META', $contact);

  // DEFINE SEASONS as seasons array
  $current_year = date('Y');
  $seasons = [
    'winter' => [
      'start' => date('Y-m-d', strtotime($current_year.'-01-01')),
      'end' => date('Y-m-d', strtotime($current_year.'-03-15')),
    ],
    'spring' => [
      'start' => date('Y-m-d', strtotime($current_year.'-03-16')),
      'end' => date('Y-m-d', strtotime($current_year.'-05-31')),
    ],
    'summer' => [
      'start' => date('Y-m-d', strtotime($current_year.'-06-01')),
      'end' => date('Y-m-d', strtotime($current_year.'-09-21')),
    ],
    'autumn' => [
      'start' => date('Y-m-d', strtotime($current_year.'-09-22')),
      'end' => date('Y-m-d', strtotime($current_year.'-11-14')),
    ],
    'winter2' => [
      'start' => date('Y-m-d', strtotime($current_year.'-11-15')),
      'end' => date('Y-m-d', strtotime($current_year.'-12-31')),
    ],
  ];

  if ( in_between(DT['date'],$seasons['spring']['start'],$seasons['spring']['end']) ) {

    $current_season = [
      'slug' => 'spring',
      'name' => __('Frühling', LOCAL),
    ];

  } else if ( in_between(DT['date'], $seasons['summer']['start'], $seasons['summer']['end']) ) {

    $current_season = [
      'slug' => 'summer',
      'name' => __('Sommer', LOCAL),
    ];

  } else if ( in_between(DT['date'], $seasons['autumn']['start'], $seasons['autumn']['end']) ) {

    $current_season = [
      'slug' => 'autumn',
      'name' => __('Herbst', LOCAL),
    ];

  } else if ( ( in_between(DT['date'], $seasons['winter2']['start'], $seasons['winter2']['end'])) ||
    ( in_between(DT['date'], $seasons['winter']['start'], $seasons['winter']['end']) ) ) {

    $current_season = [
      'slug' => 'winter',
      'name' => __('Winter', LOCAL),
    ];

  }

  $seasons['current'] = $current_season;

  define('SEASONS', $seasons);

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

  } else {

    $prices = get_field('summer','option');

  }
  if ( isset($prices) && is_array($prices) && count($prices) > 0 ) {
    foreach ( $prices as $key => $price ) {
      if ( $price == '' ) {
        $prices[$key] = __('Auf Anfrage', LOCAL);
      }
    }

    define('PRICE', $prices);
  }
