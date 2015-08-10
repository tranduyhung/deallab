<?php
/**
 * @version		$Id: edit_images.php 2013-07-17 studio4 $
 * @package		DealLab
 * @author		Studio4 http://www.studio4.lt
 * @copyright	Copyright (c) 2010 - 2013 UAB Studio4. All rights reserved.
 * @license		GNU/GPL license: http://shop.studio4.lt/deallab-license
 */

// no direct access
defined('_JEXEC') or die;

$images = dlDataHelper::getDealData($this->item->id, 'images', '*', null, 'ordering'); ?>
<ul id="images-list">
	<?php if (count($images) > 0) : 
	foreach ($images as $key => $image) : ?>
		<li id="row<?php echo $key; ?>" class="ui-state-default">
			<a class="image-thumb" href="<?php echo DL_IMG . $image->original; ?>" target="_blank">
				<img src="<?php echo DL_IMG . 'thumb_' . $image->original; ?>" alt="" />
			</a>
			<div class="clr"></div>
			<a href="#" class="removeRow" onclick="removerow(<?php echo $key; ?>); return false;"></a>
			<a href="#" class="dragRow" onclick="return false;"></a>
		</li>
	<?php endforeach; 
	endif; ?>
</ul>
<button id="upload-btn" class="deallab-button" onclick="return false;"><?php echo JText::_('DEALLAB_BUTTON_ADD_IMAGE'); ?></button>
<div id="mestatus"></div>
<div id="image-fields">
	<?php if(count($images) > 0) :
		foreach($images as $key => $image) : ?>
			<input type="hidden" name="images[<?php echo $key; ?>][id]" value="<?php echo $image->id; ?>" />
			<input id="row<?php echo $key; ?>-ordering" type="hidden" name="images[<?php echo $key; ?>][ordering]" value="<?php echo $image->ordering; ?>" />
			<input id="imgstatus<?php echo $key; ?>" type="hidden" name="images[<?php echo $key; ?>][status]" value="1" />
		<?php endforeach; 
	endif;?>
</div>
<script type="text/javascript" >
	jQuery(function(){
		var counter = <?php echo count($images); ?>;
		var btnUpload=jQuery('#upload-btn');
		var mestatus=jQuery('#mestatus');
		var files=jQuery('#deal-images');
		new AjaxUpload(btnUpload, {
			action: 'index.php?option=com_deallab&task=ajax.dealimageupload',
			name: 'uploadfile',
			onSubmit: function(file, ext){
				 if (! (ext && /^(jpg|png|jpeg|gif)$/.test(ext))){
					mestatus.text('Only JPG, PNG or GIF files are allowed');
					return false;
				}
				mestatus.html('<img src="components/com_deallab/assets/js/ajax-loader.gif" height="16" width="16">');
			},
			onComplete: function(file, response){
				files.html('');
				mestatus.html('');
				if(response==="error"){
					alert(text(response));
				}else{
					addRow(response)
				}
			}
		});
		
	});
	
	function addRow(filename) {
		counter++;
		jQuery('#images-list').append('<li id="row'+counter+'" class="ui-state-default"><div class="img-container" style="background-image: url(\'<?php echo DL_IMG; ?>/'+filename+'\');"></div><a href="#" class="removeRow" onclick="removerow('+counter+'); return false;"></a><a href="#" class="dragRow" onclick="return false;"></a><div class="clr"></div></li>');
		jQuery('#image-fields').append('<input id="imgfield'+counter+'" type="hidden" name="images['+counter+'][filename]" value="'+filename+'"/><input id="imgstatus'+counter+'" type="hidden" name="images['+counter+'][status]" value="1"/>');
	}
	
	function removerow(id) {
		jQuery('#row'+id).remove();
		jQuery('#imgstatus'+id).val('0');
	}		
</script>