<?php
/**
 * @version		$Id: default_contact.php 2013-07-17 studio4 $
 * @package		DealLab
 * @author		Studio4 http://www.studio4.lt
 * @copyright	Copyright (c) 2010 - 2013 UAB Studio4. All rights reserved.
 * @license		GNU/GPL license: http://shop.studio4.lt/deallab-license
 */

// no direct access
defined('_JEXEC') or die; ?>
<script src="media/deallab/assets/js/jquery.validate.min.js" type="text/javascript"></script>
<div id="deallab-checkout">
	<form id="checkout-form" name="checkout-form" method="POST" action="index.php">
		<div class="order-details">
			<?php echo $this->loadTemplate('order_details'); ?>
		</div>
		<div class="account-details">
			<?php echo $this->loadTemplate('customer_details'); ?>
		</div>
		<?php if ($this->gift) : ?>
		<div class="gift-details">
			<?php echo $this->loadTemplate('gift_details'); ?>
		</div>
		<?php endif; ?>
		<div class="payment-methods">
			<?php echo $this->loadTemplate('payment_methods'); ?>
		</div>
		<div class="inner">
			<?php if ($this->params->get('show_tems')) : ?>
				<input type="checkbox" id="rules_agree" name="rules_agree" <?php echo ($this->params->get('must_agree'))?'class="required"':''; ?> />
				<a href="#" onclick="jQuery('#termsModal').modal('show'); return false;"><?php echo JText::_('DEALLAB_TERMS_AGREE'); ?></a>
				<label for="rules_agree" generated="true" class="error" style="display: block;"></label>
			<?php endif; ?>
			<input class="buy-button" type="submit" name="submit" value="<?php echo JText::_('DEALLAB_BUTTON_COMPLETE_ORDER'); ?>" />
			<div class="clearfix"></div>
		</div>
		
		<input type="hidden" name="order[deal_id]" value="<?php echo $this->deal->id; ?>" />
		<input type="hidden" name="order[gift]" value="<?php echo $this->gift; ?>" />
		<input type="hidden" name="order[currency]" value="<?php echo $this->params->get('currency_code'); ?>" />
		<input type="hidden" name="option" value="com_deallab" />
		<input type="hidden" name="task" value="checkout" />
	</form>
</div>
<script type="text/javascript">
	jQuery(document).ready(function(){
		toggleShipping();
		
		jQuery('#checkout-form').validate();
		
		<?php /* Initial basic rules */ ?>
		
		jQuery('#amount').rules('add', {
 			required: true,
 			digits: true,
 			min: 1
		});
		
		jQuery('#option_id').rules('add', {
 			required: true,
 			digits: true,
 			min: 1
		});
		
		jQuery('#amount').rules('add', {
 			max: 100
		});
		
		jQuery('#email').rules('add', {
 			required: true,
 			email: true
		});
		
		<?php /* Basic rules END */ ?>
		
		<?php if ($this->params->get('show_tems')) : ?>
		jQuery('#termsModal').modal({
  			backdrop: true,
  			keyboard: true,
  			show: false
  		}).css({
  			width: '820px',
  			'margin-left': '-410px'
  		});
		<?php endif; ?>
	});
</script>
<?php if ($this->params->get('show_tems')) : ?>
<div class="modal" id="termsModal" tabindex="-1" role="dialog" aria-labelledby="Terms & Conditions" aria-hidden="true" style="display: none;">
	<div class="modal-header">
    	<h3 id="termsModalLabel"><?php echo JText::_('DEALLAB_FIELDSET_CONFIG_TERMS'); ?></h3>
  	</div>
  	<div class="modal-body">
  		<div>
			<?php echo $this->params->get('terms'); ?>
		</div>
		<div class="controls">
			<input type="button" class="btn btn-primary" value="OK" onclick="jQuery('#termsModal').modal('hide');" />
		</div>
	</div>
</div>
<?php endif; ?>