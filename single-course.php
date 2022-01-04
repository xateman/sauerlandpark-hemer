<?php get_header(); ?>
<div class="page-navigation">
  <div class="elements-wrap">
    <?php get_template_part('parts/crumbs'); ?>
  </div>
</div>
<?php $url = get_bloginfo('url'); ?>
<?php if (have_posts()) : ?>
  <?php while (have_posts()) : the_post(); ?>

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
          <?php the_content(); ?>
          <div class="request-form-links">
            <h3>Zum Anfrageformular</h3>
            <?php if ( $forms = get_field('forms', 'options') ) {
              foreach ( $forms as $form ) {
                echo "<a href='{$form['url']}?id={$post->ID}'>{$form['title']}</a>";
              }
            } ?>
          </div>
        </div>
      </div>
    </main>
  <?php endwhile; ?>
<?php endif; ?>

<?php get_footer(); ?>
