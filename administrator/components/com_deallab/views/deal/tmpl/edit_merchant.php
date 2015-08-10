<?php
/**
 * @version		$Id: edit_merchant.php 2013-07-17 studio4 $
 * @package		DealLab
 * @author		Studio4 http://www.studio4.lt
 * @copyright	Copyright (c) 2010 - 2013 UAB Studio4. All rights reserved.
 * @license		GNU/GPL license: http://shop.studio4.lt/deallab-license
 */

// no direct access
defined('_JEXEC') or die;

$fieldSet = $this->form->getFieldset('merchant-info');
?>
<legend><?php echo JText::_('DEALLAB_FIELDSET_MERCHANT_INFO'); ?></legend>
<ul class="adminformlist">
	<?php foreach ($fieldSet as $field) : ?>
		<li>
			<?php echo $field->label; ?>
			<?php echo $field->input; ?>
		</li>
	<?php endforeach; ?>
	<?php if (($this->item->id > 0) && ($this->item->merchant_email != '')) : ?>
		<li>
			<a href="#" onclick="Joomla.submitbutton('deal.preview');" class="right"><?php echo JText::_('DEALLAB_BUTTON_PREVIEW_SEND'); ?></a>
			<div class="clr"></div>
		</li>
	<?php endif; ?>
</ul>