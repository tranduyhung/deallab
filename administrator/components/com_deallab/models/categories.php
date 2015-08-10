<?php
/**
 * @version		$Id: categories.php 2013-07-17 studio4 $
 * @package		DealLab
 * @author		Studio4 http://www.studio4.lt
 * @copyright	Copyright (c) 2010 - 2013 UAB Studio4. All rights reserved.
 * @license		GNU/GPL license: http://shop.studio4.lt/deallab-license
 */

// no direct access
defined('_JEXEC') or die;

jimport('joomla.application.component.modellist');

class DealLabModelCategories extends JModelList
{
    public function __construct($config = array()) {
		if (empty($config['filter_fields'])) {
			$config['filter_fields'] = array('id', 'title', 'alias', 'state', 'published');
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

		parent::populateState('ordering', 'asc');
	}

    protected function getListQuery()
    {
        $db     = $this->getDbo();
        $query  = $db->getQuery(true);
        $user   = JFactory::getUser();

        $query->select(
            $this->getState(
                'list.select',
                'id, title, alias, state, ordering'
            )
        );
		
        $query->from($db->quoteName('#__deallab_categories'));

        $published = $this->getState('filter.state');
        if (is_numeric($published)) {
            $query->where('state = '.(int) $published);
        } elseif ($published === '') {
            $query->where('(state IN (0, 1))');
        }

        $search = $this->getState('filter.search');
        if (!empty($search)) {
            if (stripos($search, 'id:') === 0) {
                $query->where('id = '.(int) substr($search, 3));
            } else {
                $search = $db->Quote('%'.$db->escape($search, true).'%');
                $query->where('(title LIKE '.$search.' OR alias LIKE '.$search.')');
            }
        }

        $orderCol   = $this->state->get('list.ordering');
        $orderDirn  = $this->state->get('list.direction');
        $query->order($db->escape($orderCol.' '.$orderDirn));

        return $query;
    }
}