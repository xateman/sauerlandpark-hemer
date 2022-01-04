<?php $allowed_html = array(
    'a' => array(
      'href' => array()
    ),
    'p' => array(),
    'h3' => array(),
  );
?>
<aside class="sidebar">
  <?php printf( wp_kses( __( '<a href="%s/events-tickets/event-ueberblick/"><h3>Events</h3></a>', LOCAL ), $allowed_html ), esc_url( get_bloginfo('url') ) ); ?>
  <ul class="archive">
    <?php
      $n = 0;
      $args_sidebar_events = array (
        'post_type'        => 'event',
        'posts_per_page'   => -1,
        'suppress_filters' => 0,
        'meta_key'         => 'cal_date_start',
        'orderby'          => 'meta_value',
        'order'            => 'DESC',
      );
      $events = get_posts( $args_sidebar_events );
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
      $prev_year = false;
      foreach ($scope_years as $years => $year) :
        settype($year,'string');
        $current_year = $year;
        if (($prev_year) && ($prev_year != $current_year)) :
          $yearClass  = 'archive-year with_border';
          $monthClass = 'archive-month';
        else:
          $yearClass  = 'archive-year';
          $monthClass = 'archive-month';
        endif;

        //echo '<li class="'.$yearClass.'"><a href="'.get_bloginfo('url').'/termine/?year='.$year.'">'.$year.'</a></li>';
         foreach ($scope_months as $month) :
          if (strpos($month, $year) !== false) {
            echo '<li class="'.$monthClass.'"><a href="'.get_bloginfo('url').'/events-tickets/event-ueberblick/?d='.$month.'">'.get_month_name(substr($month,-2),'long').' '.substr($month,0,4).'</a></li>';
          }
        endforeach;
      $prev_year = $current_year;
      endforeach;
    ?>
    <?php wp_reset_postdata(); ?>
  </ul>
</aside>