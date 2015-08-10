<?php
/**
 * @version		$Id: ajax.php 2013-07-17 studio4 $
 * @package		DealLab
 * @author		Studio4 http://www.studio4.lt
 * @copyright	Copyright (c) 2010 - 2013 UAB Studio4. All rights reserved.
 * @license		GNU/GPL license: http://shop.studio4.lt/deallab-license
 */

// no direct access
defined('_JEXEC') or die;

jimport('joomla.application.component.modelitem');

class DealLabModelAjax extends JModelItem
{
	public function addCity($title = null, $user_id = null) {
		$table = $this->getTable('City', 'DealLabTable');
				
		if ($title) {			
			$data = array(
				'title' => $title,
				'alias' => JApplication::stringURLSafe($title),
				'state' => 1,
			);
			$table->bind($data);
			
			$db = JFactory::getDbo();
			$db->setQuery('SELECT MAX(ordering) FROM #__deallab_cities');
			$max = $db->loadResult();
			$table->ordering = $max+1;
			
			if ($table->check()) {
				if ($table->store())
					return $table->id;
			} else {
				return -1;
			}		
		}
		
		return false;
	}

	public function addCategory($title, $user_id)
	{
		$table = $this->getTable('Category', 'DealLabTable');
		
		if ($title) {			
			$data = array(
				'title' => $title,
				'alias' => JApplication::stringURLSafe($title),
				'state' => 1
			);
			$table->bind($data);
			
			$db = JFactory::getDbo();
			$db->setQuery('SELECT MAX(ordering) FROM #__deallab_categories');
			$max = $db->loadResult();
			$table->ordering = $max+1;
			
			if ($table->check()) {
				if ($table->store())
					return $table->id;
			} else {
				return -1;
			}
							
		}
		
		return false;
	}
}