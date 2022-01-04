<?php get_header(); ?>
  <div class="page-navigation">
    <div class="elements-wrap">
      <?php get_template_part('parts/crumbs'); ?>
    </div>
  </div>
<main class="page 404 single">
  <div class="content-part textpart" id="default">
    <div class="content-wrap">
      <div class="post-content content">

        <h1>Fehler 404</h1>
        <p>Es wurde versucht eine nicht existierende Seite aufzurufen.<br />
          Falls Sie etwas auf unserer Seite vermissen, freuen wir uns auf Ihre <a href="<?php bloginfo('url'); ?>/kontakt">Nachricht</a>.</p>

        <form role="search" method="get" class="search-form" action="<?php bloginfo('url'); ?>">
          <input class="search-field" type="search" placeholder="<?php _e('Suchbegriff', LOCAL); ?>" value="" name="s" />
          <button class="submit-form">
            <span class="far fa-search"></span>
          </button>
        </form>

        <p><a href="<?php bloginfo('url'); ?>" title="">Zurück zur Startseite</a></p>
      </div>
    </div>
  </div>
</main>
<?php get_footer(); ?>