<?php

  function gallery_post_type() {
      $labels = array(
          'name'                => 'Galerien',
          'singular_name'       => 'Galerie',
          'menu_name'           => 'Galerien',
          'all_items'           => 'Alle Galerien',
          'view_item'           => 'Galerie ansehen',
          'add_new_item'        => 'Neue Galerie hinzufügen',
          'add_new'             => 'Neue Galerie',
          'edit_item'           => 'Galerie bearbeiten',
          'update_item'         => 'Galerie aktualisieren',
          'search_items'        => 'Suche Galerie',
          'not_found'           => 'Keine Galerie gefunden',
          'not_found_in_trash'  => 'Keine Galerie gefunden',
      );

      $rewrite = array(
          'slug'                => 'galerie',
          'with_front'          => true,
          'pages'               => true,
          'feeds'               => true,
      );

      $args = array(
          'label'               => 'galerie',
          'description'         => 'Galerie Inhalte',
          'labels'              => $labels,
          'supports'            => array( 'title', 'thumbnail' ),
          'menu_icon'           => 'dashicons-format-gallery',
          'hierarchical'        => false,
          'public'              => true,
          'show_ui'             => true,
          'show_in_menu'        => true,
          'show_in_nav_menus'   => true,
          'show_in_admin_bar'   => true,
          'menu_position'       => 5,
          'can_export'          => true,
          'has_archive'         => true,
          'exclude_from_search' => false,
          'publicly_queryable'  => true,
          'rewrite'             => $rewrite,
          'capability_type'     => 'page',
      );

      register_post_type( 'galerie', $args );
  }
  add_action( 'init', 'gallery_post_type', 0 );

?>