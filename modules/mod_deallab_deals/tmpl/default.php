<?php
/**
 * @version		$Id: default.php 2013-07-17 studio4 $
 * @package		DealLab Deals Module
 * @author		Studio4 http://www.studio4.lt
 * @copyright	Copyright (c) 2010 - 2013 UAB Studio4. All rights reserved.
 * @license		GNU/GPL license: http://shop.studio4.lt/deallab-license
 */

// no direct access
defined('_JEXEC') or die;

$dl_params  = JComponentHelper::getParams('com_deallab');
$width   	= $dl_params->get('small_w');
$height  	= $dl_params->get('small_h');

$city_id = JRequest::getVar('city_id', false);
if ($city_id)
	$city_slug = DealLabModuleHelper::getCitySlug($city_id);
$category_id = JRequest::getVar('category_id', false);
if ($category_id)
	$category_slug = DealLabModuleHelper::getCatSlug($category_id);

$params = JComponentHelper::getParams('com_deallab');
?>
<div class="deallab-other-deals">
	<div class="other-deals-title">
		<h3 class="deallab-other-deals"><?php echo JText::_('MOD_DEALLAB_DEALS_TITLE'); ?></h3>
	</div>
	<?php if (count($deals) > 0) :
		foreach ($deals as $deal) :
			$url = 'index.php?option=com_deallab&task=deal';
			if ($city_id)
				$url .= '&city_id='.$city_slug;
			if ($category_id)
				$url .= '&category_id='.$category_slug;			
			$url .= '&deal_id='.$deal->id.':'.$deal->alias;
			$link = JRoute::_($url);
			?>
			<div class="deallab-other-deal">
				<a href="<?php echo $link; ?>" alt="<?php echo $deal->title; ?>">
					<span class="other-deal-title"><?php echo $deal->title; ?></span>
					<div class="other-deal-image" style="width: <?php echo $width; ?>px; height: <?php echo $height; ?>px;">
						<?php if ($deal->original) : ?>
							<img src="<?php echo DEALLAB_IMG . 'small_' . $deal->original; ?>" width="<?php echo $width; ?>" height="<?php echo $height; ?>" />
						<?php endif; ?>
						<div class="clearfix"></div>
					</div>
					<div class="other-deal-details">
						<span id="timeleft<?php echo $deal->id; ?>" class="other-deal-timeleft"></span>
						<?php if ((@$deal->options->show_discounts == 1) || ((@$deal->options->show_discounts == 0) && ($params->get('show_discounts', 1) == 1))) : ?>
							<div class="block"><?php echo JText::_('MOD_DEALLAB_DEALS_PRICE'); ?><br /><span style="color: #e75b11;"><?php echo dlDealHelper::renderPrice($deal->price); ?></span></div>
							<div class="block"><?php echo JText::_('MOD_DEALLAB_DEALS_VALUE'); ?><br/ ><span><?php echo dlDealHelper::renderPrice($deal->value); ?></span></div>
						<?php else: ?>
							<div class="block" style="width: 100%;"><?php echo JText::_('MOD_DEALLAB_DEALS_PRICE'); ?><br /><span style="color: #e75b11;"><?php echo dlDealHelper::renderPrice($deal->price); ?></span></div>
						<?php endif; ?>
						<div class="clearfix"></div>
						<a href="<?php echo $link; ?>" class="other-deal-button"><span><?php echo JText::_('MOD_DEALLAB_DEALS_BUTTON'); ?></span></a>
					</div>
					<div class="clearfix"></div>
				</a>
			</div>
			<script type="text/javascript">
			jQuery(document).ready(function(){
				var untilDate<?php echo $deal->id; ?> = new Date(<?php echo strtotime($deal->publish_down); ?>000);
				jQuery('#timeleft<?php echo $deal->id; ?>').countdown({
					labels: ['y ', 'm ', 'w ', 'days ', 'h ', 'm ', 's'], 
					until: untilDate<?php echo $deal->id; ?>,
					format: 'dHMS',
					compact: true
				});
			});
			</script>
		<?php endforeach;	
	else : ?>
		<p>No deals found</p>
	<?php endif; ?>
</div>