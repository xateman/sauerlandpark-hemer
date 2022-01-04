<?php
/*
  Template Name: Formular Template
*/
get_header(); ?>

<?php if (have_posts()) : ?>
  <?php while (have_posts()) : the_post(); ?>
    <div class="page-navigation">
      <div class="elements-wrap">
        <?php get_template_part('parts/crumbs'); ?>
      </div>
    </div>
    <main class="request-form page-item">
      <div class="content-wrap">
        <div class="post-content content">
          <?php

          $course_post_id = $_GET['id'];

          if ( get_field('course_id', $course_post_id) ) {

            $_GET['coursename'] = get_field('course_name', $course_post_id);
            $_GET['courseid']   = get_field('course_id',   $course_post_id);
            the_content();

          } else {

            echo '<p>';
            _e( 'Das Anfrageformular für den ausgewählten Kurs ist aktuelle deaktiviert.', LOCAL );
            echo '</p>';

          }
          ?>
        </div>
      </div>
    </main>
  <?php endwhile; ?>
<?php endif; ?>

<?php get_footer(); ?>
