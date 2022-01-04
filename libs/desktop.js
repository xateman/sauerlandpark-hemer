/*global jQuery,$,Swiper*/
jQuery(function ($) {
  "use strict";
  
  let home_swiper = new Swiper('.home-swiper-container', {
    loop: true,
    speed: 1000,
    autoplay: 5000,
    effect: 'fade'
  });

  let intro = $$('#intro-video');
  let vh    = $(window).height();

  $(window).scroll(function () {
    "use strict";
    if ( intro ) {
      if ( $(window).scrollTop() > vh ) {
        intro.pause();
      } else {
        intro.play();
      }
    }
  });

});