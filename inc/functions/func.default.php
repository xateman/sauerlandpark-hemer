<?php

  //date_default_timezone_set('Europe/Berlin');

  //_____________TRANSIENT TIME SETUP
  /*
  MINUTE_IN_SECONDS  = 60 (seconds)
  HOUR_IN_SECONDS    = 60 * MINUTE_IN_SECONDS
  DAY_IN_SECONDS     = 24 * HOUR_IN_SECONDS
  WEEK_IN_SECONDS    = 7 * DAY_IN_SECONDS
  YEAR_IN_SECONDS    = 365 * DAY_IN_SECONDS
  */
  define('LONG_TRANSIENT', DAY_IN_SECONDS);
  define('MID_TRANSIENT', HOUR_IN_SECONDS);
  define('SHORT_TRANSIENT', 15 * MINUTE_IN_SECONDS);

  function get_path ( $folder = 'default', $type = 'url' ) {

    if ( $type == 'url' ) {
      $base_path = get_template_directory_uri();
    } else {
      $base_path = get_template_directory();
    }
    if ($folder === 'default') {
      return $base_path;
    } else {
      return $base_path . '/' . $folder;
    }
  }

  function the_path ( $folder = 'default', $type = 'url' ) {
    echo get_path ( $folder, $type );
  }

  function get_el($str, $class = '', $id = '', $type = 'div') {
    if (empty($str) ) {
      return;
    } else {
      $attr = '';
      if ($id) {
        $attr .= ' id="'.$id.'"';
      }
      if ($class) {
        $attr .= ' class="'.$class.'"';
      }
      return '<'.$type.' '.$attr.'>'.$str.'</'.$type.'>';
    }
  }

  function the_el($str, $class = '', $id = '', $type = 'div') {
    echo get_el($str, $class, $id, $type);
  }


  function element_location() {
    if ( is_front_page() ) {

      $location = 'home';

    } else if ( is_home() ) {

      $location = 'blog';

    } else {

      if ( is_archive() || is_page_template('templates/tpl-events-overview.php') || is_page_template('templates/tpl-news-overview.php') ) {

        $location = 'page archive';

      } else if ( is_page() ) {

        $location = 'page single';

      } else if ( is_single() ) {

        $location = 'post single post-type-' . get_post_type();

      } else if ( is_search() ) {

        $location = 'page search';

      } else if ( is_404() ) {

        $location = 'page error';

      }

    }
    return $location;
  }

  function get_pagination($query, $maxPosts, $postType) {
    if (!$query) {
      return false;
    }
    if (!$postType) {
      $postType = get_post_type();
    }
    if ( $query->found_posts >= $maxPosts ) {
      $big = 999999999;
      $args = [
        'base'      => str_replace( $big, '%#%', esc_url( get_pagenum_link( $big ) ) ),
        'prev_text' => '<span class="fal fa-long-arrow-left"></span>',
        'next_text' => '<span class="fal fa-long-arrow-right"></span>',
        'current'   => max( 1, get_query_var('paged') ),
        'total'     => $query->max_num_pages,
        'type'      => 'plain'
      ];
      $pagination = paginate_links($args);
      $out = "<div class='paging'><div class='pagination $postType-pagination'>$pagination</div></div>";
      return $out;
    } else {
      return false;
    }
  }

  function walk_subpages( $post, $depth = 0, $tree = [] ) {
    $parent = $post->post_parent;
    if ( $parent > 0 ) {

      $parent_post = get_post($parent);
      $tree[] = (object)[
        'url'   => get_the_permalink($parent),
        'title' => get_the_title($parent),
        'depth' => $depth,
        'id'    => $parent_post->ID
      ];
      $depth++;
      return walk_subpages($parent_post, $depth, $tree);

    } else {
      $out = array_reverse($tree);
      return $out;
    }
  }

  function get_data_from_url($data_url, $type = 'json', $assoc = TRUE) {

    $connection = curl_init();
    curl_setopt($connection, CURLOPT_SSL_VERIFYPEER, false);
    curl_setopt($connection, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($connection, CURLOPT_URL, htmlspecialchars_decode($data_url));
    $result = curl_exec($connection);
    curl_close($connection);

    if ($type == 'json') {
      return json_decode($result, $assoc);
    } else {
      return $result;
    }

  }

  function unique_multi_array($array, $key) {
    $temp_array = array();
    $i = 0;
    $key_array = array();

    foreach($array as $val) {
      if (!in_array($val[$key], $key_array)) {
        $key_array[$i] = $val[$key];
        $temp_array[$i] = $val;
      }
      $i++;
    }
    return $temp_array;
  }

  function in_between($val, $smaller, $larger) {
    if ( ($val >= $smaller) && ($val <= $larger) ) {
      return true;
    } else {
      return false;
    }
  }

  function add_script_attributes($tag, $handle) {
    preg_match('/(\#[a-z]+)$/', $handle, $matches);
    if ( isset ( $matches[0] ) ) {
      switch ( $matches[0] ) {
        case '#ad':
          return str_replace( ' src', ' async defer src', $tag );
          break;
        case '#a':
          return str_replace( ' src', ' async src', $tag );
          break;
        case '#d':
          return str_replace( ' src', ' defer src', $tag );
          break;
        case '#jsx':
          return str_replace( 'text/javascript', 'text/jsx', $tag );
          break;
        default:
          return $tag;
          break;
      }
    } else {
      return $tag;
    }
  }
  add_filter('script_loader_tag', 'add_script_attributes', 10, 2);

  /*
   * function - e-mail and domain prettify function
   *
   * @param $data     - (str) of full mail address / full URL including http(s)
   * @param $type     - (str) "email" or "url"
   * @param $style    - (str) "default" or "custom"
   * @param $elements - (arr) ("dot" => "(dot)", "at" => "(at)") // (arr) "text" => "contact us" only possible if $style is set to "custom"
   * @return string   - formatted pretty output.
   */
  function format_link( $data, $type = 'email', $style = 'default', $elements = [] )
  {

    $defaults = [
      'dot'  => ' . ',
      'at'   => ' [at] ',
      'text' => false
    ];

    $e = array_merge($defaults, $elements);

    if ( $type == 'email' )
    {
      if ( $style == 'custom' && $e['text'] )
      {
        $link_text = $e['text'];
      }
      else
      {
        $link_text = $data;
        if ($e['dot'] != '.')
        {
          $link_text = preg_replace("/[.]/", $e['dot'], $link_text);
        }
        $link_text = preg_replace("/[@]/", $e['at'], $link_text);
      }
      return '<a href="mailto:'.$data.'">'.$link_text.'</a>';
    }
    else if ( $type == 'url' )
    {
      if ( $style == 'custom' && $e['text'] )
      {
        $link_text = $e['text'];
      }
      else
      {
        $replace_table = array(
          'https://www' => 'www',
          'http://www'  => 'www',
          'https://'    => 'www',
          'http://'     => 'www'
        );
        $link_text = strtr($data, $replace_table);
      }
      return '<a href="'.$data.'" target="_blank">'.$link_text.'</a>';
    }
  }

  //_____________ simple string cleanup
  function clean_string($string){
    $sonderzeichen = [
      '&' => '-',
      'ä' => 'ae',
      'ü' => 'ue',
      'ö' => 'oe',
      'Ä' => 'Ae',
      'Ü' => 'Ue',
      'Ö' => 'Oe'
    ];
    $output = strtr($string, $sonderzeichen);
    $output = str_replace(' ', '-', $output);
    $output = preg_replace("/-{1,}/", "-", $output);;
    $output = preg_replace('/[^A-Za-z0-9\-]/', '', $output);
    $output = strtolower($output);
    return $output;
  }

  function title_to_slug($string) {
    $replace = array(
      '-' => '',
      ' ' => '-',
      '_' => ''
    );
    $temp = clean_string($string);
    $output = strtr($temp, $replace);
    return $output;
  }

  function clean_dashes($string) {
    $replace = array(
      '-' => '',
      ' ' => '',
      '_' => ''
    );
    $temp = clean_string($string);
    $output = strtr($temp, $replace);
    return $output;
  }

  function print_pre($array) {

    echo "<pre>";
    print_r($array);
    echo "</pre>";

  }

  function __debug($input, $style = 'print_r', $output = 'hidden') {

    $attr = "";

    switch ($output) {
      case "hidden":
        $attr = "style='display:none;'";
        break;
    }

    if ( is_array($input) ) {
      echo "<pre $attr>";
      print_r($input);
      echo "</pre>";
    } else {
      echo "<pre $attr>";
      var_dump($input);
      echo "</pre>";
    }

  }

  // logic handlers
  function get_ID_by_slug($page_slug) {
    $page = get_page_by_path($page_slug);
    if ($page) {
      return $page->ID;
    } else {
      return null;
    }
  }

  function the_slug($id) {
    $post_data = get_post($id, ARRAY_A);
    $slug = $post_data['post_name'];
    return $slug;
  }

  function hex2rgb($hexStr, $returnAsString = false, $seperator = ',') {
    $hexStr = preg_replace("/[^0-9A-Fa-f]/", '', $hexStr); // Gets a proper hex string
    $rgbArray = array();
    if (strlen($hexStr) == 6) { //If a proper hex code, convert using bitwise operation. No overhead... faster
      $colorVal = hexdec($hexStr);
      $rgbArray['red']   = 0xFF & ($colorVal >> 0x10);
      $rgbArray['green'] = 0xFF & ($colorVal >> 0x8);
      $rgbArray['blue']  = 0xFF & $colorVal;
    } elseif (strlen($hexStr) == 3) { //if shorthand notation, need some string manipulations
      $rgbArray['red']   = hexdec(str_repeat(substr($hexStr, 0, 1), 2));
      $rgbArray['green'] = hexdec(str_repeat(substr($hexStr, 1, 1), 2));
      $rgbArray['blue']  = hexdec(str_repeat(substr($hexStr, 2, 1), 2));
    } else {
      return false; //Invalid hex color code
    }
    return $returnAsString ? implode($seperator, $rgbArray) : $rgbArray; // returns the rgb string or the associative array
  }

  //_____________ echo month name in current language by month number short and long variant possible
  function get_month_name($month_num, $type = 'short') {
    $month_names = array(
      '01' => array(
        'short' => __( 'Jan', LOCAL ),
        'long'  => __( 'Januar', LOCAL ),
      ),
      '02' => array(
        'short' => __( 'Feb', LOCAL ),
        'long'  => __( 'Februar', LOCAL ),
      ),
      '03' => array(
        'short' => __( 'Mrz', LOCAL ),
        'long'  => __( 'März', LOCAL ),
      ),
      '04' => array(
        'short' => __( 'Apr', LOCAL ),
        'long'  => __( 'April', LOCAL ),
      ),
      '05' => array(
        'short' => __( 'Mai', LOCAL ),
        'long'  => __( 'Mai', LOCAL ),
      ),
      '06' => array(
        'short' => __( 'Jun', LOCAL ),
        'long'  => __( 'Juni', LOCAL ),
      ),
      '07' => array(
        'short' => __( 'Jul', LOCAL ),
        'long'  => __( 'Juli', LOCAL ),
      ),
      '08' => array(
        'short' => __( 'Aug', LOCAL ),
        'long'  => __( 'August', LOCAL ),
      ),
      '09' => array(
        'short' => __( 'Sep', LOCAL ),
        'long'  => __( 'September', LOCAL ),
      ),
      '10' => array(
        'short' => __( 'Okt', LOCAL ),
        'long'  => __( 'Oktober', LOCAL ),
      ),
      '11' => array(
        'short' => __( 'Nov', LOCAL ),
        'long'  => __( 'November', LOCAL ),
      ),
      '12' => array(
        'short' => __( 'Dez', LOCAL ),
        'long'  => __( 'Dezember', LOCAL ),
      ),
    );
    $output = $month_names[$month_num][$type];
    return $output;
  }

  //_____________ echo weekday name in current language by weekday number
  function get_weekday_name($weekday_no,$type) {
    $week = array(
      '1' => array(
        'short' => __( 'Mo', LOCAL ),
        'long'  => __( 'Montag', LOCAL ),
      ),
      '2' => array(
        'short' => __( 'Di', LOCAL ),
        'long'  => __( 'Dienstag', LOCAL ),
      ),
      '3' => array(
        'short' => __( 'Mi', LOCAL ),
        'long'  => __( 'Mittwoch', LOCAL ),
      ),
      '4' => array(
        'short' => __( 'Do', LOCAL ),
        'long'  => __( 'Donnerstag', LOCAL ),
      ),
      '5' => array(
        'short' => __( 'Fr', LOCAL ),
        'long'  => __( 'Freitag', LOCAL ),
      ),
      '6' => array(
        'short' => __( 'Sa', LOCAL ),
        'long'  => __( 'Samstag', LOCAL ),
      ),
      '7' => array(
        'short' => __( 'So', LOCAL ),
        'long'  => __( 'Sonntag', LOCAL ),
      )
    );
    if ($type) {
      $output = $week[$weekday_no][$type];
    } else {
      $output = $week[$weekday_no]['short'];
    }
    return $output;
  }

  function encode_to_utf8_if_needed($string) {
    $encoding = mb_detect_encoding($string, 'UTF-8, ISO-8859-9, ISO-8859-1');
    if ($encoding != 'UTF-8') {
      $string = mb_convert_encoding($string, 'UTF-8', $encoding);
    }
    return $string;
  }
