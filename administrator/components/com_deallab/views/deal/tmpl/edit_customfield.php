<?php
/**
 * @version		$Id: edit_customfield.php 2013-07-17 studio4 $
 * @package		DealLab
 * @author		Studio4 http://www.studio4.lt
 * @copyright	Copyright (c) 2010 - 2013 UAB Studio4. All rights reserved.
 * @license		GNU/GPL license: http://shop.studio4.lt/deallab-license
 */

// no direct access
defined('_JEXEC') or die;

$customfields	= dlDataHelper::getDealData($this->item->id, 'customfields', '*', null, 'ordering');
$doc 			= JFactory::getDocument();
$editor			= JFactory::getEditor();
?>
<div id="customfieldsShadow"></div>
<div id="customfieldsBlock">
	<?php if ($customfields) :
		foreach ($customfields as $key => $row) : ?>
    		<div id="customfield-<?php echo $key; ?>" class="customfield" rel="<?php echo $row->ordering; ?>">
		        <div>
		            <input type="hidden" name="customfields[<?php echo $key; ?>][id]" value="<?php echo $row->id; ?>" />
		            <input type="text" name="customfields[<?php echo $key; ?>][name]" value="<?php echo $row->name; ?>" style="width: 765px;" placeholder="<?php echo JText::_('DEALLAB_PLACEHOLDER_TITLE'); ?>" />
		        </div>
        		<div class="clr"></div>
		        <div class="text-editor">
		            <?php echo $editor->display('customfields[' . $key . '][value]', $row->value, '765', '200', null, null, false); ?>
		        </div>
        		<div class="clr"></div>
        		<div style="text-align: right;"><input type="button" value="<?php echo JText::_('DEALLAB_BUTTON_UPDATE'); ?>" onclick="updateCustomfield();" /></div>
    		</div>
		<?php endforeach; 
	endif; ?>
</div>
<div class="clr"></div>
<fieldset style="margin-top: 20px;">
    <legend><?php echo JText::_('DEALLAB_FIELDSET_CUSTOMFIELDS'); ?></legend>
    <ul id="custom-fields"></ul>
    <div class="clr"></div>
    <input type="button" value="<?php echo JText::_('DEALLAB_BUTTON_ADD'); ?>" onclick="addCustomField();" />
</fieldset>
<script type="text/javascript">
	jQuery(document).ready(function(){
        listAllCustomFields();
    });
    
    function listAllCustomFields(){
        var customfields = jQuery('#customfieldsBlock').children(),
            fields = jQuery('#custom-fields'),
            rows = '';
        
        fields.html('');
        
        customfields.each(function(){
            var block = jQuery(this),
                key = block.attr('id').replace('customfield-', ''),
                order = block.attr('rel');
                name = block.find('input[name="customfields[' + key + '][name]"]').val();
                
            rows = rows + '<li id="customfieldInList-' + key + '" class="ui-state-default">';
            rows = rows + '<input type="text" value="' + name + '" readonly="true" placeholder="<?php echo JText::_('DEALLAB_PLACEHOLDER_TITLE'); ?>" size="125" />';
            rows = rows + '<input type="hidden" name="customfields['+key+'][ordering]" value="' + order + '" class="ordering" />';
            rows = rows + '<input type="button" value="<?php echo JText::_('DEALLAB_BUTTON_EDIT'); ?>" onclick="showCustomfieldBlock('+key+');" />';
            rows = rows + '<a href="#" class="removeRow" onclick="removeCustomfieldBlock('+key+'); return false;"></a>';
            rows = rows + '<a href="#" class="dragRow" onclick="return false;"></a>';
            rows = rows + '<div class="clr"></div>';
            rows = rows + '</li>';
        });
        
        fields.append(rows);
    }
    
    function addCustomField(){
        var block = jQuery('#customfieldsBlock'),
            div = '';
        
        if(block.children(':last').attr('id'))
            var key = parseInt(block.children(':last').attr('id').replace('customfield-', '')) + 1;
        else
            var key = '0';
            
		if(block.children(':last').attr('rel'))
            var order = parseInt(block.children(':last').attr('rel')) + 1;
        else
            var order = '1';
        
		
        div += '<div id="customfield-' + key + '" class="customfield" rel="'+order+'">';
        div += '    <div>';
        div += '        <input type="hidden" name="customfields[' + key + '][id]" value="" />';
        div += '        <input type="text" name="customfields[' + key + '][name]" value="" style="width: 765px;" placeholder="<?php echo JText::_('DEALLAB_PLACEHOLDER_TITLE'); ?>" />';
        div += '    </div>';
        div += '    <div class="clr"></div>';
        div += '    <div class="text-editor"></div>';
        div += '    <div class="clr"></div>';
        div += '    <div style="text-align: right;"><input type="button" value="<?php echo JText::_('DEALLAB_BUTTON_UPDATE'); ?>" onclick="updateCustomfield();" /><input type="button" value="<?php echo JText::_('DEALLAB_BUTTON_CLOSE'); ?>" onclick="hideAllCustomfieldBlocks();" /></div>';
        div += '</div>';
        
        block.append(div);
        setTimeout(function(){
            loadTextEditor('customfields[' + key + '][value]', '', 765, 200, jQuery('#customfield-' + key + ' .text-editor'), function(){
                showCustomfieldBlock(key);
            });
        }, 340);
    }
    
    function showCustomfieldBlock(id){
        showCustomfieldMask();
        jQuery('#customfield-' + id).show(300);
    }
    
    function showCustomfieldMask(){
        jQuery('#customfieldsShadow').show();
    }
    
    function updateCustomfield(){
        listAllCustomFields();
     	hideAllCustomfieldBlocks();
    }
    
    
    function hideAllCustomfieldBlocks(){
        jQuery('#customfieldsBlock').children().each(function(){
            hideCustomfieldBlock(jQuery(this));
        });
    }
    
    function hideCustomfieldBlock(block){
        block.hide(300, hideCustomfieldMask);
    }
    
    function hideCustomfieldMask(){
        jQuery('#customfieldsShadow').hide();
    }
    
    function loadTextEditor(name, value, width, height, blockToLoadIn, callBack){
        jQuery.ajax({
            url: '?option=com_deallab&task=ajaxReturnEditor',
            type: 'POST',
            data: {'name': name, 'value': value, 'width': width, 'height': height}
        })
        .done(function(editorHTML){
            blockToLoadIn.html(editorHTML);
            loadEditor();
            callBack();
        });
    }
    
    function removeCustomfieldBlock(id){
    	jQuery('#customfieldInList-'+id).remove();
        jQuery('#customfield-' + id).remove();
    }
</script>
<?php
echo "<script type='text/javascript'>
function loadEditor(){";
echo $doc->_script["text/javascript"];
echo "}
</script>";
?>