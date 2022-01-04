<?php get_header(); ?>

<?php if (have_posts()) : ?>
<?php while (have_posts()) : the_post(); ?>

<div class="page-navigation">
  <div class="elements-wrap">
    <?php get_template_part('parts/crumbs'); ?>
    <?php get_template_part('parts/post','filter'); ?>
  </div>
</div>

<?php if ((get_field('header_select') == 'image') || (get_field('header_select') == 'slider')) {
  get_template_part('parts/slider');
  $extendedClass = 'has-header-image';
} elseif (get_field('cal_image')) {
  $feature = get_field('cal_image');
  $extendedClass = 'has-header-image';
  ?>
  <div class="post-feature">
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
<main class="<?php echo element_location() . ' ' . $extendedClass; ?>">
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
</main>
<?php endwhile; ?>
<?php endif; ?>
<?php get_footer(); ?>
