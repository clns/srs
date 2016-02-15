<div class="wrap" id="dynamic_slidetabs">
	<div id="icon-edit" class="icon32"></div><h2><?php echo $form_action == 'create'  ? 'Add Dynamic SlideTabs' : 'Edit Dynamic SlideTabs'; ?></h2>
    
    <?php echo $slidetabs_class->display_message(); ?>
    
	<form action="" method="post" id="dynamic_slidetabs_form">
	    <?php function_exists('wp_nonce_field') ? wp_nonce_field('slidetabs-for-wordpress', 'slidetabs-' . $form_action . '_wpnonce') : ''; ?>
		<input type="hidden" name="action" value="<?php echo $form_action; ?>" id="form_action" />
		<input type="hidden" name="tabs_post_id" value="<?php echo $slidetabs['tabs_post_id']; ?>" id="slidetabs_post_id" />
		<input type="hidden" name="dynamic" value="1" />
        <input type="hidden" name="template" value="default" />
		<?php if (isset($slidetabs['id']) && !empty($slidetabs['id'])): ?>
			<input type="hidden" name="id" value="<?php echo $slidetabs['id']; ?>" id="slidetabs_id" />
		<?php endif; ?>
        
		<div class="metabox-holder has-right-sidebar">
			<?php include($slidetabs_class->dir('/includes/add_edit_sidebar.php')); ?>
			
			<div class="editor-wrapper">
				<div class="editor-body">
					<div id="titlediv">
						<div id="titlewrap">
							<input type="text" name="title" size="40" maxlength="255" value="<?php echo !empty($slidetabs['title']) ? $slidetabs['title'] : 'Featured Posts'; ?>" id="title" />
						</div>
					</div>
				
					<table class="form-table">
						<tbody>
							<tr valign="top">
								<th scope="row" class="first_row">Template and Layout</th>
								<td>
									<input type="hidden" name="template" value="<?php echo $slidetabs['template']; ?>" id="slidetabs_dynamic_template" />
									<div class="dynamic_templates">
									<?php foreach((array) $templates as $template): ?>
                                        <a href="#<?php echo $template['slug']; ?>" class="template_thumb<?php echo ($template['slug'] == $slidetabs['template']) ? ' active' : ''; ?>">
                                            <img src="<?php echo $template['thumbnail']; ?>" />
                                            <span><?php echo $template['meta']['Template Name']; ?></span>
										</a>
									<?php endforeach; ?>
									</div>
								</td>
							</tr>
                            <tr valign="top">
								<th scope="row" class="first_row">Content to Display</th>
								<td>
                                    <label><span>Display:</span>
                                        <select name="dynamic_options[type]">
                                            <option value="all"<?php echo $slidetabs['dynamic_options']['type'] == 'all' ? ' selected="selected"' : ''; ?>>All</option>
                                            <option value="featured"<?php echo $slidetabs['dynamic_options']['type'] == 'featured' ? ' selected="checked"' : ''; ?>>Featured</option>
                                        </select>
                                    </label>
                                    
                                    <label><span>Post Type:</span>
                                        <select name="dynamic_options[post_type]">
                                            <?php foreach ($post_types as $post_type): ?>
                                            <option value="<?php echo $post_type['value']; ?>"<?php echo $slidetabs['dynamic_options']['post_type'] == $post_type['value'] ? ' selected="selected"' : ''; ?>><?php echo $post_type['label']; ?></option>
                                            <?php endforeach; ?>
                                        </select>
                                    </label>
                                    
                                    <label><span>Order By:</span>
                                        <select name="dynamic_options[orderby]">
                                            <?php foreach ($orderby as $label => $value): ?>
                                            <option value="<?php echo $value; ?>"<?php echo $slidetabs['dynamic_options']['orderby'] == $value ? ' selected="selected"' : ''; ?>><?php echo $label; ?></option>
                                            <?php endforeach; ?>
                                        </select>
                                    </label>
                                    
                                    <label><span>Order Direction:</span>
                                        <select name="dynamic_options[order]">
                                            <?php foreach ($order as $label => $value): ?>
                                            <option value="<?php echo $value; ?>"<?php echo $slidetabs['dynamic_options']['order'] == $value ? ' selected="selected"' : ''; ?>><?php echo $label; ?></option>
                                            <?php endforeach; ?>
                                        </select>
                                    </label>
                                    
                                    <label><span>Limit:</span>
                                    	<input type="text" size="1" value="<?php echo $slidetabs_class->get_dynamic_option($slidetabs, 'total'); ?>" name="dynamic_options[total]" /> Entries
                                    </label>
                                    
                                    <div id="content_filters" style="clear:both;">
                                        <label><span>Filter by Content:</span>
                                    	<input type="checkbox" value="1" name="dynamic_options[image_posts_only]" <?php echo $slidetabs['dynamic_options']['image_posts_only'] == '1' ? ' checked="checked"' : ''; ?> /> Only display posts including an image
                                    </label>
                                        
                                        <label><span>Filter by Category:</span>
                                        	<input type="checkbox" value="1" name="dynamic_options[filter_by_category]" id="slidetabs_filter_by_category"<?php echo $slidetabs['dynamic_options']['filter_by_category'] == '1' ? ' checked="checked"' : ''; ?> /> Enable/show post categories
                                        </label>
                                        <div id="category_filter_categories"<?php echo $slidetabs['dynamic_options']['filter_by_category'] != 1 ? ' style="display:none;"' : ''; ?>>
                                        <?php foreach((array) $categories as $category): ?>
                                            <label><input type="checkbox" name="dynamic_options[filter_categories][]" value="<?php echo $category->cat_ID; ?>"<?php echo in_array($category->cat_ID, (array) $slidetabs['dynamic_options']['filter_categories']) ? ' checked="checked"' : ''; ?> /> <?php echo $category->name; ?></label>
                                        <?php endforeach; ?>
                                        </div>
                                    </div>
								</td>
							</tr>
							<tr valign="top">
								<th scope="row" class="first_row">Tab Content</th>
								<td>
                                    <label style="float:none;"><input type="radio" name="dynamic_options[tab_content_type]" value="titles"<?php echo $slidetabs['dynamic_options']['tab_content_type'] == 'titles' ? 'checked="checked"' : ''; ?> /> Titles</label>
                                    
                                    <label style="display:inline-block; float:none;"><input type="radio" name="dynamic_options[tab_content_type]" value="dates"<?php echo $slidetabs['dynamic_options']['tab_content_type'] == 'dates' ? 'checked="checked"' : ''; ?> /> Dates in Format:&nbsp;</label>
                                    <input type="text" size="4" value="<?php echo $slidetabs_class->get_dynamic_option($slidetabs, 'date_format'); ?>" name="dynamic_options[date_format]" id="slidetabs_date_format_custom" />
                                    <span class="example"><?php echo date_i18n($slidetabs['dynamic_options']['date_format'], time()); ?></span>
                                    <span id="slidetabs_date_format_loading">&nbsp;</span>
                                    <p style="margin-left:115px;"><a href="http://codex.wordpress.org/Formatting_Date_and_Time">Documentation on date and time formatting</a></p>
								</td>
							</tr>
                            <tr valign="top">
								<th scope="row" class="first_row">Title Settings</th>
								<td>
                                    <label for="slidetabs_title_length"><span>Length:</span>
                                        <input type="text" size="4" value="<?php echo $slidetabs_class->get_dynamic_option($slidetabs, 'title_length'); ?>" name="dynamic_options[title_length]" /> Characters
                                    </label>
                                    
                                    <label for="slidetabs_title_with_image_length"><span>Length with Image:</span>
                                        <input type="text" size="4" value="<?php echo $slidetabs_class->get_dynamic_option($slidetabs, 'title_length_with_image'); ?>" name="dynamic_options[title_length_with_image]" /> Characters
                                    </label>
								</td>
							</tr>
                            <tr valign="top">
								<th scope="row" class="first_row">Excerpt Settings</th>
								<td>
                                    <label for="slidetabs_excerpt_length"><span>Length:</span>
                                        <input type="text" size="4" value="<?php echo $slidetabs_class->get_dynamic_option($slidetabs, 'excerpt_length'); ?>" name="dynamic_options[excerpt_length]" id="slidetabs_excerpt_length" /> Words
                                    </label>
                                    
                                    <label for="slidetabs_excerpt_with_image_length"><span>Length with Image:</span>
                                        <input type="text" size="4" value="<?php echo $slidetabs_class->get_dynamic_option($slidetabs, 'excerpt_length_with_image'); ?>" name="dynamic_options[excerpt_length_with_image]" id="slidetabs_excerpt_with_image_length" /> Words
                                    </label>
								</td>
							</tr>
                            <tr valign="top">
								<th scope="row" class="first_row last">Image Settings</th>
								<td class="last">
                                    <label for="slidetabs_image_source"><span>Display:</span>
                                        <select name="dynamic_options[image_source]" id="slidetabs_image_source">
                                            <option id="image_option_content" value="content"<?php echo $slidetabs_class->get_dynamic_option($slidetabs, 'image_source') == 'content' ? ' selected="selected"' : ''; ?>>First Image in Content</option>
                                            <?php if (function_exists('current_theme_supports')) { ?>
                                                <?php if (current_theme_supports('post-thumbnails')) { ?>
                                                    <option id="image_option_thumbnail" value="featured"<?php echo $slidetabs['dynamic_options']['image_source'] == 'featured' ? ' selected="selected"' : ''; ?>>Featured Image</option>
                                                <?php } ?>
                                            <?php } ?>
                                            <option id="image_option_none" value="none"<?php echo $slidetabs_class->get_dynamic_option($slidetabs, 'image_source') == 'none' ? ' selected="selected"' : ''; ?>>No Image</option>
                                        </select>
                                    </label>
                                    
                                    <label for="slidetabs_max_image_width"><span>Max Width:</span>
                                        <input type="text" size="4" value="<?php echo $slidetabs_class->get_dynamic_option($slidetabs, 'max_image_width'); ?>" name="dynamic_options[max_image_width]" /> Pixels
                                    </label>
                                    
                                    <label for="slidetabs_max_image_height"><span>Max Height:</span>
                                        <input type="text" size="4" value="<?php echo $slidetabs_class->get_dynamic_option($slidetabs, 'max_image_height'); ?>" name="dynamic_options[max_image_height]" /> Pixels
                                    </label>
                                    
                                    <span class="note">(Original aspect ratio is maintained)</span>
								</td>
							</tr>
						</tbody>
					</table>
				</div>
			</div>
		</div>
	
    </form>
    
</div> <!-- /#dynamic_sliding_tabs -->