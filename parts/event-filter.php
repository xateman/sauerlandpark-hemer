<?php
$n = 0;

$type = get_query_var( 'event_type' );

$args_sidebar_events = array (
  'post_type'        => 'event',
  'posts_per_page'   => -1,
  'suppress_filters' => 0,
  'meta_key'         => 'cal_date_start',
  'orderby'          => 'meta_value',
  'order'            => 'DESC',
);

if ( $type == 'highlight' ) {
  $args_sidebar_events['meta_query'] = array(
    array(
      'key'        => 'cal_is_highlight',
      'value'      => 1,
      'compare'    => '=',
    )
  );
} else if ( $type == 'culture' ) {
  $args_sidebar_events['meta_query'] = array(
    array(
      'key'        => 'cal_is_culture',
      'value'      => 1,
      'compare'    => '=',
    )
  );
} else if ( $type == 'soundgarten' ) {
  $args_sidebar_events['meta_query'] = array(
    array(
      'key'        => 'cal_is_soundgarten',
      'value'      => 1,
      'compare'    => '=',
    )
  );
}
$events = get_posts( $args_sidebar_events );
if ( $events ) {
  foreach ( $events as $post ) {
    setup_postdata( $post );
    $range[][date('Y', strtotime(get_field('cal_date_start')))] = date('m', strtotime(get_field('cal_date_start')));
  }
  foreach($range as $n => $years) {
    $scope_years[] = key($years);
    foreach ($years as $year => $month) {
      $scope_months[] = $year.'-'.$month;
    }
  }
  $scope_years  = array_unique($scope_years);
  $scope_months = array_unique($scope_months);
  ?>
  <div class="dropdown-element events-filter">
    <div class="toggle-list current-filter">Datum wählen</div>
    <ul class="item-list">
      <?php if (isset($_GET['d'])) { ?>
        <li class="current">
          <a href="<?php bloginfo('url'); ?>/kalender/"><?php _e('Aktuelle Events',LOCAL); ?></a>
        </li>
      <?php } ?>
      <?php foreach ($scope_years as $years => $year) {
        settype($year,'string');
        foreach ($scope_months as $month) {
          if (strpos($month, $year) !== false) {
            $link  = get_bloginfo('url').'/kalender/?d='.$month;
            $title = get_month_name(substr($month,-2),'long').' '.$year;
            if ( isset($_GET['d']) && $_GET['d'] === $month ) {
              echo '<li class="archive-month"><span class="current">'.$title.'</span></li>';
            } else {
              echo '<li class="archive-month"><a href="'.$link.'">'.$title.'</a></li>';
            }
          }
        }
      } ?>
    </ul>
  </div>
<?php }
wp_reset_postdata();