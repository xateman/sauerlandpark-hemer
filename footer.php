<?php if ( !is_front_page() ) { ?>
  <div class="sponsors-container page">
    <div class="inner-container">
      <h2>Vielen Dank an Unsere Unterstützer</h2>
      <?php
        if ( false === ( $sponsors = get_transient( 'sponors_frontpage_cache' ) ) ) {

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

          set_transient( 'sponors_frontpage_cache', $sponsors, LONG_TRANSIENT );
        }
        if ( $sponsors->have_posts() ) {
          echo '<div class="content-wrap sponsors-wrap grid-s-2 grid-m-5 grid-l-10">';
          while ($sponsors->have_posts()) {
            $sponsors->the_post();
            echo '<div class="sponsor">';
            if (get_field('sponsor_link')) {
              echo '<a href="' . get_field('sponsor_link') . '" target="_blank">';
              if (get_field('sponsor_img')) {
                $image = get_field('sponsor_img');
                echo '<img src="' . $image['sizes']['L_SPONSOR'] . '" alt="' . get_the_title() . '" />';
              } else {
                echo get_the_title();
              }
              echo '</a>';
            } else {
              if (get_field('sponsor_img')) {
                $image = get_field('sponsor_img');
                echo '<img src="' . $image['sizes']['L_SPONSOR'] . '" class="sponsor-logo" alt="' . get_the_title() . '" />';
              } else {
                echo get_the_title();
              }
            }
            echo '</div>';
          }
          echo '</div>';
        }
        wp_reset_postdata();
      ?>
    </div>
  </div>
  <?php } ?>

  <footer>
    <?php

    $footerCols = get_field('footer_info','option');
    if ($footerCols) {
      $columnCount = count($footerCols) + 1;
      $tabletColumns = $columnCount / 2;
    ?>
    <div class="footer-element infos">
      <div class="content-wrap footer-columns grid-m-<?php echo $tabletColumns; ?> grid-l-<?php echo $columnCount; ?>">
        <?php foreach ( $footerCols as $col ) {
          echo '<div class="footer-column">';
          the_el($col['headline'], '', '', 'h3');
          the_el($col['content'], 'col-content');
          echo '</div>';
        } ?>
        <div class="footer-column newsletter">
          <script>
            function loadjQuery(e,t){var n=document.createElement("script");n.setAttribute("src",e);n.onload=t;n.onreadystatechange=function(){if(this.readyState=="complete"||this.readyState=="loaded")t()};document.getElementsByTagName("head")[0].appendChild(n)}function main(){
              var $cr=jQuery.noConflict();var old_src;$cr(document).ready(function(){$cr(".cr_form").submit(function(){$cr(this).find('.clever_form_error').removeClass('clever_form_error');$cr(this).find('.clever_form_note').remove();$cr(this).find(".musthave").find('input, textarea').each(function(){if(jQuery.trim($cr(this).val())==""||($cr(this).is(':checkbox'))||($cr(this).is(':radio'))){if($cr(this).is(':checkbox')||($cr(this).is(':radio'))){if(!$cr(this).parent().find(":checked").is(":checked")){$cr(this).parent().addClass('clever_form_error')}}else{$cr(this).addClass('clever_form_error')}}});if($cr(this).attr("action").search(document.domain)>0&&$cr(".cr_form").attr("action").search("wcs")>0){var cr_email=$cr(this).find('input[name=email]');var unsub=false;if($cr("input['name=cr_subunsubscribe'][value='false']").length){if($cr("input['name=cr_subunsubscribe'][value='false']").is(":checked")){unsub=true}}if(cr_email.val()&&!unsub){$cr.ajax({type:"GET",url:$cr(".cr_form").attr("action").replace("wcs","check_email")+$cr(this).find('input[name=email]').val(),success:function(data){if(data){cr_email.addClass('clever_form_error').before("<div class='clever_form_note cr_font'>"+data+"</div>");return false}},async:false})}var cr_captcha=$cr(this).find('input[name=captcha]');if(cr_captcha.val()){$cr.ajax({type:"GET",url:$cr(".cr_form").attr("action").replace("wcs","check_captcha")+$cr(this).find('input[name=captcha]').val(),success:function(data){if(data){cr_captcha.addClass('clever_form_error').after("<div  class='clever_form_note cr_font'>"+data+"</div>");return false}},async:false})}}if($cr(this).find('.clever_form_error').length){return false}return true});$cr('input[class*="cr_number"]').change(function(){if(isNaN($cr(this).val())){$cr(this).val(1)}if($cr(this).attr("min")){if(($cr(this).val()*1)<($cr(this).attr("min")*1)){$cr(this).val($cr(this).attr("min"))}}if($cr(this).attr("max")){if(($cr(this).val()*1)>($cr(this).attr("max")*1)){$cr(this).val($cr(this).attr("max"))}}});old_src=$cr("div[rel='captcha'] img:not(.captcha2_reload)").attr("src");if($cr("div[rel='captcha'] img:not(.captcha2_reload)").length!=0){captcha_reload()}});function captcha_reload(){var timestamp=new Date().getTime();$cr("div[rel='captcha'] img:not(.captcha2_reload)").attr("src","");$cr("div[rel='captcha'] img:not(.captcha2_reload)").attr("src",old_src+"?t="+timestamp);return false}
            }
            if(typeof jQuery==="undefined"){loadjQuery("//ajax.googleapis.com/ajax/libs/jquery/1.4.2/jquery.min.js",main)}else{main()}
          </script>
          <h3>Newsletter</h3>
          <form class="layout_form cr_form cr_font" action="https://eu1.cleverreach.com/f/62618-200825/wcs/" method="post" target="_blank">
            <div id="4235237" rel="email" class="cr_ipe_item ui-sortable musthave" >
              <input id="text4235237" name="email" value="" placeholder="E-Mail" type="text"  />
            </div>
            <div id="4235242" rel="checkbox" class="cr_ipe_item ui-sortable musthave" >
              <input id="accept-privacy-policy" class="cr_ipe_checkbox" name="1123787[]" value="Ich stimme den Datenschutzbestimmungen zu." type="checkbox"  />
              <label for="accept-privacy-policy">Ich stimme den <a href="https://sauerlandpark-hemer.de/datenschutz/" target="_blank">Datenschutzbestimmungen</a> zu.</label>
            </div>
            <div id="4235243" rel="checkbox" class="cr_ipe_item ui-sortable musthave" >
              <input id="accept-newsletter" class="cr_ipe_checkbox" name="1123788[]" value="Ich möchte den regelmäßigen Newsletter erhalten. Die Abmeldung ist jederzeit möglich." type="checkbox"  />
              <label for="accept-newsletter">Ich möchte den regelmäßigen Newsletter erhalten. Die Abmeldung ist jederzeit möglich.</label>
            </div>
            <div id="4235239" rel="button" class="cr_ipe_item ui-sortable submit_container" >
              <button type="submit" class="cr_button">Anmelden</button>
            </div>
          </form>
        </div>
      </div>
    </div>
    <?php } ?>

    <div class="footer-element navigation">
      <div class="content-wrap">
        <?php get_template_part('parts/navigation'); ?>
      </div>
    </div>

    <?php if (get_field('o_footer_copyright','option')) { ?>
    <div class="footer-element copyright">
      <div class="content-wrap">
        <p>© <?php echo date("Y"); ?> <?php the_field('o_footer_copyright','option'); ?></p>
      </div>
    </div>
    <?php } ?>

  </footer>
