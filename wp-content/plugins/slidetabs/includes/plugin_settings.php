<div id="slidetabs_plugin_settings" class="wrap">
    
    <div id="icon-options-general" class="icon32"></div>
    
    <h2 id="manage_heading">Plugin Settings</h2>
        
    <?php echo $slidetabs_class->display_message($static_message); ?>  
    
    <div class="plugin_settings">        
        <form action="" method="post">
            <?php function_exists('wp_nonce_field') ? wp_nonce_field('slidetabs-for-wordpress', 'slidetabs-plugin-settings_wpnonce') : ''; ?>
                <ul>
                    <li><label><input type="checkbox" name="enqueue_jquery" value="1"<?php echo $slidetabs_class->plugin_settings['enqueue_jquery'] == true ? ' checked="checked"' : ''; ?> /> <strong>Include jQuery 1.7.2</strong> - If you want to include your own version of jQuery this option can be disabled (note that SlideTabs needs at least version 1.4.2).</label></li>
                    <li><label><input type="checkbox" name="enqueue_jquery_easing" value="1"<?php echo $slidetabs_class->plugin_settings['enqueue_jquery_easing'] == true ? ' checked="checked"' : ''; ?> /> <strong>Include jQuery Easing plugin</strong> - If you are not using any easing effects for the animations this option can be disabled.</label></li>
                    <li><label><input type="checkbox" name="enqueue_jquery_mousewheel" value="1"<?php echo $slidetabs_class->plugin_settings['enqueue_jquery_mousewheel'] == true ? ' checked="checked"' : ''; ?> /> <strong>Include jQuery MouseWheel plugin</strong> - If you want to use a different plugin for handling the mouse wheel scrolling this option can be disabled.</label></li>
                </ul>
            <input type="submit" class="button-primary" value="Update Settings" />
        </form>
    </div>    	

</div> <!-- /#slidetabs_plugin_settings -->