<?php
/**
 * @version		$Id: data.php 2013-07-17 studio4 $
 * @package		DealLab
 * @author		Studio4 http://www.studio4.lt
 * @copyright	Copyright (c) 2010 - 2013 UAB Studio4. All rights reserved.
 * @license		GNU/GPL license: http://shop.studio4.lt/deallab-license
 */

// no direct access
defined('_JEXEC') or die;

class dlDataHelper
{
	static function getDealData($id, $name, $selector = '*', $output = null, $ordering = null)
	{
		$db = JFactory::getDBO();
		$query = 'SELECT ' . $selector . ' FROM #__deallab_' . $name . ' WHERE deal_id = ' . $id;
		if ($ordering)
			$query .= ' ORDER BY ' . $ordering . ' ASC';
		
		$db->setQuery($query);
		
		switch ($output) {
			case 'array':
				return $db->loadResultArray();
				break;
			default:
				return $db->loadObjectList();
				break;
		}		
	}
	
	static function cityList()
	{
		$db = JFactory::getDBO();
		$query = 'SELECT * FROM #__deallab_cities WHERE state = 1 ORDER BY ordering ASC';
		$db->setQuery($query);
		
		return $db->loadObjectList();
	}
	
	static function categoryList()
	{
		$db = JFactory::getDBO();
		$query = 'SELECT * FROM #__deallab_categories WHERE state = 1 ORDER BY ordering ASC';
		$db->setQuery($query);
		
		return $db->loadObjectList();
	}
}
