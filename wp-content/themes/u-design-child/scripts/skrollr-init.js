( function( $ ) {
  $(window).load(function() {
    // Init Skrollr
    var s = skrollr.init({
        render: function(data) {
            //Debugging - Log the current scroll position.
            //console.log(data.curTop);
        },
        forceHeight: false,
        smoothScrolling: true,
        smoothScrollingDuration: 300
    });
	});
} )( jQuery );