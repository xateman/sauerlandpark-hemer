<?php
$columns      = get_sub_field('columns');
$column_count = count($columns);
$grid_m       = round(2 / $column_count);
$img_size     = round((100 / $column_count), 2);
$wrapClass    = 'grid-m-'.$grid_m.' grid-l-'.$column_count;

if ($columns) {

  echo '<div class="'.$wrapClass.'">';

  foreach( $columns as $column ) {
    $vert_align = $column['vertical_align'];
    $type       = $column['column_type'];
    echo '<div class="column pos-v-'.$vert_align.' '.$type.'-column">';

    echo "<div class='$type-wrap'>";

    if ($type === 'image') {
      the_img_set($column['column_img'], 'relative', $img_size, ['lightbox' => true]);
    } else if ($type === 'text') {
      echo $column['column_text'];
    } else if ($type == 'button') {
      $button = $column['button-content'];
      echo "<a class='page-button' href='{$button['url']}' target='{$button['target']}'>{$button['title']}</a>";
    }

    echo "</div>";
    echo '</div>';
  }
  echo '</div>';
}