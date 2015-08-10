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

<?php if (count($images)) : ?>
	<div id="main-deal-images" style="width: <?php echo $width; ?>px; height: <?php echo $height; ?>px; overflow: hidden;">
		<?php foreach ($images as $key => $image) : ?>
			<img src="media/deallab/images/large_<?php echo $image->original; ?>" class="img-<?php echo $key; ?>" width="<?php echo $width; ?>" height="<?php echo $height; ?>" />
		<?php endforeach; ?> 
	</div>
	<script type="text/javascript">
		var currentImage = 0,
			count = 0,
			images = Array();
		
		jQuery(document).ready(function(){
			var images = jQuery('#main-deal-images').children();
			count = images.length;
			jQuery(images[currentImage]).fadeIn(50, function(){
				if (count > 1) {
					setTimeout("nextImage()", 5000);
				}
			});
		});
			
		function nextImage() {
			if (currentImage < (count - 1))
				var nextImage = currentImage + 1;
			else
				var nextImage = 0;
			
			jQuery('.img-' + currentImage).fadeOut();
			jQuery('.img-' + nextImage).fadeIn('300', function(){
				currentImage = nextImage;
				setTimeout("nextImage()", 5000);
			});
		}
	</script>
<?php endif; ?>