<?php
/**
 * @version		$Id: order.php 2013-07-17 studio4 $
 * @package		DealLab
 * @author		Studio4 http://www.studio4.lt
 * @copyright	Copyright (c) 2010 - 2013 UAB Studio4. All rights reserved.
 * @license		GNU/GPL license: http://shop.studio4.lt/deallab-license
 */

// no direct access
defined('_JEXEC') or die;

jimport('joomla.application.component.modeladmin');

class DealLabModelOrder extends JModelAdmin
{
    protected function canDelete($record)
    {
        if (!empty($record->id)) {
            if ($record->state != -2) {
                return ;
            }
            $user = JFactory::getUser();

            if ($record->catid) {
                return $user->authorise('core.delete', 'com_deallab.order.'.(int) $record->catid);
            }
            else {
                return parent::canDelete($record);
            }
        }
    }

    protected function canEditState($record)
    {
        $user = JFactory::getUser();

		return parent::canEditState($record);
    }
    
    public function getTable($type = 'Order', $prefix = 'DealLabTable', $config = array())
    {
        return JTable::getInstance($type, $prefix, $config);
    }

    public function getForm($data = array(), $loadData = true)
    {
        $app    = JFactory::getApplication();

        $form = $this->loadForm('com_deallab.order', 'order', array('control' => 'jform', 'load_data' => $loadData));
        if (empty($form)) {
            return false;
        }


        if (!$this->canEditState((object) $data)) {
            $form->setFieldAttribute('ordering', 'disabled', 'true');
            $form->setFieldAttribute('state', 'disabled', 'true');

            $form->setFieldAttribute('ordering', 'filter', 'unset');
            $form->setFieldAttribute('state', 'filter', 'unset');
        }

        return $form;
    }

    protected function loadFormData()
    {
        $data = JFactory::getApplication()->getUserState('com_deallab.edit.order.data', array());
        if (empty($data)) {
            $data = $this->getItem();
        }

        return $data;
    }

    public function getItem($pk = null)
    {
        if ($item = parent::getItem($pk)) {
            $registry = new JRegistry;
        }
		
		$query = 'SELECT title FROM #__deallab_deals WHERE id = ' . $item->deal_id;
		$this->_db->setQuery($query);
		
		$item->deal_title = $this->_db->loadResult();
		
		$query = 'SELECT title FROM #__deallab_prices WHERE id = ' . $item->option_id;
		$this->_db->setQuery($query);
		
		$item->option_title = $this->_db->loadResult();
		
		$query = 'SELECT * FROM #__deallab_coupons WHERE order_id = ' . $item->id;
		$this->_db->setQuery($query);
		
		$item->coupons = $this->_db->loadObjectList();
		
		$query = 'SELECT * FROM #__deallab_callbacks WHERE order_id = ' . $item->id . ' ORDER BY cdate DESC';
		$this->_db->setQuery($query);
		
		$item->callbacks = $this->_db->loadObjectList();
			
        return $item;
    }
	
	public function makePaid($id) 
	{
		$order = $this->getTable();

		if ($order->load($id)) { 
			if ($order->makePaid())
				return $this->setCallback($id, 1, 1);
			else
				return false;
		} else 
			return false;
	}
	
	public function makeNotPaid($id) 
	{
		$order = $this->getTable();

		if ($order->load($id)) { 
			if ($order->makeNotPaid())
				return $this->setCallback($id, 0, 1);
			else
				return false;
		} else 
			return false;
	}
	
	public function store($data)
	{
		$table = $this->getTable();
		$table->bind($data);
		
		return $table->store();
	}
	
	private function setCallback($order_id, $status = 0, $manual = 0) {
		$callback = $this->getTable('Callback');
		
		$data = array(
			'order_id' => $order_id,
			'status'   => $status,
			'manual'   => $manual,
			'cdate'	   => date('Y-m-d H:i:s')
		);
		
		$callback->bind($data);
		$callback->check();
		
		return $callback->store();
	}
}
