<?php $allowed_html = array(
    'a' => array(
      'href' => array()
    ),
    'p' => array(),
    'h3' => array(),
  );

  $homeID     = get_option('page_on_front');
  $base       = (object) [
    'url'      => site_url(),
    'category' => get_option('category_base'),
    'tax'      => get_option('tag_base'),
    'title'    => get_the_title($homeID)
  ];
  $post_type  = get_post_type();
  $blogID     = get_option( 'page_for_posts' );
  $blog       = (object) [
    'id'    => $blogID,
    'url'   => get_permalink($blogID),
    'title' => get_the_title($blogID)
  ];
  $event     = (object) [
    'url'   => site_url('kalender'),
    'title' => __('Event Kalender', LOCAL)
  ];
  $pageTitle = get_the_title();
  
  $post_type = get_post_type();
  $divider   = '<li class="divider"><span class="fal fa-chevron-right"></span></li>';
?>
<div class="breadcrumbs">
  <ul class="crumbs">
    <li class="link-item">
      <a href="<?php echo $base->url; ?>">Home</a>
    </li>
    <?php echo $divider; ?>
    <?php if ( is_search() ) {

      $searchPage = __('Suche', LOCAL);
      echo "<li class='current'>$searchPage</li>";

    } else if ( is_post_type_archive('post') || $post_type == 'post' ) {
      if ( is_home() ) {

        echo "<li class='current'>{$blog->title}</li>";

      } else if ( is_archive() ) {

        echo "<li class='link-item'><a href='{$blog->url}'>{$blog->title}</a></li>";
        echo $divider;
        if ( is_day() ) {
          $archiveText = __('Archiv für ', LOCAL) . get_the_time('F jS, Y');
        } else if ( is_month() ) {
          $archiveText = __('Archiv für ', LOCAL) . get_the_time('F, Y');
        } else if ( is_year() ) {
          $archiveText = __('Archiv für ', LOCAL) . get_the_time('Y');
        } else if ( is_tax('category') ) {
          $archiveText = get_queried_object()->name;
        }
        echo "<li class='current'>{$archiveText}</li>";

      } else {

        $time = (object) [
          'year'      => get_the_time('Y'),
          'month'     => get_the_time('m'),
          'monthName' => get_the_time('F')
        ];
        $current = get_the_title();
        echo "<li class='link-item'><a href='{$blog->url}'>{$blog->title}</a></li> $divider";
        echo "<li class='link-item'><a href='{$blog->url}{$time->year}'>{$time->year}</a></li> $divider";
        echo "<li class='link-item'><a href='{$blog->url}{$time->year}/{$time->month}'>{$time->monthName}</a></li> $divider";
        echo "<li class='current'>$current</li>";

      }

    } else if ( is_post_type_archive('event') || is_tax('event_tax') || $post_type == 'event' ) {

      if ( is_tax('event_tax') ) {

        echo "<li class='link-item'><a href='{$event->url}'>{$event->title}</a></li> $divider";
        $rubrikName = get_queried_object()->name;
        echo "<li class='current'>{$rubrikName}</li>";

      } else if ( is_archive() ) {

        if ( !empty( $_GET['d'] ) ) {
          echo "<li class='link-item'><a href='{$event->url}'>{$event->title}</a></li> $divider";
          $month = substr($_GET['d'], '-2');
          $year  = substr($_GET['d'], 0, 4);
          $archiveText = __('Archiv für ', LOCAL) . get_month_name($month,'long') . ' ' . $year;
          echo "<li class='current'>{$archiveText}</li>";
        } else {
          echo "<li class='current'>{$event->title}</li>";
        }

      } else if ( is_single() ) {

        $start_date = get_field('cal_date_start');
        $from = array(
          'y' => substr($start_date, 0, 4),
          'm' => substr($start_date, 5, 2),
          'd' => substr($start_date, -2)
        );
        $dateQuery = $from['y'].'-'.$from['m'];
        $dateTitle = get_month_name($from['m'],'long').' '.$from['y'];
        echo "<li class='link-item'><a href='{$event->url}'>{$event->title}</a></li> $divider";
        echo "<li class='link-item'><a href='{$event->url}/?d=$dateQuery'>$dateTitle</a></li> $divider";
        echo "<li class='current'>$pageTitle</li>";

      } else if ( is_search() ) {

        echo "<li class='link-item'><a href='{$event->url}'>{$event->title}</a></li>";

      } else {
        echo "<li class='current'>{$event->title}</li>";
      }

    } else if ( $post_type == 'poi' ) {

      echo "<li class='link-item'><a href='{$base->url}/gaesteservice/lage-anreise/uebersichtsplan/'>Übersichtsplan</a></li> $divider";
      echo "<li class='current'>$pageTitle</li>";

    } else if ($post_type == 'course') { ?>

      <li class="link-item">
        <?php if ( _DEVICE != 'PHONE' ) { ?>
          <a href="<?php bloginfo('url'); ?>/<?php _e('park', LOCAL); ?>/">...</a>
        <?php } else { ?>
          <?php printf( wp_kses( __( '<a href="%s/park/">Park</a>', LOCAL ), $allowed_html ), esc_url( $base->url ) ); ?>
        <?php } ?>
      </li>
      <?php echo $divider; ?>
      <li class="link-item">
        <?php if (  _DEVICE != 'PHONE' ) { ?>
          <a href="<?php bloginfo('url'); ?>/<?php _e('park/fuer-wissenshungrige', LOCAL); ?>/">...</a>
        <?php } else { ?>
          <?php printf( wp_kses( __( '<a href="%s/park/fuer-wissenshungrige/">Für Wissenshungrige</a>', LOCAL ), $allowed_html ), esc_url( $base->url ) ); ?>
        <?php } ?>
      </li>
      <?php echo $divider; ?>
      <li class="link-item">
        <?php printf( wp_kses( __( '<a href="%s/park/fuer-wissenshungrige/gruenes-klassenzimmer/">Grünes Klassenzimmer</a>', LOCAL ), $allowed_html ), esc_url( $base->url ) ); ?>
      </li>
      <?php echo $divider; ?>
      <li class="link-item">
        <?php printf( wp_kses( __( '<a href="%s/park/fuer-wissenshungrige/gruenes-klassenzimmer/kurse-angebote/">Kurse & Angebote</a>', LOCAL ), $allowed_html ), esc_url( $base->url ) ); ?>
      </li>
      <?php echo $divider; ?>
      <li class="current depth_0"><?php the_title(); ?></li>

  <?php } else if ( is_page() ) {

      $subpages = walk_subpages($post);

      foreach ($subpages as $subpage) {
        if ( $subpage->depth >= 3 && _DEVICE == 'PHONE' ) {
          echo "<li class='link-item depth-{$subpage->depth}'><a href='{$subpage->url}'>...</a></li> $divider";
        } else {
          echo "<li class='link-item depth-{$subpage->depth}'><a href='{$subpage->url}'>{$subpage->title}</a></li> $divider";
        }
      }

      echo "<li class='current'>$pageTitle</li>";

    } else {

      echo "<li class='current'>Seite konnte nicht gefunden werden.</li>";

    } ?>
  </ul>
</div>