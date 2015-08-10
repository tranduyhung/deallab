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

jimport('joomla.application.component.modeladmin');

class DealLabModelDeal extends JModelAdmin
{
	public function getTable($type = 'Deal', $prefix = 'DealLabTable', $config = array())
	{
		return JTable::getInstance($type, $prefix, $config);
	}

	public function getForm($data = array(), $loadData = true)
	{
		$app	= JFactory::getApplication();

		$form = $this->loadForm('com_deallab.deal', 'deal', array('control' => 'jform', 'load_data' => $loadData));

		if (empty($form)) {
			return false;
		}

		return $form;
	}

	protected function loadFormData()
	{
		$data = JFactory::getApplication()->getUserState('com_deallab.edit.deal.data', array());
		if (empty($data))
			$data = $this->getItem();
		
		return $data;
	}

	public function getItem($pk = null)
	{
		if ($item = parent::getItem($pk)) {
			if (property_exists($item, 'shipping'))
			{
				$registry = new JRegistry;
				$registry->loadString($item->shipping);
				$item->shipping = $registry->toArray();
			}
			if (property_exists($item, 'options'))
			{
				$registry = new JRegistry;
				$registry->loadString($item->options);
				$item->options = $registry->toArray();
			}
		}

		return $item;
	}
	
	protected function prepareTable($table)
	{
		$date = JFactory::getDate();
		$user = JFactory::getUser();

		$table->title = htmlspecialchars_decode($table->title, ENT_QUOTES);
		$table->alias = JApplication::stringURLSafe($table->alias);

		if (empty($table->alias)) {
			$table->alias = JApplication::stringURLSafe($table->title);
		}

		if (empty($table->id)) {
			if (empty($table->ordering)) {
				$db = JFactory::getDbo();
				$db->setQuery('SELECT MAX(ordering) FROM #__deallab_deals');
				$max = $db->loadResult();

				$table->ordering = $max+1;
			}
		}
	}

	protected function getReorderConditions($table)
	{
		$condition = array();
		
		return $condition;
	}
	
	public function sendPreview($id)
	{
		$table	= $this->getTable();
		 
		if ($table->load($id) && ($table->merchant_email != "")) {
			$mailer = JFactory::getMailer();
			$config = JFactory::getConfig();
			
			$sender = array( 
    			$config->getValue('config.mailfrom'),
    			$config->getValue('config.fromname')
			);
 			
			$preview_link	= JRoute::_(JUri::root().'index.php?option=com_deallab&task=preview&deal_id='.$table->id.'&hash='.substr(md5($table->id . $table->publish_up), 10, -10));
			$sales_link		= JRoute::_(JUri::root().'index.php?option=com_deallab&task=sales&deal_id='.$table->id.'&hash='.substr(md5($table->id . $table->publish_up . $table->publish_down), 10, -10));
			
			$mailer->setSender($sender);
			$mailer->addRecipient($table->merchant_email);
			
			$mailer->isHTML(true);
			
			$title	= JText::_('DEALLAB_EMAIL_PREVIEW_TITLE');
			$body	= JText::sprintf('DEALLAB_EMAIL_PREVIEW_BODY', $preview_link, $sales_link);
			
			$mailer->setSubject($title);
			$mailer->setBody($body);
			
			return $mailer->Send();
		} else {
			return false;
		}
	}
}
