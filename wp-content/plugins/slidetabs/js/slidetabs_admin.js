/*
 * SlideTabs for WordPress - Admin Pages
 */
  
var tb_pathToImage = stVars.pluginURL+'/images/thickbox/loadingAnimation.gif',
	tb_closeImage = stVars.pluginURL+'/images/thickbox/tb-close.png';	
	
var stAdmin = {
	processing: false,
	namespace: 'slidetabs',
	
	slugTT: function(a) {
		a = jQuery(a);
		stAdmin.slugTip.css({'display': 'block', 'top': (a.offset().top - 46) + 'px', 'left': (a.offset().left - 8) + 'px'});
	},
	
	// Update the tab titles
	updateTitle: function($titleInput) {
		if (this.timer) { clearTimeout(this.timer); }
						
		var title = $titleInput.value.replace(/(<.*?>)/ig,'');
		
		this.timer = setTimeout(function() {
			jQuery('#hndle_for_' + jQuery($titleInput).parents('.tab')[0].id).text(title); 
			jQuery($titleInput).parents('.tab').find('h3.hndle span').text(title);
			stAdmin.$orderSelect.children('option[value="' + (jQuery($titleInput).parents('.tab').find('input.tab_order')[0].value) + '"]')[0].text = title;
		}, 150);
		
		return true;
	},
	
	//slugCount: (parseInt(stAdmin.$tabPanels.children('.tab').length) + 1),
			
	setUniqueSlug: function(slug) {
		this.$slugs.each(function() {
			if(jQuery(this).text() == slug) {
				stAdmin.slugCount++;
				stAdmin.slug = 'tab-' + stAdmin.slugCount;
				stAdmin.title = 'Tab ' + stAdmin.slugCount;
				stAdmin.setUniqueSlug(stAdmin.slug); // run the check again to see if the new name has any duplicates
				return;
			}
		});
	},
	
	//tabCount: parseInt(stAdmin.$tabPanels.children('.tab').length),
	
	setUniqueCount: function(id) {
		this.$panels.each(function() {
			if (jQuery(this).attr('id') == 'tab_editor_' + id) {
				stAdmin.tabCount++;
				stAdmin.setUniqueCount(stAdmin.tabCount); // run the check again to see if the new id has any duplicates
				return;
			}
		});
	},
	
	addTab: function(btn) {
		var self = this;
						
		if (this.processing === false) {			
			this.processing = true;
			
			var url = typeof(ajaxurl) != 'undefined' ? ajaxurl : btn.href.split('?')[0].replace(document.location.protocol + '//' + document.location.hostname, "");
						
			self.$panels = stAdmin.$tabPanels.children('.tab');
			self.$slugs = stAdmin.$tabPanels.find('.ext_link_key');
						
			// default slug and title
			self.slugCount = (parseInt(self.$panels.length) + 1);
			self.tabCount = parseInt(self.$panels.length);
			self.slug = 'tab-' + self.slugCount;
			self.title = 'Tab ' + self.slugCount;
									
			self.setUniqueCount(self.tabCount); // make sure the id is unique
			self.setUniqueSlug(self.slug); // make sure the slug is unique
						
			isAjaxTab = (btn.id == 'add_ajax_tab') ? true : false;
			
			jQuery.ajax({
				url: url,
				type: 'get',
				data: {
					action: 'slidetabs_add_tab',
					ajaxTab: isAjaxTab,
					count: self.tabCount,
					slug: self.slug,
					tabs_post_id: jQuery('#slidetabs_post_id').val(),
					title: self.title
				},
				complete: function(data) {
					var row_id = 'tab_editor_' + self.tabCount,
						editor_id = 'tab_' + self.tabCount + '_content';
					
					stAdmin.$tabPanels.append(data.responseText);
					stAdmin.$tabsOrderUl.append('<li><a href="#' + row_id + '" class="hndle" id="hndle_for_tab_editor_' + self.tabCount + '">' + self.title + '</a></li>');
					stAdmin.$orderSelect.append('<option id="option_for_tab_editor_' + self.tabCount + '" value="' + self.tabCount + '">' + self.title + '</option>');
					
					if (!isAjaxTab) {
						if (stVars.oldEditor == 'true') {
							tinyParams = tinyMCEPreInit.mceInit;
							tinyParams.mode = 'exact';
							tinyParams.elements = editor_id;
						} else {
							var i = 0;
							for (var k in tinyMCEPreInit.mceInit) {
								if (i == 0) tinyParams = tinyMCEPreInit.mceInit[k];
								i++;
							}
							tinyParams.mode = 'exact';
							tinyParams.elements = editor_id;
							
							quicktags({
								id: editor_id,
								buttons: '',
								disabled_buttons: ''
							});
							QTags._buttonsInit();
							jQuery('#wp-'+editor_id+'-wrap').removeClass('html-active').addClass('tmce-active');
						}					
						
						tinyMCE.init(tinyParams);
						
						// make sure wpActiveEditor is set to editor_id when the 'Add Media' or 'Upload/Insert' buttons are clicked so the media is inserted into the correct editor
						jQuery('#wp-' + editor_id + '-media-buttons').children('.add_media').bind('click', function() {
							wpActiveEditor = editor_id;
						});
					}
					
					self.setTabOrderValues();
					
					var width = jQuery(window).width(), 
						H = jQuery(window).height(), 
						W = (720 < width) ? 720 : width,
						href;
					
					jQuery('#'+row_id).find('div.inside a.thickbox').each(function() {
						self.setTBAttr(this, W, H);
					});
					
					self.bindEditorControls(jQuery('#' + 'tab_editor_' + self.tabCount)); // html element textarea.
					self.processing = false;
				}
			});
		}
	},
	
	// Set the tab-order values
	setTabOrderValues: function() {
		stAdmin.$orderSelect.children('option').each(function(i) { this.value = (i + 1); });
				
		// update the tab panel's input.tab_order values in the same order as the sortable list
		stAdmin.$tabsOrderUl.children('li').each(function(i) {
			target = jQuery(this).find('a.hndle').attr('href').split('#')[1];
			jQuery('#'+target).find('input.tab_order').val(i+1); // set the input.tab_order value to the incremented value
		});
	},
	
	// Bind the added tab panel controls
	bindEditorControls: function($tab) {
		$tab.find('.tab_title').unbind('keyup.'+this.namespace).bind('keyup.'+this.namespace, function() { stAdmin.updateTitle(this); });
		$tab.find('.editor-nav a.mode').unbind('click.'+this.namespace).bind('click.'+this.namespace, function(event) { stAdmin.editorNavigation(this); return false; });
		$tab.find('.tab-delete').unbind('click.'+this.namespace).bind('click.'+this.namespace, function(event) { stAdmin.deleteTab(this); return false; });
		$tab.find('h3.hndle, .handlediv').unbind('click.'+this.namespace).bind('click.'+this.namespace, function(event) { stAdmin.toggleBoxes(this); return false; });
		$tab.find('.tab-delete').unbind('click.'+this.namespace).bind('click.'+this.namespace, function(event) { stAdmin.deleteTab(this); return false; });
		$tab.find('.media-buttons').show();
		$tab.find('.media-buttons a.thickbox').unbind('click.'+this.namespace).bind('click.'+this.namespace, function() { stAdmin.tbClick(this); });
		$tab.find('a.ext_link_edit').unbind('click.'+this.namespace).bind('click.'+this.namespace, function(event) { jQuery(this).hide(); stAdmin.editExtLinkSlug(this); return false; });
		$tab.find('a.bg_edit_url').unbind('click.'+this.namespace).bind('click.'+this.namespace, function(event) { jQuery(this).hide(); stAdmin.editBgURL(this); return false; });
		if (stVars.oldMediaManager == 'true') { $tab.find('textarea').each(function() { stAdmin.updateUploadInsertLinks(this); }); }
		
		$tab.find('.slug_info').hover(function() { 
			stAdmin.slugTT(this);
		}, function() {	stAdmin.slugTip.hide(); });
	},
	
	deleteTab: function(e) {
		if (confirm("Are you sure you would like to delete this tab?")) {
			var $tabPanel = jQuery(e).parents('.tab'),
				tabID = $tabPanel.attr('id').split('_')[2],
				$option = stAdmin.$orderSelect.children('#option_for_tab_editor_' + tabID);			
						
			if ($option.hasClass('selected')) {
				$option.remove(); // remove the active tab select option
								
				$option = stAdmin.$orderSelect.children('option').first().addClass('selected'); // set the first option as the active/selected and give it the 'selected' class
				
				var activeVal = $option.val(); // get the active option's value
				stAdmin.$orderSelect.val(activeVal); // use val() to select the option
			} else {
				$option.remove(); // remove the active tab select option
			}

			jQuery('#hndle_for_tab_editor_' + tabID).parents('li').remove(); // remove the sortable list element
			$tabPanel.remove(); // remove the tab panel
			
			this.setTabOrderValues();
		}
	},
	
	// Set the width and height attributes for the thickbox modal window
	setTBAttr: function(a, W, H) {
		href = a.href;
		
		if (!href) { return; }
		href = href.replace(/&width=[0-9]+/g, '');
		href = href.replace(/&height=[0-9]+/g, '');
		a.href = href+'&width='+(W-80)+'&height='+(H-85);
	},
	
	tbClick: function(e){
		if (typeof tinyMCE != 'undefined' && tinyMCE.activeEditor) {
			var url = jQuery(e).attr('href');
			url = url.split('editor=');
			if(url.length>1){
				url = url[1];
				url = url.split('&');
				if(url.length>1){
					editorid = url[0];
				}
			}
			tinyMCE.get(editorid).focus();
			tinyMCE.activeEditor.windowManager.bookmark = tinyMCE.activeEditor.selection.getBookmark('simple');
			jQuery(window).resize();
		}
	},

	// Enable/disable the visual editor
	editorNavigation: function(e){
		var p = jQuery(e).parents('li:eq(0)'),
			navs = p.find('.editor-nav a');
		
		navs.removeClass('active');
		jQuery(e).addClass('active');

		var editor = e.href.split('#')[1],
			textarea = p.find('textarea.tab_content')[0];
		
		switch(editor) {
			case 'visual':
				this.switchEditorNav(textarea.id, 'tinymce');
			break;
			case 'html':
				this.switchEditorNav(textarea.id, 'html');
			break;
		}
	},
	
	switchEditorNav: function(textarea_id, mode) {
		var editor = false;
		
		if (typeof(tinyMCE) != 'undefined') { editor = tinyMCE.get(textarea_id); }
		
		var textarea = jQuery('#' + textarea_id);
		
		switch(mode) {
			case 'tinymce':
				textarea.css('color','#fff').val(switchEditors.wpautop(textarea.val()));
				editor.show();
				tinyMCE.execCommand('mceAddControl', false, textarea_id);
				textarea.css('color','#000');
			break;
			case 'html':
				textarea.css('color','#000');
				editor.hide();
			break;
		}
	},
	
	// Add 'editor' attribute to all upload/insert buttons for WYSIWYG editors
	updateUploadInsertLinks: function(textarea) {
		if (stVars.oldEditor == 'false') {
		    var $textarea = jQuery(textarea),
            	$parent = $textarea.closest('.wp-editor-wrap'),
            	$editor_tools = $parent.find('.wp-editor-tools'),
            	$upload_insert = $editor_tools.find('a.add_media'),
           		href = $upload_insert.attr('href');
            
            if (href.match(/editor\=/)) { href = href.replace(/editor\=([a-zA-Z0-9\-_]+)/, 'editor=' + $textarea.attr('id')); }
			else { href = href.replace('TB_iframe=1', 'editor=' + $textarea.attr('id') + '&TB_iframe=1'); }
            
            $upload_insert.attr('href', href);
        }
    },
	
	$imgCont: null,
	
	ttMouseenter: function(img) {
		var $img = jQuery(img),
			url = ($img.attr('src')).split('src=')[1],
			path = stVars.timthumbURL+'?src='+url+'&w=170&h=110&q=100',
			top = $img.offset().top-(this.$imgCont.outerHeight(true)-$img.outerHeight(true))/2,
			left = $img.offset().left+$img.outerWidth(true)+10;
		
		this.$imgCont.css({'top':top+'px', 'left':left+'px', display:'block'});
		
		jQuery('<img />').load(function() { stAdmin.$imgCont.css('backgroundImage', 'url('+path+')'); }).attr('src', path);
	},
	
	ttMouseleave: function() {
		this.$imgCont.css('backgroundImage', 'none').hide();
	},
	
	stringToSlug: function(text) {	
		var space = '-', chars = [];
	
		text = jQuery.trim(text.toString());		
		for (var i = 0; i < 32; i++) { chars.push (''); }
				
		chars.push(space);chars.push('');chars.push('');chars.push('');chars.push('');chars.push('');chars.push('');chars.push("");chars.push('-');chars.push('-');chars.push('');chars.push('');chars.push('-');chars.push('-');chars.push('-');chars.push('-');chars.push('0');chars.push('1');chars.push('2');chars.push('3');chars.push('4');chars.push('5');chars.push('6');chars.push('7');chars.push('8');chars.push('9');chars.push('');chars.push('');chars.push('');chars.push('');chars.push('');chars.push('');chars.push('');chars.push('A');chars.push('B');chars.push('C');chars.push('D');chars.push('E');chars.push('F');chars.push('G');chars.push('H');chars.push('I');chars.push('J');chars.push('K');chars.push('L');chars.push('M');chars.push('N');chars.push('O');chars.push('P');chars.push('Q');chars.push('R');chars.push('S');chars.push('T');chars.push('U');chars.push('V');chars.push('W');chars.push('X');chars.push('Y');chars.push('Z');chars.push('-');chars.push("-");chars.push('-');chars.push('');chars.push('-');chars.push('');chars.push('a');chars.push('b');chars.push('c');chars.push('d');chars.push('e');chars.push('f');chars.push('g');chars.push('h');chars.push('i');chars.push('j');chars.push('k');chars.push('l');chars.push('m');chars.push('n');chars.push('o');chars.push('p');chars.push('q');chars.push('r');chars.push('s');chars.push('t');chars.push('u');chars.push('v');chars.push('w');chars.push('x');chars.push('y');chars.push('z');chars.push('-');chars.push('');chars.push('-');chars.push('');chars.push('');chars.push('C');chars.push('A');chars.push('');chars.push('f');chars.push('');chars.push('');chars.push('T');chars.push('t');chars.push('');chars.push('');chars.push('S');chars.push('');chars.push('CE');chars.push('A');chars.push('Z');chars.push('A');chars.push('A');chars.push('');chars.push('');chars.push('');chars.push('');chars.push('');chars.push('-');chars.push('-');chars.push('');chars.push('TM');chars.push('s');chars.push('');chars.push('ae');chars.push('A');chars.push('z');chars.push('Y');chars.push('');chars.push('');chars.push('c');chars.push('L');chars.push('o');chars.push('Y');chars.push('');chars.push('S');chars.push('');chars.push('c');chars.push('a');chars.push('');chars.push('');chars.push('');chars.push('r');chars.push('-');chars.push('o');chars.push('');chars.push('2');chars.push('3');chars.push('');chars.push('u');chars.push('p');chars.push('');chars.push('');chars.push('1');chars.push('o');chars.push('');chars.push('');chars.push('');chars.push('');chars.push('');chars.push('A');chars.push('A');chars.push('A');chars.push('A');chars.push('A');chars.push('A');chars.push('AE');chars.push('C');chars.push('E');chars.push('E');chars.push('E');chars.push('E');chars.push('I');chars.push('I');chars.push('I');chars.push('I');chars.push('D');chars.push('N');chars.push('O');chars.push('O');chars.push('O');chars.push('O');chars.push('O');chars.push('x');chars.push('O');chars.push('U');chars.push('U');chars.push('U');chars.push('U');chars.push('Y');chars.push('D');chars.push('B');chars.push('a');chars.push('a');chars.push('a');chars.push('a');chars.push('a');chars.push('a');chars.push('ae');chars.push('c');chars.push('e');chars.push('e');chars.push('e');chars.push('e');chars.push('i');chars.push('i');chars.push('i');chars.push('i');chars.push('o');chars.push('n');chars.push('o');chars.push('o');chars.push('o');chars.push('o');chars.push('o');chars.push('');chars.push('o');chars.push('u');chars.push('u');chars.push('u');chars.push('u');chars.push('y');chars.push('');chars.push('y');chars.push('z');chars.push('');chars.push('');chars.push('');chars.push('');chars.push('');chars.push('');chars.push('');chars.push('');chars.push('');chars.push('');chars.push('');chars.push('C');chars.push('c');chars.push('D');chars.push('d');chars.push('');chars.push('');chars.push('');chars.push('');chars.push('');chars.push('');chars.push('');chars.push('');chars.push('');chars.push('');chars.push('E');chars.push('e');chars.push('');chars.push('');chars.push('');chars.push('');chars.push('');chars.push('');chars.push('');chars.push('');chars.push('');chars.push('');chars.push('');chars.push('');chars.push('');chars.push('');chars.push('');chars.push('');chars.push('');chars.push('');chars.push('');chars.push('');chars.push('');chars.push('');chars.push('');chars.push('');chars.push('');chars.push('');chars.push('');chars.push('');chars.push('');chars.push('');chars.push('');chars.push('');chars.push('');chars.push('');chars.push('');chars.push('');chars.push('');chars.push('');chars.push('');chars.push('');chars.push('');chars.push('');chars.push('');chars.push('N');chars.push('n');chars.push('');chars.push('');chars.push('');chars.push('');chars.push('');chars.push('');chars.push('');chars.push('');chars.push('');chars.push('');chars.push('');chars.push('');chars.push('');chars.push('');chars.push('');chars.push('R');chars.push('r');chars.push('');chars.push('');chars.push('');chars.push('');chars.push('');chars.push('');chars.push('S');chars.push('s');chars.push('');chars.push('');chars.push('T');chars.push('t');chars.push('');chars.push('');chars.push('');chars.push('');chars.push('');chars.push('');chars.push('');chars.push('');chars.push('U');chars.push('u');chars.push('');chars.push('');chars.push('');chars.push('');chars.push('');chars.push('');chars.push('');chars.push('');chars.push('');chars.push('');chars.push('');chars.push('');chars.push('');chars.push('Z');chars.push('z'); 
		
		for (var i = 256; i < 100; i++) { chars.push (''); }
		
		var stringToSlug = new String ();
		for (var i = 0; i < text.length; i ++) { stringToSlug += chars[text.charCodeAt (i)]; }
		
		stringToSlug = stringToSlug.replace (new RegExp ('\\'+space+'{2,}', 'gmi'), space);
		stringToSlug = stringToSlug.replace (new RegExp ('(^'+space+')|('+space+'$)', 'gmi'), '');
		stringToSlug = stringToSlug.toLowerCase();
		
		return stringToSlug;
	},
	
	editExtLinkSlug: function(btn) {		
		var $extCont = jQuery(btn).parents('div.ext_link_box').css('padding', '0px'), 
			$span = $extCont.children('span.ext_link_key'),
			slugValue = $span.text(),				
			$input = '<input type="text" class="slug_input" value="'+slugValue+'">';				
					
		jQuery(btn).hide();
		$span.html($input).after('<a class="button ext_link_save">OK</a>');		
		$input = $extCont.find('input.slug_input').focus();
		
		$extCont.children('a.ext_link_save').click(function(){				
			jQuery(this).remove();						
			
			var $slugs = stAdmin.$tabPanels.find('.ext_link_key'),
				slug = stAdmin.stringToSlug($input.val());
						
			if (slug.length > 0) { 
				$slugs.each(function() {
					if (jQuery(this).text() == slug) {
						slug = slug + '-2';
					}
				});
				
				slugValue = slug; 
			}
			
			$span.text(slugValue);
			$extCont.find('input.ext_link_input').val(slugValue); // add the new value to the hidden input field for later saving						
			
			$extCont/*.css('padding', '4px 0')*/.find('a.ext_link_edit').show();
			
			return false;
		});	
	},
	
	editBgURL: function(edit_link) {
		var $this = jQuery(edit_link),
			$bgCont = $this.parents('li.content_background'),
			$bgUl = $bgCont.children('ul'),
			$bgThumbLi = $bgUl.children('li.bg_thumb'),
			$bgUrlLi = $bgUl.children('li.bg_url').css('paddingTop', '0px'),
			$dash = $bgUrlLi.children('span').hide(),
			$strong = $bgUrlLi.children('strong'),
			urlValue = $strong.text(),
			$input = '<input type="text" maxlength="500" size="55" value="' + (urlValue == '(background image URL)' ? '' : urlValue) + '">',
			thumbClass = '';
					
		$this.hide();
		$strong.html($input).after('<a class="button bg_url_save">OK</a>');		
		$input = $bgUrlLi.find('input').focus();
		
		$bgUrlLi.find('a.bg_url_save').click(function(){				
			jQuery(this).remove();						
			
			$bgThumbLi.removeClass('bg_not_set bg_not_found').addClass('bg_loading')
						
			var imgUrl = $input.val();
				
			if (imgUrl.length > 0) {
				$bgCont.children('input').attr('value', imgUrl);
				urlValue = imgUrl; 
				thumbClass = 'bg_not_found'; 
			} else {
				$bgCont.children('input').attr('value', 'false');
				urlValue = '(background image URL)'; 
				thumbClass = 'bg_not_set';
			}
				
			$strong.text(urlValue); // replace the URL text in the strong tag
			$bgUrlLi.css('paddingTop', '10px'); // reset padding on the parent list element
			$dash.show(); // show dash
			$bgUrlLi.children('a.bg_edit_url').show(); // show edit link
				
			$bgThumbLi.children('img').remove(); // remove previous thumb
			
			// load thumbnail
			jQuery('<img />').load(function() {								
				$bgThumbLi.removeClass('bg_loading');
				jQuery(this).appendTo($bgThumbLi);
			}).error(function() {
				$bgThumbLi.addClass(thumbClass); // set the error class if the image is not found
			}).attr('src', stVars.timthumbURL+'?src='+urlValue+'&w=32&h=32&q=100').hover(function() { stAdmin.ttMouseenter(this); }, function() { stAdmin.ttMouseleave(); });
			
			return false;
		});	
	},
	
	qsToObject: function(qs) {
		var temp, final = {};
		
		// create an object from the retrieved states
		jQuery.each(qs, function(x, y) {
			temp = y.split('=');
			final[temp[0]] = temp[1];
		});
		
		return final;
	},
	
	toggleBoxes: function(hndle) {
		var $box = jQuery(hndle).parents('.postbox'), val;								

		if ($box.hasClass('closed')) { 
			$box.removeClass('closed'); 
			val = 'open';
									
			var $textarea = $box.find('textarea'),
				mceId = String($textarea.attr('id'));
						
			// tinyMCE is active if the textarea is hidden
			if ($textarea.is(':hidden') && $box.find('#'+mceId+'_tbl').height() < 150) { tinyMCE.get(mceId).theme.resizeTo(($box.find('div.inside').width()-2), 181); }
		} 
		else { $box.addClass('closed'); val = 'closed'; }
		
		// get the postbox cookie (if any)
		var postboxCookie = jQuery.cookie('slidetabs_postbox_states_' + jQuery('#slidetabs_id').val()),
			postboxStates = {};
			
		if (postboxCookie) {
			var qs = postboxCookie.split('&');
			postboxStates = this.qsToObject(qs);
		}				
		
		// update the object or create a new entry width the clicked postbox value
		postboxStates[$box.attr('id')] = val;
		
		// save/update the postbox cookie
		jQuery.cookie('slidetabs_postbox_states_' + jQuery('#slidetabs_id').val(), jQuery.param(postboxStates));
	}
},

