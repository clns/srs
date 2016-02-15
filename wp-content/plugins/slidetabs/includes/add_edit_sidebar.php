		    <div  id="slidetabs_options" class="inner-sidebar">
                
                <div id="major-publishing-actions" class="postbox">                               
                    <div title="Click to toggle" class="handlediv">&nbsp;</div>                    
                    <h3 class="hndle">Actions</h3>
                    
                    <div class="inside">
                    	<?php if ($form_action != 'create'): ?>
                        <div id="slidetabs_preview" class="show">
                        	<div class="ajax-masker"></div>
                            <a href="<?php echo admin_url('admin-ajax.php'); ?>?action=slidetabs_preview&slidetabs_id=<?php echo $slidetabs['id']; ?>" title="<?php echo !empty($slidetabs['title']) ? $slidetabs['title'] : 'New SlideTabs'; ?>" class="slidetabs_preview button">Preview</a>
                        </div>
                            
                        <div style="float:left; width:100%;">
                            <div id="delete-action" class="submitbox">
                            	<a href="<?php echo wp_nonce_url($slidetabs_class->action() . '&action=delete&id=' . $slidetabs['id'] . '&message=3', 'slidetabs_delete_' . $slidetabs['id']); ?>" class="submitdelete deletion" id="slidetabs_delete">Delete Tabs</a>
                            </div>
                        <?php else : ?>
                        <div style="float:left; width:100%;">
                            <div id="slidetabs_preview">
                            	<a href="javascript:void(null);" title="Save the tabs to enable preview" class="button disabled">Preview</a>
                            </div>
                        <?php endif; ?>
                            <div id="slidetabs_publishing">
                                <input type="submit" class="button-primary" value="<?php echo $form_action == 'create' ? 'Save' : 'Update'; ?>" />
                            </div>
                        </div>
                	</div>
                </div> <!-- /#major-publishing-actions -->
                
                <div id="general_options" class="postbox">             					
                    <div title="Click to toggle" class="handlediv">&nbsp;</div>                    
                    <h3 class="hndle">General</h3>

                    <ul class="inside">
                        <li style="margin-bottom:8px;">
                            <label><span class="wide" title="Set the total dimension of the tabs main container.">Dimensions <em class="label_em">(width x height)</em></span></label>
                            <input type="text" name="slidetabs_options[totalWidth]" value="<?php echo $slidetabs_class->get_option($slidetabs, 'totalWidth'); ?>" class="dimensions" />&nbsp;by&nbsp;<input type="text" name="slidetabs_options[totalHeight]" value="<?php echo $slidetabs_class->get_option($slidetabs, 'totalHeight'); ?>" class="dimensions" /> px
                        </li>                        
						<?php  if ($slidetabs['dynamic'] !== '1'): ?>
                        <li class="select_spacing">
                            <label><span class="wide" title="Select the tab to display as active when the page loads.">Display as Active</span>
                                <select name="slidetabs_options[tabActive]" class="select-wide" id="active_tab">
									<?php $count = 1; foreach((array) $tabs as $tab): ?>
                                        <option id="option_for_tab_editor_<?php echo $count; ?>" value="<?php echo $tab['tab_order']; ?>"<?php echo $slidetabs_class->get_option($slidetabs, 'tabActive') == $tab['tab_order'] ? 'class="selected" selected="selected"' : ''; ?>><?php echo empty($tab['clean_title']) ? "Tab " . $tab['tab_order'] : $tab['clean_title']; ?></option>
                                    <?php $count++; endforeach; ?>
                                </select>
                            </label>
                   		</li>
                        <li class="select_spacing">
                            <label style="float:left;"><span class="wide" title="Select a template for the tabs.">Templates</span></label>
                            <label style="float:right;"><input type="checkbox" name="slidetabs_options[preloadTemplate]" value="true"<?php echo $slidetabs_class->get_option($slidetabs, 'preloadTemplate') == 'true' ? ' checked="checked"' : ''; ?> /> <span title="Always include the selected template on this site.">Preload</span></label>
                            <select name="template" class="select-wide" id="slidetabs_template">
                                <?php foreach ((array) $templates as $template): ?>
                                    <option value="<?php echo $template['slug']; ?>"<?php echo ($slidetabs['template'] == $template['slug']) ? ' selected="selected"' : ''; ?>><?php echo $template['meta']['Template Name']; ?></option>
                                <?php endforeach; ?>
                            </select>
                   		</li>
                        <?php endif; ?>
                        <li style="margin-top:13px;"><label><input type="checkbox" name="slidetabs_options[useWebKit]" value="true"<?php echo $slidetabs_class->get_option($slidetabs, 'useWebKit') == 'true' ? ' checked="checked"' : ''; ?> /> <span title="Use hardware accelerated CSS3 transitions if supported. This will improve the sliding animations on touch devices.">CSS3 Transitions</span></label></li>
                        <li><label><input type="checkbox" name="slidetabs_options[responsive]" value="true"<?php echo $slidetabs_class->get_option($slidetabs, 'responsive') == 'true' ? ' checked="checked"' : ''; ?> /> <span title="Enable if your website has a responsive layout.">Responsive</span></label></li>
                        <li><label><input type="checkbox" name="slidetabs_options[touchSupport]" value="true"<?php echo $slidetabs_class->get_option($slidetabs, 'touchSupport') == 'true' ? ' checked="checked"' : ''; ?> /> <span title="Enable 'swiping' support for touchscreen devices.">Touchscreen Support</span></label></li>
                    </ul>
                   
                </div> <!-- /#general_options -->
                
                <?php  if ($slidetabs['dynamic'] !== '1'): ?>
                <div id="ajax_options" class="postbox closed">             					
                    <div title="Click to toggle" class="handlediv">&nbsp;</div>                    
                    <h3 class="hndle">AJAX</h3>
                    <ul class="inside">
                        <li><label><input type="checkbox" name="slidetabs_options[ajaxCache]" value="true"<?php echo $slidetabs_class->get_option($slidetabs, 'ajaxCache') == 'true' ? ' checked="checked"' : ''; ?>> <span title="Enable caching if you don't want to re-load the content each time the tab is clicked.">Cache Content</span></label></li>
                        <li><label><input type="checkbox" name="slidetabs_options[ajaxSpinner]" value="true"<?php echo $slidetabs_class->get_option($slidetabs, 'ajaxSpinner') == 'true' ? ' checked="checked"' : ''; ?>> <span title="A &lt;span&gt; element with the class 'st_spinner' will be added to the main container when the content is loading.">Display Preloader</span></label></li>
                        <li class="ajax_error">
                            <label><span class="wide" title="The error message displayed if the AJAX content fails to load.">Error Message:</span></label>
                            <input type="text" class="ajax_error_message" value="<?php echo $slidetabs_class->get_option($slidetabs, 'ajaxError'); ?>" name="slidetabs_options[ajaxError]">
                        </li>
                    </ul>
                   
                </div> <!-- /#ajax_options -->
                <?php endif; ?>
                
                <div id="autoplay_options" class="postbox closed">             					
                    <div title="Click to toggle" class="handlediv">&nbsp;</div>                    
                    <h3 class="hndle">Autoplay</h3>
                    <ul class="inside">
                        <li class="double_input">
                            <label><input type="checkbox" name="slidetabs_options[autoplay]" value="true"<?php echo $slidetabs_class->get_option($slidetabs, 'autoplay') == 'true' ? ' checked="checked"' : ''; ?>> <span title="Enable autoplay and set the time between each transition.">Autoplay with</span></label>
                            <label><input type="text" name="slidetabs_options[autoplayInterval]" value="<?php echo intval($slidetabs_class->get_option($slidetabs, 'autoplayInterval')) / 1000; ?>" class="short_text" size="1" /> Second Intervals</label>
                        </li>
                        <li><label><input type="checkbox" name="slidetabs_options[autoplayClickStop]" value="true"<?php echo $slidetabs_class->get_option($slidetabs, 'autoplayClickStop') == 'true' ? ' checked="checked"' : ''; ?>> <span title="Stop autoplay when a tab or external tab-link is clicked.">Stop Autoplay on Tab Click</span></label></li>
                    </ul>
                   
                </div> <!-- /#autoplay_options -->
                
                <div id="tab_options" class="postbox closed">
                    <div title="Click to toggle" class="handlediv">&nbsp;</div>                    
                    <h3 class="hndle">Tabs</h3>
					                                        
                    <ul class="inside">
                        <li>
                            <label>
                                <span title="Select the tabs alignment.">Alignment:</span>
                                <select name="slidetabs_options[tabsAlignment]">
                                    <?php $alignments = array('Top' => 'align_top', 'Left' => 'align_left', 'Bottom' => 'align_bottom', 'Right' => 'align_right'); ?>
                                    <?php foreach ((array) $alignments as $key => $value): ?>
                                        <option value="<?php echo $value; ?>"<?php echo $slidetabs_class->get_option($slidetabs, 'tabsAlignment') == $value ? ' selected="selected"' : '' ;?>><?php echo $key; ?></option>
                                    <?php endforeach; ?>
                                </select>
                            </label>
                        </li>
                        <li>
                        	<label>
                                <span title="Enter the amount of tabs to slide into view at once.">Amount to Slide:</span>
                                <input type="text" name="slidetabs_options[tabsToSlide]" value="<?php echo intval($slidetabs_class->get_option($slidetabs, 'tabsToSlide')); ?>" size="1" /> tab(s)
                            </label>
                        </li>
                        <li>
                        	<label>
                            	<span title="Set the tabs animation speed. (1 sec = 1000 ms)">Animation Speed:</span>
                                <input type="text" name="slidetabs_options[tabsAnimSpeed]" value="<?php echo intval($slidetabs_class->get_option($slidetabs, 'tabsAnimSpeed')); ?>" size="1" /> ms
                            </label>
                        </li>
                        <li>    
                            <label><span title="Select the functionality of the directional buttons.">Directional Buttons:</span>
                                <select name="slidetabs_options[buttonsFunction]">
                                    <?php $functions = array('Click Tabs' => 'click', 'Slide Tabs' => 'slide'); ?>
                                    <?php foreach ((array) $functions as $key => $value): ?>
                                        <option value="<?php echo $value; ?>"<?php echo $slidetabs_class->get_option($slidetabs, 'buttonsFunction') == $value ? ' selected="selected"' : '' ;?>><?php echo $key; ?></option>
                                    <?php endforeach; ?>
                                </select>
                            </label>
                        </li>
                        <li>
                            <label><span title="Select a easing effect for the tabs sliding animation.">Easing Effect:</span>
                                <select name="slidetabs_options[tabsEasing]">
                                    <?php $effects = array('(none)', 'jswing', 'easeInQuad', 'easeOutQuad', 'easeInOutQuad', 'easeInCubic', 'easeOutCubic', 'easeInOutCubic', 'easeInQuart', 'easeOutQuart', 'easeInOutQuart', 'easeInQuint', 'easeOutQuint', 'easeInOutQuint', 'easeInSine', 'easeOutSine', 'easeInOutSine', 'easeInExpo', 'easeOutExpo', 'easeInOutExpo', 'easeInCirc', 'easeOutCirc', 'easeInOutCirc', 'easeInElastic', 'easeOutElastic', 'easeInOutElastic', 'easeInBack', 'easeOutBack', 'easeInOutBack', 'easeInBounce', 'easeOutBounce', 'easeInOutBounce'); ?>
                                    <?php foreach ((array) $effects as $effect): ?>
                                        <option value="<?php echo ($effect !== '(none)') ? $effect : ''; ?>"<?php echo $slidetabs_class->get_option($slidetabs, 'tabsEasing') == $effect ? ' selected="selected"' : '' ;?>><?php echo $effect; ?></option>
                                    <?php endforeach; ?>
                                </select>
                            </label>
                        </li>
                        <li><label><span title="Slide back to start at the end of each sliding direction.">Loop Navigation: </span><input type="checkbox" name="slidetabs_options[tabsLoop]" value="true"<?php echo $slidetabs_class->get_option($slidetabs, 'tabsLoop') == 'true' ? ' checked="checked"' : ''; ?> class="tabs_cb" /></label></li>
                        <li><label><span title="Save the active tab with a cookie, and set it active again on page reload.">Save Active Tab: </span><input type="checkbox" name="slidetabs_options[tabsSaveState]" value="true"<?php echo $slidetabs_class->get_option($slidetabs, 'tabsSaveState') == 'true' ? ' checked="checked"' : ''; ?> class="tabs_cb" /></label></li>
                        <li><label><span title="Navigate the tabs with the mouse scroll wheel.">Scroll Navigation: </span><input type="checkbox" name="slidetabs_options[tabsScroll]" value="true"<?php echo $slidetabs_class->get_option($slidetabs, 'tabsScroll') == 'true' ? ' checked="checked"' : ''; ?> class="tabs_cb" /></label></li>
                        <!--<li><label><span title="Offset the top or left sliding direction. Enter a positive or negative number.">Offset Top/Left:</span><input type="text" name="slidetabs_options[OffsetTL]" value="<?php //echo intval($slidetabs_class->get_option($slidetabs, 'OffsetTL')); ?>" size="1" /> pixels</label></li>
                        <li><label><span title="Offset the bottom or right sliding direction. Enter a positive or negative number.">Offset Bottom/Right:</span><input type="text" name="slidetabs_options[offsetBR]" value="<?php //echo intval($slidetabs_class->get_option($slidetabs, 'offsetBR')); ?>" size="1" /> pixels</label></li>-->
                	</ul>
                </div> <!-- /#tab_options -->
                
                <div id="content_options" class="postbox closed">
                    <div title="Click to toggle" class="handlediv">&nbsp;</div>                    
                    <h3 class="hndle">Content</h3>
					                                        
                    <ul class="inside">
                        <li>
                        	<label>
                            	<span title="Set the content animation speed. (1 sec = 1000 ms)">Animation Speed:</span>
                                <input type="text" name="slidetabs_options[contentAnimSpeed]" value="<?php echo intval($slidetabs_class->get_option($slidetabs, 'contentAnimSpeed')); ?>" size="1" /> ms
                            </label>
                        </li>
                        <li>
                            <label><span title="Select the content animation.">Animation Type:</span>
                                <select name="slidetabs_options[contentAnim]">
                                    <?php $animations = array('(none)' => '', 'Fade' => 'fade', 'Fade-Out-In' => 'fadeOutIn', 'Horizontal Slide' => 'slideH', 'Vertical Slide' => 'slideV'); ?>
                                    <?php foreach ((array) $animations as $key => $value): ?>
                                        <option value="<?php echo $value; ?>"<?php echo $slidetabs_class->get_option($slidetabs, 'contentAnim') == $value ? ' selected="selected"' : '' ;?>><?php echo $key; ?></option>
                                    <?php endforeach; ?>
                                </select>
                            </label>
                        </li>
                        <li>
                            <label>
                                <span title="Automatically adjust the content's height.">Auto Height:</span>
                                <select name="slidetabs_options[autoHeight]">
                                    <?php $autoheight = array('Enabled' => 'true', 'Disabled' => 'false'); ?>
                                    <?php foreach ((array) $autoheight as $key => $value): ?>
                                        <option value="<?php echo $value; ?>"<?php echo $slidetabs_class->get_option($slidetabs, 'autoHeight') == $value ? ' selected="selected"' : '' ;?>><?php echo $key; ?></option>
                                    <?php endforeach; ?>
                                </select>
                            </label>
                        </li>
                        <li>
                            <label>
                                <span title="Animate the height transition. (1 sec = 1000 ms)">Auto Height Speed:</span>
                                <input type="text" name="slidetabs_options[autoHeightSpeed]" value="<?php echo $slidetabs_class->get_option($slidetabs, 'autoHeightSpeed'); ?>" size="1" /> ms
                            </label>
                        </li>
                        <li>
                            <label><span title="Text formatting for the content. Changes double line-breaks into HTML paragraphs and single line-breaks into HTML &lt;br&gt; tags (depending on the selected option).">Change Line Breaks to:</span>
                                <select name="slidetabs_options[textConversion]">
                                    <?php $formatting = array('(disable)' => 'false', '&lt;p&gt;' => 'p', '&lt;br&gt;' => 'br', '&lt;p&gt; and &lt;br&gt;' => 'pb'); ?>
                                    <?php foreach ((array) $formatting as $key => $value): ?>
                                        <option value="<?php echo $value; ?>"<?php echo $slidetabs_class->get_option($slidetabs, 'textConversion') == $value ? ' selected="selected"' : '' ;?>><?php echo $key; ?></option>
                                    <?php endforeach; ?>
                                </select>
                            </label>
                        </li>
                        <li>
                            <label><span title="Select the easing effect for the content's sliding animations.">Easing Effect:</span>
                                <select name="slidetabs_options[contentEasing]">
                                    <?php foreach ((array) $effects as $effect): ?>
                                        <option value="<?php echo ($effect !== '(none)') ? $effect : ''; ?>"<?php echo $slidetabs_class->get_option($slidetabs, 'contentEasing') == $effect ? ' selected="selected"' : '' ;?>><?php echo $effect; ?></option>
                                    <?php endforeach; ?>
                                </select>
                            </label>
                        </li>
                	</ul>
                </div> <!-- /#content_options -->
                
                <div id="external_linking_options" class="postbox closed">             					
                    <div title="Click to toggle" class="handlediv">&nbsp;</div>                    
                    <h3 class="hndle">External Links</h3>
                    <ul class="inside">
                    	<li><label><input type="checkbox" name="slidetabs_options[externalLinking]" value="true"<?php echo $slidetabs_class->get_option($slidetabs, 'externalLinking') == 'true' ? ' checked="checked"' : ''; ?> class="tabs_cb" /><span title="Allow linking to tabs from external anchor tags."> Allow External Links</span></label></li>
                        <li><label><input type="checkbox" name="slidetabs_options[urlLinking]" value="true"<?php echo $slidetabs_class->get_option($slidetabs, 'urlLinking') == 'true' ? ' checked="checked"' : ''; ?> class="tabs_cb" /><span title="Allow linking to tabs from external pages via the URL."> Allow URL Links</span></label></li>
                    </ul>
                   
                </div> <!-- /#external_linking_options -->
                
				<?php  if ($slidetabs['dynamic'] !== '1'): ?>
				<div id="tabs_order" class="postbox">					
                    <div title="Click to toggle" class="handlediv">&nbsp;</div>
                    <h3 class="hndle">Rearrange Tabs</h3>
					
                    <div class="inside">
						<ul class="ui-sortable tab_order">
							<?php $count = 1; ?>
							<?php foreach ((array) $tabs as $tab): ?>
								<li><a href="#tab_editor_<?php echo $count; ?>" class="hndle" id="hndle_for_tab_editor_<?php echo $count; ?>"><?php echo empty($tab['clean_title']) ? "Tab " . $count : $tab['clean_title']; ?></a></li>
								<?php $count++; ?>
							<?php endforeach; ?>
						</ul>
                        <p><!--Drag the list elements to change the tabs order.-->Note: Click <em><?php echo $form_action == 'create' ? 'Save' : 'Update'; ?></em> to re-order the tab panels</p>
					</div>				
                </div> <!-- /#tabs_order -->
                <?php endif; ?>
                                                
			</div> <!-- /#slidetabs_options -->