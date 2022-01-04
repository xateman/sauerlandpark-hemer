<?php get_header(); ?>
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
  <?php if ( post_password_required() ) {
    echo get_the_password_form();
  } else {
    get_template_part('parts/conditional.logic');
  } ?>
</main>
<?php }
}
get_footer();