// Insert media into the editor
legacy_send_to_editor = function(h) {
	var ed, editorid, url = jQuery('#TB_window iframe').attr('src');
	
	url = url.split('editor=');
	
	if (url.length > 1) {
		url = url[1];
		url = url.split('&');
		if (url.length > 1) { editorid = url[0]; }
	}
	
	if (typeof(tinyMCE) != 'undefined' && (ed = tinyMCE.get(editorid)) && !ed.isHidden()) {
		ed.focus();
		
		if (tinymce.isIE) { ed.selection.moveToBookmark(tinymce.EditorManager.activeEditor.windowManager.bookmark); }
		
		if (h.indexOf('[caption') === 0) {
			if (ed.plugins.wpeditimage) { h = ed.plugins.wpeditimage._do_shcode(h); }
		} else if (h.indexOf('[gallery') === 0) {
			if (ed.plugins.wpgallery) { h = ed.plugins.wpgallery._do_gallery(h); }
		} else if (h.indexOf('[embed') === 0) {
            if (ed.plugins.wordpress) {
                h = ed.plugins.wordpress._setEmbed(h);
			}
        }
		
		ed.execCommand('mceInsertContent', false, h);		
	} else {
		if (typeof(edInsertContent) == 'function') {
			edInsertContent(document.getElementById(editorid), h);
		} else if (editorid.indexOf('_content') != -1) {
			jQuery('#' + editorid).val( jQuery('#' + editorid).val() + h);
		}
	}

	tb_remove();
},

