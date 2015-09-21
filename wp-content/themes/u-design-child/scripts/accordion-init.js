/**
 * Created by Eric Hall on 11/13/2014.
 */

(function($) {
    $(window).load(function() {
        $(".expanding-heading").on('click', function(){
            $(this).next('.expanding-content').slideToggle();
            $(this).find('.expanding-content').slideToggle();
            $(this).children('.expanding-icon').toggleClass('expanding-icon-plus expanding-icon-minus');
        });
    });

})(jQuery)