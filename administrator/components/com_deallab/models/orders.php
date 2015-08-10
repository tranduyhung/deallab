<?php
/**
 * @version		$Id: orders.php 2013-07-17 studio4 $
 * @package		DealLab
 * @author		Studio4 http://www.studio4.lt
 * @copyright	Copyright (c) 2010 - 2013 UAB Studio4. All rights reserved.
 * @license		GNU/GPL license: http://shop.studio4.lt/deallab-license
 */

// no direct access
defined('_JEXEC') or die;

jimport('joomla.application.component.modellist');

class DealLabModelOrders extends JModelList
{
    public function __construct($config = array())
    {
        if (empty($config['filter_fields'])) {
            $config['filter_fields'] = array(
                'O.id', 'O.email', 'O.deal_id', 'O.amount', 'O.status', 'coupons', 'O.cdate'
            );
        }

        parent::__construct($config);
    }

    protected function populateState($ordering = null, $direction = null)
    {
        $app = JFactory::getApplication('administrator');

        $search = $this->getUserStateFromRequest($this->context.'.filter.search', 'filter_search');
        $this->setState('filter.search', $search);

        $published = $this->getUserStateFromRequest($this->context.'.filter.state', 'filter_published', '', 'string');
        $this->setState('filter.state', $published);
		
		$gateway = $this->getUserStateFromRequest($this->context.'.filter.gateway', 'filter_gateway', '', 'string');
        $this->setState('filter.gateway', $gateway);

        $params = JComponentHelper::getParams('com_deallab');
        $this->setState('params', $params);

        parent::populateState('id', 'desc');
    }

    protected function getStoreId($id = '')
    {
        $id.= ':' . $this->getState('filter.search');
        $id.= ':' . $this->getState('filter.state');

        return parent::getStoreId($id);
    }

    protected function getListQuery()
    {
        $db     = $this->getDbo();
        $query  = $db->getQuery(true);
        $user   = JFactory::getUser();

        $query->select(
            $this->getState(
                'list.select',
                'O.id, O.email, O.deal_id, O.option_id, O.amount, O.status, O.cdate'
            )
        );
        $query->from($db->quoteName('#__deallab_orders').' AS O');

        // Join over the users for the checked out user.
        
        $query->select('D.title AS deal_name');
        $query->join('LEFT', '#__deallab_deals AS D ON D.id=O.deal_id');
		
		$query->select('P.title AS option_name');
        $query->join('LEFT', '#__deallab_prices AS P ON P.id=O.option_id');
		
		$query->select('COUNT(CPN.ID) AS coupons');
        $query->join('LEFT', '#__deallab_coupons AS CPN ON CPN.order_id=O.id');

        // Filter by published state
        $published = $this->getState('filter.state');
		
        if (is_numeric($published)) {
            $query->where('O.status = '.(int) $published);
        } elseif ($published === '') {
            $query->where('(O.status IN (0, 1))');
        }
		
		$gateway = $this->getState('filter.gateway');
		
        if ($gateway != "") {
            $query->where('O.gateway = "' . $gateway . '"');
        }
		
        $search = $this->getState('filter.search');
		
        if (!empty($search)) {
			$search = $db->Quote('%'.$db->escape($search, true).'%');
			$query->where('(O.id LIKE '.$search.' OR O.email LIKE '.$search.' OR D.title LIKE '.$search.' OR P.title LIKE '.$search.')');
        }
		
		$query->group('O.id');

        // Add the list ordering clause.
        $orderCol   = $this->state->get('list.ordering');
        $orderDirn  = $this->state->get('list.direction');
        $query->order($db->escape($orderCol.' '.$orderDirn));
		
        return $query;
    }
}