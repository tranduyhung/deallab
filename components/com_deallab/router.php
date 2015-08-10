<?php
/**
 * @version		$Id: router.php 2013-07-17 studio4 $
 * @package		DealLab
 * @author		Studio4 http://www.studio4.lt
 * @copyright	Copyright (c) 2010 - 2013 UAB Studio4. All rights reserved.
 * @license		GNU/GPL license: http://shop.studio4.lt/deallab-license
 */

// no direct access
defined('_JEXEC') or die;

function DealLabBuildRoute(&$query)
{
	$segments = array();
	
	if (!isset($query['task']))
		$query['task'] = '';
	
	switch ($query['task'])
	{
		case 'deal' :
			if (isset($query['city_id'])) {
				$segments[] = 'city';
				$segments[] = $query['city_id'];
				unset($query['city_id']);
			}
			
			if (isset($query['category_id'])) {
				$segments[] = $query['category_id'];
				unset($query['category_id']);
			}
			
			if (isset($query['deal_id'])) {
				$segments[] = $query['deal_id'];
				unset($query['deal_id']);
			}
			
			break;
		case 'checkout' :
			if (isset($query['gift'])) {
				$segments[] = 'gift';
				unset($query['gift']);
			} else {
				$segments[] = 'buy';
			}
			
			if (isset($query['deal_id'])) {
				$segments[] = $query['deal_id'];
				unset($query['deal_id']);
			}
			
			break;
	}
	unset($query['task']);
	
	return $segments;
}

function DealLabParseRoute($segments)
{
	$vars = array();
	
	switch ($segments[0]) {
		case 'city' :
			$vars['task'] = 'deal';
			if (count($segments) == 2) {
				$vars['city_id'] = $segments[1];
			} 
			else if (count($segments) == 3) {
				$vars['city_id'] = $segments[1];
				$vars['deal_id'] = $segments[2];
			} else if (count($segments) == 4) {
				$vars['city_id'] = $segments[1];
				$vars['category_id'] = $segments[2];
				$vars['deal_id'] = $segments[3];
			}

			break;
		case 'buy' :
			$vars['task'] = 'checkout';
			
			if (isset($segments[1])) {
				$vars['deal_id'] = $segments[1];	
			}
			break;
		case 'gift' :
			$vars['task'] = 'checkout';
			$vars['gift'] = 1;
			
			if (isset($segments[1])) {
				$vars['deal_id'] = $segments[1];	
			}
			break;
		default: 
			$vars['task'] = 'deal';
			if (count($segments) == 1) {
				$vars['deal_id'] = $segments[0];
			}
			break;
		
	}
	
	return $vars;
}