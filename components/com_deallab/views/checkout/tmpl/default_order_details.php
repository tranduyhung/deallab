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

$deal_options = dlDealHelper::loadOptionList($this->deal->id, true);
?>
<h3 class="title-block"><?php echo JText::_('DEALLAB_ORDER_DETAILS'); ?></h3>
<div class="inner">
	<div class="block" style="width: 290px;">
		<label>Deal</label>
		<?php if (count($deal_options) > 1) : ?>
			<select id="option_id" name="order[option_id]" onchange="updatePrice();">
				<?php foreach($deal_options as $opt)
					echo '<option value="'.$opt->id.'">'.$opt->title.'</option>'; ?>
			</select>
		<?php else : ?>
			<input type="hidden" id="option_id" name="order[option_id]" value="<?php echo $deal_options[0]->id; ?>" />
			<h5><?php echo $deal_options[0]->title; ?></h5>
		<?php endif; ?>
	</div>
	<div class="block" style="width: 120px; text-align: center;">
		<label><?php echo JText::_('DEALLAB_LABEL_QTY'); ?></label>
		<input type="text" id="amount" class="inputbox" name="order[amount]" value="1" onkeyup="updatePrice();" />
		<label for="amount" generated="true" class="error" style="display: none; margin-left: 0px;"></label>
	</div>
	<div class="block" style="width: 60px; padding-left: 50px;">
		<label><?php echo JText::_('DEALLAB_LABEL_PRICE'); ?></label>
		<span id="item-price"></span>
	</div>
	<div class="block" style="padding-left: 40px; width: 50px; text-align: center;">
		<label><?php echo JText::_('DEALLAB_LABEL_TOTAL'); ?></label>
		<span id="total-price"></span>
	</div>
	<div class="clearfix"></div>
</div>
<script type="text/javascript">
	var currency = '<?php echo $this->params->get('currency_symbol'); ?>';
	var currencyPosition = <?php echo $this->params->get('currency_position'); ?>;
	
	var opts = new Array();
	<?php foreach ($deal_options as $opt) : ?>
		opts[<?php echo $opt->id; ?>] = new Array();
		opts[<?php echo $opt->id; ?>]['price'] = <?php echo $opt->price; ?>;
		opts[<?php echo $opt->id; ?>]['cpn_left'] = <?php echo ($opt->max_coupons - $opt->couponsSold); ?>;
	<?php endforeach; ?>
	
	function updatePrice() 
	{
		jQuery('#checkout-form').validate();
		
		var id = jQuery('#option_id').val();
		var amount = jQuery('#amount').val();
		
		var itemPrice = opts[id]['price'];
		
		if (jQuery('#use_shipping').val() == 1) {
			var shippingPrice = jQuery('#shipping_price').val();
			if (jQuery('#shipping_type').val() == 1) {
				var totalPrice = (itemPrice * amount) + parseFloat(shippingPrice);
			} else {
				var totalPrice = (itemPrice + parseFloat(shippingPrice)) * amount;
			}
			if (jQuery('#cash_on_delivery').val() == 1) {
				jQuery('#payment-plugin').append('<option value="cash-on-delivery">Cash on Delivery</option>');
			}
		} else {
			var totalPrice = itemPrice * amount;
			jQuery('option[value="cash-on-delivery"]').remove();
		}
		
		jQuery('#amount').rules('remove', 'max');
		jQuery('#amount').rules('add', {max: opts[id]['cpn_left']});
		
		if (currencyPosition == 0) {
			var price = itemPrice + ' ' + currency;
			var total = totalPrice + ' ' + currency;
		} else if (currencyPosition == 1) {
			var price =  currency + ' ' + itemPrice;
			var total = currency + ' ' + totalPrice;
		} else {
			var price =  itemPrice;
			var total = totalPrice;
		}
		
		jQuery('#item-price').html(price);
		jQuery('#total-price').html(total);
	}
	
	function qtyChange(dir){
		var id     = jQuery('#option_id').val();
		var amount = jQuery('#amount').val();
		
		if (dir == 'up') {
			if (amount < opts[id]['cpn_left']) {
				amount++;
			} else {
				amount = opts[id]['cpn_left'];
			}
		} else if (dir == 'down') {
			if (amount > 1) {
				amount--;
			} else {
				amount = 1;
			}
		} else {
			return false;
		}
		jQuery('#amount').val(amount);
		updatePrice();
	}
</script>
<?php if (count($deal_options) > 1) : ?>
<div class="modal" id="buyModal" tabindex="-1" role="dialog" aria-labelledby="Select an option:" aria-hidden="true">
	<div class="modal-header">
    	<h3 id="buyModalLabel"><?php echo JText::_('DEALLAB_LABEL_DEAL_TYPE'); ?></h3>
  	</div>
  	<div class="modal-body">
		<ul id="options-list">
			<?php foreach ($deal_options as $key => $opt) : ?>
				<li class="color8">
					<div class="option-title color6"><?php echo $opt->title; ?></div>
					<div class="value">
						<h5><?php echo JText::_('DEALLAB_LABEL_DEAL_VALUE'); ?></h5>
						<span><?php echo dlDealHelper::renderPrice($opt->value); ?></span>
					</div>
					<div class="discount">
						<h5><?php echo JText::_('DEALLAB_LABEL_DEAL_DISCOUNT'); ?></h5>
						<span><?php echo floor((1 - $opt->price / $opt->value) * 100); ?>%</span>
					</div>
					<div class="savings">
						<h5><?php echo JText::_('DEALLAB_LABEL_DEAL_SAVINGS'); ?></h5>
						<span><?php echo dlDealHelper::renderPrice(($opt->value - $opt->price)); ?></span>
					</div>
					<div class="bought">
						<h5><?php echo JText::_('DEALLAB_LABEL_BOUGHT'); ?></h5>
						<span><?php echo $opt->couponsSold; ?></span>
					</div>
					<a class="buy-button" href="#" data-dismiss="modal" onclick="setOption(<?php echo $opt->id; ?>); return false;">
						<span class="title"><?php echo JText::_('DEALLAB_BUTTON_BUY'); ?></span>
						<span class="dl-price"><?php echo dlDealHelper::renderPrice($opt->price); ?></span>
					</a>
					<div class="clearfix"></div>
				</li>
			<?php endforeach; ?>
		</ul>
	</div>
</div>
<script type="text/javascript">
	jQuery(function() {
        jQuery('#buyModal').modal({
  			backdrop: true,
  			keyboard: true,
  			show: true,
  		}).css({
  			width: '820px',
  			'margin-left': '-410px'
  		});
  		
  		updatePrice();
	});
</script>
<script type="text/javascript">
	function setOption(id) {
		jQuery('#option_id').val(id);
		updatePrice();
		jQuery('#deallab-checkout-modal').modal('close');
	}
</script>
<?php endif; ?>