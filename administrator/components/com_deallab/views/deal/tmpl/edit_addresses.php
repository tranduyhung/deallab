<?php
/**
 * @version		$Id: edit_addresses.php 2013-07-17 studio4 $
 * @package		DealLab
 * @author		Studio4 http://www.studio4.lt
 * @copyright	Copyright (c) 2010 - 2013 UAB Studio4. All rights reserved.
 * @license		GNU/GPL license: http://shop.studio4.lt/deallab-license
 */

// no direct access
defined('_JEXEC') or die;
require_once(JPATH_COMPONENT_ADMINISTRATOR . '/helpers/deal.php');
$addresses = dlDataHelper::getDealData($this->item->id, 'addresses', '*', null, 'ordering');
$fieldSet = $this->form->getFieldset('deal-shipping');
?>
<div class="address-labels">
	<div class="w150"><?php echo JText::_('DEALLAB_LABEL_TITLE'); ?></div>
	<div class="w150"><?php echo JText::_('DEALLAB_LABEL_ADDRESS'); ?></div>
	<div class="w200"><?php echo JText::_('DEALLAB_LABEL_ADDITIONAL_INFO'); ?></div>
	<div class="clr"></div>
</div>
<ul id="address-list">
	<?php if (count($addresses) > 0) :
		foreach ($addresses as $key => $address) : ?>
			<li class="ui-state-default" id="address-<?php echo $key; ?>">
				<div class="adminformlist deallab-addresslist">
					<div class="w150"><input class="required" type="text" name="addrs[<?php echo $key; ?>][title]" value="<?php echo $address->title; ?>" /></div>
					<div class="w150"><input class="required" type="text" id="address<?php echo $key; ?>" name="addrs[<?php echo $key; ?>][address]" value="<?php echo $address->address; ?>" /></div>
					<div class="w200"><input type="text" class="inputbox" name="addrs[<?php echo $key; ?>][info]" value="<?php echo $address->info; ?>" /></div>
					<div class="right">
						<?php if (($address->lat != 0) && ($address->lng != 0)) : ?>
							<div style="margin-right: 100px;">
								<?php echo dlDealHelper::renderMapImage($address, 'pickLocation', $key); ?>
							</div>
						<?php else: ?>
							<input style="margin-right: 100px;" type="button" value="<?php echo JText::_('DEALLAB_BUTTON_PICK_COORDINATES'); ?>" onclick="pickLocation(<?php echo $key; ?>);" />
						<?php endif; ?>
						<a href="#" class="removeRow" onclick="jQuery('#address-<?php echo $key; ?>').remove(); return false;"></a>
						<a href="#" class="dragRow" onclick="return false;"></a>
					</div>
					<input type="hidden" name="addrs[<?php echo $key; ?>][id]" value="<?php echo $address->id; ?>" />
					<input type="hidden" class="ordering" name="addrs[<?php echo $key; ?>][ordering]" value="<?php echo $address->ordering; ?>" />
					<input type="hidden" id="lat<?php echo $key; ?>" name="addrs[<?php echo $key; ?>][lat]" value="<?php echo $address->lat; ?>" />
					<input type="hidden" id="lng<?php echo $key; ?>" name="addrs[<?php echo $key; ?>][lng]" value="<?php echo $address->lng; ?>" />
					<div class="clr"></div>					
				</div>
			</li>
		<?php endforeach;
	endif; ?>
</ul>
<input type="button" class="right" onclick="addAddress();" value="<?php echo JText::_('DEALLAB_BUTTON_ADD_LOCATION'); ?>" />
<div class="clr"></div>
<ul>
	<li>
		<?php echo $this->form->getLabel('use_shipping'); ?>
		<?php echo $this->form->getInput('use_shipping'); ?>
	</li>
</ul>
<div class="clr"></div>
<div id="shipping-info" style="display: none;">
	<fieldset>
		<legend><?php echo JText::_('DEALLAB_LABEL_SHIPPING_DETAILS'); ?></legend>
		<ul>
			<?php foreach ($fieldSet as $name => $field) : ?>
				<li><?php echo $field->label; ?>
				<?php echo $field->input; ?>
				</li>
			<?php endforeach; ?>
		</ul>
	</fieldset>
