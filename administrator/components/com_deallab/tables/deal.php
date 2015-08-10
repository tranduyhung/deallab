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



class DealLabTableDeal extends JTable
{
	protected $is_new = false;
	
	public function __construct($db)
	{
		parent::__construct('#__deallab_deals', 'id', $db);
	}

	public function bind($array, $ignore = '')
	{
		if (isset($array['shipping']) && is_array($array['shipping'])) {
			$registry = new JRegistry();
			$registry->loadArray($array['shipping']);
			$array['shipping'] = (string)$registry;
		}
		if (isset($array['options']) && is_array($array['options'])) {
			$registry = new JRegistry();
			$registry->loadArray($array['options']);
			$array['options'] = (string)$registry;
		}
		
		return parent::bind($array, $ignore);
	}

	public function store($updateNulls = false)
	{
		if (!$this->id)
			$this->is_new = true;
		
		$result = parent::store($updateNulls);
		
		if ($result) {
			$images 		= JRequest::getVar('images', array(), 'post', 'array');
			$prices 		= JRequest::getVar('prices', array(), 'post', 'array');
			$cities 		= JRequest::getVar('cities', array(), 'post', 'array');
			$categories 	= JRequest::getVar('categories', array(), 'post', 'array');
			$addresses		= JRequest::getVar('addrs', array(), 'post', 'array');
			$customFields	= JRequest::getVar('customfields', array(), 'post', 'array');
			
			$this->storeImages($images);
			$this->storePrices($prices); 
			$this->storeCities($cities);
			$this->storeCategories($categories);
			$this->storeAddresses($addresses);
			$this->storeCustomfields($customFields);
		}
		
		return $result;
	}
	
	public function publish($pks = null, $state = 1, $userId = 0)
	{
		$k = $this->_tbl_key;

		JArrayHelper::toInteger($pks);
		$userId = (int) $userId;
		$state  = (int) $state;

		if (empty($pks))
		{
			if ($this->$k) {
				$pks = array($this->$k);
			} else {
				$this->setError(JText::_('JLIB_DATABASE_ERROR_NO_ROWS_SELECTED'));
				return false;
			}
		}

		$where = $k.'='.implode(' OR '.$k.'=', $pks);

		if (!property_exists($this, 'checked_out') || !property_exists($this, 'checked_out_time')) {
			$checkin = '';
		} else {
			$checkin = ' AND (checked_out = 0 OR checked_out = '.(int) $userId.')';
		}

		$this->_db->setQuery(
			'UPDATE '.$this->_db->quoteName($this->_tbl) .
			' SET '.$this->_db->quoteName('state').' = '.(int) $state .
			' WHERE ('.$where.')' .
			$checkin
		);
		$this->_db->query();

		if ($this->_db->getErrorNum()) {
			$this->setError($this->_db->getErrorMsg());
			return false;
		}

		if ($checkin && (count($pks) == $this->_db->getAffectedRows()))
		{
			foreach($pks as $pk)
			{
				$this->checkin($pk);
			}
		}

		if (in_array($this->$k, $pks)) {
			$this->state = $state;
		}

		$this->setError('');
		return true;
	}

	private function storeImages($images)
	{
		foreach ($images as $image) {
			$table = JTable::getInstance('Image', 'DealLabTable');
			
			$image['deal_id'] = $this->id;
			
			if (count($images) == 1)	
				$image['default'] = 1;
			
			if ($image['status'] == 0) {
				if (isset($image['id'])) {
					if ($table->load($image['id'])) {
						$table->removeImages();
						$table->delete();
					}
				} else {
					jimport('joomla.filesystem.file');
					JFile::delete(DL_IMG_ROOT . $image['filename']);		
				}
			} else {
				if (empty($image['id'])) {
					$image['original'] = $image['filename'];				
					unset($image['filename']);
					$table->bind($image);
					$table->resizeImage();					
				} else {
					$table->bind($image);
				}
				
				if (empty($table->ordering) || ($table->ordering == 0)) {
                	$this->_db->setQuery('SELECT MAX(ordering) FROM #__deallab_images WHERE deal_id = ' . $table->deal_id);
                	$max = $this->_db->loadResult();

                	$table->ordering = $max+1;
				}
				$table->store();
			}	
		}		
	}

