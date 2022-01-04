<?php
  $header_type = get_field('header_select');
  $no_scale    = get_field('do_not_scale');
  $format      = get_field('image_format');

  if ( !isset($format) || $format == '' ) {
    $format = 'wide';
  }
  if ( _DEVICE == 'PHONE' ) {
    $format = 'mobile';
  }

  $wrapperClass = $header_type;
  if ( $no_scale ) {
    $wrapperClass .= ' no-scale';
  } else {
    $wrapperClass .= ' scale';
  }
?>
<div class="post-feature style-<?php echo $wrapperClass; ?>">
  <?php if ($header_type  == 'image') {
    $image = get_field('header_image');
    if( $image ) {
      if ( is_array($image) ) {
        the_img_set($image, $format, '90', ['lightbox' => true] );
      } else {
        the_img_set( $image, $format, '90', ['source' => 'wp','lightbox' => true] );
      }
      if (get_copyright_info($image['id'], 'img')) { ?>
        <div class="copyright-overlay">
          <?php echo get_copyright_info($image['id'], 'img'); ?>
        </div>
      <?php }
    }
  } else if ($header_type == 'slider') {
    $images = get_field('header_slider');
    if( $images ) { ?>
      <div class="header-swiper-container swiper-container">
        <div class="header--slider-navgiation">
          <div class="header-button-prev swiper-button button-prev">
            <span class="fad fa-angle-left"></span>
          </div>
          <div class="header-swiper-pagination"></div>
          <div class="header-button-next swiper-button button-next">
            <span class="fad fa-angle-right"></span>
          </div>
        </div>
        <div class="swiper-wrapper">
        <?php foreach( $images as $image ) { ?>
          <div class="swiper-slide <?php echo $wrapperClass; ?>">
            <?php if (get_copyright_info($image['id'], 'img')) { ?>
              <div class="copyright-overlay">
                <?php echo get_copyright_info($image['id'], 'img'); ?>
              </div>
            <?php } ?>
            <a href="<?php echo $image['url']; ?>" data-fancybox="<?php the_title(); ?>" rel="<?php echo $post->post_name; ?>">
              <?php the_img_set($image, $format, '80', ['lightbox' => true]); ?>
            </a>
          </div>
        <?php } //endforeach $images ?>
        </div>
      </div>
    <?php } //endif $images
  } ?>
</div>