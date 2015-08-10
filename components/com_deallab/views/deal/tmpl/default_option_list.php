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

$options = dlDealHelper::loadOptionList($this->deal->id, true); 
?>
<?php if (count($options) > 1) : ?>
	<h5><?php echo JText::_('DEALLAB_DEAL_OPTIONS_TITLE'); ?></h5>
	<ul class="option-list">
		<?php foreach ($options as $option) : ?>
			<li><b class="color1"><?php echo dlDealHelper::renderPrice($option->price);?></b> <?php echo $option->title; ?></li>
		<?php endforeach; ?>
	</ul>
<?php endif; ?>