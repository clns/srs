function changeBootcampDisplay(id) {
        $(".bootcampInfoBlock").each( function(index, element) { 
                if ( $(this).hasClass(id) ) {
                        $(this).show();
                } else {
                        $(this).hide();
                }
        });
}
