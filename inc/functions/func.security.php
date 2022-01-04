<?php

  // SECURITY SETTINGS

  /*
   * DISABLE REST ENDPOINTS
   * need to be enabled for wordpress blocks to function
  add_filter( 'rest_endpoints', 'remove_default_endpoints' );
  function remove_default_endpoints( $endpoints ) {
    return array( );
  }
  */

  // remove users rest endpoints
  function configure_rest_endpoints( $endpoints ) {
    if ( isset( $endpoints['/wp/v2/users'] ) ) {
      unset( $endpoints['/wp/v2/users'] );
    }
    if ( isset( $endpoints['/wp/v2/users/(?P<id>[\d]+)'] ) ) {
      unset( $endpoints['/wp/v2/users/(?P<id>[\d]+)'] );
    }
    return $endpoints;
  }
  add_filter( 'rest_endpoints', 'configure_rest_endpoints' );

  // change default rest access route from wp-json
  function rest_url_prefix() {
    return 'api';
  }
  add_filter( 'rest_url_prefix', 'rest_url_prefix' );

  // REDIRECT AUTHOR TESTS
  function author_page_redirect() {
    if ( is_author() ) {
      wp_redirect( home_url() );
    }
  }
  add_action( 'template_redirect', 'author_page_redirect' );

  // REMOVE XMLRPC
  add_filter( 'xmlrpc_enabled', '__return_false' );

  function remove_xmlrpc_pingback( $headers ) {
    unset( $headers['X-Pingback'] );
    return $headers;
  }
  add_filter( 'wp_headers', 'remove_xmlrpc_pingback' );

  // REMOVE VERSION INFO FROM HEAD AND FEEDS
  remove_action('wp_head', 'wp_generator');
  function complete_version_removal() {
    return '';
  }
  add_filter('the_generator', 'complete_version_removal');