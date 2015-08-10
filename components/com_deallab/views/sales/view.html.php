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

class DealLabViewSales extends JViewLegacy
{
	protected $state;

	function display($tpl = null)
	{
		$db  =& JFactory::getDBO();
		$deal_id = JRequest::getVar('deal_id', 0);
		$hash    = JRequest::getVar('hash', false);
		
		$query = 'SELECT id, publish_up, publish_down FROM #__deallab_deals WHERE id = ' . $deal_id;
		$db->setQuery($query);
		
		$deal = $db->loadObject();
		
		if ($hash == substr(md5($deal->id . $deal->publish_up . $deal->publish_down), 10, -10)) {
			$query = 'SELECT * FROM #__deallab_coupons WHERE deal_id = ' . $deal_id . ' AND status = 1';
			$db->setQuery($query);
			$this->sales = $db->loadObjectList(); 
		}
			
		
		parent::display();
	}
}
