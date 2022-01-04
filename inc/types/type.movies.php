<?php
  function movie_post_type() {
    $labels = array(
      'name'                => 'Filme',
      'singular_name'       => 'Film',
      'menu_name'           => 'Autokino Filme',
      'all_items'           => 'Alle Filme',
      'view_item'           => 'Film ansehen',
      'add_new_item'        => 'Neuen Film hinzufügen',
      'add_new'             => 'Neuer Film',
      'edit_item'           => 'Film bearbeiten',
      'update_item'         => 'Film aktualisieren',
      'search_items'        => 'Filme suchen',
      'not_found'           => 'Keine Filme gefunden',
      'not_found_in_trash'  => 'Keine Filme gefunden',
    );

    $rewrite = array(
      'slug'                => 'movie',
      'with_front'          => false,
      'pages'               => true,
      'feeds'               => true,
    );

    $args = array(
      'label'               => 'movie',
      'description'         => 'Inhalte',
      'labels'              => $labels,
      'supports'            => array( 'title' ),
      'menu_icon'           => 'dashicons-video-alt',
      'hierarchical'        => false,
      'public'              => false,
      'show_ui'             => true,
      'show_in_menu'        => true,
      'show_in_nav_menus'   => true,
      'show_in_admin_bar'   => true,
      'menu_position'       => 5,
      'can_export'          => true,
      'has_archive'         => false,
      'exclude_from_search' => false,
      'publicly_queryable'  => false,
      'rewrite'             => $rewrite,
      'capability_type'     => 'page',
    );

    register_post_type( 'movie', $args );
  }
  add_action( 'init', 'movie_post_type', 0 );