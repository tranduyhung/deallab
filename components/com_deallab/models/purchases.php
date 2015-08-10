<?php
/**
 * @version		$Id: purchases.php 2013-07-17 studio4 $
 * @package		DealLab
 * @author		Studio4 http://www.studio4.lt
 * @copyright	Copyright (c) 2010 - 2013 UAB Studio4. All rights reserved.
 * @license		GNU/GPL license: http://shop.studio4.lt/deallab-license
 */

// no direct access
defined('_JEXEC') or die;

jimport('joomla.application.component.modelitem');

class DealLabModelPurchases extends JModelItem
{
	function getPurchases()
	{
		$user = JFactory::getUser();
		$db   = JFactory::getDBO();
		
		$q = $db->getQuery(true);
		
		$q->select(array('c.*', 'd.title as deal_title', 'p.title as option_title'))->
			from($db->quoteName('#__deallab_coupons').' AS c')->
			join('LEFT', $db->quoteName('#__deallab_orders').' AS o ON c.order_id = o.id')->
			join('LEFT', $db->quoteName('#__deallab_deals').' AS d ON c.deal_id = d.id')->
			join('LEFT', $db->quoteName('#__deallab_prices').' AS p ON o.option_id = p.id')->
			where($db->quoteName('o.user_id') . ' = ' . $user->id)->
			order($db->escape('o.cdate DESC'))->
			order($db->escape('c.code ASC'));
		
		$db->setQuery($q);
		
		$coupons = $db->loadObjectList();
		
		$result = array();
		
		if (count($coupons)) {
			foreach ($coupons as $key => $cpn) {
				if (!isset($result[$cpn->order_id])) {
					$result[$cpn->order_id]['title'] = $cpn->deal_title;
					$result[$cpn->order_id]['option'] = $cpn->option_title;
					$result[$cpn->order_id]['cdate'] = $cpn->cdate;
					$result[$cpn->order_id]['coupons'][] = $cpn->code;
				} else {
					$result[$cpn->order_id]['coupons'][] = $cpn->code;
				}
			}
		}
		
		return $result;
	}
}