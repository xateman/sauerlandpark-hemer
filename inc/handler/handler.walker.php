<?php

class customWalker extends Walker_Nav_Menu {

  function start_lvl( &$output, $depth = 0, $args = [] ) {

    $display_depth = ( $depth + 1 );
    $classes = array(
      'sub-menu',
      'menu-depth-' . $display_depth
    );
    $class_names = implode( ' ', $classes );

    $output .= '<ul class="' . $class_names . '">';
  }

  function start_el( &$output, $item, $depth = 0, $args = [], $id = 0 ) {

    $title  = apply_filters( 'the_title', $item->title, $item->ID );

    // depth dependent classes
    $depth_classes = array(
      ( $depth == 0 ? 'main-menu-item' : 'sub-menu-item' ),
      'menu-item-depth-' . $depth
    );
    $depth_class_names = esc_attr( implode( ' ', $depth_classes ) );

    // passed classes
    $classes = empty( $item->classes ) ? array() : (array) $item->classes;
    $class_names = esc_attr( implode( ' ', apply_filters( 'nav_menu_css_class', array_filter( $classes ), $item ) ) );

    $output .= '<li id="nav-menu-item-'. $item->ID . '" class="' . $depth_class_names . ' ' . $class_names . '">';

    $page_functions = get_field('page_function', $item->object_id);
    if ( is_array($page_functions) && in_array('no-page', $page_functions)) {

      $class = 'class="section-head"';

      $item_output = sprintf( '<span %1$s>%2$s</span>',
        $class,
        $title
      );

    } else {

      $attributes  = !empty( $item->attr_title ) ? ' title="'.esc_attr( $item->attr_title ).'"' : '';
      $attributes .= !empty( $item->target )? ' target="'.esc_attr( $item->target ).'"' : '';
      $attributes .= !empty( $item->xfn ) ? ' rel="'. esc_attr( $item->xfn ).'"' : '';
      $attributes .= !empty( $item->url ) ? ' href="'.esc_attr( $item->url ).'"' : '';

      $item_output = sprintf( '%1$s<a%2$s>%3$s%4$s%5$s</a>%6$s',
        $args->before,
        $attributes,
        $args->link_before,
        $title,
        $args->link_after,
        $args->after
      );

    }

    if ( 'publish' === get_post_status( $item->object_id ) ) {
      // build html if page is published
      $output .= apply_filters( 'walker_nav_menu_start_el', $item_output, $item, $depth, $args );
    }
  }
}