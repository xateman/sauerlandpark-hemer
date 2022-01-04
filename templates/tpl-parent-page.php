<?php
  /*
    Template Name: Übersicht der Unterseiten
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
      <div class="post-content">
        <?php the_content(); ?>
      </div>
      <?php } ?>
      <?php get_template_part('parts/subpages'); ?>
    </div>
  </main>
<?php endwhile; ?>
<?php endif; ?>

<?php get_footer(); ?>
