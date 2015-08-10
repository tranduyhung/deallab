<?php
/**
 * @version		$Id: deals.php 2013-07-17 studio4 $
 * @package		DealLab
 * @author		Studio4 http://www.studio4.lt
 * @copyright	Copyright (c) 2010 - 2013 UAB Studio4. All rights reserved.
 * @license		GNU/GPL license: http://shop.studio4.lt/deallab-license
 */

// no direct access
defined('_JEXEC') or die;

jimport('joomla.application.component.modellist');

class DealLabModelDeals extends JModelList
{
	protected function populateState($ordering = null, $direction = null)
	{
		$app = JFactory::getApplication('administrator');

		$search = $this->getUserStateFromRequest($this->context.'.filter.search', 'filter_search');
		$this->setState('filter.search', $search);
		
		$published = $this->getUserStateFromRequest($this->context.'.filter.state', 'filter_published', '', 'string');
		$this->setState('filter.state', $published);
		
		parent::populateState('id', 'desc');
	}
	
	protected function getListQuery()
	{
		$db		= $this->getDbo();
		$query	= $db->getQuery(true);
		$user	= JFactory::getUser();

		$query->select(
			$this->getState(
				'list.select',
				'D.id, D.title, D.alias, D.state, D.checked_out, D.checked_out_time, D.ordering, D.publish_up, D.publish_down'
			)
		);
		
		$query->select('uc.name AS editor');
		$query->join('LEFT', '#__users AS uc ON uc.id = D.checked_out');
		
		$query->from($db->quoteName('#__deallab_deals').' AS D');
		
		$search = $this->getState('filter.search');
		
		if (!empty($search)) {
			if (stripos($search, 'id:') === 0) {
				$query->where('D.id = '.(int) substr($search, 3));
			} else {
				$search = $db->Quote('%'.$db->escape($search, true).'%');
				$query->where('(D.title LIKE '.$search.' OR D.alias LIKE '.$search.')');
			}
		}
		
		$published = $this->getState('filter.state');
		
		if (is_numeric($published)) {
			$query->where('D.state = '.(int) $published);
		} elseif ($published === '') {
			$query->where('(D.state IN (0, 1))');
		}
		
		$orderCol	= $this->state->get('list.ordering');
		$orderDirn	= $this->state->get('list.direction');
		
		if ($orderCol == 'D.ordering') {
			$orderCol = 'D.title '.$orderDirn.', D.ordering';
		}
		
		$query->order($db->escape($orderCol.' '.$orderDirn));

		return $query;
	}
}