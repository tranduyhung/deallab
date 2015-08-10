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

jimport('joomla.application.component.controllerform');

class DealLabControllerDeal extends JControllerForm
{
	public function preview($key = null, $urlVar = null)
	{
		$app   	 	= JFactory::getApplication();
		$context 	= "$this->option.edit.$this->context";
		$this->task = 'apply';
		
		$save	= parent::save($key, $urlVar);
		$id		= $app->getUserState($context . '.id');
		$id		= $id[0];
		
		$deal = $this->getModel('Deal'); 
		$deal->sendPreview($id);
		
		return true;
	}
}