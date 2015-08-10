<?php
/**
 * @version		$Id: edit_params.php 2013-07-17 studio4 $
 * @package		DealLab
 * @author		Studio4 http://www.studio4.lt
 * @copyright	Copyright (c) 2010 - 2013 UAB Studio4. All rights reserved.
 * @license		GNU/GPL license: http://shop.studio4.lt/deallab-license
 */

// no direct access
defined('_JEXEC') or die;
 
echo JHtml::_('sliders.panel', 'Additional options', 'deal-options'); 
$fieldSet = $this->form->getFieldset('deal-options'); ?>
<fieldset class="panelform">
	<ul class="adminformlist">
		<?php foreach ($fieldSet as $field) : ?>
			<li>
				<?php echo $field->label; ?>
				<?php echo $field->input; ?>
			</li>
		<?php endforeach; ?>
	</ul>
</fieldset>