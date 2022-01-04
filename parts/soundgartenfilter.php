<?php
  $n = 0;
  $args_sidebar_events = array (
    'post_type'        => 'event',
    'posts_per_page'   => -1,
    'suppress_filters' => 0,
    'meta_query'     => array(
      array(
        'key'        => 'cal_is_soundgarten',
        'value'      => 1,
        'compare'    => '=',
      ),
    ),
    'meta_key'         => 'cal_date_start',
    'orderby'          => 'meta_value',
    'order'            => 'DESC',
  );
  $events = get_posts( $args_sidebar_events );
  if ( $events ) :
  foreach ( $events as $post ) :
    setup_postdata( $post );
    $range[][date('Y', strtotime(get_field('cal_date_start')))] = date('m', strtotime(get_field('cal_date_start')));
  endforeach;
  foreach($range as $n => $years) :
    $scope_years[] = key($years);
    foreach ($years as $year => $month) :
      $scope_months[] = $year.'-'.$month;
    endforeach;
  endforeach;
  $scope_years = array_unique($scope_years);
  $scope_months = array_unique($scope_months);
?>
<div class="events-filter">
  <?php $get_date = $_GET['d']; ?>
  <?php if (!empty($get_date)) { ?>
    <div class="current-filter">
      <?php echo get_month_name(substr($get_date, -2),'long').' '.substr($get_date, 0, 4); ?>
      <div class="status">
        <span class="fa fa-angle-down closed"></span>
        <span class="fa fa-angle-up opened"></span>
      </div>
    </div>
  <?php } else { ?>
    <div class="current-filter">
      <?php _e('Alle Events',LOCAL); ?>
      <div class="status">
        <span class="fa fa-angle-down closed"></span>
        <span class="fa fa-angle-up opened"></span>
      </div>
    </div>
  <?php } ?>
  <ul class="filter-list">
  <?php if (!empty($get_date)) { ?>
    <li><a href="<?php bloginfo('url'); ?>/events-tickets/soundgarten/"><?php _e('Alle Events',LOCAL); ?></a></li>
  <?php } ?>
  <?php foreach ($scope_years as $years => $year) :
      settype($year,'string');
      foreach ($scope_months as $month) :
        if (strpos($month, $year) !== false) {
          if ($get_date == $month) {
            //echo '<li class="archive-month current-item">'.get_month_name(substr($month,-2),'long').' '.$year.'</li>';
          } else {
            echo '<li class="archive-month"><a href="'.get_bloginfo('url').'/events-tickets/soundgarten/?d='.$month.'">'.get_month_name(substr($month,-2),'long').' '.$year.'</a></li>';
          }
        }
      endforeach;
    endforeach; ?>
  </ul>
</div>
<?php endif; ?>
<?php wp_reset_postdata(); ?>