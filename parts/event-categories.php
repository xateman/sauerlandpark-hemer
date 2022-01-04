<?php
  $url_base = get_bloginfo('url');
  $cat_base = 'rubrik';
  $cat_args = [
    'orderby'    => 'name',
    'order'      => 'ASC',
    'taxonomy'   => 'event_tax',
    'hide_empty' => true
  ];
  $categories = get_terms($cat_args);
  if ( count ( $categories ) > 0 ) {

    echo "<div class='dropdown-element event-categories'>";
    echo '<div class="toggle-list">' . __('Rubrik wählen', LOCAL) . '</div>';
    echo "<ul class='item-list'>";
    echo "<li><a href='$url_base/$cat_base/'>Zukünftige Events</a></li>";
    foreach ($categories as $cat) {
      $url = site_url( $cat_base ) . '/' . $cat->slug;
      echo "<li><a href='$url'>{$cat->name} ({$cat->count})</a></li>";
    }
    echo "</ul>";
    echo "</div>";

  }