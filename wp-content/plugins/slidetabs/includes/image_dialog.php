<style type="text/css">
	div#TB_ajaxContent {
		width:100% !important;
		padding:0 !important;
		overflow:auto;
		overflow-y:auto;
		overflow-x:auto;
	}
	#TB_ajaxContent p {
		margin:0 !important;
		padding:0 !important;
		color:#555555;
	}
	div#bg_dialog_tt {
		width:150px !important;
		height:100px !important;
	}
</style>

<div id="slidetabs_image_dialog">
    
    <?php if (!empty($images)): ?>
    <div class="image_filtering">
        <select id="thumb_dates">
            <option value="all" >All dates</option>
            <?php foreach($dates as $key => $value): ?>
            <option value="<?php echo $key; ?>" <?php echo ($date == $key) ? 'selected="selected"' : ''; ?>><?php echo $value; ?></option>
            <?php endforeach; ?>
        </select>
        <?php if ($total_pages > 1): ?>
        <select id="thumb_pages">
            <?php for ($i = 1; $i <= $total_pages; $i++): ?>
            <option value="<?php echo $i; ?>" <?php echo ($page == $i) ? 'selected="selected"' : ''; ?>>Page <?php echo $i; ?></option>
            <?php endfor; ?>
        </select>
        <?php endif; ?>
        <a class="button-secondary view_button">View &raquo;</a>
        <span id="slidetabs_image_view_loading">&nbsp;</span>
	</div> <!-- /.image_filtering -->
		
    <table class="widefat">
        <tbody>
            <?php foreach ($images as $image): ?>
            <tr>
                <td width="43px"><img src="<?php echo $slidetabs_class->url('/includes/timthumb/timthumb.php') . '?q=100&w=32&h=32&a=cc&src=' . $image['guid']; ?>"/></td>
                <td><?php echo $image['post_title']; ?></td>
                <td width="83px"><?php echo date("Y/m/d", strtotime($image['post_date'])); ?></td>
                <td width="103px"><a class="button set_image" href="<?php echo $image['guid']; ?>">Use Image</a></td>
            </tr>
            <?php endforeach; ?>    	
        </tbody>
    </table>
    <?php else: ?>
    <p>No image attachments found.</p>
    <?php endif; ?>

</div> <!-- /#slidetabs_image_dialog -->

<script type="text/javascript">
(function($) {
	
	var $tbCont = $('#TB_ajaxContent'),
		$spinr = $('#slidetabs_image_view_loading'),
		ajaxNow = false;
		
	// 'view' button AJAX call
	$tbCont.find('a.view_button').click(function() {
		if (ajaxNow) { return false; };
		ajaxNow = true;
		$spinr.css('visibility', 'visible');
		
		$.ajax({
			url: stVars.ajaxURL,
			type: 'get',
			dataType: 'html',
			data: { action: 'slidetabs_add_background', tab_editor: '<?php echo $tab_editor; ?>', height: $('div#TB_window').height(), page: $tbCont.find('select#thumb_pages').val(), date: $tbCont.find('select#thumb_dates').val() },
			complete: function(data) {
				ajaxNow = false;
				$spinr.css('visibility', 'hidden');
				$tbCont.empty();				
				$(data.responseText).appendTo($tbCont);
			}
		});
		
		return false;
	});
	
	// 'use image' button event
	$tbCont.find('a.set_image').click(function() {
		var $bgCont = $('div#<?php echo $tab_editor; ?> div.inside').find('ul.form_rows li.content_background'),
			href = $(this).attr('href'),
			fullPath = stVars.timthumbURL+'?src='+href+'&w=32&h=32&q=100',
			$bgUl = $bgCont.children('ul'),			
			$bgThumbLi = $bgUl.children('li.bg_thumb').removeClass('bg_not_set bg_not_found').addClass('bg_loading'),
			$bgUrlLi = $bgUl.children('li.bg_url'),
			$saveBtn = $bgUrlLi.children('a.bg_url_save');
		
		$bgCont.children('input').attr('value', href);
		$bgUl.find('li.bg_url strong').text(href);		
		
		if ($saveBtn.length) {
			$saveBtn.hide();
			$bgUrlLi.css('paddingTop', '10px');
			$bgUrlLi.children('span').show();
			$bgUrlLi.children('a.bg_edit_url').show();
		}
		
		$bgThumbLi.find('img').remove();
		
		$('<img />').load(function() {								
			$bgThumbLi.removeClass('bg_loading');
			$(this).appendTo($bgThumbLi);
		}).attr('src', fullPath).hover(function() { stAdmin.ttMouseenter(this); }, function() { stAdmin.ttMouseleave(); });
		
		$('div#TB_overlay').trigger('click');
		
		return false;
	});
	
	var $imgCont = $('<div id="bg_dialog_tt" class="bg_tt"></div>').appendTo($tbCont);
	
	// thumbnail hover
	$tbCont.find('table.widefat tr td img').hover(function() {
		var url = ($(this).attr('src')).split('src=')[1],
			ttPath = stVars.timthumbURL+'?src='+url+'&w=150&h=100&q=100',
			t = $(this).position().top-($imgCont.outerHeight(true)-$(this).outerHeight(true))/2,
			l = $(this).position().left+$(this).outerWidth(true)+10;

		$imgCont.css({'top':t+'px', 'left':l+'px', display:'block'});
		
		$('<img />').load(function() { $imgCont.css('backgroundImage', 'url('+ttPath+')'); }).attr('src', ttPath);
	}, function() { $imgCont.css('backgroundImage', 'none').hide(); });
	
})(jQuery);
</script>