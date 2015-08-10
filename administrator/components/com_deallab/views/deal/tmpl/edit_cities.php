<?php
/**
 * @version		$Id: edit_cities.php 2013-07-17 studio4 $
 * @package		DealLab
 * @author		Studio4 http://www.studio4.lt
 * @copyright	Copyright (c) 2010 - 2013 UAB Studio4. All rights reserved.
 * @license		GNU/GPL license: http://shop.studio4.lt/deallab-license
 */

// no direct access
defined('_JEXEC') or die;
 
$cities     = dlDataHelper::cityList();
$dealCities = dlDataHelper::getDealData($this->item->id, 'deal_cities', 'city_id', 'array');

if (!$dealCities)
	$dealCities = array();
?>
<ul class="adminformlist" id="citiesList">
	<?php if (count($cities)) : ?>
		<?php foreach ($cities as $city) : ?>
			<li><input type="checkbox" class="deallab-checkbox" name="cities[<?php echo $city->id; ?>]" value="1" <?php echo (in_array($city->id, $dealCities))?'checked="checked"':''; ?> /><?php echo $city->title; ?></li>
		<?php endforeach; ?>
	<?php endif; ?>
</ul>
<input type="text" value="" id="addNewCity" /><input type="button" value="<?php echo JText::_('DEALLAB_BUTTON_ADD'); ?>" onclick="addCity();" />
<span class="errorMsg" id="city-error"></span>
<script type="text/javascript">
	function addCity() {
		jQuery('#city-error').html('');
		if (jQuery('#addNewCity').val() != '') {
			addFieldToList(jQuery('#addNewCity'), jQuery('#citiesList'), '?option=com_deallab&task=ajax.addCity', 'cities');
		} else {
			jQuery('#city-error').html('<p>Please enter a new city.</p>');
		}
	}
</script>