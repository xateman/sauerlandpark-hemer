<?php
  add_filter( 'manage_media_columns', 'az_media_columns_filesize' );
  /**
   * Filter the Media list table columns to add a File Size column.
   *
   * @param array $posts_columns Existing array of columns displayed in the Media list table.
   * @return array Amended array of columns to be displayed in the Media list table.
   */
  function az_media_columns_filesize( $posts_columns ) {
    $posts_columns['filesize'] = 'Größe';
  
    return $posts_columns;
  }
  
  add_action( 'manage_media_custom_column', 'az_media_custom_column_filesize', 10, 2 );
  /**
   * Display File Size custom column in the Media list table.
   *
   * @param string $column_name Name of the custom column.
   * @param int    $post_id Current Attachment ID.
   */
  function az_media_custom_column_filesize( $column_name, $post_id ) {
    if ( 'filesize' !== $column_name ) {
      return;
    }
  
    $bytes = filesize( get_attached_file( $post_id ) );
  
    echo size_format( $bytes, 2 );
  }
  
  add_action( 'admin_print_styles-upload.php', 'az_filesize_column_filesize' );
  /**
   * Adjust File Size column on Media Library page in WP admin
   */
  function az_filesize_column_filesize() {
    echo
    '<style>
      .fixed .column-filesize {
        width: 10%;
      }
    </style>';
  }