<?php
/**
 * @version		$Id: default_images.php 2013-07-17 studio4 $
 * @package		DealLab
 * @author		Studio4 http://www.studio4.lt
 * @copyright	Copyright (c) 2010 - 2013 UAB Studio4. All rights reserved.
 * @license		GNU/GPL license: http://shop.studio4.lt/deallab-license
 */

// no direct access
defined('_JEXEC') or die;

$images = dlDealHelper::loadDealImages($this->deal->id);
$height = $this->params->get('large_h');
$width  = $this->params->get('large_w');
?>
<div style="width: <?php echo $width; ?>px; height: <?php echo $height; ?>px; overflow: hidden;">
	<ul id="main-deal-images">
	<?php foreach ($images as $key => $image) : ?>
		<li>
			<img src="media/deallab/images/large_<?php echo $image->original; ?>" width="<?php echo $width; ?>" height="<?php echo $height; ?>" />
		</li>
	<?php endforeach; ?> 
	</ul>	
</div>