	private function storePrices($prices)
	{
		$this->_db->setQuery('SELECT id FROM #__deallab_prices WHERE deal_id = ' . $this->id);
		$cur_prices = $this->_db->loadResultArray();
		
		foreach ($prices as $price) {
			$table = JTable::getInstance('Price', 'DealLabTable');
			$price['deal_id'] = $this->id;
			$price['published'] = 1;
			
			if (isset($price['id']) && $this->is_new)
				unset($price['id']);
						
			if (isset($price['id']) && (in_array($price['id'], $cur_prices)))
				$cur_prices = array_diff($cur_prices, array($price['id']));
			
			$table->bind($price);
			$table->check();
			$table->store();
		}

		if (count($cur_prices)) {
			$query = 'DELETE FROM #__deallab_prices WHERE id IN (' . implode(',', $cur_prices) .')';
			$this->_db->setQuery($query);
			$this->_db->query();
		}
		
		return true;
	}
	
	private function storeCities($cities) 
	{
		$this->_db->setQuery('SELECT city_id FROM #__deallab_deal_cities WHERE deal_id = ' . $this->id);
		$cur_cities = $this->_db->loadResultArray();
		
		foreach ($cities as $id => $val) {			
			if (in_array($id, $cur_cities)) {
				$cur_cities = array_diff($cur_cities, array($id));
			} else {
				$query = 'INSERT INTO #__deallab_deal_cities (`deal_id`, `city_id`) VALUES ('.$this->id.', '.$id.')';				
				$this->_db->setQuery($query);
				$this->_db->query();				
			}			
		}

		if (count($cur_cities) > 0) {
			$query = 'DELETE FROM #__deallab_deal_cities WHERE deal_id = ' . $this->id . ' AND city_id IN (' . implode(',', $cur_cities) .')';
			$this->_db->setQuery($query);
			$this->_db->query();
		}
		return true;
	}
	
	private function storeCategories($categories) 
	{
		$this->_db->setQuery('SELECT category_id FROM #__deallab_deal_categories WHERE deal_id = ' . $this->id);
		$cur_cats = $this->_db->loadResultArray();
		
		foreach ($categories as $id => $val) {			
			if (in_array($id, $cur_cats)) {
				$cur_cats = array_diff($cur_cats, array($id));
			} else {
				$query = 'INSERT INTO #__deallab_deal_categories (`deal_id`, `category_id`) VALUES ('.$this->id.', '.$id.')';				
				$this->_db->setQuery($query);
				$this->_db->query();				
			}			
		}
		
		if (count($cur_cats) > 0) {
			$query = 'DELETE FROM #__deallab_deal_categories WHERE deal_id = ' . $this->id . ' AND category_id IN (' . implode(',', $cur_cats) .')';
			$this->_db->setQuery($query);
			$this->_db->query();
		}
		return true;
	}
	
	private function storeAddresses($addresses)
    {
        $this->_db->setQuery('SELECT id FROM #__deallab_addresses WHERE deal_id = ' . $this->id);
        $cur_addrs = $this->_db->loadResultArray();
        
        foreach ($addresses as $key => $addr) {
        	$table = JTable::getInstance('Address', 'DealLabTable');
            
            $addr['deal_id'] = $this->id;
			
			if ($this->is_new && isset($addr['id']))
				unset ($addr['id']);
			
            if (in_array($addr['id'], $cur_addrs))
                $cur_addrs = array_diff($cur_addrs, array($addr['id']));
			
            $table->bind($addr);
            $table->check();
            $table->store();
        }
        
        if (count($cur_addrs) > 0) {
            $query = 'DELETE FROM #__deallab_addresses WHERE id IN (' . implode(',', $cur_addrs) .')';
            $this->_db->setQuery($query);
            $this->_db->query();
        }
        return true;
    }


    private function storeCustomfields($customfields)
    {
        $this->_db->setQuery('SELECT id FROM #__deallab_customfields WHERE deal_id = ' . $this->id);
        $cur_customfields = $this->_db->loadResultArray();
        
        foreach ($customfields as $customfield) {
        	$table = JTable::getInstance('Customfield', 'DealLabTable');
            
            $customfield['deal_id'] = $this->id;
			
            if (in_array($customfield['id'], $cur_customfields))
                $cur_customfields = array_diff($cur_customfields, array($customfield['id']));
			
            $table->bind($customfield);
            $table->check();
            $table->store();
        }
        
        if (count($cur_customfields) > 0) {
            $query = 'DELETE FROM #__deallab_customfields WHERE id IN (' . implode(',', $cur_customfields) .')';
            $this->_db->setQuery($query);
            $this->_db->query();
        }
        
        return true;
    }
}