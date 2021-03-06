/*global jQuery,$,Swiper,Slideout*/

jQuery(function ($) {
  "use strict";

  let markers       = [];
  let groups        = {};
  let mapMinZoom    = 3;
  let mapMaxZoom    = 20;
  let active_marker = $('#park-map').data('active');

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

  let assets = 'http://aliaz.ddns.net:81/sph/wp-content/themes/sph-theme/assets/'
  let streetmap = L.tileLayer(
    'https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png',
    {
      attribution: 'Map data © <a href="https://openstreetmap.org">OpenStreetMap</a> contributors'
    }
  );

  let map = L.map('park-map', {
    center: [51.38502, 7.78330],
    zoom: 17,
    maxZoom: mapMaxZoom,
    minZoom: mapMinZoom,
    attributionControl: false,
    zoomControl: false,
    scrollWheelZoom: false,
    layers: streetmap
  });

  L.control.mousePosition({
    position: 'bottomleft'
  }).addTo(map);

  L.control.zoom({
    position: 'topright'
  }).addTo(map);

  map.on('click', function(e) {
    let values = e.latlng.lat.toFixed(6) + ', ' + e.latlng.lng.toFixed(6);
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

  function create_markers() {
    function showRoute(id) {
      if ( id ) {
        L.Routing.control({
          waypoints: [
            L.latLng(51.386024, 7.774318),
            L.latLng( markers[id].latlng[0], markers[id].latlng[1] )
          ]
        }).addTo(map);
      } else {
        console.log('no id provided');
      }
    }

    for (let key in markers) {
      if (markers.hasOwnProperty(key)) {
        let color = markers[key].color;
        let html  = '';
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

        mapmarker[ key ] = L.marker(markers[key].latlng,{
          title: key + ' | ' + markers[key].title,
          icon : icon[key]
        }).addTo(map).bindPopup( popupContent );

      }
    }
  }

  create_markers();

  if (active_marker && active_marker != '') {
    map.setView( markers[active_marker].latlng, 17 );
    mapmarker[active_marker].openPopup();
  } else {
    map.setView([51.38502, 7.78330], 17);
  }

  $('.item-list .item.marker_anchor').each( function() {
    $(this).click( function () {
      $('body,html').animate({
        scrollTop: 0
      }, 800);

      let id = $(this).data('id');
      map.setView( markers[id].latlng, 17);
      mapmarker[id].openPopup();
    });
  });

  //ONLY WITH HTTPS

  function getLocation() {
    if (navigator.geolocation) {
      navigator.geolocation.getCurrentPosition(showPosition);
    } else {
      console.log("Geolocation is not supported by this browser.");
    }
  }
  function showPosition(position) {
    console.log('Latitude: ' + position.coords.latitude);
    console.log('Longitude: ' + position.coords.longitude);
  }

  getLocation();

});
