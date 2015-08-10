<?php
/**
 * @version		$Id: default_customer_details.php 2013-07-17 studio4 $
 * @package		DealLab
 * @author		Studio4 http://www.studio4.lt
 * @copyright	Copyright (c) 2010 - 2013 UAB Studio4. All rights reserved.
 * @license		GNU/GPL license: http://shop.studio4.lt/deallab-license
 */

// no direct access
defined('_JEXEC') or die;

$addresses 	= dlDealHelper::loadAddressList($this->deal->id, true);
$user 		= JFactory::getUser();
$history	= false;

if ($this->deal->params)
	$params = json_decode($this->deal->params);
else
	$params = null;

if (!$user->guest) {
	$db = JFactory::getDBO();
	$q = 'SELECT details FROM #__deallab_orders WHERE user_id = ' . $user->id . ' ORDER BY id DESC LIMIT 0, 1';
	$db->setQuery($q);
	$result = $db->loadResult();
	$history = json_decode($result);
}
?>
<h3 class="title-block"><?php echo JText::_('DEALLAB_FIELDSET_ORDER_INFO'); ?></h3>
<div id="address-fields" class="inner">
	<?php if ((count($addresses) > 1) || ((count($addresses) >= 0)) && $this->deal->use_shipping) : ?>
		<div>
			<label><?php echo JText::_('DEALLAB_PLACEHOLDER_ADDRESS'); ?></label>
			<select class="inputbox" id="order-address" name="order[address_id]" onchange="toggleShipping();">
				<?php foreach($addresses as $address) : ?>
					<option value="<?php echo $address->id; ?>"><?php echo $address->title; ?></option>					
				<?php endforeach; ?>
				<?php if ($this->deal->use_shipping) : ?>
					<option value="-1"><?php echo JText::_('DEALLAB_OPTIONS_SHIP_TO_ADDRESS'); ?></option>
				<?php endif; ?>
			</select>
			<div class="clearfix"></div>
		</div>
	<?php else: ?>
		<input type="hidden" name="order[address_id]" value="<?php echo $addresses[0]->id; ?>" />
	<?php endif; ?>
	<div>
		<label><?php echo JText::_('DEALLAB_PLACEHOLDER_EMAIL'); ?><span class="req-span">*</span></label>
		<input type="text" id="email" class="inputbox required email" name="order[email]" value="<?php echo (!$user->guest)?$user->email:''; ?>" />
		<div class="clearfix"></div>
		<label for="email" generated="true" class="error" style="display: none;"></label>
	</div>
	<?php if ($this->gift == 1) : ?>	
		<div>
			<label><?php echo JText::_('DEALLAB_PLACEHOLDER_NAME'); ?><span class="req-span">*</span></label>
			<input type="text" class="inputbox required" name="order[name]" rel="name" value="<?php echo isset($history->name)?$history->name:''; ?>" />
			<div class="clearfix"></div>
			<label for="order[name]" generated="true" class="error"></label>
		</div>
	<?php endif; ?>
	<?php if ($this->deal->use_shipping == 1) : ?>
		<div id="required-fields" style="display: none;">
			<?php if (($this->deal->shipping->customer_name == 1) && ($this->gift != 1)) : ?>	
				<div>
					<label><?php echo JText::_('DEALLAB_PLACEHOLDER_NAME'); ?><span class="req-span">*</span></label>
					<input type="text" class="inputbox required" name="order[name]" rel="name" value="<?php echo isset($history->name)?$history->name:''; ?>" />
					<div class="clearfix"></div>
					<label for="order[name]" generated="true" class="error"></label>
				</div>
			<?php endif; ?>
			<?php if ($this->deal->shipping->customer_address == 1) : ?>
				<div>
					<label><?php echo JText::_('DEALLAB_PLACEHOLDER_ADDRESS'); ?><span class="req-span">*</span></label>
					<input type="text" class="inputbox required" name="order[address]" rel="address" value="<?php echo isset($history->address)?$history->address:''; ?>" />
					<div class="clearfix"></div>
					<label for="order[address]" generated="true" class="error"></label>
				</div>
			<?php endif; ?>
			<?php if ($this->deal->shipping->customer_city == 1) : ?>
				<div>
					<label><?php echo JText::_('DEALLAB_PLACEHOLDER_CITY'); ?><span class="req-span">*</span></label>
					<input type="text" class="inputbox required" name="order[city]" rel="city" value="<?php echo isset($history->city)?$history->city:''; ?>" />
					<div class="clearfix"></div>
					<label for="order[city]" generated="true" class="error"></label>
				</div>
			<?php endif; ?>
			<?php if ($params->zip == 1) : ?>
				<div>
					<label><?php echo JText::_('DEALLAB_PLACEHOLDER_ZIP'); ?><span class="req-span">*</span></label>
					<input type="text" class="inputbox required" name="order[zip]" rel="zip" value="<?php echo isset($history->zip)?$history->zip:''; ?>" />
					<div class="clearfix"></div>
					<label for="order[zip]" generated="true" class="error"></label>
				</div>
			<?php endif; ?>
			<?php if ($this->deal->shipping->customer_phone == 1) : ?>
				<div>
					<label><?php echo JText::_('DEALLAB_PLACEHOLDER_PHONE'); ?><span class="req-span">*</span></label>
					<input type="text" class="inputbox required" name="order[phone]" rel="phone" value="<?php echo isset($history->name)?$history->phone:''; ?>" />
					<div class="clearfix"></div>
					<label for="order[phone]" generated="true" class="error"></label>
				</div>
			<?php endif; ?>
		</div>
	<?php endif; ?>
	<input type="hidden" id="use_shipping" name="use_shipping" value="0" />
	<input type="hidden" id="shipping_price" name="shipping_price" value="<?php echo $this->deal->shipping->shipping_price; ?>" />
	<input type="hidden" id="shipping_type" name="shipping_type" value="<?php echo $this->deal->shipping->price_type; ?>" />
	<input type="hidden" id="cash_on_delivery" name="cash_on_delivery" value="<?php echo $this->deal->shipping->on_delivery; ?>" />
</div>
<script type="text/javascript">
	function toggleShipping()
	{
		var id = jQuery('#order-address').val();
		if (id == '-1') {
			jQuery('#required-fields').show();
			jQuery('#use_shipping').val(1);
		} else {
			jQuery('#required-fields').hide();
			jQuery('#use_shipping').val(0);
		}
		updatePrice();
	}
</script>