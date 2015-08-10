<?php
/**
 * @version		$Id: default_gift_details.php 2013-07-17 studio4 $
 * @package		DealLab
 * @author		Studio4 http://www.studio4.lt
 * @copyright	Copyright (c) 2010 - 2013 UAB Studio4. All rights reserved.
 * @license		GNU/GPL license: http://shop.studio4.lt/deallab-license
 */

// no direct access
defined('_JEXEC') or die;

$deal_options = dlDealHelper::loadOptionList($this->deal->id, true);
?>
<h3 class="title-block"><?php echo JText::_('DEALLAB_GIFT_DETAILS'); ?></h3>
<div class="inner">
	<div class="block" style="margin-right: 20px;">
		<label><?php echo JText::_('DEALLAB_GIFT_FRIEND_NAME'); ?></label>
		<input type="text" class="inputbox" name="order[gift_name]" value="" />
	</div>
	<div class="block">
		<label><?php echo JText::_('DEALLAB_GIFT_FRIEND_EMAIL'); ?></label>
		<input type="text" class="inputbox" name="order[gift_email]" value="" />
	</div>
	<div class="clearfix"></div>
	<div class="block">
		<label><?php echo JText::_('DEALLAB_GIFT_FRIEND_MESSAGE'); ?></label>
		<textarea style="width: 100%;" class="textarea" name="order[gift_message]"></textarea>
	</div>
	<div class="clearfix"></div>
</div>