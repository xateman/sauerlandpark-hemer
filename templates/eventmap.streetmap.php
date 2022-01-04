<?php
/*
  Template Name: Übersichtsplan Streetmap
*/
get_header();

if (have_posts()) :
  while (have_posts()) : the_post();
    ?>

    <div class="page-navigation">
      <div class="elements-wrap">
        <?php get_template_part('parts/crumbs'); ?>
      </div>
    </div>

    <div class="park-map-wrapper">
      <div class="map-switcher">
        <a href="<?php the_permalink($post->post_parent); ?>" title="<?php _e('Übersichtsplan', LOCAL); ?>"><span class="fad fa-map"></span></a>
        <?php if ( _DEVICE == 'PHONE' ) { ?>
          <a id="scrollDown" class="anchorLink" href="#legend" title="<?php _e('Legende', LOCAL); ?>"><span class="fad fa-angle-down"></span></a>
        <?php } ?>
      </div>
      <div id="park-map" class="fullsize"></div>
    </div>

    <main class="<?php echo element_location(); ?> has-header-image">
      <div class="content-wrap">
        <div class="post-content content">
          <h1><?php the_title(); ?></h1>
          <div id="legend" class="location-groups grid-m-3">
            <?php
            $args = [
              'type'         => 'poi',
              'child_of'     => 0,
              'parent'       => '',
              'orderby'      => 'include',
              'order'        => 'ASC',
              'hierarchical' => 1,
              'taxonomy'     => 'poi_tax',
              'hide_empty'   => 0,
              'pad_counts'   => false
            ];
            $location_groups = get_categories($args);
            $icon_arr  = [];
            foreach ($location_groups as $lg) {
              $term_id = 'term_' . $lg->term_id;
              $color   = get_field('tax_color', $term_id);
              ?>
              <div class="location-group group-<?php echo $lg->slug; ?>">
                <h3><?php echo $lg->name; ?></h3>
                <div class="item-list">
                  <?php
                  $args = [
                    'post_type'      => 'poi',
                    'posts_per_page' => -1,
                    'tax_query'      => [
                      [
                        'taxonomy' => 'poi_tax',
                        'field'    => 'term_id',
                        'terms'    => $lg->term_id,
                      ],
                    ],
                    'meta_key'       => 'marker_id',
                    'orderby'        => 'meta_value_num',
                    'order'          => 'ASC'
                  ];
                  $pois = new WP_Query( $args );
                  if ( $pois->have_posts() ) {
                    while ( $pois->have_posts() ) {
                      $pois->the_post();
                      $poi = (object)[
                        'id'     => get_field('marker_id'),
                        'title'  => get_field('marker_title'),
                        'coord'  => get_field('plan_coordiantes'),
                        'latlng' => get_field('latlng'),
                        'link'   => get_field('show_content'),
                        'icons'  => get_field('marker_icons'),
                        'x'      => get_field('marker_x'),
                        'y'      => get_field('marker_y')
                      ];
                      $attr  = ' data-id="'.$poi->id.'"';
                      $attr .= ' data-group="'.$lg->slug.'"';
                      if ( isset ( $poi->link ) && $poi->link != false ) {
                        $attr .= ' data-page="'.$poi->link.'"';
                      } else {
                        $attr .= ' data-page="false"';
                      }
                      if ( !empty($poi->coord) ) {
                        $attr .= ' data-yx="['.$poi->coord.']"';
                      } else {
                        $attr .= ' data-yx="[-'.$poi->y.', '.$poi->x.']"';
                      }
                      if ( isset($poi->latlng) && !empty($poi->latlng) ) {
                        $attr .= ' data-latlng="['.$poi->latlng.']"';
                      }
                      if ( is_array($poi->icons) ) {
                        $icon_json = json_encode( $poi->icons );
                        $icon_arr  = array_merge($icon_arr, $poi->icons);
                        $attr .= " data-icons='$icon_json'";
                      } else {
                        $attr .= " data-icons='false'";
                      }
                      $attr .= " data-title='{$poi->title}'";
                      $attr .= ' data-color="'.$color.'"';
                      ?>
                      <div class="item marker_anchor" <?php echo $attr; ?>>
                        <span class="icon circle"><?php echo $poi->id; ?></span>
                        <span class="title"><?php echo $poi->title; ?></span>
                      </div>
                      <?php
                    }
                  }
                  ?>
                </div>
                <style scoped>
                  .group-<?php echo $lg->slug; ?> h3 {
                    border-bottom-color: <?php echo $color; ?>;
                  }
                  .group-<?php echo $lg->slug; ?> .item .icon.circle {
                    background-color: <?php echo $color; ?>;
                  }
                </style>
              </div>
            <?php } ?>
            <div class="location-legend">
              <h3>Legende</h3>
              <div class="item-list">
                <?php
                $icons = unique_multi_array($icon_arr, 'value');
                foreach ( $icons as $icon ) {
                  $icon_image = get_path('assets/map/icons').'/'.$icon['value'].'.png';
                  ?>
                  <div class="item" data-filter="<?php echo $icon['value']; ?>">
                    <span class="icon image" style="background-image:url('<?php echo $icon_image; ?>')"></span>
                    <span class="title"><?php echo $icon['label']; ?></span>
                  </div>
                <?php } ?>
              </div>
            </div>
          </div>
        </div>
      </div>
    </main>

  <?php endwhile; ?>
<?php endif; ?>

<?php get_footer(); ?>
