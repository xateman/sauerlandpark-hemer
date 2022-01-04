<?php
$uniqid = 0;
$slug   = $post->post_name;
$id     = $post->ID;
$cp_id  = $slug.'_'.$uniqid;
$showSubpages = true;

if ( !empty($post->post_content)) {
  $showSubpages = false;
  ?>
  <div class="content-part textpart" id="default">
    <div class="content-wrap">
      <div class="post-content content">
        <?php the_content(); ?>
      </div>
    </div>
  </div>
<?php }

if( have_rows('content_parts') ) {

  $showSubpages = false;

  while( have_rows('content_parts') ) {

    the_row();
    $layout_type = get_row_layout();

    if ( $layout_type === 'text' ) {
      ?>
      <div class="content-part textpart" id="<?php echo $cp_id; ?>">
        <div class="content-wrap">
          <div class="post-content content">
            <?php the_sub_field('content'); ?>
          </div>
        </div>
      </div>
      <?php
    } elseif ( $layout_type === 'columns' ) {

      echo '<div class="content-part columns" id="'.$cp_id.'">';
      echo '<div class="content-wrap columns">';
      set_query_var( 'uniqid', $uniqid );
      get_template_part('scope/part','columns');
      echo '</div>';
      echo '</div>';

    } elseif ( $layout_type === 'tabs' ) {

      echo '<div class="content-part tabs" id="'.$cp_id.'">';
      set_query_var( 'uniqid', $uniqid );
      get_template_part('scope/part','tabs');
      echo '</div>';

    } else if ( $layout_type === 'accordion' ) {

      echo '<div class="content-part accordion" id="'.$cp_id.'">';
      set_query_var( 'uniqid', $uniqid );
      get_template_part('scope/part','accordion');
      echo '</div>';

    } else if ( $layout_type === 'highlight_link' ) {

      echo '<div class="content-part highlight_link" id="'.$cp_id.'">';

      echo '<div class="link-element">';

      $type = get_sub_field('link_type');

      if ( $type === 'link' ) {

        $link = get_sub_field('link_field');
        $build_link  = '<a href="'.$link['url'].'" target="'.$link['target'].'" >';
        $build_link .= '<span class="text">'.$link['title'].'</span>';
        if ($link['target'] == '_blank') {
          $build_link .= '<span class="external-link-icon fas fa-external-link-alt"></span>';
        }
        $build_link .= '</a>';

      } else if ( $type === 'file' ) {

        $file = get_sub_field('file_link');

        $build_link  = '<a href="'.$file['url'].'" target="_blank" >';
        switch ($file['mime_type']) {
          case 'application/pdf' :
            $icon = 'fa-file-pdf';
            break;
          default :
            $icon = 'fa-file-alt';
            break;
        }
        $build_link .= '<span class="file-icon fas '.$icon.'"></span>';
        $build_link .= '<span class="text">'.$file['title'].'</span>';
        $build_link .= '</a>';

      }

      echo $build_link;

      echo '</div>';

      echo '</div>';

    } else if ( $layout_type === 'gallery' ) {

      echo '<div class="content-part gallery" id="'.$cp_id.'">';
      echo '<div class="gallery-wrap grid-nospace grid-m-4">';
      $images = get_sub_field('images');
      foreach($images as $image) {
        the_img_set($image, 'default', '30', ['lightbox' => true]);
      }
      echo '</div>';
      echo '</div>';

    } else if ( $layout_type === 'downloads' ) {

      echo '<div class="content-part downloads" id="'.$cp_id.'">';
      set_query_var( 'uniqid', $uniqid );
      get_template_part('scope/loop','downloads');
      echo '</div>';

    } else if ( $layout_type === 'news' ) {

      echo '<div class="content-part news" id="'.$cp_id.'">';
      set_query_var( 'uniqid', $uniqid );
      get_template_part('scope/loop','news');
      echo '</div>';

    } else if ( $layout_type === 'button' ) {

      $button = get_sub_field('button-content');

      echo '<div class="content-part button" id="'.$cp_id.'">';
      echo "<div class='content-wrap button-wrap'>";
      echo "<a class='page-button' href='{$button['url']}' target='{$button['target']}'>{$button['title']}</a>";
      echo '</div>';
      echo '</div>';

    } else if ( $layout_type === 'opening-hours' ) {
      ?>
      <div class="content-part opening-hours" id="<?php echo $cp_id; ?>">
        <div class="content-wrap">
          <div class="post-content content">
            <?php get_template_part('scope/opening','hours'); ?>
          </div>
        </div>
      </div>
      <?php
    } else if ( $layout_type === 'subpages' ) {

      echo "<div class='content-part subpages' id='$cp_id'>";
      get_template_part('parts/subpages');
      echo "</div>";

    }
    $uniqid++;
  } //endwhile
} // endif

  if ( $showSubpages ) {
    set_query_var( 'subpageHead', 'Inhalte' );
    get_template_part('parts/subpages');
  }