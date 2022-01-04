<?php
  function press_post_type() {
      $labels = [
          'name'                => 'Presse-Artikel',
          'singular_name'       => 'Presse-Artikel',
          'menu_name'           => 'Presse',
          'all_items'           => 'Alle Artikel',
          'view_item'           => 'Artikel ansehen',
          'add_new_item'        => 'Neuen Artikel hinzufügen',
          'add_new'             => 'Neuer Artikel',
          'edit_item'           => 'Artikel bearbeiten',
          'update_item'         => 'Artikel aktualisieren',
          'search_items'        => 'Suche Artikel',
          'not_found'           => 'Keine Artikel gefunden',
          'not_found_in_trash'  => 'Keine Artikel gefunden',
      ];

      $args = [
          'label'               => 'press',
          'description'         => 'Artikel Inhalte',
          'labels'              => $labels,
          'supports'            => array( 'title' ),
          'menu_icon'           => 'dashicons-media-spreadsheet',
          'hierarchical'        => false,
          'public'              => false,
          'show_ui'             => true,
          'show_in_menu'        => true,
          'show_in_nav_menus'   => false,
          'show_in_admin_bar'   => false,
          'menu_position'       => 5,
          'can_export'          => true,
          'has_archive'         => false,
          'exclude_from_search' => true,
          'publicly_queryable'  => false,
          'rewrite'             => false,
          'capability_type'     => 'page',
      ];

      register_post_type( 'press', $args );
  }
  add_action( 'init', 'press_post_type', 0 );