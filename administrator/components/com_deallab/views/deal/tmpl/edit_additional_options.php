<?php
/**
 * @version		$Id: edit_additional_options.php 2013-07-17 studio4 $
 * @package		DealLab
 * @author		Studio4 http://www.studio4.lt
 * @copyright	Copyright (c) 2010 - 2013 UAB Studio4. All rights reserved.
 * @license		GNU/GPL license: http://shop.studio4.lt/deallab-license
 */

// no direct access
defined('_JEXEC') or die;

$fieldSet = $this->form->getFieldset('jparams');

echo JHtml::_('sliders.panel', JText::_('DEALLAB_FIELDSET_ADDITIONAL_OPTIONS'), 'additional-options'); ?>
<fieldset class="panelform">
	<ul class="adminformlist">
		<?php foreach ($fieldSet as $name => $field) : ?>
			<li><?php echo $field->label; ?>
			<?php echo $field->input; ?></li>
		<?php endforeach; ?>
	</ul>
</fieldset>