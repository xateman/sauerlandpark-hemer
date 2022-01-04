<?php
function poi_post_type() {
  $labels = [
    'name'                => 'POIs',
    'singular_name'       => 'POI',
    'menu_name'           => 'Parkplan',
    'all_items'           => 'Alle POIs',
    'view_item'           => 'POI ansehen',
    'add_new_item'        => 'Neuen POI hinzufügen',
    'add_new'             => 'Neuer POI',
    'edit_item'           => 'POI bearbeiten',
    'update_item'         => 'POI aktualisieren',
    'search_items'        => 'Suche POI',
    'not_found'           => 'Keine POIs gefunden',
    'not_found_in_trash'  => 'Keine POIs gefunden',
  ];

  $rewrite = [
    'slug'                => 'poi',
    'with_front'          => false,
    'pages'               => true,
    'feeds'               => true,
  ];

  $args = [
    'label'               => 'poi',
    'description'         => 'POI Inhalte',
    'labels'              => $labels,
    'supports'            => array( 'title', 'editor' ),
    'menu_icon'           => 'dashicons-location-alt',
    'hierarchical'        => false,
    'public'              => false,
    'show_ui'             => true,
    'show_in_menu'        => true,
    'show_in_nav_menus'   => false,
    'show_in_admin_bar'   => false,
    'menu_position'       => 6,
    'can_export'          => true,
    'has_archive'         => false,
    'exclude_from_search' => true,
    'publicly_queryable'  => false,
    'rewrite'             => false,
    'capability_type'     => 'page',
  ];

  register_post_type( 'poi', $args );
}
add_action( 'init', 'poi_post_type', 0 );

function create_poi_category() {
  $labels = [
    'name'                        => 'Kategorie',
    'singular_name'               => 'Kategorie',
    'search_items'                => 'Kategorie suchen',
    'all_items'                   => 'Alle Kategorien',
    'edit_item'                   => 'Kategorie editieren',
    'update_item'                 => 'Kategorie aktualisieren',
    'add_new_item'                => 'Neue Kategorie hinzufügen',
    'new_item_name'               => 'Neuer Kategorien Name',
    'separate_items_with_commas'  => 'Trenne Kategorien durch Kommas',
    'add_or_remove_items'         => 'Kategorie hinzufügen oder entfernen',
    'choose_from_most_used'       => 'Wähle aus den häufig genutzten Kategorien',
    'not_found'                   => 'Keine Kategorie gefunden.',
    'menu_name'                   => 'Kategorien',
  ];

  $rewrite = [
    'slug'       => 'pois',
    'with_front' => false
  ];

  $args = [
    'hierarchical'      => true,
    'labels'            => $labels,
    'show_ui'           => true,
    'show_admin_column' => true,
    'query_var'         => true,
    'rewrite'           => $rewrite,
  ];
  register_taxonomy( 'poi_tax', 'poi', $args );
}
add_action( 'init', 'create_poi_category', 0 );

function pois_columns_head($columns) {
  $new_columns['cb']      = '<input type="checkbox" />';
  $new_columns['id']      = 'ID';
  $new_columns['title']   = 'Titel';
  $new_columns['poi_tax'] = 'Kategorie';
  $new_columns['date']    = 'Zuletzt geändert';
  return $new_columns;
}
add_filter('manage_edit-poi_columns', 'pois_columns_head');

function pois_columns_content($column_name, $post_ID) {
  if ($column_name == 'id') {
    $id = get_field('marker_id', $post_ID);
    echo $id;
  }
  if ($column_name == 'poi_tax') {
    $categories = get_the_terms( $post_ID, 'poi_tax' );
    if ($categories) {
      foreach($categories as $category) {
        $taxonomies[] = $category->name;
      }
      echo implode(', ', $taxonomies);
    } else {
      echo ' - ';
    }
  }
}
add_action('manage_poi_posts_custom_column', 'pois_columns_content', 10, 2);

function pois_sortable_column( $columns ) {
  $columns['id']      = 'id';
  $columns['poi_tax'] = 'poi_tax';
  return $columns;
}
add_filter('manage_edit-poi_sortable_columns', 'pois_sortable_column');

function poi_orderby( $query ) {
  if( ! is_admin() )
    return;
  $orderby = $query->get( 'orderby');
  if($orderby == 'id') {
    $query->set('meta_key','marker_id');
    $query->set('orderby','meta_value_num');
  }
}
add_action('pre_get_posts', 'poi_orderby');