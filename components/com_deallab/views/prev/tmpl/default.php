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

$document     =& JFactory::getDocument();
$main_width   = $this->params->get('tpl_width');
$img_height   = $this->params->get('large_h');
$img_width    = $this->params->get('large_w');
$customFields = dlDealHelper::loadCustomFields($this->deal->id);

if (!isset($this->deal->id)) {
	echo JText::_('DEALLAB_DEAL_NOT_FOUND');
} else { 
?>
<script type="text/javascript">
	jQuery(document).ready(function(){
		var untilDate = new Date(<?php echo strtotime($this->deal->publish_down); ?>000);
		jQuery('#timeleft').countdown({
			labels: ['y ', 'm ', 'w ', 'days ', 'h ', 'm ', 's'], 
			until: untilDate,
			format: 'dHMS',
			compact: true
		});
	});
</script>
<div id="deallab-main" style="width: <?php echo $main_width; ?>px">
	<h3 class="main-deal-title color6"><?php echo $this->deal->title; ?></h3>
	<div class="social-icons">
		<?php if ($this->deal->fb_like) : ?>
			<div class="social-icon">
				<div id="fb-root"></div>
				<script>(function(d, s, id) {
  					var js, fjs = d.getElementsByTagName(s)[0];
  					if (d.getElementById(id)) return;
  						js = d.createElement(s); js.id = id;
  						js.src = "//connect.facebook.net/en_US/all.js#xfbml=1<?php echo $this->params->get('fb_api_key')?'&appId='.$this->params->get('fb_api_key'):''; ?>";
  						fjs.parentNode.insertBefore(js, fjs);
					}(document, 'script', 'facebook-jssdk'));
				</script>
				<div class="fb-like" data-href="<?php echo JURI::base(); ?>" data-send="false" data-layout="button_count" data-width="100" data-show-faces="false"></div>
			</div>
		<?php endif; ?>
		<?php if ($this->deal->g_plus) : ?>
			<div class="social-icon">
				<script type="text/javascript" src="https://apis.google.com/js/plusone.js"></script>
				<g:plusone href="<?php echo JURI::base(); ?>" size="medium" annotation="bubble" width="70" align="align"></g:plusone>
			</div>
		<?php endif; ?>
		<div class="clr"></div>
	</div>
	<div id="main-deal-block">
		<div class="main-deal-image-block">
			<?php echo $this->loadTemplate('images'); ?>
		</div>
		<div class="main-deal-details-block" style="width: <?php echo ($main_width - $img_width); ?>px; height: <?php echo $img_height; ?>px;">
			<div class="countdown-block">
				<span id="timeleft" style="width: <?php echo ($main_width - $img_width - 70); ?>px;"></span>
			</div>
			<div class="savings-block">
				<div class="savings-value">
					<h4 style="color: #fff;"><?php echo JText::_('DEALLAB_LABEL_DEAL_VALUE'); ?></h4>
					<span><?php echo dlDealHelper::renderPrice($this->deal->prices->price); ?></span>
				</div>
				<div class="savings-discount">
					<h4 style="color: #fff;"><?php echo JText::_('DEALLAB_LABEL_DEAL_DISCOUNT'); ?></h4>
					<span class="color4"><?php echo ($this->deal->prices->value > 0)?floor((1 - $this->deal->prices->price / $this->deal->prices->value) * 100):'-'; ?>%</span>
				</div>
				<div class="savings-savings">
					<h4 style="color: #fff;"><?php echo JText::_('DEALLAB_LABEL_DEAL_SAVINGS'); ?></h4>
					<span class="color5"><?php echo dlDealHelper::renderPrice(($this->deal->prices->value - $this->deal->prices->price)); ?></span>
				</div>
				<div class="clearfix"></div>
			</div>
			<a id="main-deal-buy" href="<?php echo JRoute::_('index.php?option=com_deallab&task=checkout&deal_id='.$this->deal->id.':'.$this->deal->alias); ?>" style="width: <?php echo ($main_width - $img_width); ?>px;">
				<span class="buy_label"><?php echo JText::_('DEALLAB_BUTTON_BUY'); ?></span>
				<span class="buy_price"><?php echo dlDealHelper::renderPrice($this->deal->prices->price); ?></span>
				<div class="clr"></div>
			</a>
			<?php if ($this->params->get('friend_buy]')) : ?>
				<a id="main-deal-buy-friend" href="<?php echo JRoute::_('index.php?option=com_deallab&task=checkout&deal_id='.$this->deal->id.':'.$this->deal->alias); ?>" style="width: <?php echo ($main_width - $img_width); ?>px;">
					<span><?php echo JText::_('DEALLAB_BUTTON_BUY_FRIEND'); ?></span>
				</a>
			<?php endif; ?>
		</div>
		<div class="clr"></div>
	</div>
	<div class="main-deal-shadow"></div>
	<div class="description-block" style="width: <?php echo $main_width; ?>px">
		<div class="main-deal-addresses">
			<h4 class="description-title location-title color6"><?php echo JText::_('DEALLAB_DEAL_ADDR_LABEL'); ?></h4>
			<?php echo $this->loadTemplate('address_list'); ?>
		</div>
		<div class="main-deal-description" style="width: <?php echo ($main_width - 255); ?>px">
			<h4 class="description-title desc-title color6"><?php echo JText::_('DEALLAB_DEAL_DESC_LABEL'); ?></h4>
			<?php echo $this->loadTemplate('option_list'); ?>
			<div class="full-description">		
				<?php echo $this->deal->description; ?>
			</div>
			<?php if ($customFields) : ?>
				<div class="custom-fields">
				<?php foreach ($customFields as $field) : ?>
					<strong><?php echo $field->name; ?></strong><br />
					<?php echo $field->value; ?>
				<?php endforeach; ?>
				</div>
			<?php endif; ?>
			<?php //echo $this->loadTemplate('contact'); ?>
		</div>	
		<div class="clearfix"></div>
	</div>
</div>
<?php } ?>