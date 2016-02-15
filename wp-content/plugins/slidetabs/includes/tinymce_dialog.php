<div id="slidetabs_tinymce_dialog">    
	<?php if (isset($slidetabs) && !empty($slidetabs)): ?>
		<table class="widefat post fixed" cellspacing="0">
			<thead>
				<tr>
					<th class="manage-column column-title" scope="col">Title</th>
					<th width="90" class="manage-column column-date" scope="col">Modified</th>
				</tr>
			</thead>
			<tbody>
				<?php $alternate = 0; ?>
				<?php foreach ((array) $slidetabs as $slidetab): ?>
					<tr id="slidetabs_id_<?php echo $slidetab['id']; ?>" class="author-self status-publish iedit<?php echo ($alternate & 1) ? '' : ' alternate'; ?>" valign="top">
						<td class="post-title column-title"><strong>
							<?php if ($slidetab['dynamic'] == '1'): ?>
								<img src="<?php echo $slidetabs_class->url('/images/dynamic_icon.png'); ?>" alt="Dynamic SlideTabs" />
							<?php endif; ?>
							<?php echo $slidetab['title']; ?></strong>
                        </td>
						<td clsss="date column-date"><?php echo date( "Y/m/d", strtotime($slidetab['updated_at'])); ?></td>
					</tr>
					<?php $alternate++; ?>
				<?php endforeach; ?>
			</tbody>
		</table>
	<?php else: ?>
	<div class="message">
		<p>No SlideTabs found. <a href="<?php echo $slidetabs_class->action('/slidetabs_add_new'); ?>">Add New</a></p>
	</div>
	<?php endif; ?>
</div>