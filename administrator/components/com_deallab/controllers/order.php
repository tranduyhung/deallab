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

jimport('joomla.application.component.controllerform');

class DealLabControllerOrder extends JControllerForm
{
	public function publish()
	{
		$id = JRequest::getVar('id', 0);
		
		$model =& $this->getModel();
		
		if ($model->makePaid($id)) {
			$msg = JText::_('DEALLAB_INFO_PAID');
			$type = 'info';
		} else {
			$msg = JText::_('DEALLAB_ERROR_ORDER_STATE');
			$type = 'error';
		} 
		
		$this->setRedirect('index.php?option=com_deallab&view=order&layout=edit&id='.$id, $msg, $type);
		$this->redirect();
	}
	
	public function unpublish()
	{
		$id = JRequest::getVar('id', 0);
		
		$model =& $this->getModel();
		
		if ($model->makeNotPaid($id)) {
			$msg = JText::_('DEALLAB_INFO_NOT_PAID');
			$type = 'info';
		} else {
			$msg = JText::_('DEALLAB_ERROR_ORDER_STATE');
			$type = 'error';
		} 
		
		$this->setRedirect('index.php?option=com_deallab&view=order&layout=edit&id='.$id, $msg, $type);
		$this->redirect();
	}
	
	public function save($key = null, $urlVar = null)
	{
		JSession::checkToken() or jexit(JText::_('JINVALID_TOKEN'));

		$app        = JFactory::getApplication();
		$lang       = JFactory::getLanguage();
		$model      = $this->getModel();
		
		$data       = JRequest::getVar('order', array(), 'post', 'array');
		$task       = $this->getTask();
		
		$model->store($data);

		if (!$model->save($data))
		{
			$this->setError(JText::sprintf('JLIB_APPLICATION_ERROR_SAVE_FAILED', $model->getError()));
			$this->setMessage($this->getError(), 'error');

			$this->setRedirect(JRoute::_('index.php?option=' . $this->option . '&view=' . $this->view_item));

			return false;
		}

		$this->setMessage(
			JText::_(
				($lang->hasKey($this->text_prefix . ($recordId == 0 && $app->isSite() ? '_SUBMIT' : '') . '_SAVE_SUCCESS')
					? $this->text_prefix
					: 'JLIB_APPLICATION') . ($recordId == 0 && $app->isSite() ? '_SUBMIT' : '') . '_SAVE_SUCCESS'
			)
		);

		switch ($task)
		{
			case 'apply':
				$this->setRedirect('index.php?option=' . $this->option . '&view=order&layout=edit&id='.$data['id']);
				break;

			default:
				$this->releaseEditId($context, $recordId);
				$app->setUserState($context . '.data', null);
				
				$this->setRedirect('index.php?option=' . $this->option . '&view=orders');
				break;
		}

		$this->postSaveHook($model, $validData);

		return true;
	}
}