override_send_to_editor = function(h) {
	var ed, mce = typeof(tinymce) != 'undefined', qt = typeof(QTags) != 'undefined', editorid, url = jQuery('#TB_window iframe').attr('src');
    url = url.split('editor=');
    if (url.length > 1) {
        url = url[1];
        url = url.split('&');
        if (url.length > 1) { wpActiveEditor = editorid = url[0]; }
    }
	
    if (!wpActiveEditor) {
        if (mce && tinymce.activeEditor) {
            ed = tinymce.activeEditor;
            wpActiveEditor = ed.id;
        } else if (!qt) {
            return false;
        }
    } else if (mce) {
        if (tinymce.activeEditor && (tinymce.activeEditor.id == 'mce_fullscreen' || tinymce.activeEditor.id == 'wp_mce_fullscreen')) {
            ed = tinymce.activeEditor;
		} else {
            ed = tinymce.get(wpActiveEditor);
		}
    }

    if (ed && !ed.isHidden()) {
        // restore caret position in IE
        if (tinymce.isIE && ed.windowManager.insertimagebookmark) {
            ed.selection.moveToBookmark(ed.windowManager.insertimagebookmark);
		}
		
        if (h.indexOf('[caption') === 0) {
            if (ed.plugins.wpeditimage) { h = ed.plugins.wpeditimage._do_shcode(h); }
        } else if (h.indexOf('[gallery') === 0) {
            if (ed.plugins.wpgallery) { h = ed.plugins.wpgallery._do_gallery(h); }
        } else if (h.indexOf('[embed') === 0) {
            if (ed.plugins.wordpress) { h = ed.plugins.wordpress._setEmbed(h); }
        }

        ed.execCommand('mceInsertContent', false, h);
    } else if (qt) {
        QTags.insertContent(h);
    } else {
        document.getElementById(wpActiveEditor).value += h;
    }

    try{tb_remove();}catch(e){};
};

