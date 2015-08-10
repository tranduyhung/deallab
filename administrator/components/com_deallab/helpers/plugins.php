<?php
/**
 * @version		$Id: plugins.php 2013-07-17 studio4 $
 * @package		DealLab
 * @author		Studio4 http://www.studio4.lt
 * @copyright	Copyright (c) 2010 - 2013 UAB Studio4. All rights reserved.
 * @license		GNU/GPL license: http://shop.studio4.lt/deallab-license
 */

// no direct access
defined('_JEXEC') or die;

jimport('joomla.log.log');

class DealLabPluginsHelper
{
	static function getOrder($id)
	{
		$db = JFactory::getDBO();
		$query = 'SELECT * FROM #__deallab_orders WHERE id = ' . $id;
		$db->setQuery($query);
		
		return $db->loadObject();
	}
	
	static function saveCallback($order_id, $status, $full_response)
	{
		$db = JFactory::getDBO();
		$query = "INSERT INTO #__deallab_callbacks VALUES ('', ".$order_id.", ".$status.", 0, '".json_encode($full_response)."', '".date('Y-m-d H:i:s')."')";
		$db->setQuery($query);
		
		return $db->query();
	}
	
	static function makePaid($order_id)
	{
		$db = JFactory::getDBO();
		$query = 'UPDATE #__deallab_orders SET state = 1 WHERE id = ' . $order_id;
		$db->setQuery($query);
		$db->query();
		
		$query = 'UPDATE #__deallab_coupons SET state = 1 WHERE order_id = ' . $order_id;
		$db->setQuery($query);
		$db->query();
		
		return true;
	}
}
