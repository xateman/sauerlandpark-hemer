<?php
/*
Insert Google Maps into your post or page using Shortcode
*/

function gmaps_shortcode( $atts ) {
	$atts = shortcode_atts( array(
		'api_key'    => false,
		'address'    => false,
		'lat'        => false,
		'lng'        => false,
		'zoom'       => '10',
		'height'     => '350px',
		'width'      => '350px',
		'marker'     => 0,
		'infowindow' => false,
	), $atts );
	
	wp_print_scripts( 'wp-gmaps-api' );
	
	if ( $atts['address'] ) {
		$coordinates = wp_gmaps_decode_address( $atts['address'] );
		if ( is_array ( $coordinates ) ) {
			$atts['lat'] = $coordinates['lat'];
			$atts['lng'] = $coordinates['lng'];
		}
	}
	
	$map_id = uniqid( 'gmaps_' );
	
	// show marker or not
	$atts['marker'] = (int) $atts['marker'] ? true : false;

	ob_start(); ?>
	<div class="googlemap" id="<?php echo esc_attr( $map_id ); ?>" style="height: <?php echo esc_attr( $atts['height'] ); ?>; width: <?php echo esc_attr( $atts['width'] ); ?>"></div>
    <script type="text/javascript">
		let map_<?php echo $map_id; ?>;
		let marker_<?php echo $map_id; ?>;
		let infowindow_<?php echo $map_id; ?>;
		let geocoder = new google.maps.Geocoder();
		function wp_gmaps_<?php echo $map_id; ?>() {
			let location = new google.maps.LatLng("<?php echo esc_attr( $atts['lat'] ); ?>", "<?php echo esc_attr( $atts['lng'] ); ?>");
			let map_options = {
				zoom: <?php echo esc_attr( $atts['zoom'] ) ?>,
				center: location,
				mapTypeId: google.maps.MapTypeId.ROADMAP
			}
			map_<?php echo $map_id; ?> = new google.maps.Map(document.getElementById("<?php echo $map_id; ?>"), map_options);
			
			<?php if ( $atts['marker'] ): ?>
				marker_<?php echo $map_id ?> = new google.maps.Marker({
					position: location,
					map: map_<?php echo $map_id; ?>
				});
			
				<?php if ( $atts['infowindow'] ): ?>
					infowindow_<?php echo $map_id; ?> = new google.maps.InfoWindow({
						content: '<?php echo esc_attr( $atts['infowindow'] ) ?>'
					});
					google.maps.event.addListener(marker_<?php echo $map_id ?>, 'click', function() {
						infowindow_<?php echo $map_id; ?>.open(map_<?php echo $map_id; ?>, marker_<?php echo $map_id ?>);
					});
				<?php endif; ?>
			<?php endif; ?>
		}
		wp_gmaps_<?php echo $map_id; ?>();
	</script>
	<?php
	
	return ob_get_clean();
}
add_shortcode( 'googlemap', 'gmaps_shortcode' );

function wp_gmaps_decode_address( $address ) {
  $address_hash = md5( $address );
  $coordinates = get_transient( $address_hash );
  if ( false === $coordinates ) {
    $args = array( 'address' => urlencode( $address ), 'key' => 'AIzaSyAtY3TqvgF7ciabuG7tc2Lkpneaka3HCIw' );
    $url = add_query_arg( $args, 'https://maps.googleapis.com/maps/api/geocode/json' );
    $response = wp_remote_get( $url );
    if ( is_wp_error( $response ) )
      return;
    if ( $response['response']['code'] == 200 ) {
      $data = wp_remote_retrieve_body( $response );
      if ( is_wp_error( $data ) )
          return;
      $data = json_decode( $data );
      if ( $data->status === 'OK' ) {
          $coordinates = $data->results[0]->geometry->location;

          $cache_value['lat'] = $coordinates->lat;
          $cache_value['lng'] = $coordinates->lng;

          // cache coordinates for 1 month
          set_transient( $address_hash, $cache_value, 3600*24*30 );
          $coordinates = $cache_value;

      } elseif ( $data->status === 'ZERO_RESULTS' ) {
          return __( 'Keinen Ort zur angegebenen Adresse gefunden.', LOCAL );
      } elseif( $data->status === 'INVALID_REQUEST' ) {
          return __( 'Ungültige Anfrage. Keine Adresse eingegeben.', LOCAL );
      } else {
          return __( 'Beim laden der Karte ist etwas schief gegangen.', LOCAL );
      }
    } else {
      return __( 'Der Google API Service konnte nicht geladen werden.', LOCAL );
    }
  }
  return $coordinates;
}

/**
 * Loads Google Map API
 */
function wp_gmaps_load_scripts() {
  wp_register_script( 'wp-gmaps-api', '//maps.google.com/maps/api/js?key=AIzaSyAtY3TqvgF7ciabuG7tc2Lkpneaka3HCIw' );
}
add_action( 'wp_enqueue_scripts', 'wp_gmaps_load_scripts' );
