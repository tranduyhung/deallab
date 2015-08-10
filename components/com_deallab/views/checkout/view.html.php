<?php
/**
 * @version		$Id: view.html.php 2013-07-17 studio4 $
 * @package		DealLab
 * @author		Studio4 http://www.studio4.lt
 * @copyright	Copyright (c) 2010 - 2013 UAB Studio4. All rights reserved.
 * @license		GNU/GPL license: http://shop.studio4.lt/deallab-license
 */

// no direct access
defined('_JEXEC') or die;

class DealLabViewCheckout extends JViewLegacy
{
	protected $state;

	function display($tpl = null)
	{
		$this->deal    	= $this->get('Deal');
		$this->gift		= JRequest::getInt('gift', 0);
		$this->params  	= JComponentHelper::getParams('com_deallab');
		
		parent::display();
	}
}
