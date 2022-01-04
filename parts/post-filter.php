  <?php
    global $wpdb;
    $limit = 0;
    $year_prev = null;

    $blogID = get_option( 'page_for_posts' );
    $blog   = (object) [
      'id'    => $blogID,
      'url'   => get_permalink($blogID),
      'title' => get_the_title($blogID)
    ];

    $months = $wpdb->get_results("
    SELECT DISTINCT MONTH( post_date ) AS month ,	YEAR( post_date ) AS year, COUNT( id ) as post_count
    FROM $wpdb->posts
    INNER JOIN $wpdb->term_relationships ON ($wpdb->posts.ID = $wpdb->term_relationships.object_id)
    AND $wpdb->posts.post_type = 'post'
    AND ($wpdb->posts.post_status = 'publish')
    AND $wpdb->posts.post_date <=now( )
    GROUP BY month , year
    ORDER BY post_date DESC");
  ?>
    <div class="dropdown-element post-filter">
      <div class="toggle-list current-filter">Datum wählen</div>
      <ul class="item-list">
        <?php if ( is_archive() || is_single() ) {
          echo "<li class='archive-link'><a href='{$blog->url}'>Alle Beiträge</a></li>";
        }
        foreach($months as $month) {

          $year_current  = $month->year;
          $month_current = str_pad($month->month, '2', '0', STR_PAD_LEFT);
          $current       = "$year_current-$month_current";
          $year_url      = $blog->url . $month->year;
          $month_url     = $year_url.'/'.$month_current;
          $monthName     = get_month_name($month_current,'long') . ' ' . $month->year;

          if ( is_month() && get_the_time('Y-m') === $current ) {
            echo "<li class='archive-month'><span class='current'>$monthName</span></li>";
          } else {
            echo "<li class='archive-month'><a href='$month_url'>$monthName</a></li>";
          }
        } ?>
      </ul>
    </div>
  <?php
  wp_reset_postdata();