<?php
/**
 * @version		$Id: mod_deallab_cities.php 2013-07-17 studio4 $
 * @package		DealLab Cities Module
 * @author		Studio4 http://www.studio4.lt
 * @copyright	Copyright (c) 2010 - 2013 UAB Studio4. All rights reserved.
 * @license		GNU/GPL license: http://shop.studio4.lt/deallab-license
 */

// no direct access
defined('_JEXEC') or die;

require_once (JPATH_ADMINISTRATOR . DS . 'components/com_deallab/helpers/module.php');

$city = JRequest::getVar('city_id', 0);
$city = explode(':', $city);
$currentCity = $city[0];

$cities = DealLabModuleHelper::getCityList();

if (count($cities) < 1)
	return true;

$moduleclass_sfx = htmlspecialchars($params->get('moduleclass_sfx'));

require JModuleHelper::getLayoutPath('mod_deallab_cities', $params->get('layout', 'default'));