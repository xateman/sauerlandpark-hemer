<?php
  function sponsor_post_type() {
    $labels = array(
      'name'                => 'Sponsoren',
      'singular_name'       => 'Sponsor',
      'menu_name'           => 'Sponsoren',
      'all_items'           => 'Alle Sponsoren',
      'view_item'           => 'Sponsor ansehen',
      'add_new_item'        => 'Neuen Sponsor hinzufügen',
      'add_new'             => 'Neuer Sponsor',
      'edit_item'           => 'Sponsor bearbeiten',
      'update_item'         => 'Sponsor aktualisieren',
      'search_items'        => 'Suche Sponsor',
      'not_found'           => 'Keine Sponsoren gefunden',
      'not_found_in_trash'  => 'Keine Sponsoren gefunden',
    );

    $rewrite = array(
      'slug'                => 'sponsor',
      'with_front'          => true,
      'pages'               => true,
      'feeds'               => true,
    );

    $args = array(
      'label'               => 'sponsor',
      'description'         => 'Sponsor Inhalte',
      'labels'              => $labels,
      'supports'            => array( 'title', 'page-attributes' ),
      'menu_icon'           => 'dashicons-megaphone',
      'hierarchical'        => false,
      'public'              => true,
      'show_ui'             => true,
      'show_in_menu'        => true,
      'show_in_nav_menus'   => true,
      'show_in_admin_bar'   => true,
      'menu_position'       => 5,
      'can_export'          => true,
      'has_archive'         => true,
      'exclude_from_search' => true,
      'publicly_queryable'  => true,
      'rewrite'             => $rewrite,
      'capability_type'     => 'page',
    );

    register_post_type( 'sponsor', $args );
  }
  add_action( 'init', 'sponsor_post_type', 0 );

  /*

  function sponsors_columns_head($columns) {
    $new_columns['cb'] = '<input type="checkbox" />';

    $new_columns['title'] = 'Titel';
    $new_columns['sponsor_art'] = 'Sponsor-Art';

    $new_columns['date'] = 'Datum';

    return $new_columns;
  }

  // SHOW THE FEATURED IMAGE
  function sponsors_columns_content($column_name, $post_ID) {
    if ($column_name == 'sponsor_art') {
      $page_fields = get_field_object('sponsor_type', $post_ID);
      echo $page_fields['choices'][$page_fields['value']];
    }  
  }

  function sponsors_sortable_column( $columns ) {
    $columns['sponsor_art'] = 'sponsor_art';

    return $columns;
  }

  function sponsors_orderby( $query ) {
    if( ! is_admin() )
      return;

    $orderby = $query->get( 'orderby');

    if( 'sponsor_art' == $orderby ) {
      $query->set('meta_key','sponsor_type');
      $query->set('orderby','meta_value');
    }
  }

  add_filter('manage_edit-sponsor_columns', 'sponsors_columns_head');
  add_filter('manage_edit-sponsor_sortable_columns', 'sponsors_sortable_column');
  add_action('pre_get_posts', 'sponsors_orderby');

  add_action('manage_sponsor_posts_custom_column', 'sponsors_columns_content', 10, 2);

  */