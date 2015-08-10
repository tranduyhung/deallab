<?php
/**
 * @version		$Id: default.php 2013-07-17 studio4 $
 * @package		DealLab
 * @author		Studio4 http://www.studio4.lt
 * @copyright	Copyright (c) 2010 - 2013 UAB Studio4. All rights reserved.
 * @license		GNU/GPL license: http://shop.studio4.lt/deallab-license
 */

// no direct access
defined('_JEXEC') or die;

if (isset($this->sales)) : ?>
	<?php if (count($this->sales) > 0) : ?>
		<h1><?php echo JText::_('DEALLAB_SALES_COUPONS_LIST'); ?></h1>
		<?php foreach ($this->sales as $coupon): ?>
			<h5><?php echo $coupon->code; ?></h5>
		<?php endforeach; ?>
	<?php else : ?>
		<h3><?php echo JText::_('DEALLAB_SALES_NO_SALES'); ?></h3>
	<?php endif; ?>
<?php else : ?>
	<h3><?php echo JText::_('DEALLAB_DEAL_NOT_FOUND'); ?></h3>
<?php endif; ?>