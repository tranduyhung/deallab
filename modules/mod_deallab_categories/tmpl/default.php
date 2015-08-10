<?php
/**
 * @version		$Id: default.php 2013-07-17 studio4 $
 * @package		DealLab Categories Module
 * @author		Studio4 http://www.studio4.lt
 * @copyright	Copyright (c) 2010 - 2013 UAB Studio4. All rights reserved.
 * @license		GNU/GPL license: http://shop.studio4.lt/deallab-license
 */

// no direct access
defined('_JEXEC') or die;

$city_slug = DealLabModuleHelper::getCitySlug(JRequest::getVar('city_id'));
$cat_slug =  JRequest::getVar('category_id', 0);
$category = explode(':', $cat_slug);
?>
<div class="deallab-categories">
	<ul class="nav cities-menu">
		<li class="dropdown">
			<a class="dropdown-toggle" data-toggle="dropdown" href="#"><?php echo ($category[0] == 0)?'Select category':$cats[$category[0]]->title; ?></a>
			<ul class="dropdown-menu">
				<li><a href="<?php echo JRoute::_('index.php?option=com_deallab&task=deal'); ?>"><?php echo JText::_('MOD_DEALLAB_CATEGORIES_ALL'); ?></a></li>
				<?php foreach ($cats as $cat) :
					$deal_slug = DealLabModuleHelper::getCatCityId($cat->id); ?>
					<li <?php echo ($cat->id == $category[0])?'class="active"':''; ?>>
						<a href="<?php echo JRoute::_('index.php?option=com_deallab&task=deal&city_id='.$city_slug.'&category_id='.$cat->id.':'.$cat->alias.'&deal_id='.$deal_slug); ?>"><?php echo $cat->title; ?></a>
					</li>
				<?php endforeach; ?>
    		</ul>
  		</li>
	</ul>
</div>