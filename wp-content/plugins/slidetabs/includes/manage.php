<div id="slidetabs_manage" class="wrap">
    
    <div id="icon-edit" class="icon32"></div>
    
    <h2 id="manage_heading">Manage SlideTabs
        <a class="button add-new-h2" href="<?php echo $slidetabs_class->action('/slidetabs_add_new'); ?>">Add New</a>
    </h2>           
    
    <?php echo $slidetabs_class->display_message(); ?>
    
    <?php if (!empty($slidetabs)) : ?>
        <table id="slidetabs" class="widefat post fixed" cellspacing="0">
            <thead>
                <tr>
                    <th class="manage-column column-title <?php echo ($slidetabs_class->get_current_orderby('title') !== false) ? $slidetabs_class->get_current_orderby('title') : ''; ?>" scope="col"><a href="<?php echo $slidetabs_class->orderby('title'); ?>"><span>Title</span><span class="sorting-indicator"></span></a></th>
                    <th width="340" class="manage-column" scope="col">Actions</th>
                    <th width="200" class="manage-column" scope="col">Shortcode</th>
                    <th width="80" class="manage-column column-date <?php echo ($slidetabs_class->get_current_orderby('modified') !== false) ? $slidetabs_class->get_current_orderby('modified') : ''; ?>" scope="col"><a href="<?php echo $slidetabs_class->orderby('modified'); ?>"><span>Modified</span><span class="sorting-indicator"></span></a></th>
                </tr>
            </thead>
            <tfoot>
                <tr>                    
                    <th class="manage-column column-title <?php echo ($slidetabs_class->get_current_orderby('title') !== false) ? $slidetabs_class->get_current_orderby('title') : ''; ?>" scope="col"><a href="<?php echo $slidetabs_class->orderby('title'); ?>"><span>Title</span><span class="sorting-indicator"></span></a></th>
                    <th width="340" class="manage-column" scope="col">Actions</th>
                    <th width="200" class="manage-column" scope="col">Shortcode</th>
                    <th width="80" class="manage-column column-date <?php echo ($slidetabs_class->get_current_orderby('modified') !== false) ? $slidetabs_class->get_current_orderby('modified') : ''; ?>" scope="col"><a href="<?php echo $slidetabs_class->orderby('modified'); ?>"><span>Modified</span><span class="sorting-indicator"></span></a></th>
                </tr>
            </tfoot>
            <tbody>
                <?php $alternate = 0; ?>
                <?php foreach( (array) $slidetabs as $slidetab ) : ?>
                    <tr class="author-self status-publish iedit<?php echo ($alternate & 1) ? '' : ' alternate'; ?>" valign="top">
                        <td class="post-title column-title">    							
                            <a href="<?php echo $slidetabs_class->action($slidetab['dynamic'] == '1' ? '/slidetabs_dynamic' : ''); ?>&action=edit&id=<?php echo $slidetab['id']; ?>" class="row-title">
								<?php if ($slidetab['dynamic'] == '1'): ?>
                                    <img src="<?php echo $slidetabs_class->url('/images/dynamic_icon.png' ); ?>" alt="Dynamic SlideTabs" />
                                <?php endif; ?>
								<?php echo $slidetab['title']; ?>
                            </a><span class="slidetabs_id"> (ID = <?php echo $slidetab['id']; ?>)</span>
                        </td>
                        <td class="manage-column" scope="col">
                            <span><a href="<?php echo $slidetabs_class->action($slidetab['dynamic'] == '1' ? '/slidetabs_dynamic' : ''); ?>&action=edit&id=<?php echo $slidetab['id']; ?>" class="slidetabs_action">Edit</a> &nbsp;|&nbsp;</span>
                            <span><a href="#embed_dialog" class="theme_code_link" rel="<?php echo $slidetab['id']; ?>">Embed</a> &nbsp;|&nbsp;</span>
                            <span><a href="<?php echo wp_nonce_url($slidetabs_class->action() . '&action=duplicate&id=' . $slidetab['id'] . '&message=6', 'slidetabs_duplicate_' . $slidetab['id']); ?>" class="slidetabs_action">Duplicate</a> &nbsp;|&nbsp;</span>
                            <span class="trash"><a href="<?php echo wp_nonce_url($slidetabs_class->action() . '&action=delete&id=' . $slidetab['id'] . '&message=4', 'slidetabs_delete_' . $slidetab['id']); ?>" class="slidetabs_action delete">Delete</a> &nbsp;|&nbsp;</span>
                            <span><a href="<?php echo admin_url('admin-ajax.php'); ?>?action=slidetabs_preview&slidetabs_id=<?php echo $slidetab['id']; ?>" title="<?php echo !empty($slidetab['title']) ? $slidetab['title'] : 'New SlideTabs'; ?>" class="slidetabs_preview">Preview</a></span>
                            
                        </td>
                        <td class="column-date">[slidetabs id="<?php echo $slidetab['id']; ?>"]</td>
                        <td class="date column-date"><?php echo date("Y/m/d", strtotime($slidetab['updated_at'])); ?></td>
                    </tr>
                    <?php $alternate++; ?>
                <?php endforeach; ?>
            </tbody>
        </table>
    
    <?php else: ?>
    <div id="message" class="updated">
        <p>No SlideTabs found. <a href="<?php echo $slidetabs_class->action('/slidetabs_add_new'); ?>">Add New</a></p>
    </div>
    <?php endif; ?>    	

</div> <!-- /#slidetabs_manage -->