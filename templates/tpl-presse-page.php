<?php
  /*
    Template Name: Presse-Seite
  */
  get_header();
?>

<?php if (have_posts()) : ?>
<?php while (have_posts()) : the_post(); ?>
<main class="singlepage page-item">
  <?php
    get_template_part('parts/crumbs');
    if ((get_field('header_select') == 'image') || (get_field('header_select') == 'slider')) {
      get_template_part('parts/slider');
      echo '<div class="content-wrap dynw page-with-feature">';
    } else {
      echo '<div class="content-wrap dynw">';
    }
      echo '<div class="content">';
      the_content();
      echo '</div>';
    echo '</div>';
  ?>
</main>

<?php if( have_rows('press_articles') ): ?>
<section class="press-articles">
  <h1>Pressemitteilungen</h1>
<?php while ( have_rows('press_articles') ) : the_row(); ?>
  <?php
    $title = get_sub_field('article_titel');
    $file = get_sub_field('article_file');
    $file_size = size_format(filesize( get_attached_file($file['ID'])));
  ?>
  <article class="press-item">
    <a href="<?php echo $file['url']; ?>" target="_blank"><span class="fa fa-file-text"></span>
    <?php if ($title) { ?>
      <span class="title"><?php echo $title; ?></span>
    <?php } else { ?>
      <span class="title"><?php echo $file['filename']; ?></span>
    <?php } ?>
    (<?php echo $file_size; ?>)
    </a>
  </article>  
<?php endwhile; ?>
</section>
<?php endif; ?>
<?php endwhile; ?>
<?php else : ?>
<main class="singlepage page-item infos-page">
  <div class="content-wrap">
    <ul class="crumbs">
      <li class="link-object">
        <a href="<?php bloginfo('url'); ?>">Home</a>
      </li>
      <li class="divider"><span class="fa fa-chevron-right"></span></li>
      <li class="current">Seite nicht gefunden</li>
    </ul>
    <div class="content">
      <h2>Fehler!</h2>
      Die gewünschte Seite ist nicht verfügbar.
    </div>
  </div>
</main>
<?php endif; ?>

<?php get_footer(); ?>
