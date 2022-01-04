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
  <?php
    $extendedClass = '';
    if ( _DEVICE != 'PHONE' && ( get_field('header_select') == 'image' || get_field('header_select') == 'slider' ) ) {
      get_template_part('parts/slider');
      $extendedClass = 'has-header-image';
    }
  ?>
  <main class="<?php echo element_location() . ' ' . $extendedClass; ?> overview-page">
    <?php get_template_part('parts/conditional.logic'); ?>
    <div class="content-wrap">
      <h2 class="section-title">Weitere Inhalte</h2>
      <?php get_template_part('parts/subpages'); ?>
    </div>
  </main>
<?php endwhile; ?>
<?php endif; ?>

<?php get_footer(); ?>
