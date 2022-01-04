<?php

/*
  Öffnungszeiten Shortcode
*/

function times_shortcode() {
  $hours = get_opening_hours('current');
  return $hours->message;
}
add_shortcode( 'oeffnungszeiten', 'times_shortcode' );