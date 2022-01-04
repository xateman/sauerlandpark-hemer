<?php
  /*
    Template Name: Presse-Seite
  */
  get_header();
?>
<?php check_noindex( $post->ID ); ?>
  <div class="page-navigation">
    <div class="elements-wrap">
      <?php get_template_part('parts/crumbs'); ?>
    </div>
  </div>
<?php if ( ( get_field('header_select') == 'image' ) || ( get_field('header_select') == 'slider' ) ) {
  get_template_part('parts/slider');
  $extendedClass = 'has-header-image';
} else {
  $extendedClass = '';
}

  if ( have_posts() ) {
    while ( have_posts() ) {
      the_post(); ?>
      <main class="<?php echo element_location() . ' ' . $extendedClass; ?>">
        <?php
          get_template_part('parts/conditional.logic');

          get_template_part('scope/loop','press');
        ?>
      </main>
    <?php }
  }
  get_footer();
