<?php
/*
  Template Name: Formular Template
*/
get_header(); ?>

<?php if (have_posts()) : ?>
  <?php while (have_posts()) : the_post(); ?>
    <main class="request-form page-item">
      <?php get_template_part('parts/crumbs'); ?>
      <div class="content-wrap dynw">
        <div class="content">
          <?php

          $course_post_id = $_GET['id'];

          if ( get_field('course_id', $course_post_id) )
          {
            $_GET['coursename'] = get_field('course_name', $course_post_id);
            $_GET['courseid']   = get_field('course_id',   $course_post_id);

            the_content();
          }
          else
          {
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
