<?php
/**
 * @version		$Id: mod_deallab_categories.php 2013-07-17 studio4 $
 * @package		DealLab Categories Module
 * @author		Studio4 http://www.studio4.lt
 * @copyright	Copyright (c) 2010 - 2013 UAB Studio4. All rights reserved.
 * @license		GNU/GPL license: http://shop.studio4.lt/deallab-license
 */

// no direct access
defined('_JEXEC') or die;

require_once (JPATH_ADMINISTRATOR . DS . 'components/com_deallab/helpers/module.php');

$cats = DealLabModuleHelper::getCategoryList();
$moduleclass_sfx = htmlspecialchars($params->get('moduleclass_sfx'));

require JModuleHelper::getLayoutPath('mod_deallab_categories', $params->get('layout', 'default'));