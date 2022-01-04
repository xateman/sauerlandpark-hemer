<?php



  function gjs_optout_link_sc() {
    return '<a href="javascript:gaOptout()">Google Analytics deaktivieren</a>';
  }
  add_shortcode( 'gaoolink', 'gjs_optout_link_sc' );

  //_____________ALIAZ GMBH ADMIN FOOTER
  function remove_footer_admin () {
    echo 'Developed by <a href="https://aliaz.de" target="_blank">aliaz werbeagentur</a> </p>';
  }
  add_filter('admin_footer_text', 'remove_footer_admin');

  //_____________ALIAZ GMBH DASHBOARD WIDGET
  function aliaz_dashboard_help() {
    echo '
    <p><b>aliaz werbeagentur gmbh</b><br /> 
    Frankfurter Straße 28<br />
    58095 Hagen<br /><br />

    Tel 0 23 31 / 37 57 90<br />
    <a href="mailto:info@aliaz.de">info@aliaz.de</a></p>';
  }
  function aliaz_dashboard_widgets() {
    global $wp_meta_boxes;
    wp_add_dashboard_widget('aliaz_help_widget', '#aliaz', 'aliaz_dashboard_help');
  }
  add_action('wp_dashboard_setup', 'aliaz_dashboard_widgets');

  remove_action('welcome_panel', 'wp_welcome_panel');

  function check_noindex($id) {
    /*
     * Werte von pagefunctions direkt wie benötigt für meta robots
     * dann export via implode direkt in das meta feld
     */
    $page_functions = get_field('page_function', $id);
    if ( is_array($page_functions) && count ($page_functions ) > 0 ) {

      function no_index() {
        echo '<meta name="robots" content="noindex" />';
      }
      add_action( 'wp_head', 'no_index' );
    }
  }

  function formatBytes($bytes, $precision = 2) {
    $units = array('B', 'KB', 'MB', 'GB', 'TB');

    $bytes = max($bytes, 0);
    $pow   = floor(($bytes ? log($bytes) : 0) / log(1024));
    $pow   = min($pow, count($units) - 1);

    // Uncomment one of the following alternatives
    $bytes /= pow(1024, $pow);
    // $bytes /= (1 << (10 * $pow));

    return round($bytes, $precision) . ' ' . $units[$pow];
  }