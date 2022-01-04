<?php
/*
  Template Name: Übersichtsplan
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
      <?php
        $childArgs = [
          'post_type'    => 'page',
          'post_parent'  => $post->ID
        ];
        $children = new WP_Query( $childArgs );
        $child_id = $children->posts[0]->ID;
        wp_reset_query();
      ?>
      <a href="<?php the_permalink($child_id); ?>" title="<?php _e('Straßenplan', LOCAL); ?>"><span class="fad fa-road"></span></a>
      <?php if ( _DEVICE == 'PHONE' ) { ?>
        <a id="scrollDown" class="anchorLink" href="#legend" title="<?php _e('Legende', LOCAL); ?>"><span class="fad fa-angle-down"></span></a>
      <?php } ?>
    </div>
    <div id="park-map" class="fullsize"
         data-map="<?php the_path('assets/page'); ?>/sauerlandpark-hemer-parkplan.jpg"
         data-swz="false"
         data-active="false"
         data-controls="true"
    ></div>
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
            'hide_empty'   => 1,
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
                      'icons'  => get_field('marker_icons')
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
            <div class="item-list icons">
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
        <?php the_content(); ?>
      </div>
    </div>
  </main>

  <?php endwhile; ?>
<?php endif; ?>

<?php get_footer(); ?>
