<?php
  get_header();
  $allowed_html = array(
    'a' => array(
      'href' => array()
    ),
    'p' => array(),
  );
  if ( _DEVICE == 'PHONE' ) {
    $homeClass = 'page-item device phone';
  } elseif ( _DEVICE == 'TABLET' ) {
    $homeClass = 'page-item device tablet';
  } else {
    $homeClass = 'page-item desktop';
  }
  $today = (object) DT;
?>
<div class="<?php echo $homeClass; ?>" id="intro">

  <?php if ( get_field( 'o_show_notice', 'option' ) ) { ?>
    <div class="info-box current span-m-5 span-l-4">
      <h2>Aktuell</h2>
      <div class="text">
        <?php the_field( 'o_notice_tab_text', 'option' ); ?>
      </div>
    </div>
  <?php }

    $beachgarden = get_field( 'beachgarden', 'option' );
    if ( $beachgarden['show-info'] ) { ?>
      <div class="info-box beachgarden span-m-4 span-l-3">
        <?php if ( $beachgarden['traffic'] === 'green' ) {
          echo "<div class='trafficLight green'><span class='far fa-traffic-light-go'></span></div>";
        } else if ( $beachgarden['traffic'] === 'red' ) {
          echo "<div class='trafficLight red'><span class='far fa-traffic-light-stop'></span></div>";
        } ?>
        <div class="text">
          <?php echo $beachgarden['infotext']; ?>
        </div>
      </div>
    <?php }

    $hours      = get_opening_hours('current');
    $show_hours = get_field('oh_on_frontpage', 'option');
    
    if ( $hours && $show_hours ) {
      if ( $hours->status !== 'closed' ) { ?>
        <div class="info-box time span-m-4 span-l-3">
          <h2>Öffnungszeiten Information</h2>
          <div class="text">
            <?php echo $hours->message; ?>
          </div>
        </div>
      <?php }
    }

    $special_element = get_field('special_element');
    if ( $special_element['show_element'] ) {
      if ( $special_element['image'] ) {
        $button = "<img src='{$special_element['image']['url']}' alt='{$special_element['image']['alt']}' />";
        if ( is_array( $special_element['link'] ) ) {
          $elem = "<a href='{$special_element['link']['url']}' target='{$special_element['link']['target']}'>$button</a>";
        } else {
          $elem = $button;
        }
        echo "<div class='special-element'>$elem</div>";
      }
    }

  if ( $specials = get_field('soh_datetime', 'option') ) {
    foreach ( $specials as $special ) {
      if (
        in_between($today->date, $special['start_date'], $special['end_date']) &&
        in_between($today->time, $special['start_time'], $special['end_time'])
      ) {

        $content = "<p>{$special['text']}</p>";

        if ( is_array($special['link']) && $special['link']['url'] != '' ) {
          //var_dump($special['link']);
          if ( $special['link']['title'] != '' ) {
            $label = $special['link']['title'];
          } else {
            $label = __('Mehr Informationen', LOCAL);
          }
          $content .= "<a class='readMore' href='{$special['link']['url']}' target='{$special['link']['target']}'><span class='label'>$label</span><span class='icon far fa-external-link'></span></a>";
          $boxClass = "info-box notice linked span-m-4 span-l-3";
        } else {
          $boxClass = "info-box notice span-m-4 span-l-3";
        }
        echo "<div class='$boxClass'>";
        echo "<h2>Mitteilung</h2>";
        echo "<div class='text'>$content</div>";
        echo "</div>";
      }
    }
  }

    if ( $slideOpt = get_field('slider-setting') ) {
      $data = get_field('home-slides');
      ?>
      <?php if ( $slideOpt === 'video' ) { ?>
        <?php if ( _DEVICE != 'PHONE' ) { ?>
          <video id="intro-video" autoplay muted loop poster="<?php echo $data['video']['fallback_image']['url']; ?>">
            <source src="<?php echo $data['video']['mp4-file']['url']; ?>" type="video/mp4">
          </video>
        <?php } else { ?>
          <div class="image-box" style="background-image:url('<?php echo $data['video']['fallback_image']['url']; ?>');"></div>
        <?php } ?>
      <?php } else { ?>
        <?php
        if ($slideOpt === 'seasons') {
          $images = $data[ SEASONS['current']['slug'] ];
        } elseif ($slideOpt === 'special') {
          $images = $data[ 'special' ];
        }
        $selection = array_rand($images, 3);
        ?>
        <div class="home-swiper-container home swiper-container">
          <div class="swiper-wrapper">
            <?php foreach ( $selection as $index ) { ?>
              <div class="swiper-slide home-slide">
                <?php the_img_set( $images[$index], 'wide', '100', ['type' => 'bg'] ); ?>
              </div>
            <?php } ?>
          </div>
        </div>
      <?php } ?>
  <?php } ?>
</div>


