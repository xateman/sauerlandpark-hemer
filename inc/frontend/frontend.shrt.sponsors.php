<?php 

/* 
FIELDS SPONSORS 
name: sponsor_type

Hauptsponsor : Hauptsponsor
Ausrüster : Ausrüster
Premiumpartner : Premiumpartner
Partner : Partner
Unterstützer : Unterstützer

*/

function qry_sponsor_types( $atts ) { 
  ob_start();
  
  extract( shortcode_atts( array(
    'type' => ''
  ), $atts ) );
  
  $args = array(
    'post_type'       => 'sponsor',
    'posts_per_page'  => -1,
    'orderby'         => 'menu_order',
    'order'           => 'ASC'
  );
  
  $loop = new WP_Query( $args );

  ?>
  <div class="sponsoren" id="<?php echo sanitize_html_class(clean_string($type)); ?>">
  <?php
  
  while ( $loop->have_posts() ) : $loop->the_post();
  
   if ($type == get_field('sponsor_type')) {
    if (get_field('sponsor_url')) :
      echo "<span class='sponsor_inner'>";
      echo '<a href="'.get_field('sponsor_url').'" target="_blank">';
      if ( has_post_thumbnail() ) :
        the_post_thumbnail('sponsor-thumb');
      else :
        echo "<span class='title'>".get_the_title()."</span>";
      endif;
      echo '</a></span>';
    else :
    if ( has_post_thumbnail() ) :
        the_post_thumbnail('sponsor-thumb');
      else :
        echo get_the_title();
      endif;
    endif;
  }
  
  endwhile;
  ?>
  </div> 
  <?php
  
  //delete current output buffer
  $clean = ob_get_clean();
  return $clean;

}
add_shortcode( 'sponsor_list', 'qry_sponsor_types' );