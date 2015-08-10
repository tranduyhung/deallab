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

class dlDealHelper
{
	static function getDefaultCity()
	{
		$db = JFactory::getDBO();
		$query = 'SELECT
					C.id, C.alias
				FROM
					#__deallab_cities C
				LEFT JOIN #__deallab_deal_cities DC ON C.id = DC.city_id
				LEFT JOIN #__deallab_deals D ON DC.deal_id = D.id
				WHERE
					C.state = 1
					AND D.state = 1
					AND D.publish_up < "'.date("Y-m-d H:i:s").'"
					AND D.publish_down > "'.date("Y-m-d H:i:s").'"
				GROUP BY
					C.id
				ORDER BY
					C.ordering ASC
				LIMIT 0, 1';

		$db->setQuery($query);
		$city = $db->loadObject();
		
		if ($city)
			return $city->id . ':' . $city->alias;
		else 
			return false;
	}
	
	static function findDeal($city = false, $category_id = false)
	{
		if ($city) {
			$city = explode(':', $city);
			$city_id = $city[0];
		}
		$db = JFactory::getDBO();
		$query = '
				SELECT
					D.`id`, D.`alias`
				FROM
					#__deallab_deals D
				LEFT JOIN `#__deallab_deal_cities` DC ON D.id = DC.deal_id
				LEFT JOIN `#__deallab_deal_categories` DCAT ON D.id = DCAT.deal_id
				WHERE
					D.`state` = 1 AND
					D.`publish_up` < "'.date("Y-m-d H:i:s").'" AND
					D.`publish_down` > "'.date("Y-m-d H:i:s").'"';
				if ($city)
					$query .= ' AND DC.`city_id` = ' . $city_id;
				if ($category_id)
					$query .= ' AND DCAT.category_id = ' . $category_id;
				$query .= ' LIMIT 0, 1';
		
		$db->setQuery($query);
		$deal = $db->loadObject();
		
		if ($deal)
			return  $deal->id . ':' . $deal->alias;
		else
			return false;
	}
	
	static function renderPrice($price)
	{
		$params = JComponentHelper::getParams('com_deallab');

		if ($params->get('currency_position') == 0)
			$text = $params->get('currency_symbol') . ' ' . $price;
		elseif ($params->get('currency_position') == 1)
			$text = $price . ' ' . $params->get('currency_symbol');
		else
			$text = $price;
		
		return $text;
	}
	
	static function loadDealImages($id) {
		$db = JFactory::getDBO();
		
		$query = 'SELECT * FROM #__deallab_images WHERE `deal_id` = ' . $id . ' ORDER BY `ordering` ASC';
		$db->setQuery($query);

		return $db->loadObjectList();
	}
	
	static function renderMapImage($item, $onclick = 'renderMap', $key = false)
	{
		if ($onclick == 'renderMap') {
			$onclick = 'onclick="renderMap('.$item->lat.','.$item->lng.'); return false;"';
		} elseif ($onclick == 'pickLocation') {
			$onclick = 'onclick="pickLocation('.$key.'); return false;"';
		}
		
		$html = '<a href="#" '.$onclick.'>';
		$html .= '<img src="http://maps.googleapis.com/maps/api/staticmap?center='.$item->lat.','.$item->lng.'&zoom=13&size=202x103&markers=color:blue%7C'.$item->lat.','.$item->lng.'&sensor=false" />';
		$html .= '</a>';		
		return $html;
	}
	
	static function loadAddressList($deal_id)
	{
		$db = JFactory::getDBO();
		$query = 'SELECT * FROM #__deallab_addresses WHERE deal_id = ' . $deal_id;
		$db->setQuery($query);
		
		return $db->loadObjectList();
	}
	
	static function loadOptionList($deal_id, $countSales = false)
	{
		$db = JFactory::getDBO();
						
		if ($countSales) {
			$query = 'SELECT 
						P.*,
						COUNT(C.id) AS couponsSold
					FROM 
						#__deallab_prices P
					LEFT JOIN #__deallab_coupons C
						ON P.deal_id = C.deal_id AND C.status = 1
					WHERE 
						P.deal_id = ' . $deal_id . '
					GROUP BY
						P.id 
					ORDER BY 
						P.ordering ASC';
		} else {
			$query = 'SELECT 
						* 
					FROM 
						#__deallab_prices 
					WHERE 
						deal_id = ' . $deal_id . ' 
					ORDER BY 
						ordering ASC';
		}
		
		$db->setQuery($query);
		
		return $db->loadObjectList();
	}
	
	static function validateCustomer($customer = array()) {
		return true;
	}
	
	static function loadCustomFields($id) {
		$db = JFactory::getDBO();
		$query = 'SELECT * FROM #__deallab_customfields WHERE deal_id = ' . $id . ' ORDER BY ordering ASC';
		$db->setQuery($query);
		
		return $db->loadObjectList();
	}
	
	static function validateOrder($order = array()) {
		return true;
	}
}