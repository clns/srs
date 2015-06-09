(function($) {
  $(window).load(function() {
    $('.flexslider').flexslider({
      animation: 'slide',
			slideshow: false,
			animationLoop: false,
			controlNav: false,
			directionNav: false
 		}).flexsliderManualDirectionControls();
  });
})(jQuery)