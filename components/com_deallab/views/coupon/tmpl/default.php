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

$doc = JFactory::getDocument(); 
$doc->addStyleSheet(JUri::root() . '/media/deallab/assets/css/coupon.css');

$config = JFactory::getConfig();
?>
<a href="#" onclick="window.print(); return false;"><?php echo JText::_('DEALLAB_BUTTON_PRINT'); ?></a>
<div id="deallab-coupon">
	<h1><?php echo $this->item->deal_title; ?></h1>
	<h2><?php echo $this->item->option_title; ?></h2>
	<h2 class="coupon-code"><?php echo JText::sprintf('DEALLAB_COUPON_CODE', $this->item->code); ?></h2>
	<div>
		<?php if ($this->item->address_id == -1) : ?>
			<h4><?php echo JText::_("DEALLAB_COUPON_SHIPPING"); ?></h4>
			<?php $details = json_decode($this->item->details, true); 
			echo implode(', ', $details); ?>
		<?php else: ?>
			<h4><?php echo JText::_("DEALLAB_COUPON_REDEMPTION"); ?></h4>
			<p style="margin-left: 50px;"><?php echo $this->item->address_title; ?>, <?php echo $this->item->address; ?></p>
		<?php endif; ?>
	</div>
	<p style="margin-top: 30px;"><?php echo JText::_("DEALLAB_THANKS_FOR_BUYING"); ?> <a href="<?php echo JUri::root(); ?>"><?php echo $config->getValue( 'config.sitename' ); ?></a></p>
</div>
