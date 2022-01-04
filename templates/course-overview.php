<?php
/*
  Template Name: Übersicht der Kurse
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
    <?php if ((get_field('header_select') == 'image') || (get_field('header_select') == 'slider')) {
      get_template_part('parts/slider');
      $extendedClass = 'has-header-image';
    } else {
      $extendedClass = '';
    } ?>
    <main class="<?php echo element_location() . ' ' . $extendedClass; ?> overview-page">
      <div class="content-wrap">
        <?php if ( $post->post_content != '' ) { ?>
          <div class="post-content overview-preview">
            <?php the_content(); ?>
          </div>
        <?php } ?>
        <?php
        $parent = $post->ID;
        $parent_slug = $post->post_name;
        $args = array(
          'post_type'       => 'course',
          'posts_per_page'  => -1,
          'meta_key'       => 'course_id',
          'orderby'        => 'meta_value',
          'order'          => 'ASC',
        );
        $coursesloop = new WP_Query( $args );
        if ( $coursesloop->have_posts() ) {
          ?>
          <h2 class="section-title">Übersicht unserer Kurse</h2>
          <div class="subpage-wrap courses-wrap grid-m-4">
            <?php while ( $coursesloop->have_posts() ) { $coursesloop->the_post(); ?>
              <div class="subpage-item course">
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
        <?php } ?>
      </div>
    </main>
  <?php endwhile; ?>
<?php endif; ?>

<?php get_footer(); ?>