</div>
<div id="picker" title="Location picker">
	<label>Address : </label> <input id="addresspicker_map" />
	<input type="hidden" id="lat" val="">
	<input type="hidden" id="lng" val="">
	<input type="hidden" id="currentKey" val="" />
	<div id="map" style="width: 400px; height: 250px;"></div>
</div>
<script type="text/javascript">
	jQuery(document).ready(function(){
		jQuery("#picker").dialog({
			autoOpen: false,
			modal: true,
			width: 430,
			buttons: {
				"Pick location": function(){
					var lat = jQuery('#lat').val();
					var lng = jQuery('#lng').val();
					var key = jQuery('#currentKey').val();
					jQuery('#lat'+key).val(lat);
					jQuery('#lng'+key).val(lng);
					jQuery(this).dialog('close');
				}
			}	
		});
	});
</script>
<script type="text/javascript">
	function pickLocation(key) {
		jQuery('#currentKey').val('');
		jQuery('#lat').val('');
		jQuery('#lng').val('');
		jQuery('#addresspicker_map').val('');
		
		var addresspickerMap = null;
		var addrInput = jQuery('#address'+key);
		var addrVal = jQuery(addrInput).val();
		var addrLat = jQuery('#lat'+key).val();
		var addrLng = jQuery('#lng'+key).val();
		
		if (!addrLat)
			addrLat = 0;
		if (!addrLng)
			addrLng = 0;
			 
		jQuery('#currentKey').val(key);
		jQuery('#lat').val(addrLat);
		jQuery('#lng').val(addrLng);
		jQuery('#addresspicker_map').val(addrVal);
		
		var addresspickerMap = jQuery( "#addresspicker_map" ).addresspicker({
		  elements: {
		    map:      "#map",
		    lat:      "#lat",
		    lng:      "#lng",
		    locality: '#locality',
		    country:  '#country'
		  }
		});
		
		var gmarker = addresspickerMap.addresspicker("marker");
		gmarker.setVisible(true);
		addresspickerMap.addresspicker("reloadPosition");
		
		jQuery('#picker').dialog('open');
	}
</script>
<script type="text/javascript">
	function toggleShipping(val) {
		if (val == 1) {
			jQuery('#shipping-info').show();
		} else {
			jQuery('#shipping-info').hide();
		}
		
	}
</script>
<script type="text/javascript">
	var counter = <?php echo count($addresses); ?>;
    
	function addAddress() {
		var output = '';
		output += '<li class="ui-state-default" id="address-'+counter+'"><div class="adminformlist deallab-addresslist">';
		output += '<div class="w150"><input class="required" type="text" name="addrs['+counter+'][title]" value="" /></div>';
		output += '<div class="w150"><input class="required" id="address'+counter+'" type="text" name="addrs['+counter+'][address]" value="" /></div>';
		output += '<div class="w200"><input type="text" class="inputbox" name="addrs['+counter+'][info]" value="" /></div>';
		output += '<div class="right">';
		<?php /* output += '<input type="button" value="<?php echo JText::_('DEALLAB_BUTTON_MAP'); ?>" onclick="jQuery(this).parent().parent().remove();" />'; */ ?>
		output += '<input style="margin-right: 100px;" type="button" value="<?php echo JText::_('DEALLAB_BUTTON_PICK_COORDINATES'); ?>" onclick="pickLocation('+counter+');" />';
		output += '<a href="#" class="removeRow" onclick="jQuery(\'#address-'+counter+'\').remove(); return false;"></a>';
		output += '<a href="#" class="dragRow" onclick="return false;"></a>';
		output += '</div>';
		output += '<input type="hidden" name="addrs['+counter+'][id]" value="" />';
		output += '<input type="hidden" class="ordering" name="addrs['+counter+'][ordering]" value="'+counter+'" />';
		output += '<input type="hidden" name="addrs['+counter+'][lat]" value="" />';
		output += '<input type="hidden" name="addrs['+counter+'][lng]" value="" />';
		output += '</div><div class="clr"></div></li>';
		counter += 1;
		jQuery('#address-list').append(output);		
	}
	<?php /* if (count($addresses) == 0) : ?>
		addAddress();
	<?php endif; */ ?>
	toggleShipping(<?php echo $this->item->use_shipping; ?>);
</script>