<?php
/**
 * @version		$Id: default_contact.php 2013-07-17 studio4 $
 * @package		DealLab
 * @author		Studio4 http://www.studio4.lt
 * @copyright	Copyright (c) 2010 - 2013 UAB Studio4. All rights reserved.
 * @license		GNU/GPL license: http://shop.studio4.lt/deallab-license
 */

// no direct access
defined('_JEXEC') or die;

$dispatcher	= JDispatcher::getInstance();
JPluginHelper::importPlugin('deallab');
$plugins = $dispatcher->trigger('paymentPluginList', array()); 
?>
<h3 class="title-block"><?php echo JText::_('DEALLAB_PAYMENT_SELECT_METHOD'); ?></h3>
<div class="inner">
	<?php if (count($plugins) > 0) : ?>
	<select id="payment-plugin" name="order[payment_plugin]">
		<?php foreach ($plugins as $plugin) :
			echo '<option value="'.$plugin[0].'">'.$plugin[1].'</option>';
		endforeach; ?>	
	</select>
	<?php else : ?>
		<p><?php echo JText::_('DEALLAB_NO_PAYMENT_PLUGINS'); ?></p>
	<?php endif; ?>
</div>