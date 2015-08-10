<?php
/**
 * @version		$Id: coupons.php 2013-07-17 studio4 $
 * @package		DealLab
 * @author		Studio4 http://www.studio4.lt
 * @copyright	Copyright (c) 2010 - 2013 UAB Studio4. All rights reserved.
 * @license		GNU/GPL license: http://shop.studio4.lt/deallab-license
 */

// no direct access
defined('_JEXEC') or die;

jimport('joomla.application.component.modellist');

class DealLabModelCoupons extends JModelList
{
    public function __construct($config = array())
    {
        if (empty($config['filter_fields'])) {
            $config['filter_fields'] = array(
                'C.id', 'C.order_id', 'C.deal_id', 'P.price', 'C.code', 'C.status', 'C.cdate'
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

        $params = JComponentHelper::getParams('com_deallab');
        $this->setState('params', $params);

        parent::populateState('C.cdate', 'desc');
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
                'C.id, C.deal_id, C.order_id, C.code, C.status, C.cdate'
            )
        );
        $query->from($db->quoteName('#__deallab_coupons').' AS C');

        $query->select('D.title AS deal_name');
        $query->join('LEFT', '#__deallab_deals AS D ON D.id=C.deal_id');
		
		$query->select('O.option_id AS option_id');
		$query->join('LEFT', '#__deallab_orders AS O ON O.id=C.order_id');
		
		$query->select('P.title AS option_title, P.price AS option_price');
		$query->join('LEFT', '#__deallab_prices AS P ON O.option_id=P.id');
		
        // Filter by published state
        $published = $this->getState('filter.state');
        if (is_numeric($published)) {
            $query->where('C.status = '.(int) $published);
        } elseif ($published === '') {
            $query->where('(C.status IN (0, 1))');
        }
		
        $search = $this->getState('filter.search');
        if (!empty($search)) {
			$search = $db->Quote('%'.$db->escape($search, true).'%');
			$query->where('(C.id LIKE '.$search.' OR C.code LIKE '.$search.' OR P.title LIKE '.$search.' OR D.title LIKE '.$search.')');
        }
		
        $orderCol   = $this->state->get('list.ordering');
        $orderDirn  = $this->state->get('list.direction');
        $query->order($db->escape($orderCol.' '.$orderDirn));

        return $query;
    }
}