<?php
  $newsHighlight = get_field('sticky_news');
  $nh_start      = get_field('news_start');
  $nh_end        = get_field('news_end');
  if ( ($newsHighlight && !$nh_start && !$nh_end) || ($newsHighlight && in_between(DT['date'], $nh_start, $nh_end) ) ) {
    $highlight_args = [
      'post_type'           => 'post',
      'posts_per_page'      => -1,
      'orderby'             => 'date',
      'order'               => 'DESC',
      'post__in'            => $newsHighlight,
      'ignore_sticky_posts' => true
    ];
    $highlights = new WP_Query( $highlight_args );
    if ( $highlights->found_posts > 1 ) {
      $elem_title = __('Wichtige Mitteilungen', LOCAL);
    } else {
      $elem_title = __('Wichtige Mitteilung', LOCAL);
    }
?>
    <div id="highlights" class="frontpage-element">
      <div class="content-wrap">
        <h2><?php echo $elem_title; ?></h2>
        <div class="highlights-wrap grid-x-<?php echo $highlights->found_posts; ?>">
          <?php while ( $highlights->have_posts() ) { $highlights->the_post(); ?>
            <article class="highlight-news">
              <a href="<?php the_permalink(); ?>">
                <?php
                if ( get_field('news_title') ) {
                  $title = get_field('news_title');
                } else {
                  $title = get_the_title();
                }
                ?>
                <div class="title">
                  <?php the_el($title, 'news-head', '', 'h3'); ?>
                </div>
              </a>
            </article>
          <?php } wp_reset_query(); //endwhile ?>
        </div>
      </div>
    </div>
<?php } ?>

