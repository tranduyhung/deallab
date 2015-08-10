<?php
/**
 * @version		$Id: html.php 2013-07-17 studio4 $
 * @package		DealLab
 * @author		Studio4 http://www.studio4.lt
 * @copyright	Copyright (c) 2010 - 2013 UAB Studio4. All rights reserved.
 * @license		GNU/GPL license: http://shop.studio4.lt/deallab-license
 */

// no direct access
defined('_JEXEC') or die;
class dlHtmlHelper
{
	static function addCss($frontend, $backend, $ui, $bootstrap = false)
	{
		$document = JFactory::getDocument();
		$cssPath  = JUri::root() . 'media/deallab/assets/css/';
		$tplPath  = JUri::root() . 'components/com_deallab/tpl/';
		
		if ($frontend) {
			$params = JComponentHelper::getParams('com_deallab');
			$color  = $params->get('color-preset'); 
			
			$document->addStyleSheet($cssPath . 'layout.css');
			$document->addStyleSheet($tplPath . $color . '/style.css');
		}
		
		if ($backend) {
			$document->addStyleSheet($cssPath . 'style.css');
		}
		
		if ($ui) {
			$document->addStyleSheet($cssPath . 'ui-lightness/jquery-ui-1.9.2.custom.min.css');
		}
		
		if ($bootstrap) {
			$document->addStyleSheet($cssPath . 'bootstrap.css');
		}
	}
	
	static function addJs($jQuery = true, $noConflict = true, $ui = true, $bootstrap = false)
	{
		$document = JFactory::getDocument();
		$jsPath   = JUri::root() . 'media/deallab/assets/js/';
		
		if ($jQuery) {
			$document->addScript($jsPath . 'jquery-1.8.1.min.js');
			if ($noConflict) {
				$document->addScript($jsPath . 'noConflict.js');
			}
			if ($ui) {
				$document->addScript($jsPath . 'jquery-ui-1.9.2.custom.min.js');
				$document->addScript('http://maps.google.com/maps/api/js?sensor=false');
				$document->addScript($jsPath . 'jquery.ui.addresspicker.js');
				$document->addScript($jsPath . 'ajaxUpload.js');
			}
			if ($bootstrap) {
				$document->addScript($jsPath . 'bootstrap.min.js');
				$document->addScript($jsPath . 'jquery.countdown.min.js');
			}
			
			$document->addScript($jsPath . 'ajaxAddFieldToList.js');
		}
	}	
}