<?php get_header(); ?>
<div class="page-navigation">
  <div class="elements-wrap">
    <?php get_template_part('parts/crumbs'); ?>
    <?php get_template_part('parts/event', 'categories'); ?>
    <?php get_template_part('parts/event', 'filter'); ?>
  </div>
</div>

<?php if ( (get_field('header_select') == 'image') || (get_field('header_select') == 'slider') ) {
  get_template_part('parts/slider');
  $extendedClass = 'has-header-image';
} else if (get_field('cal_image')) {
  $feature = get_field('cal_image');
  $extendedClass = 'has-header-image';
  ?>
  <div class="post-feature no-scale">
    <?php if (get_copyright_info($feature['id'], 'img')) { ?>
      <div class="copyright-overlay">
        <?php echo get_copyright_info($feature['id'], 'img'); ?>
      </div>
    <?php }

    the_img_set($feature, 'wide', '90', ['lightbox' => true]);
    ?>
  </div>
<?php } else {
  $extendedClass = '';
} ?>
<?php if (have_posts()) { ?>
<?php while (have_posts()) { the_post(); ?>
<main class="<?php echo element_location() . ' ' . $extendedClass; ?>">
  <?php
    $dt = (object) [
      'dateStart' => get_field('cal_date_start'),
      'dateEnd'   => get_field('cal_date_end'),
      'timeStart' => get_field('cal_time_start'),
      'timeEnd'   => get_field('cal_time_end')
    ];
    $title_datetime = get_event_date($dt->dateStart, $dt->dateEnd, $dt->timeStart, $dt->timeEnd);
    $dateProp = $dt->dateStart;
    if ( $dt->timeStart ) {
      $dateProp .= "T{$dt->timeStart}";
    }
  ?>
  <div class="post-meta-header">
    <div class="datetime"><?php echo $title_datetime['string']; ?></div>
    <div class="flex-element"></div>
    <?php if (get_field('cal_ticket')) { ?>
      <div class="ticket-link">
        <a href="<?php the_field('cal_ticket'); ?>" target="_blank">Tickets</a>
      </div>
    <?php } ?>
    <?php if (get_field('cal_show_access')) { ?>
      <div class="accessibility <?php echo $extendedClass; ?>">
        <div class="access-content">
          <h2>Barrierefreiheit</h2>
          <div class="access-table">
            <?php for ($i = 1; $i <= 5; $i++) {
              $field = get_field_object('cal_access_'.$i);
              $label = $field['label'];
              echo '<div class="label">'.$label.'</div>';
              echo '<div class="value">';
              if ( get_field('cal_access_'.$i) ) {
                echo '<span class="far fa-check-square"></span>';
              } else {
                echo '<span class="far fa-square"></span>';
              }
              echo '</div>';
            } ?>
          </div>
        </div>
        <div class="access-toggle closed">
          <span class="icon open fas fa-wheelchair"></span>
          <span class="icon close fas fa-times"></span>
        </div>
      </div>
    <?php } ?>
  </div>
  <div class="content-wrap">
    <div class="post-content content">
      <?php
      if ( get_field('cal_title') ) {
        $title = get_field('cal_title');
      } else {
        $title = get_the_title();
      }
      the_el($title, 'headline', '', 'h1');
      the_content();
      ?>
    </div>
  </div>
  <?php if ( get_field('cal_location') ) {
    $location = get_field('cal_location');
    $location = $location[0];
    $poi = (object) [
      'id'     => get_field('marker_id', $location),
      'title'  => get_field('marker_title', $location),
      'coord'  => get_field('plan_coordiantes', $location),
      'latlng' => get_field('latlng', $location),
      'link'   => get_field('show_content', $location),
      'icons'  => get_field('marker_icons', $location)
    ];
    $terms = wp_get_post_terms( $location, 'poi_tax', array('fields' => 'all') );
    if ( is_array($terms) && count ($terms) > 0 ) {
      $color = get_field('tax_color', "term_{$terms[0]->term_id}");
      $attr  = " data-id='{$poi->id}'";
      $attr .= " data-group='{$terms[0]->slug}'";
      if ( isset ( $poi->link ) && $poi->link != false ) {
        $attr .= " data-page='{$poi->link}'";
      } else {
        $attr .= " data-page='false'";
      }
      if ( !empty($poi->coord) ) {
        $attr .= " data-yx='[{$poi->coord}]'";
      }
      if ( isset($poi->latlng) && !empty($poi->latlng) ) {
        $attr .= " data-latlng='[{$poi->latlng}]'";
      }
      if ( is_array($poi->icons) ) {
        $icon_json = json_encode( $poi->icons );
        $attr .= " data-icons='$icon_json'";
      } else {
        $attr .= " data-icons='false'";
      }
      $attr .= " data-title='{$poi->title}'";
      $attr .= ' data-color="'.$color.'"';
    ?>
      <div class="event-location">
        <h3>Veranstaltungsort im Sauerlandpark Hemer: <b><?php echo $poi->title; ?></b></h3>
        <div class="park-map-wrapper">
          <div class="map-setup">
            <div class="item marker_anchor" <?php echo $attr; ?>>
              <span class="icon circle"><?php echo $poi->id; ?></span>
              <span class="title"><?php echo $poi->title; ?></span>
              <?php if ( is_array($poi->icons) ) {
                $icon_json = json_encode( $poi->icons );
                ?>
                <span class="icons" style="display:none;"><?php echo $icon_json; ?></span>
              <?php } ?>
            </div>
          </div>
          <div id="park-map" class="event-map"
               data-map="<?php the_path('assets/page'); ?>/sauerlandpark-hemer-parkplan.jpg"
               data-active="<?php echo $poi->id; ?>"
               data-swz="false"
               data-controls="false"
          ></div>
        </div>
      </div>
    <?php } // endif terms
  } //endif location ?>

  <script type="application/ld+json">
    {
      "@context": "https://schema.org",
      "@type": "Event",
      "name": "<?php echo $title; ?>",
      <?php if ( $ticket_url = get_field('cal_ticket') ) { ?>
      "offers": {
        "@type": "Offer",
        "url": "<?php echo $ticket_url; ?>"
      },
      <?php } ?>
      "startDate": "<?php echo $dateProp; ?>"
    }
  </script>

</main>
<?php } //endwhile ?>
<?php } //endif; ?>

<?php get_footer(); ?>