<?php
/**
 * @version		$Id: default.php 2013-07-17 studio4 $
 * @package		DealLab Cities Module
 * @author		Studio4 http://www.studio4.lt
 * @copyright	Copyright (c) 2010 - 2013 UAB Studio4. All rights reserved.
 * @license		GNU/GPL license: http://shop.studio4.lt/deallab-license
 */

// no direct access
defined('_JEXEC') or die;
?>
<div class="deallab-cities">
	<ul class="nav cities-menu">
		<li class="dropdown">
			<a class="dropdown-toggle" data-toggle="dropdown" href="#"><?php echo ($currentCity == 0)?'Choose the city':$cities[$currentCity]->title; ?></a>
			<ul class="dropdown-menu">
				<?php foreach ($cities as $city) : ?>
					<?php $link = JRoute::_('index.php?option=com_deallab&task=deal&city_id='.$city->id.':'.$city->alias); ?>
					<li <?php echo ($city->id == $currentCity)?'class="active"':''; ?>><a href="<?php echo $link; ?>"'><?php echo $city->title; ?></a></li>
				<?php endforeach; ?>
    		</ul>
  		</li>
	</ul>
</div>