</div>
<?php wp_footer(); ?>

<script type="text/javascript" async defer>
  /* <![CDATA[ */
  <?php if (!empty($content_parts_slider)) { ?>
  let sliders = [];
  <?php foreach($content_parts_slider as $s) {
    $json = json_encode($s);
    echo "sliders.push($json);";
  } ?>

  window.addEventListener('DOMContentLoaded', (event) => {
    sliders.forEach((slider) => {
      createNewSlider(slider.id, slider.type, slider.name, slider.options);
    });
  });
  <?php } ?>
  let path = '<?php the_path(); ?>';
  /* ]]> */
</script>

<?php
  $trackingID = get_field('ga_tracking_id', 'option');
  if ( isset($trackingID) && $trackingID != '' ) { ?>
    <script async src="https://www.googletagmanager.com/gtag/js?id=<?php echo $trackingID; ?>"></script>
    <script>
      function analyticsInit(type) {
        let duration = 60;
        if ( type === 'complete' ) {
          duration = 28 * 24 * 60 * 60;
        }
        window.dataLayer = window.dataLayer || [];
        function gtag(){dataLayer.push(arguments);}
        gtag('js', new Date());
        gtag('config', '<?php echo $trackingID; ?>', {
          'cookie_expires': duration,  // 28 days, in seconds
          'anonymize_ip': true
        });
        console.log('duration: ' + duration);
      }
      <?php if ( isset($_COOKIE['cookieNotice']) && $_COOKIE['cookieNotice'] === 'complete' ) { ?>
      analyticsInit('complete');
      <?php } ?>
    </script>
  <?php } else { ?>
    <script>
      function analyticsInit(type) {
        console.log('no tracking id available; type: ' + type);
      }
      <?php if ( isset($_COOKIE['cookieNotice']) && $_COOKIE['cookieNotice'] === 'complete' ) { ?>
      analyticsInit('complete');
      <?php } ?>
    </script>
  <?php } ?>

</body>
</html>

<?php load_styles();