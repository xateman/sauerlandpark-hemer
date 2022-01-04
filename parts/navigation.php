<?php
  $columns = get_field('main_menu_columns', 'option');
  $column_count = count($columns);
  if ($column_count > 0) {
    ?>
    <div class="menu-columns menu-grid-<?php echo $column_count; ?>">
      <?php foreach ( $columns as $column ) {
        $title = $column['col_name'];
        $slug  = 'head_' . sanitize_html_class( clean_string( $column['col_name'] ) );
        $col_class = 'menu-column ' . $slug;
        if ( is_active_sidebar( $slug ) ) {
          echo '<div class="' . $col_class . '">';
          dynamic_sidebar( $slug );
          echo '</div>';
        }
      }  ?>
    </div>
  <?php }