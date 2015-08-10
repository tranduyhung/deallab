<?php
/**
 * @version		$Id: mod_deallab_deals.php 2013-07-17 studio4 $
 * @package		DealLab Deals Module
 * @author		Studio4 http://www.studio4.lt
 * @copyright	Copyright (c) 2010 - 2013 UAB Studio4. All rights reserved.
 * @license		GNU/GPL license: http://shop.studio4.lt/deallab-license
 */

// no direct access
defined('_JEXEC') or die;

require_once (JPATH_ADMINISTRATOR . DS . 'components/com_deallab/helpers/deal.php');
require_once (JPATH_ADMINISTRATOR . DS . 'components/com_deallab/helpers/module.php');

define('DEALLAB_IMG', JUri::root() . 'media/deallab/images/');

$document = JFactory::getDocument();
$document->addStyleSheet(JUri::root() . 'modules/mod_deallab_deals/assets/style.css');

$city     = JRequest::getVar('city_id', null);
$category = JRequest::getVar('category_id', null);
if ($city) {
	$city = explode(':', $city);
	$city_id = $city[0];
} else {
	$city_id = false;
}
if ($category) {
	$category = explode(':', $category);
	$category_id = $category[0];
} else {
	$category_id = false;
}

$deals = DealLabModuleHelper::getDealList($city_id, $category_id, true);

$moduleclass_sfx = htmlspecialchars($params->get('moduleclass_sfx'));

require JModuleHelper::getLayoutPath('mod_deallab_deals', $params->get('layout', 'default'));