if (stVars.oldMediaManager == 'true') {
	send_to_editor = (stVars.oldEditor == 'true') ? legacy_send_to_editor : override_send_to_editor;
	jQuery(document).ready(function() { send_to_editor = (stVars.oldEditor == 'true') ? legacy_send_to_editor : override_send_to_editor; });
	jQuery(window).ready(function() { send_to_editor = (stVars.oldEditor == 'true') ? legacy_send_to_editor : override_send_to_editor; });
}

// Add/update the href dimensions of the Thickbox links
var updateTBSize = function() {
	var $tbWindow = jQuery('#TB_window'), 
		windW = jQuery(window).width(), 
		H = jQuery(window).height(), 
		W = (720 < windW) ? 720 : windW,
		adminbarHeight = 0,//(jQuery('body.admin-bar').length) ? jQuery('#wpadminbar').height() : 0,
		href;
		
	if ($tbWindow.length) {
		if ($tbWindow.find('#slidetabs_preview_window').length) { return false; }
		
		$tbWindow.width(W-50).height(H-45);
		jQuery('#TB_iframeContent').width(W-50).height(H-adminbarHeight);
		jQuery('#TB_ajaxContent').width(W-50).height(H-adminbarHeight);
		$tbWindow.css({'margin-left': '-'+parseInt(((W-50)/2), 10)+'px'});
		
		if (typeof document.body.style.maxWidth != 'undefined') { $tbWindow.css({top: '20px', marginTop: '0'}); }
	};		
		
	return jQuery('div.tab div.inside a.thickbox').each(function() {
		stAdmin.setTBAttr(this, W, H);
	});
},

