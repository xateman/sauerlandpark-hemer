<?php

function get_copyright_info($id, $id_type = 'post') {
  if (!$id) {
    return false;
  }
  if ( $id_type == 'post' ) {
    $thumb_id = get_post_thumbnail_id( $id );
  } else if ( $id_type == 'img') {
    $thumb_id = $id;
  }

  $alt  = get_post( $thumb_id );

  if ($alt->post_content) {
    $copyright = $alt->post_content;
  } else {
    $meta = wp_get_attachment_metadata( $thumb_id );
    if ( $meta['image_meta']['copyright'] ) {
      $copyright = $meta['image_meta']['copyright'];
    } else {
      $copyright = false;
    }
  }
  return $copyright;
}

function get_contact_info($type) {
  if (!$type) {
    return false;
  }
  $info = get_field('default-' . $type, 'option');
  return $info;
}

function get_category_icon($id) {
  $type = get_field('source', $id);
  if ($type === 'fa') {
    $code = get_field('icon_code', $id);
    $icon = '<span class="icon '.$code.'"></span>';
  } else {
    $code   = get_field('svg_code', $id);
    $width  = get_field('svg_width', $id);
    $height = get_field('svg_height', $id);
    $icon = '<svg class="icon icon-'.$id.'" width="'.$width.'" height="'.$height.'" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 '.$width.' '.$height.'">'.$code.'</svg>';
  }
  return $icon;
}

function the_category_icon($id) {
  echo get_category_icon($id);
}

function get_topics($id, $taxonomy) {
  $topic_args = array(
    'orderby' => 'name',
    'order'   => 'ASC',
    'fields'  => 'all'
  );
  $topics       = wp_get_post_terms( $id, $taxonomy, $topic_args );
  $topic_list   = '';
  $index        = 0;
  $topics_count = count($topics);
  foreach ($topics as $topic) {
    $index++;
    $topic_list .= '<a href="'.get_bloginfo('url').'/archiv/'.$topic->slug.'">'.$topic->name.'</a>';
    if ($index < $topics_count) {
      $topic_list .= ', ';
    }
  }
  return $topic_list;
}

// note: requires func.constants.php (PRICE)
/*
  adult
  child
  pupil
  family
  group_adult
  group_child
  group_pupil
 */
function get_price($price) {
  $temp = number_format($price, 2, ',', '.');
  return $temp . ' €';
}

function the_price($price) {
  echo get_price($price);
}

function price_shortcode( $atts ) {
  $a = shortcode_atts( array(
    'id' => 'adult'
  ), $atts );
  return get_price( PRICE[ $a['id'] ] );
}
add_shortcode( 'preis', 'price_shortcode' );

function get_event_date($date_start, $date_end = false, $time_start = false, $time_end = false, $month_type = 'num') {
  $weekday_start = date('N', strtotime($date_start));
  $out['start'] = array(
    'y' => substr($date_start, 0, 4),
    'm' => substr($date_start, 5, 2),
    'd' => substr($date_start, -2)
  );
  $out['start']['eu'] = $out['start']['d'].'.'.$out['start']['m'].'.'.$out['start']['y'];
  $out['start']['us'] = $out['start']['m'].'/'.$out['start']['d'].'/'.$out['start']['y'];
  $out['start']['weekday'] = array(
    'long'  => get_weekday_name($weekday_start,'long'),
    'short' => get_weekday_name($weekday_start,'short')
  );
  $out['start']['month'] = array(
    'long'  => get_month_name($out['start']['m'],'long'),
    'short' => get_month_name($out['start']['m'],'short')
  );
  switch ($month_type) {
    case 'num' :
      $string = $out['start']['weekday']['long'].', '.$out['start']['eu'];
      break;
    case 'short' :
      $string = $out['start']['weekday']['long'].', '.$out['start']['d'].'. '.$out['start']['month']['short'].' '.$out['start']['y'];
      break;
    case 'long' :
      $string = $out['start']['weekday']['long'].', '.$out['start']['d'].'. '.$out['start']['month']['long'].' '.$out['start']['y'];
      break;
  }
  if (!empty($date_end)) {
    $weekday_end = date('N', strtotime($date_end));
    $out['end'] = array(
      'y' => substr($date_end, 0, 4),
      'm' => substr($date_end, 5, 2),
      'd' => substr($date_end, -2)
    );
    $out['end']['eu'] = $out['end']['d'].'.'.$out['end']['m'].'.'.$out['end']['y'];
    $out['end']['us'] = $out['end']['m'].'/'.$out['end']['d'].'/'.$out['end']['y'];
    $out['end']['weekday'] = array(
      'long'  => get_weekday_name($weekday_end,'long'),
      'short' => get_weekday_name($weekday_end,'short')
    );
    $out['end']['month'] = array(
      'long'  => get_month_name($out['end']['m'],'long'),
      'short' => get_month_name($out['end']['m'],'short')
    );
    $string = $out['start']['d'];
    if ( ($out['end']['y'] === $out['start']['y']) && ($out['end']['m'] === $out['start']['m']) ) {
      // same year - same month
      switch ($month_type) {
        case 'num' :
          $string .= '. - '.$out['end']['eu'];
          break;
        case 'short' :
          $string .= '. - '.$out['end']['d'].'. '.$out['start']['month']['short'].' '.$out['start']['y'];
          break;
        case 'long' :
          $string .= '. - '.$out['end']['d'].'. '.$out['start']['month']['long'].' '.$out['start']['y'];
          break;
      }
    } elseif ( ($out['end']['y'] === $out['start']['y']) && ($out['end']['m'] !== $out['start']['m']) ) {
      // same year - different month
      switch ($month_type) {
        case 'num' :
          $string .= '.'.$out['start']['m'].' - '.$out['end']['d'].'.'.$out['end']['m'].'.'.$out['end']['y'];
          break;
        case 'short' :
          $string .= '. '.$out['start']['month']['short'].' - '.$out['end']['d'].'. '.$out['end']['month']['short'].' '.$out['end']['y'];
          break;
        case 'long' :
          $string .= '. '.$out['start']['month']['long'].' - '.$out['end']['d'].'. '.$out['end']['month']['long'].' '.$out['end']['y'];
          break;
      }
    } else {
      // different year
      $string .= '.'.$out['start']['m'].'.'.$out['start']['y'].' - '.$out['end']['eu'];
    }
  }
  if ($time_start) {
    $out['time']['start'] = $time_start;
    $string .= ' | '.$time_start;
    if ($time_end) {
      $out['time']['end'] = $time_end;
      $string .= ' - '.$time_end;
    }
    $string .= ' '._x('Uhr','Angabe nach der Uhrzeit im 24h Format', LOCAL);
  }
  $out['string'] = $string;
  return $out;
}

