<div id="tab_editor_<?php echo $count; ?>" class="postbox tab">
	
    <h3 class="hndle">
    	<span><?php echo empty($tab['clean_title']) ? "Tab " . $tab['tab_order'] : $tab['clean_title']; ?></span>
        <a href="#delete-tab" class="tab-delete">Delete Tab</a>
    </h3>
	
	<div class="inside">
    
		<?php if (isset($tab['id']) && !empty($tab['id'])) : ?>
			<input type="hidden" name="tab[<?php echo $count; ?>][id]" value="<?php echo $tab['id']; ?>" />
		<?php endif; ?>
        
		<input type="hidden" name="tab[<?php echo $count; ?>][tab_type]" value="slidetabs_content" />
        <input type="hidden" name="tab[<?php echo $count; ?>][tab_order]" value="<?php echo $tab['tab_order']; ?>" class="tab_order" />
        
		<ul class="form_rows">
            <li class="first_row">
				<label>Tab Title</label>
				<input type="text" name="tab[<?php echo $count; ?>][title]" value="<?php echo empty($tab['title']) ? 'Tab ' . $count : htmlspecialchars(stripslashes($tab['title'])); ?>" size="55" maxlength="500" class="tab_title" />
			</li>
            <li class="first_row">
            	<div class="ext_link_box">
                    <?php $ext_link = empty($tab['ext_link']) ? 'tab-' . $count : $tab['ext_link']; ?>
                    <strong>Slug:</strong>
                    <span class="ext_link_key"><?php echo $ext_link ?></span>
                    <span style="margin-left:5px;"><a class="button ext_link_edit">Change</a></span>
                	<input type="hidden" name="tab[<?php echo $count; ?>][ext_link]" value="<?php echo $ext_link ?>" class="ext_link_input" />
                    <span><a class="slug_info"> (?)</a></span>
                </div>
            </li>                   
			<li class="editor-area clear">
				<?php $editor_id = "tab_{$count}_content"; ?>
                
                <?php if ($slidetabs_class->use_old_tinymce_editor): ?>
                <span class="tab_media">
				    <?php include('edit_form-media_buttons.php'); ?>
                </span>
				
				<div class="editor-container">
                    <textarea name="tab[<?php echo $count; ?>][content]" cols="80" rows="10" class="tab_content" id="<?php echo $editor_id; ?>"><?php echo htmlspecialchars($slidetabs_class->process_tab_content($tab['content'], false, '', true), ENT_QUOTES); ?></textarea>
				</div>
                <?php else: ?>
                    <?php
                        wp_editor($slidetabs_class->process_tab_content($tab['content'], false, '', true, $slidetabs['new_format']), $editor_id, array(
                            'wpautop'       => true,
                            'media_buttons' => true,
                            'textarea_name' => "tab[{$count}][content]", // double quotes only
                            'textarea_rows' => 10,
                            'teeny'         => false,
                            'dfw'           => false,
                            'tinymce'       => true,
                            'quicktags'     => true
                        ));
                    ?>
				<?php endif; ?>
			</li>
            <li class="content_background">
            	<input type="hidden" name="tab[<?php echo $count; ?>][bg_url]" value="<?php echo empty($tab['bg_url']) ? '' : $tab['bg_url']; ?>" />
                <ul>
                    <?php if (empty($tab['bg_url'])) : ?>
                    <li class="bg_thumb bg_not_set"></li>
                    <li class="bg_url"><strong>(background image URL)</strong><span> - </span><a class="edit-post-status bg_edit_url" href="#edit_url">Edit</a></li>
                    <?php else : ?>
                    <li class="bg_thumb"><img src="<?php echo $slidetabs_class->url('/includes/timthumb/timthumb.php') . '?src=' . $tab['bg_url'] . '&w=32&h=32&q=100'; ?>" /></li>
                    <li class="bg_url"><strong><?php echo $tab['bg_url']; ?></strong><span> - </span><a class="edit-post-status bg_edit_url" href="#edit_url">Edit</a></li>
                    <?php endif; ?>
                    <li><a href="<?php echo admin_url('admin-ajax.php'); ?>?action=slidetabs_add_background&tab_editor=tab_editor_<?php echo $count; ?>" title="Add Background Image" class="thickbox button slidetabs_content_background">Add Background Image</a></li>
                </ul>
            </li>                                    
		</ul>
        
	</div>
    
</div> <!-- /#tab_editor-->