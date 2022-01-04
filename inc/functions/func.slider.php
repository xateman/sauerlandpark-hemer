<?php

  function create_slider($id, $images, $options = [], $class = 'default-style', $type = 'img') {

    if( isset( $options['slides'] ) ) {
      $image_width = round(100 / $options['slides']);
    } else {
      $image_width = 100;
    }

    $default_options = [
      'pagination'   => false,
      'navigation'   => false,
      'autoplay'     => 5000,
      'lightbox'     => false,
      'slides'       => 1,
      'width'        => $image_width,
      'sizes'        => 'wide',
      'loop'         => false,
      'gap'          => 0,
      'autoHeight'   => false,
      'single_class' => null,
      'link'         => false,
      'href'         => null,
      'overlay_val'  => null,
      'imgClass'     => 'default'
    ];

    $o = array_merge($default_options, $options);

    $images_count = count($images);
    if( $images_count > 1 ) { ?>

      <div class="image-element">

        <?php if ( $o['navigation'] ) { ?>
          <div class="slider-prev slider-button" id="<?php echo $id; ?>-prev">
            <span class="fas fa-chevron-left"></span>
          </div>
          <div class="slider-next slider-button" id="<?php echo $id; ?>-next">
            <span class="fas fa-chevron-right"></span>
          </div>
        <?php } ?>

        <div class="swiper-container <?php echo $class; ?>" id="<?php echo $id; ?>">

          <div class="swiper-wrapper">
            <?php foreach( $images as $image ) {
              echo '<div class="swiper-slide">';
              $options = [
                'type'         => $type,
                'lightbox'     => $o['lightbox'],
                'lightboxName' => $id,
                'imgClass'     => $o['imgClass']
              ];
              the_img_set($image, $o['sizes'], $o['width'], $options);

              echo '</div>';
            } ?>
          </div>

          <?php if ( $o['pagination'] ) { ?>
            <div class="pagination-wrapper">
              <div class="slider-pagination" id="<?php echo $id; ?>-pagination"></div>
            </div>
          <?php } ?>
        </div>

        <?php
          $content_parts_slider[$id] = [
            'id'      => $id,
            'type'    => 'slider',
            'name'    => 'slider_' . $id,
            'options' => [
              'speed'          => 400,
              'slidesPerView'  => $o['slides'],
              'spaceBetween'   => (int) $o['gap'],
              'autoHeight'     => $o['autoHeight'],
              'loop'           => $o['loop'],
              'grabCursor'     => true
            ]
          ];
          if ( $o['pagination'] ) {
            $content_parts_slider[$id]['options']['pagination'] = [
              'el'        => '#'.$id.'-pagination',
              'clickable' => true
            ];
          }
          if ( $o['navigation'] ) {
            $content_parts_slider[$id]['options']['navigation'] = [
              'nextEl' => '#'.$id.'-next',
              'prevEl' => '#'.$id.'-prev',
            ];
          }
          if ( $o['autoplay'] ) {
            $content_parts_slider[$id]['options']['autoplay'] = [
              'delay' => $o['autoplay']
            ];
          }
          set_query_var('content_parts_slider', $content_parts_slider);
        ?>


      </div>

    <?php } else if ( $images_count === 1 ) { ?>
      <div class="image-element feature-image-banner <?php echo $class; ?>">
        <?php
          $options = [
            'type'         => $type,
            'lightbox'     => $o['lightbox'],
            'lightboxName' => $id,
            'imgClass'     => $o['imgClass']
          ];
          the_img_set($images[0], $o['sizes'], $o['width'], $options);
        ?>
      </div>
    <?php }
  }