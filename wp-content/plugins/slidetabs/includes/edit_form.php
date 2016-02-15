<!--[if gt IE 6]><style type="text/css">.inner-sidebar .postbox .inside input{margin-left:0px;}#slidetabs_options ul.inside li{margin-bottom:2px;}.inner-sidebar .postbox .inside input{min-width:58px;margin-left:4px;}#general_options input{margin-left:0px;}</style><![endif]-->
<!--[if lte IE 7]><style type="text/css">div.inner-sidebar .postbox .inside input.dimensions{width:90px;}#general_options ul.inside li{margin-bottom:2px;}#slidetabs_options ul.inside li.select_spacing{margin-bottom:7px;}</style><![endif]-->
<div class="wrap" id="slidetabs_form">
	
    <div id="icon-edit" class="icon32"></div><h2><?php echo "create" == $form_action ? "Add New SlideTabs" : "Edit SlideTabs"; ?></h2>
    
    <?php echo $slidetabs_class->display_message(); ?>

	<form action="" method="post" id="slidetabs_update_form">
	    
		<?php function_exists('wp_nonce_field') ? wp_nonce_field('slidetabs-for-wordpress', 'slidetabs-' . $form_action . '_wpnonce') : ''; ?>
		<input type="hidden" name="action" value="<?php echo $form_action; ?>" id="form_action" />
		<input type="hidden" name="tabs_post_id" value="<?php echo $slidetabs['tabs_post_id']; ?>" id="slidetabs_post_id" />
		<?php if (isset($slidetabs['id']) && !empty($slidetabs['id'])) : ?>
			<input type="hidden" name="id" value="<?php echo $slidetabs['id']; ?>" id="slidetabs_id" />
		<?php endif; ?>
		
		<div class="metabox-holder has-right-sidebar">			          
            <?php include($slidetabs_class->dir('/includes/add_edit_sidebar.php')); ?>
			
			<div class="editor-wrapper">				
                <div class="editor-body">
					
                    <div id="titlediv">
						<div id="titlewrap">
							<input type="text" name="title" size="40" maxlength="255" value="<?php echo !empty($slidetabs['title']) ? $slidetabs['title'] : 'New SlideTabs'; ?>" id="title" />
						</div>
					</div>
				                      
					<div id="tab_panels">
						<?php if (!$slidetabs_class->use_old_tinymce_editor): ?>
						<div id="tinymce_preload" style="display:none;"><?php wp_editor('', 'preload'); ?></div>
                        <?php endif; ?>
						<?php 
							$count = 1;						
							
							foreach ((array) $tabs as $tab) {
								if ($tab['tab_type'] == 'slidetabs_ajax') {
									include($slidetabs_class->dir('/includes/edit_form-ajax_tab_panel.php'));
								} else {
									include($slidetabs_class->dir('/includes/edit_form-tab_panel.php'));
								}
								
								$count++;
							}
						?>
					</div> <!-- /#tab_panels -->
					
                    <div id="add_tab_buttons">
                        <div class="ajax-masker"></div>
                        <a href="<?php echo admin_url('admin-ajax.php'); ?>?action=slidetabs_add_tab" id="add_ajax_tab" class="preview button">Add AJAX Tab</a>
                        <a href="<?php echo admin_url('admin-ajax.php'); ?>?action=slidetabs_add_tab" id="add_content_tab" class="preview button">Add Content Tab</a>
                    </div>
                    
				</div> <!-- /.editor_body -->			
            </div> <!-- /.editor_wrapper -->
		
        </div> <!-- /.metabox-holder -->
	
    </form>

</div> <!-- /#slidetabs_form -->