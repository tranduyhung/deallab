<?php
/**
 * @version		$Id: default_contact.php 2013-07-17 studio4 $
 * @package		DealLab
 * @author		Studio4 http://www.studio4.lt
 * @copyright	Copyright (c) 2010 - 2013 UAB Studio4. All rights reserved.
 * @license		GNU/GPL license: http://shop.studio4.lt/deallab-license
 */

// no direct access
defined('_JEXEC') or die;

$addresses = dlDealHelper::loadAddressList($this->deal->id, true);
$api_key   = $this->params->get('maps_key');
$addr_no   = 1;
?>
<script type="text/javascript" src="http://maps.googleapis.com/maps/api/js?sensor=false"></script>

<?php if ((count($addresses) > 0) || ($this->deal->use_shipping == 1)) : ?>	
	<ul class="address_list">
		<?php if (count($addresses) > 0) : ?>
			<?php foreach ($addresses as $key => $address) : ?>
				<li>
					<span class="address-number color1"><?php echo $addr_no; ?></span>
					<?php if (($address->lat != 0) && ($address->lng != 0)) : ?>
						<?php echo dlDealHelper::renderMapImage($address); ?>
					<?php endif; ?>
					<span class="address-title color7"><strong><?php echo $address->title; ?></strong><br /><?php echo $address->address; ?></span>
				</li>
				<?php $addr_no++; ?> 
			<?php endforeach; ?>
		<?php endif; ?>
		<?php if ($this->deal->use_shipping == 1) : ?>
			<li>
				<span class="address-number color1"><?php echo $addr_no; ?></span>
				<span class="address-title color7"><?php echo JText::_('DEALLAB_OPTIONS_SHIP'); ?></span>
			</li>
		<?php endif; ?>
	</ul>
<?php endif; ?>
<div class="modal" id="mapPreview" tabindex="-1" role="dialog" aria-labelledby="mapPreviewLabel" aria-hidden="true" style="display: none; outline-width: 0;">
  <div class="modal-header">
    <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
    <h3 id="mapPreviewLabel"><?php echo JText::_('DEALLAB_FIND_US'); ?></h3>
  </div>
  <div class="modal-body">
    <div id="map_canvas" style="width: 530px; height: 300px;"></div>
  </div>
  <div class="modal-footer">
    <button class="btn btn-primary" data-dismiss="modal" aria-hidden="true"><?php echo JText::_('DEALLAB_OPTIONS_CLOSE'); ?></button>
  </div>
</div>
<script type="text/javascript">
	function renderMap(lat, lng)
	{
		var spot = new google.maps.LatLng(lat, lng);
		
  		var mapOptions = {
    		zoom: 14,
    		center: new google.maps.LatLng(lat, lng),
    		mapTypeId: google.maps.MapTypeId.ROADMAP
  		}
  		
  		var map = new google.maps.Map(document.getElementById("map_canvas"), mapOptions);
  		
  		marker = new google.maps.Marker({
    		map:map,
    		animation: google.maps.Animation.DROP,
    		position: spot
  		});
  
  		jQuery('#mapPreview').modal({
  			backdrop: true,
  			keyboard: true,
  			show: true
  		});
	}
</script>