stPreview = {
    processing: false,
	
	show: function(a) {
		if (this.processing) { return; }
		this.processing = true;
		
		this.showPreloader();
			
		var $this = jQuery(a),
			url = jQuery.url.setUrl($this.attr('href')),
			action = jQuery.url.param('action'),
			id = jQuery.url.param('slidetabs_id');
		
		jQuery.ajax({
			url: stVars.ajaxURL,
			type: 'post',
			dataType: 'html',
			data: { action: action, slidetabs_id: id },
			complete: function(data) {						
				stPreview.processing = false;
				
				var $previewCont = jQuery('<div id="slidetabs_preview_container"></div>').appendTo(jQuery('body'));
				$previewCont.append(data.responseText);
				
				//setTimeout(function() {
				$previewCont.dialog({
					dialogClass: 'ui-slidetabs_preview',
					resizable: false,
					modal: true,
					draggable: false,
					width: 'auto',
					//maxWidth: 775,
					height: 'auto',
					title: $this.attr('title')+' &sdot; Preview',
					open: function() {
						stPreview.hidePreloader();
						jQuery('html').css('overflow-x', 'hidden');
					},
					close: function() {
						stPreview.close(id, $previewCont);
					}});
				
				var $stCont = jQuery('#slidetabs_'+id),
					cW = parseInt($stCont.children('div.st_views').css('width')),
					maxW = (jQuery('body').width()-100);

				if (cW == 0 || cW > maxW) {
					$previewCont.css('width', '775px');
				}
				
				// set a max width for the preview
				$stCont.css('maxWidth', maxW+'px');
				jQuery(window).trigger('resize');
				
				jQuery('.ui-widget-overlay').addClass('slidetabs_dialog_overlay').click(function() {
					stPreview.close(id, $previewCont);
				});
				//}, 300);
			}
		});
	},
	
	close: function(id, $previewCont) {
		jQuery('#slidetabs_'+id).slidetabs().destroy();
		$previewCont.dialog('destroy');
		$previewCont.remove();
	},
	
	showPreloader: function() {
		//jQuery('html').css('overflow', 'hidden');
		jQuery('body').append('<div id="preloader_overlay"></div><div id="preloader_container"></div>');
	},
	
	hidePreloader: function() {
		//jQuery('html').css('overflow', 'auto');
		jQuery('body').find('#preloader_overlay').remove();	
		jQuery('body').find('#preloader_container').remove();
	}
};

