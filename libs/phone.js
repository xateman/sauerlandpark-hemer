/*global jQuery,$,Swiper,Slideout*/

jQuery(function ($) {
  "use strict";

  let home_swiper = new Swiper('.home-swiper-container', {
    loop: true,
    speed: 1000,
    autoplay: 5000,
    effect: 'fade'
  });

  let subMenuItem = $a('.main-menu-item.menu-item-has-children');

  subMenuItem.forEach( (item) => {
    let trigger = document.createElement('div');
    trigger.classList.add('sub-menu-trigger','closed');
    trigger.innerHTML = '<span class="fad fa-chevron-down"></span>';
    item.appendChild(trigger);
    trigger.addEventListener('click', (event) => {
      if ( trigger.classList.contains('closed') ) {
        item.querySelector('ul').style = 'display:block';
        trigger.classList.replace('closed', 'opened');
      } else if ( trigger.classList.contains('opened') ) {
        item.querySelector('ul').style = 'display:none';
        trigger.classList.replace('opened', 'closed');
      }
    });
  });

  let anchorLinks = $a('.anchorLink');

  anchorLinks.forEach((link) => {
    console.log(link);
    link.addEventListener('click', (event) => {
      jumpToAnchor(link.hash, 200, 600);
      event.preventDefault();
    });
  });

/*
  subMenuItem.addEventListener('click', function (event) {

  });
  */

});
