jQuery(document).ready(function(){
		
	jQuery('#slidetabs_tinymce_dialog').dialog({
        autoOpen: false,
        buttons: {
            'Insert': function() {
                insertSlideTabs();
            },
            'Cancel': function() {
                jQuery(this).dialog('close');
            }
        },
        open: function() {
			if (jQuery('#slidetabs_tinymce_dialog tbody tr.selected').length == 0 ) {
                jQuery('#slidetabs_tinymce_dialog tbody tr:first').addClass('selected');
            }
        },
		width: 500,
        height: 'auto',
        draggable: false,
        resizable: false,
        title: "<strong>Embed SlideTabs</strong> &sdot; Select a entry and click <em>Insert</em> to add shortcode:",
		dialogClass: parseInt(jQuery().jquery.split(".")[1]) === 2 ? 'ui-slidetabs_dialog' : 'ui-slidetabs_dialog'}).find('tbody tr').click(function(event) {
			jQuery('#slidetabs_tinymce_dialog tbody tr').removeClass('selected');
			jQuery(this).addClass('selected');
			return false;
		});
		
    function insertSlideTabs() {
        var slidetabs_id = jQuery('#slidetabs_tinymce_dialog tbody tr.selected')[0].id.split("_")[2],
        	shortcode_str = " [slidetabs id='" + slidetabs_id + "'] ";
        
        if (typeof(tinyMCE) != 'undefined' && (ed = tinyMCE.activeEditor) && !ed.isHidden()) {
            ed.focus();
            if (tinymce.isIE) {
                ed.selection.moveToBookmark(tinymce.EditorManager.activeEditor.windowManager.bookmark);
            }
            ed.execCommand('mceInsertContent', false, shortcode_str);
        } else {
            edInsertContent(edCanvas, shortcode_str);
        }
        
        jQuery('#slidetabs_tinymce_dialog').dialog('close');
    }        
    
    // add the HTML editor button
	jQuery("#ed_toolbar").append('<input type="button" class="ed_button insertSlideTabs" value="SlideTabs" />');
    jQuery("#ed_toolbar .insertSlideTabs").click(function() {
        jQuery('#slidetabs_tinymce_dialog').dialog('open');
    });
    
});