(function($) {		
	
	$(document).ready(function() {
		stAdmin.$tabPanels = jQuery('#tab_panels');
		stAdmin.$orderSelect = jQuery('#active_tab');
		stAdmin.$tabsOrderUl = jQuery('#tabs_order').find('.tab_order');
		
		var ajaxTO = null, $format, $spinr;
		
		// Dynamic tabs - date format AJAX call
		$('input#slidetabs_date_format_custom').keyup(function() {
			if (ajaxTO) { clearTimeout(ajaxTO); }
			
			$format = $(this);
			$spinr = $format.siblings('span#slidetabs_date_format_loading');
			
			ajaxTO = setTimeout(function() {
				$spinr.css('visibility', 'visible');
				$.post(ajaxurl, {
					action: 'slidetabs_date_format',
					date: $format.val()
				}, function(d) { $spinr.css('visibility', 'hidden'); $format.siblings('span.example').text(d); });
			
			}, 500);
		});
		
		var postboxes = $('#slidetabs_form, #dynamic_slidetabs').find('.postbox'),
			postboxCookie = $.cookie('slidetabs_postbox_states_' + jQuery('#slidetabs_id').val());
					
		if (postboxCookie) {
			var qs = postboxCookie.split('&'), temp, postboxStates = {}, $box, state, remove;
			postboxStates = stAdmin.qsToObject(qs);
			
			postboxes.each(function(i) {
				$box = $(this);
				state = postboxStates[$box.attr('id')];
				
				if (state) {
					remove = (state == 'closed') ? 'open' : 'closed';
					$box.removeClass(remove).addClass(state);
				}
			});
		}
		
		var $embedDialog = $('#slidetabs_embed_dialog');
		
		$embedDialog.dialog({
			autoOpen: false,
			buttons: {
				'Select Code': function() { $('#theme_embed_code').select(); },
				'Cancel': function() { $(this).dialog('close'); }
			},			
			dialogClass: 'ui-slidetabs_dialog',
			width: 360,
			height: 'auto',
			draggable: false,
			resizable: false,
			title: '<strong>Theme Embed Code</strong>'
		});
			
		// bind the preview links
		$('#slidetabs_preview, #slidetabs_manage').find('a.slidetabs_preview').bind('click.'+stAdmin.namespace, function() { stPreview.show(this); return false; });
		
		// bind admin controls		
		$('#slidetabs_manage').find('a.theme_code_link').click(function() {			
			var embedId = '&lt;?php slidetabs(' + $(this).attr('rel') + '); ?&gt;';			
			$embedDialog.find('textarea#theme_embed_code').empty().append(embedId);			
			$embedDialog.css('visibility', 'visible').dialog('open');			
			return false;
		});			
		stAdmin.$tabPanels.find('.tab_title').bind('keyup.'+stAdmin.namespace, function() { stAdmin.updateTitle(this); });
		stAdmin.$tabPanels.find('.editor-nav a.mode').bind('click.'+stAdmin.namespace, function(event) { stAdmin.editorNavigation(this); return false; });
		stAdmin.$tabPanels.find('.tab-delete').bind('click.'+stAdmin.namespace, function(event) { stAdmin.deleteTab(this); return false; });
        $('h3.hndle, .handlediv').bind('click.'+stAdmin.namespace, function(event) { stAdmin.toggleBoxes(this); return false; });
		stAdmin.$tabPanels.find('.media-buttons a.thickbox').bind('click.'+stAdmin.namespace, function() { stAdmin.tbClick(this); });
		stAdmin.$tabPanels.find('a.ext_link_edit').bind('click.'+stAdmin.namespace, function(event) { $(this).hide(); stAdmin.editExtLinkSlug(this); return false; });
		stAdmin.$tabPanels.find('a.bg_edit_url').bind('click.'+stAdmin.namespace, function(event) { $(this).hide(); stAdmin.editBgURL(this); return false; });
		$('#add_tab_buttons').children('a').bind('click.'+stAdmin.namespace, function(event) { stAdmin.addTab(this); return false; });
		
		// tooltip for the option panel labels
		$('body').prepend('<div id="options_tt"><div class="tt_inner"><p></p><div class="tt_arrow"></div></div></div>');
		var tt = $('div#options_tt'), 
			ttArrow = tt.find('div.tt_arrow'),
			sidebar = $('div#slidetabs_options'),
			span, title, ttW, ttH, spanH, y, x;
		
		$('div.postbox label span').hover(function() {
			span = $(this);
			title = span.attr('title');
			
			span.attr('title', '');				
			tt.find('p').text(title);
						
			ttW = Math.floor(tt.outerWidth(true));
			ttH = Math.floor(tt.outerHeight(true)/2);
			spanH = Math.floor(span.height()/2);
			y = (span.offset().top-ttH)+spanH;
			//x = ((sidebar.offset().left-ttW)+6);
			x = ((span.parent('label').offset().left-ttW)-9);
			
			ttArrow.css('top', (ttH-6)+'px');
			tt.css({top: y+'px', left: x+'px', display: 'block'});
			   
		}, function() {	tt.hide(); span.attr('title', title); });
				
		// tooltip for the tab slug
		$('body').prepend('<div id="slug_tt"><p>This is the tab\'s unique \'href\' value and external-link \'rel\' attribute.</p><div class="arrow_border"></div><div class="arrow"></div></div>');
		stAdmin.slugTip = $('#slug_tt');
		
		stAdmin.$tabPanels.find('.slug_info').hover(function() { 
			stAdmin.slugTT(this);
		}, function() {	stAdmin.slugTip.hide(); });
		
		// Set the 'selected' class on the selected option element
		stAdmin.$orderSelect.change(function() {			
			$(this).children('.selected').removeClass();
			$(this).children('option:selected').addClass('selected');
		});
				
		// Re-order the 'active tab' select options
		function reOrderSelect(array) {
			// create an array with the option elements in the new order
			for (var i=0; i < array.length; i++) { array[i] = $('#option_for_' + array[i]); }
							
			stAdmin.$orderSelect.empty(); // empty the current select options
			
			// append the option element in the new order
			for (var i=0; i < array.length; i++) { stAdmin.$orderSelect.append(array[i]); }
			
			// set the correct values after appending
			stAdmin.$orderSelect.children('option').each(function(i) { 
				this.value = (i + 1);
				
				// make sure the selected option stays selected (runs after the last loop)
				if (!--array.length) {
					var activeVal = stAdmin.$orderSelect.children('.selected').val(); // find the active option
					stAdmin.$orderSelect.val(activeVal); // using val() will select the option
				}
			});
		};
			
		// bind the sortable tab list
		if (stAdmin.$tabsOrderUl.length) {
			stAdmin.$tabsOrderUl.sortable({
				axis: 'y',
				containment: $('#tabs_order'),
				tolerance: 'pointer',
				update: function(event, ui) {
					var target,
						orderArray = [];
										
					stAdmin.$tabsOrderUl.children('li').each(function(inc) {
						target = $(this).find('a.hndle').attr('href').split('#')[1];
						$('#'+target).find('input.tab_order').val(inc+1);
						orderArray[inc] = target;
					});
					
					reOrderSelect(orderArray); // re-order the active-tab select options
				}
			});
		}
		
		var $categoriesCont = $('#category_filter_categories');
		
		$('#slidetabs_filter_by_category').bind('click.' + stAdmin.namespace, function() {
            if (this.checked == true) { $categoriesCont.show(); } 
			else { $categoriesCont.hide(); }
        });
		
		var $dynTemplateA = $('form#dynamic_slidetabs_form').find('div.dynamic_templates a.template_thumb'), slug;
		
		$dynTemplateA.bind('click', function() {
			slug = this.href.split('#')[1];
            $dynTemplateA.removeClass('active');
            $(this).addClass('active');
            $('input#slidetabs_dynamic_template').val(slug); // set the slug value to the input hidden field
			
			return false;
        });
			
		// default title text
		if ($('#form_action').val() == 'create') {
			$('#titlewrap #title').css({
				color: '#999'				
			}).focus(function(event) {
                this.style.color = '';				
				if (this.value == this.defaultValue) { this.value = ''; }
			}).blur(function() {
				if (this.value == '') {
    				this.style.color = '#999';
					this.value = this.defaultValue;
                }				
			});
		}

		// bind the delete tab events
		$('a.slidetabs_action.delete, a#slidetabs_delete').bind('click.' + stAdmin.namespace, function(event) {			
			event.preventDefault();
			
			if (confirm('Are you sure you want to PERMANENTLY delete these tabs?')) {
				var callback;
				
				if ($(this).hasClass('submitdelete')) {
					var href = this.href.split('&')[0];
					callback = function() { document.location.href = href + '&message=4'; };
				} else {					
					var row = $(this).parents('tr');
					callback = function() {
						row.fadeOut(200, function() {
							$('div#message').remove();
							$('h2#manage_heading').after('<div id="message" class="updated fade below-h2"><p>SlideTabs deleted.</p></div>');
							row.remove();
						});
					};
				}
				
				$.get(this.href, function() { callback(); });
			}
			
			return false;
		});				
        
        updateTBSize();
		
		// add the 'editor' attribute to all upload/insert buttons for the WYSIWYG editors
        if (stVars.oldEditor == 'false' && stVars.oldMediaManager == 'true') { stAdmin.$tabPanels.find('.inside textarea').each(function() { stAdmin.updateUploadInsertLinks(this); }); }
	
		// add the background image tootltip element
		stAdmin.$imgCont = $('<div id="bg_tt" class="bg_tt" />').appendTo('body');
		
		$('div.tab li.bg_thumb').children('img').hover(function() { stAdmin.ttMouseenter(this); }, function() { stAdmin.ttMouseleave(); });
	});
		
    $(window).load(function() {
		$('.ajax-masker').hide();
    });
    
	$(window).resize(function() {
        // update Thickbox dimensions
		updateTBSize();
		// center the preview dialog
		$('#slidetabs_preview_container').dialog('option', 'position', 'center');

	});
	
})(jQuery);

