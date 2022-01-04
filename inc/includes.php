<?php

  // Libraries
  if ( !class_exists('Mobile_Detect') ) {
    require_once('libs/mobile-detect.php');
  }

  // Functions & Handler
  require_once('functions/func.default.php');
  require_once('functions/func.constants.php');
  require_once('functions/func.basic.php');
  require_once('functions/func.custom.php');
  require_once('functions/func.create-widgets.php');
  require_once('functions/func.get-opening-hours.php');
  require_once('functions/func.slider.php');
  require_once('functions/func.security.php');

  require_once('handler/handler.images.php');
  require_once('handler/handler.walker.php');
  require_once('handler/handler.menus.php');
  require_once('handler/handler.fields.php');

  // Options & Custom Post Types
  require_once('types/type.sponsoren.php');
  require_once('types/type.events.php');
  require_once('types/type.poi.php');
  require_once('types/type.course.php');
  require_once('types/type.options.php');
  require_once('types/type.download.php');
  require_once('types/type.press.php');

  // Frontend
  require_once('frontend/frontend.enqueue.php');
  require_once('frontend/frontend.meta.php');
  require_once('frontend/frontend.shrt.gmaps.php');
  require_once('frontend/frontend.shrt.sponsors.php');
  require_once('frontend/frontend.shrt.gallery.php');
  require_once('frontend/frontend.shrt.times.php');

  // Backend
  if ( is_admin() )  {
    require_once('backend/backend.enqueue.php');
    require_once('backend/backend.extended.php');
  }