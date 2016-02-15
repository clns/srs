<div id="slidetabs_help" class="wrap">
    
    <div id="icon-edit-pages" class="icon32"></div>
    
    <h2 id="manage_heading">SlideTabs Usage Help</h2>
        
    <br />
    
    <ol class="toc">
        <li>
            <a href="#creating">Creating Tabs</a>
            <ol>                	
                <li><a href="#tab_panels">Tab Panels</a></li>
                <li><a href="#sidebar_options">Sidebar Options</a></li>
                <li><a href="#saving_tabs">Saving</a></li>
                <li><a href="#previewing">Previewing</a></li>
            </ol>
        </li>
        <li>
            <a href="#creating_dynamic">Creating Dynamic Tabs</a>
            <ol>
                <li><a href="#dynamic_templates">Dynamic Templates</a></li>
                <li><a href="#dynamic_content">The Content</a></li>
                <li><a href="#featured_posts">Specifying Featured Posts</a></li>
            </ol>
        </li>
        <li><a href="#managing">Managing Tabs</a></li>
        <li>
            <a href="#publishing">Publishing Tabs</a>
            <ol>
                <li><a href="#shortcode_generator">Shortcode Generator</a></li>
                <li><a href="#template_tag">Template Tag</a></li>                    
            </ol>
        </li>
        <li>
        	<a href="#templates">Templates</a>
        </li>
        <li><a href="#external_linking">External Linking</a></li>
        <li><a href="#url_linking">URL Linking</a></li>
        <li><a href="#plugin_settings">Plugin Settings</a></li>
        <li><a href="#troubleshooting">Troubleshooting</a></li>
    </ol>
    
    <hr />
    
    <h3 id="creating">A. Creating Tabs</h3>
    
    <p>To create a new SlideTabs entry, go to the main SlideTabs menu and click "Add New".</p>
    <img src="<?php echo $slidetabs_class->url('/images/help/title.jpg'); ?>">
    <p class="p_last">Enter a title for the SlideTabs entry.</p>
    
    <h4 id="tab_panels">1. Tab Panels</h4>
    <p>The tab panels is where you create the content for each individual tab. There are two different tab panels, one for adding HTML content and one for AJAX.</p>
    <p><strong>Tip:</strong> The panels can be collapsed/expanded by clicking on the title bar to save vertical space.</p>
    
    <h4 style="font-size:14px; margin:25px 0 15px;">Content Tab:</h4>    
    <img src="<?php echo $slidetabs_class->url('/images/help/tab_panel.jpg'); ?>">
    <p class="p_last">
        <strong>1)</strong> &nbsp;The text that will display in the tab.<br />
        <strong>2)</strong> &nbsp;This is the tab's unique 'href' value and <a href="#external_linking">external-link</a> 'rel' attribute (can only contain URL friendly characters).<br />
        <strong>3)</strong> &nbsp;Here you can create your content in a visual editor or in plain HTML. You can also upload and include media files (like images) from the "Upload/Insert" menu.<br />
        <strong>4)</strong> &nbsp;Set a background image for the content.
    </p>
    
    <h4 style="font-size:14px; margin:25px 0 15px;">AJAX Tab:</h4>    
    <img src="<?php echo $slidetabs_class->url('/images/help/ajax_tab_panel.jpg'); ?>">
    <p><strong>1)</strong> &nbsp;The <em>Tab URL</em> field is where you link to the content you want to load via AJAX. The rest of the fields are the same as the content tab panel (see above).</p>
        
    <h4 style="font-size:14px; margin:25px 0 15px;">Add New Tab:</h4>    
    <p>To add a new tab, click the "Add AJAX Tab" or "Add Content Tab" button below the tab panels:</p>
    <img src="<?php echo $slidetabs_class->url('/images/help/add_new_tab.jpg'); ?>">
    
    <h4 style="font-size:14px; margin:25px 0 15px;">Rearrange the Tabs:</h4>    
    <p>To rearrange the tabs simply drag and drop the list elements inside the "Rearrange Tabs" panel. <a href="#saving_tabs">Save the tabs</a> to show the tab panels in the new order:</p>
    <img src="<?php echo $slidetabs_class->url('/images/help/rearrange_tabs.jpg'); ?>">
    
    <h4 id="sidebar_options">2. Sidebar Options</h4>
    <p>In the sidebar on the right you have the option panels. Each panel contains a group of settings and can be collapsed/expanded to save vertical space.</p>
    <p><strong>Tip:</strong> Hover the sidebar labels to show a tooltip with info about each setting.</p>
    <img src="<?php echo $slidetabs_class->url('/images/help/sidebar_options.jpg'); ?>">
    
    <h4 id="saving_tabs">3. Saving the Tabs</h4>
    <p>After you're done creating the content and setting the options how you want them, the tabs needs to be saved to the database. Click the "Save" button inside the "Actions" panel to save the tabs:</p>
    <img src="<?php echo $slidetabs_class->url('/images/help/save.jpg'); ?>">                  
    
    <h4 id="previewing">4. Previewing the Tabs</h4>
    <p>After saving the tabs, the "Preview" button can be clicked. The preview will load in a modal window. <em>Remember to save before previewing any new changes.</em></p>
    <img src="<?php echo $slidetabs_class->url('/images/help/previewing.jpg'); ?>" class="last">  
    
    <hr />
    
    <h3 id="creating_dynamic">A. Creating Dynamic Tabs</h3>
    <p>Dynamic tabs lets you display excerpts of your posts and pages. The tabs will be generated automatically from the number of posts available or by the number you specify.</p>
    
    <h4 id="dynamic_templates">1. Dynamic Templates</h4>
    <p>The dynamic tabs uses customizable templates to display the content. You can view helpful guides in the SlideTabs documentation on <a href="http://www.slidetabs.com/docs/#dynamic-templates" target="_blank">Dynamic Templates</a>.</p>
    <img src="<?php echo $slidetabs_class->url('/images/help/dynamic_templates.jpg'); ?>">
    
    <h4 id="dynamic_content">2. The Content</h4>
    <p>You can control what type of post-content to display with the settings on the <a href="<?php echo site_url('/wp-admin/admin.php?page=slidetabs.php/slidetabs_dynamic');?>">Add Dynamic</a> page:</p>
    <img src="<?php echo $slidetabs_class->url('/images/help/dynamic_content.jpg'); ?>">
    <p class="p_last">
        <strong>1)</strong> &nbsp;Specify the type of content you want to display and how you want to display it.<br />
        <strong>2)</strong> &nbsp;Sort the posts by content and/or post categories.<br />
        <strong>3)</strong> &nbsp;The text that will display in the tabs. It can either be the post title or the post date.<br />
		<strong>4)</strong> &nbsp;Max length of the content-title, and alternative title length for when a image is included in the content.<br />
        <strong>5)</strong> &nbsp;Max length of the post-excerpt, and alternative length for when a image is included in the content.<br />
        <strong>6)</strong> &nbsp;Settings for the content image.<br />
    </p>
    
    <h4 id="featured_posts">2. Specifying Featured Posts</h4>
    <p>You can mark any post you create/edit as a SlideTabs featured post in the "SlideTabs Dynamic" metabox in the sidebar. This way you can choose to only display the posts you have marked as featured instead of all the available posts.</p>    
    <img src="<?php echo $slidetabs_class->url('/images/help/featured_post.jpg'); ?>">
    <p><strong>1)</strong> &nbsp;A optional title can also be specified. This is useful if the original post title is too long for example.</p>
    
    <hr />
    
    <h3 id="managing">C. Managing Tabs</h3>
    
    <p>All the saved tabs can be accessed through the <a href="<?php echo site_url('/wp-admin/admin.php?page=slidetabs.php');?>">Manage</a> page:</p>
    <img src="<?php echo $slidetabs_class->url('/images/help/manage.jpg'); ?>">
    <p>Each entry has it's own ID that is used when <a href="#publishing">publishing</a> the Tabs. In the menu to the right of each entry you can edit, get the template embed code, duplicate, preview and delete the tabs. You can sort the entries by their title or the date they were last modified by clicking the "Title" or "Modified" links.</p>

    <hr />

    <h3 id="publishing">D. Publishing Tabs</h3>
    
    <h4 id="shortcode_generator">1. Shortcode Generator</h4>
    <p>You can use the SlideTabs shortcode generator button on the WordPress visual editor to include the tabs in a post or page. Click the button to open the dialog window, select the tabs you want to include and click "Insert" to add the shortcode to the content:</p>
    <img src="<?php echo $slidetabs_class->url('/images/help/shortcode_generator.jpg'); ?>">
    
    <br />
    
    <p>You can also enter the shortcode manually. Enter <code>[slidetabs id="123"]</code> in a post or page, where "123" should be the ID of the tabs you want to publish.</p>
            
    <h4 id="template_tag">2. Template Tag</h4>
    <p>Including SlideTabs directly into your WordPress theme is made simple by using the <strong>slidetabs(id, dimensions)</strong> PHP template tag:</p>
    <code>&lt;?php slidetabs(id, array('width' => 700, 'height' => 350)); ?&gt;</code>
                       
    <p>The first parameter of the template tag is the ID of the tabs and the second parameter (optional) is an array with the dimensions. The second parameter will override the defined width and height specified in the Admin page.</p>
     
    <hr />
     
    <h3 id="templates">E. Templates</h3>                    
    
    <p>You can view helpful guides in the SlideTabs documentation on <a href="http://www.slidetabs.com/docs/#custom-templates" target="_blank">Creating Templates</a>.</p>
        
	<hr />
        
    <h3 id="external_linking">F. External Linking</h3>
    
    <p>You can link directly to any tab by adding links anywhere on the page. Below is an example of how it's done:</p>
    <code class="html">&lt;a href=&quot;#tab-2&quot; class=&quot;st_ext&quot; rel=&quot;slidetabs_1&quot; /&gt;</code>
    
    <ul>
        <li><strong style="display:block; padding:10px 0 8px 0;">External-Link Attributes:</strong></li>
        <li><code>href</code> : Must match the <code>rel</code> attribute of the tab you want to open (this is the tab's <em>slug</em> value).</li>
        <li><code>class</code> : Each external link must have the <code>.st_ext</code> class.</li> 
        <li><code>rel</code> : Must match the main container <code>id</code> of the tabs you want to target.</li>
    </ul>
    
    <hr />
    
    <h3 id="url_linking">G. URL Linking</h3>
    
    <p>You can also target tabs with the URL:</p>                        
    <code class="html"><span class="url">http://www.your-url.com/</span>#tab-2</code>
    <p>The URL's hash value (#tab-2) should match the <code>rel</code> attribute/slug of the tab you want to open.</p>
    
    <hr />
    
    <h3 id="plugin_settings">H. Plugin Settings</h3>
        
    <p>The <a href="<?php echo site_url('/wp-admin/admin.php?page=slidetabs.php/slidetabs_plugin_settings');?>">Plugin Settings</a> page lets you enable/disable scripts used by the SlideTabs plugin. This makes it easier to avoid conflicts and optimize your website.</p>
    <img src="<?php echo $slidetabs_class->url('/images/help/plugin_settings.jpg'); ?>">
    
    <hr />
    
    <h3 id="troubleshooting">I. Troubleshooting</h3>
                                
    <p>Follow the steps below to troubleshoot issues with the SlideTabs WordPress plugin:</p>
    
    <ul class="bullet_list">
        <li>Check that your webhost is running PHP v5.2.1+ and WordPress v3.0+.</li>
        <li>Make sure your WordPress theme is including both the <code>wp_head()</code> and <code>wp_footer()</code> functions. Also make sure <code>&lt;?php wp_head(); ?&gt;</code> is the last thing loading in your <code>&lt;head&gt;</code> tag. 
        </li><li>If you need to load JavaScript for your WordPress theme, make sure you are using the <code>wp_enqueue_script()</code> function. See <a target="new" href="http://codex.wordpress.org/Function_Reference/wp_enqueue_script" rel="nofollow">http://codex.wordpress.org/Function_Reference/wp_enqueue_script</a> for more information on how to implement this.</li>
        <li>Make sure the slider shortcode has not been wrapped in any HTML tags (use the HTML editor to check).</li>
        <li>Try using the default Twenty Ten/Eleven theme instead of your custom theme to see if could be a theme conflict.</li>
        <li>Try disabling all other plugins then re-enable them one-by-one to find which one causes a conflict.</li>
        <li>Make sure you have enabled JavaScript in your browser.</li>
    </ul>
    
    <hr class="space">

</div> <!-- /#slidetabs_help -->