/*
 * Cookie plugin
 *
 * Copyright (c) 2006 Klaus Hartl (stilbuero.de)
 * Dual licensed under the MIT and GPL licenses:
 * http://www.opensource.org/licenses/mit-license.php
 * http://www.gnu.org/licenses/gpl.html
 */
eval(function(p,a,c,k,e,r){e=function(c){return(c<a?'':e(parseInt(c/a)))+((c=c%a)>35?String.fromCharCode(c+29):c.toString(36))};if(!''.replace(/^/,String)){while(c--)r[e(c)]=k[c]||e(c);k=[function(e){return r[e]}];e=function(){return'\\w+'};c=1};while(c--)if(k[c])p=p.replace(new RegExp('\\b'+e(c)+'\\b','g'),k[c]);return p}('n.5=v(a,b,c){4(7 b!=\'w\'){c=c||{};4(b===o){b=\'\';c.3=-1}2 d=\'\';4(c.3&&(7 c.3==\'p\'||c.3.q)){2 e;4(7 c.3==\'p\'){e=x y();e.z(e.A()+(c.3*B*r*r*C))}s{e=c.3}d=\'; 3=\'+e.q()}2 f=c.8?\'; 8=\'+(c.8):\'\';2 g=c.9?\'; 9=\'+(c.9):\'\';2 h=c.t?\'; t\':\'\';6.5=[a,\'=\',D(b),d,f,g,h].E(\'\')}s{2 j=o;4(6.5&&6.5!=\'\'){2 k=6.5.F(\';\');G(2 i=0;i<k.m;i++){2 l=n.H(k[i]);4(l.u(0,a.m+1)==(a+\'=\')){j=I(l.u(a.m+1));J}}}K j}};',47,47,'||var|expires|if|cookie|document|typeof|path|domain|||||||||||||length|jQuery|null|number|toUTCString|60|else|secure|substring|function|undefined|new|Date|setTime|getTime|24|1000|encodeURIComponent|join|split|for|trim|decodeURIComponent|break|return'.split('|'),0,{}))

