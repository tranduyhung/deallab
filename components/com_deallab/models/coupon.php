<?php
/**
 * @version		$Id: checkout.php 2013-07-17 studio4 $
 * @package		DealLab
 * @author		Studio4 http://www.studio4.lt
 * @copyright	Copyright (c) 2010 - 2013 UAB Studio4. All rights reserved.
 * @license		GNU/GPL license: http://shop.studio4.lt/deallab-license
 */

// no direct access
defined('_JEXEC') or die;

jimport('joomla.application.component.modelitem');

class DealLabModelCoupon extends JModelItem
{
	public function getItem()
	{
		$id = JRequest::getVar('id', null);
		
		if ($id) {
			$db = JFactory::getDBO();
			$q = $db->getQuery(true);
			
			$q->select(array('c.*', 'd.title AS deal_title', 'p.title as option_title', 'o.address_id', 'a.title as address_title', 'a.address', 'o.amount as total', 'o.currency', 'o.details'))->
				from('#__deallab_coupons AS c')->
				join('LEFT', '#__deallab_deals AS d ON c.deal_id = d.id')->
				join('LEFT', '#__deallab_orders AS o ON c.order_id = o.id')->
				join('LEFT', '#__deallab_prices AS p ON o.option_id = p.id')->
				join('LEFT', '#__deallab_addresses AS a ON o.address_id = a.id')->
				where('c.id = ' . $id);
			
			$db->setQuery($q);
			
			return $db->loadObject();
		}
		
		return false;
	}
}
