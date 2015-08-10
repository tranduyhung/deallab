<?php
/**
 * @version		$Id: default.php 2013-07-17 studio4 $
 * @package		DealLab
 * @author		Studio4 http://www.studio4.lt
 * @copyright	Copyright (c) 2010 - 2013 UAB Studio4. All rights reserved.
 * @license		GNU/GPL license: http://shop.studio4.lt/deallab-license
 */

defined('_JEXEC') or die;

$statuses = array(
	0 => 'Waiting for payment',
	1 => 'Payed',
	2 => 'Cancelled'
);

if (count($this->orders) == 0) :
	echo JText::_('DEALLAB_NO_COUPONS_PURCHASED');
else : ?>
	<?php foreach ($this->orders as $key => $order) : ?>
		<div class="my-order">
			<h4 class="deallab-deal-title"><?php echo $order['title']; ?><br /><span><?php echo $order['option']; ?></span></h4>
			<span class="order-number">Order #<?php echo $key; ?></span>
			<span class="order-date">Date: <?php echo $order['cdate']; ?></span>
			<div class="clr"></div>
			<div class="coupons-list">
				<h3>Coupon codes:</h3>
				<span><?php echo implode(', ', $order['coupons']); ?></span>
			</div>
		</div>
	<?php endforeach; ?>
<?php endif; ?>