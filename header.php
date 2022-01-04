<!DOCTYPE HTML>
<html lang="de">
<head>
  <?php wp_head(); ?>
  <style><?php include 'css/basic.min.css'; ?></style>
</head>
<body id="top" class="<?php echo element_location(); ?>">
  <?php get_template_part('parts/cookie','notice'); ?>

  <div class="search-overlay">
    <div class="form-wrap">
      <div class="closer">
        <div class="bars">
          <span class="bar bar1"></span>
          <span class="bar bar2"></span>
        </div>
      </div>
      <form role="search" method="get" class="search-form" action="<?php bloginfo('url'); ?>">
        <input class="search-field" type="search" placeholder="<?php _e('Suchbegriff', LOCAL); ?>" value="" name="s" />
        <button class="submit-form">
          <span class="far fa-search"></span>
        </button>
      </form>
    </div>
  </div>

  <?php /*
  <div class="messenger-overlay">
    <div class="messenger-wrap">
      <div class="closer">
        <div class="bars">
          <span class="bar bar1"></span>
          <span class="bar bar2"></span>
        </div>
      </div>
      <iframe rel='noopener noreferrer' src='https://wb.messengerpeople.com/?widget_hash=071b603cdfc5a1e40b6ad941d5d8c637&lang=de&wn=0' style="border:none;height:100%;width:100%;"></iframe><a href='https://www.messengerpeople.com/' target="_blank" style="margin-top:-10px;padding-top: 6px\9;text-decoration:none;text-align:right;display:block;font-size:11px;padding-right:8px;color: rgba(8, 0, 0, 0.54);font-family:'Roboto',sans-serif;">© MessengerPeople</a>
    </div>
  </div>
 */ ?>

  <header class="main">
    <div class="header-inner-wrap">
      <div class="head-logo">
        <a href="<?php bloginfo('url') ?>">
          <img src="<?php the_path ('assets/page'); ?>/sauerlandpark-hemer-logo.png" alt="Sauerlandpark Hemer" />
        </a>
      </div>

      <div class="toggle-menu closed">
        <span class="label">Menü</span>
        <div class="bars" title="<?php _e('Menü öffnen', LOCAL); ?>">
          <span class="bar bar1"></span>
          <span class="bar bar2"></span>
          <span class="bar bar3"></span>
        </div>
      </div>

      <div class="icon-wrap">
        <div class="icon-item toggle-search closed">
          <span class="far fa-search" title="<?php _e('Suche', LOCAL); ?>"></span>
        </div>

        <?php if ( $menuIcons = get_field('header_icon_menu', 'option') ) {
          foreach ( $menuIcons as $icon ) {
            echo "<div class='icon-item link-item'>";
            echo "<a href='{$icon['link']['url']}' target='{$icon['link']['target']}'><span class='{$icon['icon_code']}' title='{$icon['link']['title']}'></span></a>";
            echo "</div>";
          }
        } ?>

        <?php /*
        <div class="icon-item toggle-chat closed">
          <span class="fab fa-whatsapp" title="<?php _e('Whatsapp Chat', LOCAL); ?>"></span>
        </div>
        */ ?>
      </div>

      <nav id="main-menu" class="option-box closed">
        <div class="scrollable-wrap">
          <?php get_template_part('parts/navigation'); ?>
        </div>
        <div class="contact-menu">
          <div class="item-groups left-items">
            <?php if ( META['phone'] ) { ?>
              <div class="item">
                <a href="tel:<?php echo META['phone']['value']; ?>" target="_blank">
                  <?php echo META['phone']['icon']; ?>
                  <span class="value"><?php echo META['phone']['value']; ?></span>
                </a>
              </div>
            <?php } ?>
            <?php if ( META['email'] ) { ?>
              <div class="item">
                <a href="mailto:<?php echo META['email']['value']; ?>" target="_blank">
                  <?php echo META['email']['icon']; ?>
                  <span class="value"><?php echo META['email']['value']; ?></span>
                </a>
              </div>
            <?php } ?>
          </div>
          <div class="item-groups right-items">
            <?php
              $shop = false;
              if ( $shop ) {
                $shopLink = 'https://shop.sauerlandpark-hemer.de';
                $shopText = __('n>tree Kartenshop', LOCAL);
                $shopName = "<span class='fad fa-shopping-cart' title='$shopText'></span>";
                echo "<div class='item shop-item'><a href='$shopLink' target='_blank'>$shopName</a></div>";
              }
              if ( $s = META['instagram'] ) {
                echo "<div class='item'><a href='{$s['value']}' target='_blank'>{$s['icon']}</a></div>";
              }
              if ( $s = META['facebook'] ) {
                echo "<div class='item'><a href='{$s['value']}' target='_blank'>{$s['icon']}</a></div>";
              }
              if ( $s = META['youtube'] ) {
                echo "<div class='item'><a href='{$s['value']}' target='_blank'>{$s['icon']}</a></div>";
              }
            ?>
          </div>
        </div>
      </nav>

    </div>
  </header>

  <div class="menu-shadow"></div>

  <div id="page-wrap" class="<?php echo element_location(); ?>">

