<?php $allowed_html = array(
    'a' => array(
      'href' => array()
    ),
    'p' => array(),
    'h3' => array(),
  );
?>
<aside class="sidebar">
  <?php printf( wp_kses( __( '<a href="%s/aktuelles/"><h3>Archiv</h3></a>', 'dsv-theme' ), $allowed_html ), esc_url( get_bloginfo('url') ) ); ?>
  <ul class="archive">
  <?php
    global $wpdb;
    $limit = 0;
    $year_prev = null;

    $months = $wpdb->get_results("
    SELECT DISTINCT MONTH( post_date ) AS month ,	YEAR( post_date ) AS year, COUNT( id ) as post_count
    FROM $wpdb->posts
    INNER JOIN $wpdb->term_relationships ON ($wpdb->posts.ID = $wpdb->term_relationships.object_id)
    AND $wpdb->posts.post_type = 'post'
    AND ($wpdb->posts.post_status = 'publish')
    AND $wpdb->posts.post_date <=now( )
    GROUP BY month , year
    ORDER BY post_date DESC");

    foreach($months as $month) :
      $year_current = $month->year;
      if ($year_current != $year_prev) {
        echo '<li class="archive-year"><a href="'.get_bloginfo('url').'/'.$month->year.'/">'.$month->year.'</a></li>';
      } /* ?>
        <li class="archive-month">
          <a href="<?php echo get_bloginfo('url').'/'.$month->year; ?>/<?php echo date("m", mktime(0, 0, 0, $month->month, 1, $month->year)) ?>">
            <span class="archive-month"><?php echo date_i18n("F", mktime(0, 0, 0, $month->month, 1, $month->year)) ?></span>
          </a>
        </li>
    <?php */ $year_prev = $year_current; endforeach; ?>
  </ul>
</aside>
