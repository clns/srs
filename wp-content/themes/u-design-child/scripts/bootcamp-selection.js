function changeBootcampDisplay(id) {
    $(".bootcampInfoBlock").each( function(index, element) { 
        if ( $(this).hasClass(id) ) {
            $(this).show();
        } else {
            $(this).hide();
        }
        if ( $(this).parent().hasClass("expanding-content") ) {
			if ( $(this).parent(".expanding-content").children("."+id).children().length > 0 ) {
				$(this).parent(".expanding-content").prev(".expanding-heading").show();
				if ( $(this).parent(".expanding-content").prev(".expanding-heading").children(".expanding-icon").hasClass("expanding-icon-minus")) {
					$(this).parent(".expanding-content").show();
				}
			} else {
				$(this).parent(".expanding-content").hide();
				$(this).parent(".expanding-content").prev(".expanding-heading").hide();				
			} 
		}
    });
}
