<?php
/**
 * @version		$Id: edit_prices.php 2013-07-17 studio4 $
 * @package		DealLab
 * @author		Studio4 http://www.studio4.lt
 * @copyright	Copyright (c) 2010 - 2013 UAB Studio4. All rights reserved.
 * @license		GNU/GPL license: http://shop.studio4.lt/deallab-license
 */

// no direct access
defined('_JEXEC') or die;

$prices = dlDataHelper::getDealData($this->item->id, 'prices', '*', null, 'ordering');
?>
<div class="price-labels">
	<div class="w100"><?php echo JText::_('DEALLAB_LABEL_DEAL_PRICE'); ?></div>
	<div class="w100"><?php echo JText::_('DEALLAB_LABEL_DEAL_VALUE'); ?></div>
	<div class="w260"><?php echo JText::_('DEALLAB_LABEL_OPTION_TITLE'); ?></div>
	<div class="w100"><?php echo JText::_('DEALLAB_LABEL_OPTION_STOCK'); ?></div>
	<div class="clr"></div>
</div>
<ul id="prices-list">
	<?php if (count($prices) > 0 ) :
		$i = 1;
		foreach ($prices as $key => $row) : ?>
			<li class="ui-state-default">
				<div class="adminformlist deallab-pricelist">			
					<div class="w100">					
						<input name="prices[<?php echo $key; ?>][price]" class="inputbox required" size="5" value="<?php echo $row->price; ?>">
					</div>	
					<div class="w100">
						<input name="prices[<?php echo $key; ?>][value]" class="inputbox required" size="5" value="<?php echo $row->value; ?>">
					</div>	
					<div class="w260">
						<input name="prices[<?php echo $key; ?>][title]" id="jform_opt_title-<?php echo $key; ?>" class="inputbox required" size="40" value="<?php echo $row->title; ?>">
					</div>
					<div class="w100">
						<input name="prices[<?php echo $key; ?>][max_coupons]" id="jform_inventory-<?php echo $key; ?>" class="inputbox required" size="5" value="<?php echo $row->max_coupons; ?>">
					</div>
					<a href="#" class="removeRow" onclick="jQuery(this).parent().parent().remove(); return false;"></a>
					<a href="#" class="dragRow" onclick="return false;"></a>
					<input type="hidden" name="prices[<?php echo $key; ?>][id]" value="<?php echo $row->id; ?>" />
					<input type="hidden" class="ordering" name="prices[<?php echo $key; ?>][ordering]" value="<?php echo $i; ?>" />
					<div class="clr"></div>
				</div>
			</li>
			<?php $i++; 
		endforeach;
	endif; ?>
</ul>
<div class="clr"></div>
<input type="button" class="right" onclick="addPrices();" value="<?php echo JText::_('DEALLAB_BUTTON_ADD_OPTION'); ?>" />
<div class="clr"></div>
<ul>
	<li><?php echo $this->form->getLabel('min_coupons'); ?>
	<?php echo $this->form->getInput('min_coupons'); ?></li>
</ul>
<script type="text/javascript">
	var counter = <?php echo count($prices); ?>;
	function addPrices() {
		counter += 1;
		var output = '';
		output += '<li class="ui-state-default"><div class="adminformlist deallab-pricelist">';
		output += '<a href="#" class="removeRow" onclick="jQuery(this).parent().parent().remove(); return false;"></a><a href="#" class="dragRow" onclick="return false;"></a>';
		output += '<div class="w100"><input name="prices['+counter+'][price]" class="inputbox required" size="5" value=""></div>';
		output += '<div class="w100"><input name="prices['+counter+'][value]" class="inputbox required" size="5" value=""></div>';
		output += '<div class="w260"><input name="prices['+counter+'][title]" id="jform_opt_title-'+counter+'" class="inputbox required" size="40" value=""></div>';
		output += '<div class="w100"><input name="prices['+counter+'][max_coupons]" id="jform_inventory-'+counter+'" class="inputbox required" size="5" value=""></div>';
		output += '<input type="hidden" name="prices['+counter+'][id]" value="" />';
		output += '<input type="hidden" class="ordering" name="prices['+counter+'][ordering]" value="'+counter+'" />';
		output += '<div class="clr"></div>';
		output += '</div></li>';
		
		jQuery('#prices-list').append(output);
	}
	
	<?php if (count($prices) == 0 ) : ?>
		addPrices();
	<?php endif; ?>
</script>