/*
 *  Custom JavaScript for the 'slider' template
 */
  
(function($) {
	function CustomMenu(container) {
		var $container = $(container), // the main container
			$tabsUl = $container.find('ul.st_tabs_ul'),
			$firstLi = $tabsUl.find('li').first(),
			$border,
			stObj = $container.slidetabs(), // get the current instance object
			pos = ($container.attr('class').indexOf('horizontal') != -1) ? 'left' : 'top',
			liPos, offset;
				
		var menuBorder = {
			init: function() {
				$firstLi.append('<div class="st_border" />'); // append the border element
				$border = $firstLi.find('.st_border'); // get the appended border element
				
				this.setPosition($tabsUl.find('.st_tab_active'), false); // position the border element
				
				stObj.setOptions({onTabClick: function() { menuBorder.setPosition(this, true); }});
			},
			
			setPosition: function($tab, animate) {
				// return false if a content animation is running
				if ($('div#'+$container.attr('id')+' div.st_views :animated').length) { return false; }
				
				// tabs offset fix
				liPos = $firstLi.position()[pos];
				liPos = (liPos > 0) ? -liPos : Math.abs(liPos);
												
				// calculate the offset
				offset = $tab.parent('li').position()[pos]+liPos;
				
				if (animate) {
					// animate the border element
					if (pos == 'left') { $border.stop().animate({left: offset+'px', width: $tab.outerWidth(false)+'px'}, 200, 'linear'); }
					else { $border.stop().animate({top: offset+'px', height: $tab.outerHeight(false)+'px'}, 200, 'linear'); }				
				} else {
					// position the border element
					if (pos == 'left') { $border.css({left: offset+'px', width: $tab.outerWidth(false)+'px'}); }
					else { $border.css({top: offset+'px', height: $tab.outerHeight(false)+'px'}); }
				}
			}
		},
		
		extButtons = {
			init: function() {
				$container.append('<a href="#" class="st_ext_btn st_ext_prev" />', '<a href="#" class="st_ext_btn st_ext_next" />');
				$container.children('.st_ext_prev').click(function() { stObj.goToPrev(); return false; });
				$container.children('.st_ext_next').click(function() { stObj.goToNext(); return false; });
			}
		};
		
		menuBorder.init();
		extButtons.init();
	};
	
	var tabsInstance;
	
	$(document).ready(function() {
		// find each SlideTabs instance using this template
		for (var i=0, slidetabs=$('.slider'); i<slidetabs.length; i++) {
			tabsInstance = slidetabs[i];
			
			// assign the navigation function to each instance
			if (typeof(tabsInstance.stCustomMenu) == 'undefined') {
				tabsInstance.stCustomMenu = CustomMenu(tabsInstance);
			}
		}
	});
})(jQuery);