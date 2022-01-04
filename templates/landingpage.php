<?php
/*
  Template Name: Landingpage mit Unterseitenübersicht
*/
get_header();

if (have_posts()) :
  while (have_posts()) : the_post();
    $images   = get_field('landingpage_header');
    $headline = get_field('landingpage_headline');
    $subhead  = get_field('landingpage_subhead');
    ?>
    <div class="landingpage-header">
      <?php if( $headline ) { ?>
        <div class="headline-wrap">
          <h1><?php echo $headline; ?></h1>
          <?php if ($subhead) { ?>
            <h2><?php echo $subhead; ?></h2>
          <?php } ?>
        </div>
      <?php } ?>
    <?php if ( count($images) > 1 ) { ?>
      <div class="landingpage-swiper-container swiper-container">
        <div class="landingpage-button landingpage-button-prev">
          <span class="fa fa-chevron-left"></span>
        </div>
        <div class="landingpage-button landingpage-button-next">
          <span class="fa fa-chevron-right"></span>
        </div>
        <div class="swiper-wrapper">
          <?php foreach( $images as $image ) { ?>
            <div class="swiper-slide"
                 style="background-image:url('<?php echo $image['url']; ?>');">
            </div>
          <?php } ?>
        </div>
      </div>
      <?php } else if (count($images) == 1) { ?>
      <div class="image-container" style="background-image:url('<?php echo $images[0]['url']; ?>');"></div>
      <?php
    } ?>
    </div>
    <main class="landingpage">
      <div class="content-wrap">
        <div class="content">
          <?php the_content(); ?>
        </div>
        <?php get_template_part('parts/subpages'); ?>
      </div>
    </main>
  <?php endwhile; ?>
<?php endif; ?>

<?php get_footer(); ?>
