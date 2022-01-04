<?php

  $menu_columns_cache = get_transient( 'menu_columns_cache' );
  if ( false === $menu_columns_cache || !is_array($menu_columns_cache) ) {
    if ( $menu_columns = get_field('main_menu_columns', 'option')) {
      $menu_columns_cache = [];
      foreach ( $menu_columns as $col ) {
        $menu_columns_cache[] = [
          'title' => 'Header: ' . $col['col_name'],
          'slug'  => 'head_' . sanitize_html_class( clean_string( $col['col_name'] ) )
        ];
      }
    }
    set_transient( 'menu_columns_cache', $menu_columns_cache, 10 );
  }

  if ( is_array($menu_columns_cache) ) {
    foreach($menu_columns_cache as $col) {
      register_sidebar( array(
        'name'          => $col['title'],
        'id'            => $col['slug'],
        'class'         => '',
        'before_widget' => '',
        'after_widget'  => '',
        'before_title'  => '<h2 class="title">',
        'after_title'   => '</h2>'
      ));
    }
  }

  $footer_columns_cache = get_transient( 'footer_columns_cache' );
  if ( false === $footer_columns_cache || !is_array($footer_columns_cache) ) {
    if ( $menu_columns = get_field('footer_menu_columns', 'option')) {
      $footer_columns_cache = [];
      foreach ( $menu_columns as $col ) {
        $footer_columns_cache[] = [
          'title' => 'Footer: ' . $col['col_name'],
          'slug'  => 'foot_' . sanitize_html_class( clean_string( $col['col_name'] ) )
        ];
      }
    }
    set_transient( 'footer_columns_cache', $footer_columns_cache, 10 );
  }

  if ( is_array($footer_columns_cache) ) {
    foreach ($footer_columns_cache as $col) {
      register_sidebar(array(
        'name' => $col['title'],
        'id' => $col['slug'],
        'class' => '',
        'before_widget' => '',
        'after_widget' => '',
        'before_title' => '<h2 class="title">',
        'after_title' => '</h2>'
      ));
    } // endforeach;
  } // endif;

  register_sidebar( array(
    'name'          => 'Menü für Mobile Geräte',
    'id'            => 'mobile_menu',
    'description'   => '',
    'class'         => 'mobile-menu-section',
    'before_widget' => '',
    'after_widget'  => '',
    'before_title'  => '',
    'after_title'   => ''
  ));