<?php

  $specials = get_field('specials');

  if ( $specials ) {

    $items = '';

    foreach ($specials as $item) {

      $active   = $item['action_active'];
      $rgb      = hex2rgb($item['overlay_color'], true);
      $gradient = 'linear-gradient(135deg, rgba('.$rgb.',0.8) 0%, rgba(0,0,0,0) 100%)';

      if ($active) {
        $type = $item['action_type'];
        if ( $type === 'event' ) {
          $event_id   = $item['event_item']->ID;
          $title      = $item['event_item']->post_title;
          $start_date = get_field('cal_date_start', $event_id);
          $end_date   = get_field('cal_date_end', $event_id);
          $date       = get_event_date($start_date, $end_date);
          $url        = get_permalink( $event_id );
          $attr       = '';
        } else if ( $type === 'youtube' ) {
          $title = $item['name'];
          $yt_id = $item['yt_id'];
          $url   = 'https://www.youtube.com/watch?v='.$yt_id;
          $attr = 'data-fancybox';
        } else if ( $type === 'link' ) {
          $title = $item['name'];
          $url   = $item['link']['url'];
          $attr  = 'target="'.$item['link']['target'].'"';
        }

        $overlay = "<div class='overlay title'>$title</div>";
        if ( $type === 'event' ) {
          $overlay .= "<div class='overlay date'>{$date['string']}</div>";
        }
        $colorEdge = "<div class='color-edge' style='background:$gradient;'></div>";
        $image = get_img_set($item['img'], 'special', '90', ['type' => 'bg']);

        $items .= "<div class='special'><a href='$url' $attr>$overlay$colorEdge$image</a></div>";
      }
    }
    $headText = __('Aktionen', LOCAL);
    if ( $items != '' ) {
      echo "<div id='specials' class='frontpage-element'><div class='content-wrap specials-wrap'><h2>$headText</h2>$items</div></div>";
    }
  } ?>

  <div class="frontpage-element" id="sponsors">
    <?php

      if ( false === ( $sponsors = get_transient( 'sponsors_frontpage_cache' ) ) ) {

        $args = [
          'post_type'       => 'sponsor',
          'posts_per_page'  => -1,
          'orderby'         => 'menu_order',
          'order'           => 'ASC',
          'meta_query' => [
            [
              'key'     => 'show_in_footer',
              'value'   => '1',
              'compare' => '=',
            ],
          ],
        ];
        $sponsors = new WP_Query( $args );

        set_transient( 'sponsors_frontpage_cache', $sponsors, MID_TRANSIENT );
      }

      if ( $sponsors->have_posts() ) {
        echo '<div class="sponsors-container home">';
        echo '<div class="inner-container">';
        echo '<h2>Vielen Dank an Unsere Unterstützer</h2>';
        echo '<div class="sponsors-wrap grid-s-2 grid-m-5">';
        while ( $sponsors->have_posts() ) { $sponsors->the_post();
          echo '<div class="sponsor">';
          if ( $image = get_field('sponsor_img') ) {
            $elem = '<img src="'.$image['sizes']['M_SPONSOR'].'" alt="'.get_the_title().'" />';
          } else {
            $elem = get_the_title();
          }
          if ( $link = get_field('sponsor_link') ) {
            echo "<a href='$link' target='_blank'>$elem</a>";
          } else {
            echo $elem;
          }
          echo '</div>';
        }
        echo '</div>';
        echo '</div>';
        echo '</div>';
      }
      wp_reset_postdata();
    ?>
  </div>

  <div id="events" class="frontpage-element">
    <div class="content-wrap events-wrap">
      <h2>Nächste Events</h2>
      <div class="article-wrap grid-l-3">
        <?php

          $args = array(
            'post_type'      => 'event',
            'posts_per_page' => 3,
            'meta_query'     => array(
              'relation'     => 'AND',
              array(
                'relation'     => 'OR',
                array(
                  'key'        => 'cal_date_start',
                  'value'      => DT['date'],
                  'compare'    => '>=',
                  'type'       => 'DATE',
                ),
                array(
                  'key'        => 'cal_date_end',
                  'value'      => DT['date'],
                  'compare'    => '>=',
                  'type'       => 'DATE',
                ),
              )
            ),
            'meta_key'       => 'cal_date_start',
            'orderby'        => 'meta_value',
            'order'          => 'ASC',
          );
          $events = new WP_Query( $args );

          $index = 1;
          while ( $events->have_posts() ) { $events->the_post();
            $start_date = get_field('cal_date_start');
            if ($start_date < DT['date']) {
              $start_date = DT['date'];
            }
            $date = date("d.m.Y", strtotime($start_date));
            $dateOut  = $date;
            $dateProp = $start_date;
            if ( $start_time = get_field('cal_time_start') ) {
              $dateOut  .= " | $start_time Uhr";
              $dateProp .= "T$start_time";
            }
        ?>
          <article class="event" itemscope itemtype="https://schema.org/Event">
            <a href="<?php the_permalink(); ?>">
              <?php if ( $feature = get_field('cal_image') ) {
                the_img_set($feature, 'wide', '33', ['type' => 'bg']);
              } ?>
              <div class="date-title">
                <div class="date" itemprop="startDate" content="<?php echo $dateProp; ?>"><?php echo $dateOut; ?></div>
                <h3 itemprop="name"><?php the_field('cal_title'); ?></h3>
              </div>
            </a>
          </article>
        <?php } //endwhile;
          wp_reset_query();
        ?>
        <div class="overview-link">
          <a href="<?php bloginfo('url'); ?>/kalender/">Alle aktuellen Veranstaltungen im Sauerlandpark Hemer</a>
        </div>
      </div>
      <h2>Unsere Eventkategorien</h2>
      <div class="event-categories grid-s-2 grid-m-4">
        <?php

          if ( false === ( $categories = get_transient( 'events_tax_frontpage_cache' ) ) ) {

            $args = [
              'type'         => 'event',
              'child_of'     => 0,
              'parent'       => '',
              'orderby'      => 'include',
              'order'        => 'ASC',
              'hierarchical' => 1,
              'taxonomy'     => 'event_tax',
              'hide_empty'   => 0,
              'pad_counts'   => false
            ];
            $categories = get_categories($args);

            set_transient( 'events_tax_frontpage_cache', $categories, LONG_TRANSIENT );

          }

          foreach ($categories as $c) {

            $term_id  = 'term_' . $c->term_id;
            $url_base = get_bloginfo('url').'/rubrik/';
            echo '<div class="category">';
            echo "<a href='$url_base/{$c->slug}'>";
            the_category_icon($term_id);
            echo '<span class="title">'.$c->name.'</span>';
            echo '</a>';
            echo '</div>';

          }

        ?>
      </div>
    </div>
  </div>

  <div id="news" class="frontpage-element">
    <div class="content-wrap">
      <h2>News</h2>
      <div class="news-wrap article-wrap grid-m-2 grid-l-4">
        <?php
        $posts_per_page = 4;
        $sticky_posts   = get_option( 'sticky_posts' );
        if ( is_array($sticky_posts) ) {
          $sticky_count = count($sticky_posts);
          if ($sticky_count < $posts_per_page) {
            $posts_per_page = $posts_per_page - $sticky_count;
          }
        }

        $news_args = [
          'post_type'       => 'post',
          'posts_per_page'  => $posts_per_page,
          'orderby'         => 'date',
          'order'           => 'DESC',
        ];
          $news = new WP_Query( $news_args );
          while ( $news->have_posts() ) : $news->the_post();
        ?>
          <article class="post">
            <a href="<?php the_permalink(); ?>">
            <?php
              if ( $feature = get_field('news_image') ) {
                the_img_set($feature, 'default', '25', ['type' => 'bg', 'source' => 'attachment']);
              } else {
                the_img_set(FALLBACK, 'default', '25', ['type' => 'bg']);
              }
              $title = get_field('news_title');
            ?>
              <div class="title">
                <?php the_el($title, 'news-head', '', 'h3'); ?>
              </div>
            </a>
          </article>
        <?php endwhile; wp_reset_query(); ?>
      </div>
      <div class="archive-link news">
        <a href="<?php bloginfo('url'); ?>/aktuelles/">Archiv</a>
      </div>
    </div>
  </div>

<?php get_footer();