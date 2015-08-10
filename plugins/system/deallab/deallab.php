<?php
/**
 * @version		$Id: deallab.php 2013-07-17 studio4 $
 * @package		DealLab System Plugin
 * @author		Studio4 http://www.studio4.lt
 * @copyright	Copyright (c) 2010 - 2013 UAB Studio4. All rights reserved.
 * @license		GNU/GPL license: http://shop.studio4.lt/deallab-license
 */

defined('_JEXEC') or die;

class plgSystemDeallab extends JPlugin
{
	protected $js = '/media/deallab/assets/js/';
	protected $css = '/media/deallab/assets/css/';
	protected $tpl = '/components/com_deallab/tpl/';
	
	public function __construct(&$subject, $config)
	{
		parent::__construct($subject, $config);
	}

	public function onAfterDispatch()
	{
		$app = JFactory::getApplication();
 		
 		if($app->isAdmin()) 
			return;
		
		$doc 	= JFactory::getDocument();
		$params = JComponentHelper::getParams('com_deallab');
		
		// Adding CSS styles
		
		if ($params->get('load_main_css', 1) == 1) {
			$doc->addStyleSheet(JUri::root() . $this->css . 'layout.css');
		}
		
		if ($params->get('load_tpl_css', 1) == 1) {
			//$color = $params->get('color_preset');
			$doc->addStyleSheet(JUri::root() . $this->tpl . 'blue' . '/style.css'); 
		}
		
		// Adding JavaScripts
		if ($params->get('load_jquery', 1) == 1)
		{
			$doc->addScript(JUri::root() . $this->js . 'jquery-1.8.1.min.js');
			if ($params->get('no_conflict', 1) == 1)
				$doc->addScript(JUri::root() . $this->js . 'noConflict.js');
		}
		
		if ($params->get('load_bootstrap', 1) == 1) {
			$doc->addScript(JUri::root() . $this->js . 'bootstrap.min.js');
			$doc->addStyleSheet(JUri::root() . $this->css . 'bootstrap.css');
		}
		
		if ($params->get('load_jqueryui', 1) == 1) {
			$doc->addScript(JUri::root() . $this->js . 'bootstrap.min.js');
			$doc->addStyleSheet(JUri::root() . $this->css . 'ui-lightness/jquery-ui-1.9.2.custom.min.css');
		}		
		
		$doc->addScript(JUri::root() . $this->js . 'jquery.countdown.min.js');
		$doc->addScript(JUri::root() . $this->js . 'jquery.validate.min.js');
	}
}