<?php
  if ( $notice = get_field('privacy_policy', 'option') ) {
    if ( !isset( $_COOKIE['cookieNotice'] ) ) { ?>
      <div id="cookie-notice">
        <div class="cookie-notice--inner">
          <div class="text-wrap">
            <?php echo $notice['text']; ?>
          </div>
          <div class="button-wrap">
            <button class="accept-minimal" onclick="acceptCookies('necessary');"><?php _e('Notwendige Cookies akzeptieren', LOCAL); ?></button>
            <button class="accept-all" onclick="acceptCookies('complete');"><?php _e('Cookies akzeptieren', LOCAL); ?></button>
          </div>
        </div>
      </div>
    <?php }
  }