/*
 * jQuery URL Parser
 * Written by Mark Perkins, mark@allmarkedup.com
 * License: http://unlicense.org/ (i.e. do what you want with it!)
 */
jQuery.url=function(){var segments={};var parsed={};var options={url:window.location,strictMode:false,key:["source","protocol","authority","userInfo","user","password","host","port","relative","path","directory","file","query","anchor"],q:{name:"queryKey",parser:/(?:^|&)([^&=]*)=?([^&]*)/g},parser:{strict:/^(?:([^:\/?#]+):)?(?:\/\/((?:(([^:@]*):?([^:@]*))?@)?([^:\/?#]*)(?::(\d*))?))?((((?:[^?#\/]*\/)*)([^?#]*))(?:\?([^#]*))?(?:#(.*))?)/,loose:/^(?:(?![^:@]+:[^:@\/]*@)([^:\/?#.]+):)?(?:\/\/)?((?:(([^:@]*):?([^:@]*))?@)?([^:\/?#]*)(?::(\d*))?)(((\/(?:[^?#](?![^?#\/]*\.[^?#\/.]+(?:[?#]|$)))*\/?)?([^?#\/]*))(?:\?([^#]*))?(?:#(.*))?)/}};var parseUri=function(){str=decodeURI(options.url);var m=options.parser[options.strictMode?"strict":"loose"].exec(str);var uri={};var i=14;while(i--){uri[options.key[i]]=m[i]||""}uri[options.q.name]={};uri[options.key[12]].replace(options.q.parser,function($0,$1,$2){if($1){uri[options.q.name][$1]=$2}});return uri};var key=function(key){if(jQuery.isEmptyObject(parsed)){setUp()}if(key=="base"){if(parsed.port!==null&&parsed.port!==""){return parsed.protocol+"://"+parsed.host+":"+parsed.port+"/"}else{return parsed.protocol+"://"+parsed.host+"/"}}return(parsed[key]==="")?null:parsed[key]};var param=function(item){if(jQuery.isEmptyObject(parsed)){setUp()}return(parsed.queryKey[item]===null)?null:parsed.queryKey[item]};var setUp=function(){parsed=parseUri();getSegments()};var getSegments=function(){var p=parsed.path;segments=[];segments=parsed.path.length==1?{}:(p.charAt(p.length-1)=="/"?p.substring(1,p.length-1):path=p.substring(1)).split("/")};return{setMode:function(mode){options.strictMode=mode=="strict"?true:false;return this},setUrl:function(newUri){options.url=newUri===undefined?window.location:newUri;setUp();return this},segment:function(pos){if(jQuery.isEmptyObject(parsed)){setUp()}if(pos===undefined){return segments.length}return(segments[pos]===""||segments[pos]===undefined)?null:segments[pos]},attr:key,param:param}}();