function events_date_query($query) {
  if ( !is_admin() && $query->is_post_type_archive('event') && $query->is_main_query() ) {
    if ( !empty($_GET['d']) ) {
      $get_date = $_GET['d'];
      $cal_days = cal_days_in_month(CAL_GREGORIAN, substr($get_date, -2), substr($get_date, 4));
      $month_start = $get_date . '-01';
      $month_end   = $get_date . '-' . $cal_days;

      $meta_query = [
        'relation'     => 'OR',
        array(
          'key'        => 'cal_date_start',
          'value'      => array($month_start, $month_end),
          'compare'    => 'BETWEEN',
          'type'       => 'DATE',
        ),
        array(
          'key'        => 'cal_date_end',
          'value'      => array($month_start, $month_end),
          'compare'    => 'BETWEEN',
          'type'       => 'DATE',
        ),
      ];
      $query->set( 'meta_query', $meta_query );
    } else if  (!$query->is_tax('event_tax') ) {
      $today = date( 'Y-m-d', current_time( 'timestamp', 1 ));
      $meta_query = [
        'relation'     => 'OR',
        array(
          'key'        => 'cal_date_start',
          'value'      => $today,
          'compare'    => '>=',
          'type'       => 'DATE',
        ),
        array(
          'key'        => 'cal_date_end',
          'value'      => $today,
          'compare'    => '>=',
          'type'       => 'DATE',
        ),
      ];

      $meta_query = [
        'relation'     => 'OR',
        array(
          'key'        => 'hide_post-in-view-cats',
          'value'      => 1,
          'compare'    => '!='
        ),
        array(
          'key'        => 'hide_post-in-view-cats',
          'compare'    => 'NOT EXISTS'
        ),
      ];

      $query->set( 'meta_query', $meta_query );
    }
    $query->set( 'meta_key', 'cal_date_start' );
    $query->set( 'orderby', 'meta_value' );
    $query->set( 'order', 'ASC' );

  } else {

    return $query;

  }
}
add_action( 'pre_get_posts', 'events_date_query' );


function navigation_add_walker( $args ) {
  return array_merge( $args,
    [
      'walker' => new customWalker()
    ]
  );
}
add_filter( 'wp_nav_menu_args', 'navigation_add_walker' );

function simple_password_form() {
  global $post;
  $o = '<form action="' . esc_url( site_url( 'wp-login.php?action=postpass', 'login_post' ) ) . '" method="post">
    ' . __( "To view this protected post, enter the password below:" ) . '
    <label for="password-input">' . __( "Password:" ) . ' </label><input name="post_password" id="password-input" type="password" size="20" maxlength="20" /><input type="submit" name="Submit" 
    value="' .
    esc_attr__( "Submit" ) . '" />
    </form>
    ';
  return $o;
}
add_filter( 'the_password_form', 'simple_password_form' );