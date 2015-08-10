<?php
/**
 * @version		$Id: deal.php 2013-07-17 studio4 $
 * @package		DealLab
 * @author		Studio4 http://www.studio4.lt
 * @copyright	Copyright (c) 2010 - 2013 UAB Studio4. All rights reserved.
 * @license		GNU/GPL license: http://shop.studio4.lt/deallab-license
 */

// no direct access
defined('_JEXEC') or die;

jimport('joomla.application.component.modelitem');

class DealLabModelDeal extends JModelItem
{
	protected $_id;
		
	function getCurrentDeal()
	{
		$deal = JRequest::getVar('deal_id', 0);
		$deal = explode(':', $deal);
		
		$this->_id = $deal[0]; 
		
		$query = 'SELECT * FROM #__deallab_deals WHERE id = ' . $this->_id;
		$this->_db->setQuery($query);
		
		$deal = $this->_db->loadObject();
		
		if ($deal->options) {
			$deal->params = json_decode($deal->options);
		}
		
		$deal->prices = $this->getDefaultPrice(); 
		return $deal;
	}
	
	public function getSold()
	{
		$deal = JRequest::getVar('deal_id', 0);
		$deal = explode(':', $deal);
		
		$this->_id = $deal[0];
		
		$query = 'SELECT COUNT(id) FROM #__deallab_coupons WHERE deal_id = ' . $this->_id . ' AND status = 1';
		$this->_db->setQuery($query);
		
		return $this->_db->loadResult();
	}
	
	function getDefaultPrice()
	{
		$query = 'SELECT * FROM #__deallab_prices WHERE deal_id = ' . $this->_id . ' ORDER BY ordering ASC LIMIT 0, 1';
		$this->_db->setQuery($query);
		
		return $this->_db->loadObject();
	}
}
