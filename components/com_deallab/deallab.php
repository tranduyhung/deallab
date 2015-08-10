<?php
/**
 * @version		$Id: deallab.php 2013-07-17 studio4 $
 * @package		DealLab
 * @author		Studio4 http://www.studio4.lt
 * @copyright	Copyright (c) 2010 - 2013 UAB Studio4. All rights reserved.
 * @license		GNU/GPL license: http://shop.studio4.lt/deallab-license
 */

// no direct access
defined('_JEXEC') or die;

require_once (JPATH_COMPONENT_ADMINISTRATOR . '/helpers/deal.php');
require_once (JPATH_COMPONENT_ADMINISTRATOR . '/helpers/html.php');

JTable::addIncludePath(JPATH_COMPONENT_ADMINISTRATOR . '/tables');
JHtml::addIncludePath(JPATH_COMPONENT_ADMINISTRATOR . '/helpers');

if (!JRequest::getCmd('task', null))
	JRequest::setVar('task', JRequest::getCmd('view'));

$controller	= JControllerLegacy::getInstance('DealLab');
$controller->execute(JRequest::getCmd('task'));
$controller->redirect();