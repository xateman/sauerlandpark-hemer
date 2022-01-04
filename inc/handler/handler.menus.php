<?php
  add_theme_support( 'menus' );
  function register_my_menus() {
    register_nav_menus(
    array(
      'mobile-mn' => 'Menü-Bereich für Mobile Geräte',
      'foot-mn'   => 'Footer-Bereich',
    ));
  }
  add_action( 'init', 'register_my_menus' );