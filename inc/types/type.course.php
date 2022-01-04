<?php
function course_post_type() {
  $labels = array(
    'name'                => 'Kurse',
    'singular_name'       => 'Kurs',
    'menu_name'           => 'Angebote / Kurse',
    'all_items'           => 'Alle Kurse',
    'view_item'           => 'Kurs ansehen',
    'add_new_item'        => 'Neuen Kurs hinzufügen',
    'add_new'             => 'Neuer Kurs',
    'edit_item'           => 'Kurs bearbeiten',
    'update_item'         => 'Kurs aktualisieren',
    'search_items'        => 'Suche Kurse',
    'not_found'           => 'Keine Kurse gefunden',
    'not_found_in_trash'  => 'Keine Kurse gefunden',
  );

  $rewrite = array(
    'slug'                => 'kurs',
    'with_front'          => true,
    'pages'               => true,
    'feeds'               => true,
  );

  $args = array(
    'label'               => 'course',
    'description'         => 'Kurs Inhalte',
    'labels'              => $labels,
    'supports'            => array( 'title', 'editor', 'thumbnail' ),
    'menu_icon'           => 'dashicons-welcome-learn-more',
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

  register_post_type( 'course', $args );
}
add_action( 'init', 'course_post_type', 0 );


function course_columns_head($columns) {
  $new_columns['cb'] = '<input type="checkbox" />';

  $new_columns['title'] = 'Titel';
  $new_columns['course_id'] = 'Kursnummer';

  $new_columns['last_modified']  = 'Zuletzt geändert';

  return $new_columns;
}
add_filter('manage_edit-course_columns', 'course_columns_head');

function course_columns_content($column_name, $post_ID) {
  if ($column_name == 'course_id') {
    $course_id = get_field('course_id', $post_ID);
    echo $course_id;
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
add_action('manage_course_posts_custom_column', 'course_columns_content', 10, 2);

function course_sortable_column( $columns ) {
  $columns['course_id'] = 'course_id';
  $columns['last_modified'] = 'last_modified';

  return $columns;
}
add_filter('manage_edit-course_sortable_columns', 'course_sortable_column');

function course_orderby( $query ) {
  if( ! is_admin() )
    return;
  $orderby = $query->get( 'orderby');
  if($orderby == 'course_id') {
    $query->set('meta_key','course_id');
    $query->set('orderby','meta_value');
  }
}
add_action('pre_get_posts', 'course_orderby');