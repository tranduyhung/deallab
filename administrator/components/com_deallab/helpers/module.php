<?php
/**
 * @version		$Id: module.php 2013-07-17 studio4 $
 * @package		DealLab
 * @author		Studio4 http://www.studio4.lt
 * @copyright	Copyright (c) 2010 - 2013 UAB Studio4. All rights reserved.
 * @license		GNU/GPL license: http://shop.studio4.lt/deallab-license
 */

// no direct access
defined('_JEXEC') or die;

class DealLabModuleHelper
{
	static function getCityList()
	{
		$db = JFactory::getDBO();
		$query = '
				SELECT 
					C.*
				FROM 
					#__deallab_cities C
				LEFT JOIN
					#__deallab_deal_cities CT
				ON
					CT.city_id = C.id
				LEFT JOIN
					#__deallab_deals D
				ON
					D.id = CT.deal_id
				WHERE 
					C.`state` = 1 AND	
					D.`state` = 1 AND
					D.`publish_up` < "'.date("Y-m-d H:i:s").'" AND
					D.`publish_down` > "'.date("Y-m-d H:i:s").'"
				GROUP BY
					C.id				
				ORDER BY 
					ordering ASC';

		$db->setQuery($query);

		return $db->loadObjectList('id');
	}
	
	static function getCitySlug($id)
	{
		$db = JFactory::getDBO();
		$query = 'SELECT alias FROM #__deallab_cities WHERE id = ' . $id;
		$db->setQuery($query);
		
		$slug = $id . ':' . $db->loadResult();
		
		return $slug;
	}
	
	static function getCatSlug($id)
	{
		$db = JFactory::getDBO();
		$query = 'SELECT alias FROM #__deallab_categories WHERE id = ' . $id;
		$db->setQuery($query);
		
		$slug = $id . ':' . $db->loadResult();
		
		return $slug;
	}
	
	static function getCategoryList()
	{
		$db = JFactory::getDBO();
		$query = '
				SELECT 
					CAT.*
				FROM 
					#__deallab_categories CAT
				LEFT JOIN
					#__deallab_deal_categories DC
				ON
					CAT.id = DC.category_id
				LEFT JOIN
					#__deallab_deals D
				ON
					DC.deal_id = D.id
				WHERE 
					CAT.state = 1 AND 
					D.state = 1
				GROUP BY
					CAT.id
				ORDER BY 
					CAT.ordering ASC';
					
		$db->setQuery($query);

		return $db->loadObjectList('id');
	}
	
	static function getCatCityId($id)
	{
		$db = JFactory::getDBO();
		
		$query = 'SELECT
					D.id, D.alias
				FROM 
					`#__deallab_deals` D
				LEFT JOIN
					`#__deallab_prices` P ON (P.`deal_id` = D.`id` AND P.`ordering` = 1)
				LEFT JOIN
					`#__deallab_images` I ON (I.`deal_id` = D.`id` AND I.`ordering` = 1)
				LEFT JOIN
					`#__deallab_deal_cities` C ON C.`deal_id` = D.`id`
				LEFT JOIN
					`#__deallab_deal_categories` CAT ON CAT.`deal_id` = D.`id`
				WHERE
					D.`state` = 1 AND
					D.`publish_up` < "'.date("Y-m-d H:i:s").'" AND
					D.`publish_down` > "'.date("Y-m-d H:i:s").'" AND
					CAT.category_id = ' . $id . '
				LIMIT
					0, 1';		
		
		$db->setQuery($query);
		
		$deal = $db->loadObject();
		
		return $deal->id . ':' . $deal->alias;
	}
	
	static function getDealList($city_id = false, $category_id = false, $options = false)
	{
		$db = JFactory::getDBO();
		$deal = JRequest::getVar('deal_id', false);
		
		if ($deal)
			$deal = explode(':', $deal);
		
		$query = 'SELECT
					D.*, P.`price`, P.`value`, I.`original`
				FROM 
					`#__deallab_deals` D
				LEFT JOIN
					`#__deallab_prices` P ON (P.`deal_id` = D.`id` AND P.`ordering` = 1)
				LEFT JOIN
					`#__deallab_images` I ON (I.`deal_id` = D.`id` AND I.`ordering` = 1)
				LEFT JOIN
					`#__deallab_deal_cities` C ON C.`deal_id` = D.`id`
				LEFT JOIN
					`#__deallab_deal_categories` CAT ON CAT.`deal_id` = D.`id`
				WHERE
					D.`state` = 1 AND
					D.`publish_up` < "'.date("Y-m-d H:i:s").'" AND
					D.`publish_down` > "'.date("Y-m-d H:i:s").'"';
				
				if ($deal) {
					$query .= 'AND D.`id` != ' . $deal[0];
				} 
				if ($city_id) {
					$city_id = explode(':', $city_id);
					$query .= ' AND C.city_id = ' . $city_id[0];
				}
				if ($category_id) {
					$category_id = explode(':', $category_id);
					$query .= ' AND CAT.category_id = ' . $category_id[0];
				}		
				$query .= ' GROUP BY 
					D.`id`';
		$db->setQuery($query);
		
		$deals = $db->loadObjectList();
		
		if ($options)
		{
			foreach ($deals as $key => $deal) {
				if (!empty($deal->options)) {
					$deals[$key]->options = json_decode($deal->options);
				} else {
					$deals[$key]->options = '';
				}
			}
		}
		
		return $deals; 
	}
}