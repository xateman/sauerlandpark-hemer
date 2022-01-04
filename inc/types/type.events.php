<?php
  function event_post_type() {
    $labels = array(
      'name'                => 'Termine',
      'singular_name'       => 'Termin',
      'menu_name'           => 'Kalender',
      'all_items'           => 'Alle Termine',
      'view_item'           => 'Termin ansehen',
      'add_new_item'        => 'Neuen Termin hinzufügen',
      'add_new'             => 'Neuer Termin',
      'edit_item'           => 'Termin bearbeiten',
      'update_item'         => 'Termin aktualisieren',
      'search_items'        => 'Suche Termin',
      'not_found'           => 'Keine Termine gefunden',
      'not_found_in_trash'  => 'Keine Termine gefunden',
    );

    $rewrite = array(
      'slug'                => 'event',
      'with_front'          => false,
      'pages'               => true,
      'feeds'               => true,
    );

    $args = array(
      'label'               => 'event',
      'description'         => 'Termin Inhalte',
      'labels'              => $labels,
      'supports'            => array( 'title', 'editor' ),
      'menu_icon'           => 'dashicons-calendar-alt',
      'hierarchical'        => false,
      'public'              => true,
      'show_ui'             => true,
      'show_in_menu'        => true,
      'show_in_nav_menus'   => true,
      'show_in_admin_bar'   => true,
      'menu_position'       => 5,
      'can_export'          => true,
      'has_archive'         => 'kalender',
      'exclude_from_search' => false,
      'publicly_queryable'  => true,
      'rewrite'             => $rewrite,
      'capability_type'     => 'page',
    );

    register_post_type( 'event', $args );
  }
  add_action( 'init', 'event_post_type', 0 );

  function create_event_category() {
    $labels = array(
      'name'                        => 'Rubrik',
      'singular_name'               => 'Rubrik',
      'search_items'                => 'Rubrik suchen',
      'all_items'                   => 'Alle Rubriken',
      'edit_item'                   => 'Rubrik editieren',
      'update_item'                 => 'Rubrik aktualisieren',
      'add_new_item'                => 'Neue Rubrik hinzufügen',
      'new_item_name'               => 'Neuer Rubrik Name',
      'separate_items_with_commas'  => 'Trenne Rubriken durch Kommas',
      'add_or_remove_items'         => 'Rubrik hinzufügen oder entfernen',
      'choose_from_most_used'       => 'Wähle aus den häufig genutzten Rubriken',
      'not_found'                   => 'Keine Rubrik gefunden.',
      'menu_name'                   => 'Rubrik',
    );

    $rewrite = [
      'slug'       => 'rubrik',
      'with_front' => false
    ];

    $args = array(
      'hierarchical'      => true,
      'labels'            => $labels,
      'public'            => true,
      'show_ui'           => true,
      'show_admin_column' => true,
      'query_var'         => true,
      'rewrite'           => $rewrite,
    );
    register_taxonomy( 'event_tax', 'event', $args );
  }
  add_action( 'init', 'create_event_category', 0 );

  function events_columns_head($columns) {
    $new_columns['cb']            = '<input type="checkbox" />';
    $new_columns['title']         = 'Titel';
    $new_columns['event_start']   = 'Termin Datum';
    $new_columns['event_tax']     = 'Kategorie';
    $new_columns['last_modified'] = 'Zuletzt geändert';
    return $new_columns;
  }
  add_filter('manage_edit-event_columns', 'events_columns_head');

  function events_columns_content($column_name, $post_ID) {
    if ($column_name == 'event_start') {
      $page_fields = get_field_object('cal_date_start', $post_ID);
      echo date($page_fields['display_format'], strtotime($page_fields['value']));
    }
    if ($column_name == 'event_tax') {
      $categories = get_the_terms( $post_ID, 'event_tax' );
      if ($categories) {
        foreach($categories as $category) {
          $taxonomies[] = $category->name;
        }
        echo implode(', ', $taxonomies);
      } else {
        echo ' - ';
      }
    }
    if ( $column_name == 'last_modified' ) {
      $modified_datetime = get_the_modified_date( 'd.m.Y H:i' );
      $modified_date     = get_the_modified_date( 'd.m.Y' );
      $modified_author   = get_the_modified_author();
      if ( $modified_author == '' ) {
        $modified_author = get_the_author();
      }
      echo "Aktualisiert von <strong>$modified_author</strong><br/><abbr title='$modified_datetime'>$modified_date</abbr>";
    }
  }
  add_action('manage_event_posts_custom_column', 'events_columns_content', 10, 2);

  function events_sortable_column( $columns ) {
    $columns['event_start'] = 'event_start';
    return $columns;
  }
  add_filter('manage_edit-event_sortable_columns', 'events_sortable_column');

  function events_orderby( $query ) {
    if( ! is_admin() )
      return;
    $orderby = $query->get( 'orderby');
    if($orderby == 'event_start') {
      $query->set('meta_key','cal_date_start');
      $query->set('orderby','meta_value');
    }
  }
  add_action('pre_get_posts', 'events_orderby');