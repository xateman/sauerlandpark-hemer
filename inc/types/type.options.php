<?php
if( function_exists('acf_add_options_page') ) {
  acf_add_options_page(array(
    'page_title'  => 'Sauerlandpark Hemer Einstellungen',
    'menu_title'  => 'Allgemein',
    'menu_slug'   => 'sph-general-settings',
    'capability'  => 'edit_posts',
    'redirect'    => false,
    'position'    => '49.1',
  ));
  acf_add_options_sub_page(array(
    'page_title'  => 'Menü Spalten - Sauerlandpark Hemer Einstellungen',
    'menu_title'  => 'Menü Spalten',
    'parent_slug' => 'sph-general-settings',
  ));
  acf_add_options_sub_page(array(
    'page_title'  => 'Öffnungszeiten - Sauerlandpark Hemer Einstellungen',
    'menu_title'  => 'Öffnungszeiten',
    'parent_slug' => 'sph-general-settings',
  ));
}