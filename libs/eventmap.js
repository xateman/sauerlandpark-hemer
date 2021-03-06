/*global jQuery,$*/

jQuery(function ($) {
  "use strict";

  let assets = 'http://aliaz.ddns.net:81/sph/wp-content/themes/sph-theme/assets/'

  let markers = [];
  let groups  = {};

  $('.marker_anchor').each(function () {
    const $this = $(this);
    let group  = $this.data('group'),
        color  = $this.data('color'),
        title  = $this.data('title'),
        yx     = $this.data('yx'),
        latlng = $this.data('latlng'),
        page   = $this.data('page'),
        icons  = $this.data('icons'),
        id     = $this.data('id');

    markers[id] = {
      'latlng' : latlng,
      'yx'    : yx,
      'color' : color,
      'title' : title,
      'icons' : icons,
      'group' : group,
      'page'  : page
    };

    if (groups[group] == null) {
      groups[group] = [];
    }
    groups[group].push(id);
  });


  console.log(groups);
  console.log(markers);

  let map_image       = $('#park-map').data('map');
  let active_marker   = $('#park-map').data('active');
  let scrollWheelZoom = $('#park-map').data('swz');

  let mapExtent = [0.00000000, -10000.00000000, 9421.00000000, 0.00000000];
  let mapMinZoom = 3;
  let mapMaxZoom = 6;
  let mapMaxResolution = 1.00000000;
  let mapMinResolution = Math.pow(2, mapMaxZoom) * mapMaxResolution;;
  let tileExtent = [0.00000000, -10000.00000000, 9421.00000000, 0.00000000];
  let crs = L.CRS.Simple;
  crs.transformation = new L.Transformation(1, -tileExtent[0], -1, tileExtent[3]);
  crs.scale = function(zoom) {
    return Math.pow(2, zoom) / mapMinResolution;
  };
  crs.zoom = function(scale) {
    return Math.log(scale * mapMinResolution) / Math.LN2;
  };

  let eventMap = new L.Map('park-map', {
    maxZoom: mapMaxZoom,
    minZoom: mapMinZoom,
    crs: crs,
    attributionControl: false,
    zoomControl: false,
    scrollWheelZoom: false
  });

  let layer = L.tileLayer( assets + 'map/{z}/{x}/{y}.png', {
    minZoom: mapMinZoom,
    maxZoom: mapMaxZoom,
    noWrap: true,
    tms: false
  }).addTo(eventMap);

  eventMap.fitBounds([
    crs.unproject(L.point(mapExtent[2], mapExtent[3])),
    crs.unproject(L.point(mapExtent[0], mapExtent[1]))
  ]);

  L.control.mousePosition({
    position: 'bottomleft'
  }).addTo(eventMap);

  L.control.zoom({
    position: 'topright'
  }).addTo(eventMap);

  eventMap.on('click', function(e) {
    let values = e.latlng.lat.toFixed(2) + ', ' + e.latlng.lng.toFixed(2);
    console.log(values);
    let el = document.createElement('textarea');
    el.value = values;
    el.setAttribute('readonly', '');
    el.style.position = 'absolute';
    el.style.left = '-9999px';
    document.body.appendChild(el);
    el.select();
    document.execCommand('copy');
    document.body.removeChild(el);
  });

  let mapmarker = {};
  let icon      = {};

  for (let key in markers) {
    if (markers.hasOwnProperty(key)) {
      let color  = markers[key].color;
      let html   = '';
      let filter = '';

      let popupContent = '<div class="popup-inner">';
      popupContent += '<span class="title">'+markers[key].title+'</span><p>';
      if ( markers[key].page ) {
        popupContent += '<a href="'+markers[key].page+'" target="_blank"><span class="fad fa-angle-right"></span> Mehr Informationen</a><br/>';
      }
      popupContent += '<a href="https://www.google.com/maps?saddr=Mein+Standort&daddr='+markers[key].latlng+'" target="_blank"><span class="fad fa-angle-right"></span> Routenplaner</a>';
      popupContent += '</p></div>';

      html += '<div class="circle" style="background-color: '+color+';">';
      html += '<div class="number">' + key + '</div>';
      html += '</div>';

      if (markers[key].icons) {
        html += '<div class="icon-wrap">';
        markers[key].icons.forEach(function(ico) {
          let background = 'background-image: url(' + assets + 'map/icons/' + ico.value + '.png)'
          filter += ' ' + ico.value;
          html   += '<span class="icon ' + ico.value + '" title="' + ico.label + '" style="' + background + '"></span>';
        });
        html += '</div>';
      }

      icon[key] = L.divIcon({
        className   : 'marker-icon ' + markers[key].group + ' ' + filter + ' marker_' + key,
        html        : html,
        iconSize    : 32,
        iconAnchor  : [16,32],
        popupAnchor : [3,-24]
      });
      mapmarker[ key ] = L.marker(markers[key].yx,{
        title: key + ' | ' + markers[key].title,
        icon : icon[key]
      }).addTo(eventMap).bindPopup( popupContent );
    }
  }

  if (active_marker && active_marker != '') {
    eventMap.setView( markers[active_marker].yx, 1 );
    mapmarker[active_marker].openPopup();
  } else {
    eventMap.setView([-5000, 4710], 4);
  }

  $('.item-list .item.marker_anchor').each( function() {
    $(this).click( function () {
      $('body,html').animate({
        scrollTop: 0
      }, 800);

      let id = $(this).data('id');
      eventMap.setView( markers[id].yx, 4);
      mapmarker[id].openPopup();
    });
  });

});
