<?php
  if ( $headline = get_query_var( 'subpageHead' )) {
    $headline = $headline;
  } else {
    $headline = 'Weitere Inhalte';
  }

  $parent      = $post->ID;
  $parent_slug = $post->post_name;
  $args = array(
    'post_type'       => 'page',
    'orderby'         => 'menu_order name',
    'posts_per_page'  => -1,
    'post_status'     => 'publish',
    'order'           => 'ASC',
    'post_parent'     => $parent
  );
  $subpages_loop = new WP_Query( $args );

  if ( $subpages_loop->have_posts() ) {
    ?>
    <div class="content-wrap">
      <h2 class="section-title"><?php echo $headline; ?></h2>
      <div class="subpage-wrap page-<?php echo $parent_slug; ?> grid-m-3">
        <?php while ( $subpages_loop->have_posts() ) { $subpages_loop->the_post(); ?>
          <div class="subpage-item">
            <a href="<?php the_permalink(); ?>">
              <?php if ( _DEVICE != 'PHONE' ) {
                if ( has_post_thumbnail() ) {
                  the_img_set($post->ID, 'default', '33', ['source' => 'wp', 'type' => 'bg']);
                } else {
                  the_img_set(FALLBACK, 'default', '33', ['type' => 'bg']);
                }
              } ?>
              <div class="sp-item-title">
                <h2><?php the_title(); ?></h2>
              </div>
            </a>
          </div>
        <?php } ?>

      </div>
    </div>

<?php }
  wp_reset_query();