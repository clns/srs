<div id="slidetabs_dynamic_meta">
        
	<?php function_exists('wp_nonce_field') ? wp_nonce_field('slidetabs-for-wordpress', 'slidetabs-for-wordpress-dynamic-meta_wpnonce') : ''; ?>
    <p>
    	<label for="slidetabs_tab_title">Tab Title:</label>
    	<input type="text" name="_slidetabs_tab_title" value="<?php echo $slidetabs_post_meta['_slidetabs_tab_title']; ?>" size="33" maxlength="40" id="slidetabs_slide_title" class="form-input-tip" />
    </p>
    <p class="last"><label><input type="checkbox" name="_slidetabs_post_featured" value="1"<?php echo (boolean) $slidetabs_post_meta['_slidetabs_post_featured'] == true ? ' checked="checked"' : ''; ?> /> This is a SlideTabs featured <?php echo $metabox['args']['view']; ?></label></p>
    
</div> <!-- /#slidetabs_meta_box -->