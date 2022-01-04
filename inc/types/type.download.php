<?php
function download_post_type() {
  $labels = [
    'name'                => 'Downloads',
    'singular_name'       => 'Download',
    'menu_name'           => 'Downloads',
    'all_items'           => 'Alle Downloads',
    'view_item'           => 'Download ansehen',
    'add_new_item'        => 'Neuen Download hinzufügen',
    'add_new'             => 'Neuer Download',
    'edit_item'           => 'Download bearbeiten',
    'update_item'         => 'Download aktualisieren',
    'search_items'        => 'Suche Download',
    'not_found'           => 'Keine Downloads gefunden',
    'not_found_in_trash'  => 'Keine Downloads gefunden',
  ];

  $rewrite = [
    'slug'                => 'file',
    'with_front'          => true,
    'pages'               => true,
    'feeds'               => true,
  ];

  $supports = [
    'title',
    'revisions'
  ];

  $args = [
    'label'               => 'download',
    'description'         => 'Download Inhalte',
    'labels'              => $labels,
    'supports'            => $supports,
    'menu_icon'           => 'dashicons-media-default',
    'hierarchical'        => false,
    'public'              => true,
    'show_ui'             => true,
    'show_in_menu'        => true,
    'show_in_nav_menus'   => true,
    'show_in_admin_bar'   => true,
    'menu_position'       => 6,
    'can_export'          => true,
    'has_archive'         => 'files',
    'exclude_from_search' => false,
    'publicly_queryable'  => true,
    'rewrite'             => $rewrite,
    'capability_type'     => 'page',
  ];

  register_post_type( 'download', $args );
}
add_action( 'init', 'download_post_type', 0 );

function create_download_tax() {
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
    'slug'       => 'downloads',
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
  register_taxonomy( 'download_tax', 'download', $args );
}
add_action( 'init', 'create_download_tax', 0 );

function downloads_columns_head($columns) {
  $new_columns['cb']           = '<input type="checkbox" />';
  $new_columns['id']           = 'ID';
  $new_columns['title']        = 'Titel';
  $new_columns['download_tax'] = 'Kategorie';
  $new_columns['date']         = 'Zuletzt geändert';
  return $new_columns;
}
add_filter('manage_edit-download_columns', 'downloads_columns_head');

function downloads_columns_content($column_name, $post_ID) {
  if ($column_name == 'download_tax') {
    $categories = get_the_terms( $post_ID, 'download_tax' );
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
add_action('manage_download_posts_custom_column', 'downloads_columns_content', 10, 2);

function downloads_sortable_column( $columns ) {
  $columns['id']      = 'id';
  $columns['download_tax'] = 'download_tax';
  return $columns;
}
add_filter('manage_edit-download_sortable_columns', 'downloads_sortable_column');