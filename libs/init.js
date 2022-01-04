/*global jQuery,$,Swiper,alert,activeIndex,Cookies*/
/*jslint plusplus: true*/

function $$(element)
{
  return document.querySelector(element);
}
function $a(element)
{
  return document.querySelectorAll(element);
}

const dynamic_images = $a('.dynamic--image-bg');
const onLoadHash     = location.hash;

function removeHash () {
  let scrollV, scrollH, loc = window.location;
  if ("pushState" in history)
    history.pushState("", document.title, loc.pathname + loc.search);
  else {
    // Prevent scrolling by storing the page's current scroll offset
    scrollV = document.body.scrollTop;
    scrollH = document.body.scrollLeft;

    loc.hash = "";

    // Restore the scroll offset, should be flicker free
    document.body.scrollTop = scrollV;
    document.body.scrollLeft = scrollH;
  }
}

function closest(num, arr) {
  var curr = arr[0];
  var diff = Math.abs (num - curr);
  for (var val = 0; val < arr.length; val++) {
    var newdiff = Math.abs (num - arr[val]);
    if (newdiff < diff) {
      diff = newdiff;
      curr = arr[val];
    }
  }
  return curr;
}

function set_background(elem) {

  if (elem === null) return;

  /*
    The function runs through every data-img attr and selects the one which
    is at least as wide / big as the viewport width - the url from this will
    replace the default background-image of the target element
   */
  let viewport = document.documentElement.clientWidth;
  let w, approx = (elem.dataset.width) / 100, sources = {}, widths = [];
  if (approx) {
    w = viewport * approx;
  } else {
    w = viewport;
  }

  for (let i = 0; i < elem.attributes.length; i++) {
    let attr = elem.attributes[i];
    if (/^data-img-/.test(attr.nodeName)) {
      let width = attr.nodeName.replace(/^data-img-/, ''),
          url   = attr.nodeValue;
      sources[width] = {
        'width' : width,
        'url' : url
      };
      widths.push(width);

    }
  }

  elem.style.background = 'url(' + sources[closest (w, widths)].url + ') no-repeat top center/cover';

}

dynamic_images.forEach(function(element) {
  set_background( element );
});

function createNewSlider(id, type, slider, options) {
  slider = new Swiper('#'+id, options);
}

function jumpToAnchor(target, offset, speed) {
  offset = (typeof offset !== 'undefined') ?  offset : 0;
  speed  = (typeof speed !== 'undefined') ?  speed : 1200;
  jQuery('html, body').stop().animate({
    scrollTop: jQuery(target).offset().top - offset
  }, speed, 'easeInOutExpo');
}

jQuery(function ($) {
  "use strict";

  $('a[href^="#"]').click( function(event) {
    let anchor = $(this)[0].hash;
    console.log('anchor clicked: ' + anchor);
    jumpToAnchor(anchor);
    event.preventDefault();
  });

  if ( onLoadHash ) {
    jumpToAnchor(onLoadHash);
    removeHash();
  }

  $('.toggle-chat').click(function () {
    $('.messenger-overlay').fadeIn(600, function() {
      $('.messenger-overlay .closer').click( function() {
        $('.messenger-overlay').fadeOut(200);
      });
    });
  });

  $('.toggle-menu').click(function () {
    let $this = $(this);
    if ( $this.hasClass('closed') ) {
      $this.addClass('opened').removeClass('closed');
      $('#main-menu').stop().fadeIn();
      $('.menu-shadow').stop().fadeIn();
      let menuScrollable = $$('.scrollable-wrap');
      let menuColumns    = $$('.menu-columns');
      if ( menuScrollable.clientHeight < menuColumns.clientHeight ) {
        $('#main-menu .scrollable-wrap').mCustomScrollbar({
          scrollInertia: 400,
          theme: 'pagelinks'
        });
      }
    } else if ( $this.hasClass('opened') ) {
      $('#main-menu').stop().fadeOut();
      $('.menu-shadow').stop().fadeOut();
      $this.addClass('closed').removeClass('opened');
    }
  });

  $('.access-toggle').click(function () {
    let $this = $(this);
    if ( $this.hasClass('closed') ) {
      $this.addClass('opened').removeClass('closed');
      $('.access-content').fadeIn(500);
    } else if ( $this.hasClass('opened') ) {
      $('.access-content').fadeOut(300);
      $this.addClass('closed').removeClass('opened');
    }
  });

  $('.toggle-search').click(function () {
    $('.search-overlay').fadeIn(600, function() {
      $('.search-overlay input').focus();
      $('.search-overlay .closer').click( function() {
        $('.search-overlay').fadeOut(200);
      });
    });
  });

  $('#s').keypress(function (e) {
    if (e.which === 13) {
      $('#searchform').submit();
    }
  });

  $('.dropdown-element .toggle-list').each(function () {
    $( this ).click(function () {
      $( this ).toggleClass('active');
      $( this ).next('ul.item-list').slideToggle(200);
    });
  });

  $('.dropdown-element ul.item-list').mCustomScrollbar({
    scrollInertia: 400,
    theme: 'pagelinks'
  });
  
  let slideHeight, cnID,
      header_slider = new Swiper('.header-swiper-container', {
        navigation: {
          nextEl: '.header-button-next',
          prevEl: '.header-button-prev',
        },
        pagination: {
          el:  '.header-swiper-pagination',
          clickable: true,
          type: 'fraction',
        },
        grabCursor: false,
        loop: true,
        speed: 800,
        autoplay: {
          delay: 5000,
        },
        autoHeight: true,
        lazyLoading: true
      }),
      landingpage_slider = new Swiper('.landingpage-swiper-container', {
        navigation: {
          nextEl: '.landingpage-button-next',
          prevEl: '.landingpage-button-prev',
        },
        grabCursor: true,
        loop: true,
        speed: 1000,
        autoplay: {
          delay: 5000,
        },
        effect: 'fade'
      });
  
  cnID = $('#cookie-notice');
    $('.cn-set-cookie').click(function (e) {
    document.cookie = "SPH=Visitor; expires = Fri, 31 Dec 9999 23:59:59 GMT;path=/";
    cnID.fadeOut(300, function () {
      cnID.remove();
    });
    e.preventDefault();
  });
  
  $('.fancybox').fancybox({
    openEffect : 'elastic',
    closeEffect : 'elastic',
    helpers: {
      overlay: {
        locked: false
      }
    }
  });
  
  $('.fancybox-media').fancybox({
    openEffect : 'none',
    closeEffect : 'none',
    helpers : {
      media : {},
      overlay: {
        locked: false
      }
    }
  });

  $('.tab-wrap').tabs({
    hide: {
      effect: 'slideUp',
      duration: 250
    },
    show: {
      effect: 'slideDown',
      duration: 250
    }
  });

  $('.accordion-wrap').accordion({
    header: '.accordion-title',
    icons: {
      'header':       'fal fa-chevron-down',
      'activeHeader': 'fal fa-chevron-up'
    },
    collapsible: true,
    heightStyle: 'content',
    hide: {
      effect: 'slideUp',
      duration: 250
    },
    show: {
      effect: 'slideDown',
      duration: 250
    }
  });

  $('.year-head.toggle').click(function() {
    let $target = $(this).next();
    if ( $target.hasClass('closed') ) {
      $target.addClass('opened').removeClass('closed');
    } else if ( $target.hasClass('opened') ) {
      $target.addClass('closed').removeClass('opened');
    }
  });
  
});