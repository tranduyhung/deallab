<?php
/**
 * @version		$Id: controller.php 2013-07-17 studio4 $
 * @package		DealLab
 * @author		Studio4 http://www.studio4.lt
 * @copyright	Copyright (c) 2010 - 2013 UAB Studio4. All rights reserved.
 * @license		GNU/GPL license: http://shop.studio4.lt/deallab-license
 */

// no direct access
defined('_JEXEC') or die;

define('DL_ASSETS', JPATH_BASE . '/media/deallab/assets');
define('DL_HELPERS', JPATH_COMPONENT_ADMINISTRATOR . '/helpers');
define('DL_IMG_ROOT', JPATH_ROOT . '/media/deallab/images/');
define('DL_IMG', JUri::root() . 'media/deallab/images/');
define('DS', DIRECTORY_SEPARATOR);

require_once (DL_HELPERS . '/html.php');

dlHtmlHelper::addCss(false, true, false, false);

jimport('joomla.application.component.controller');

$controller	= JControllerLegacy::getInstance('DealLab');
$controller->execute(JRequest::getCmd('task'));
$controller->redirect();