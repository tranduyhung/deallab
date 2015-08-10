<?php
/**
 * @version		$Id: view.html.php 2013-07-17 studio4 $
 * @package		DealLab
 * @author		Studio4 http://www.studio4.lt
 * @copyright	Copyright (c) 2010 - 2013 UAB Studio4. All rights reserved.
 * @license		GNU/GPL license: http://shop.studio4.lt/deallab-license
 */

// no direct access
defined('_JEXEC') or die;

class DealLabViewDeal extends JViewLegacy
{
	protected $state;
	protected $item;
	protected $form;

	public function display($tpl = null)
	{
		$this->state	  = $this->get('State');
		$this->item		  = $this->get('Item');
		$this->form	      = $this->get('Form');
		
		$this->params     = JComponentHelper::getParams('com_deallab');
		
		if (count($errors = $this->get('Errors'))) {
			JError::raiseError(500, implode("\n", $errors));
			return false;
		}
		
		$this->addToolbar();
		parent::display($tpl);
	}

	protected function addToolbar()
	{
		JRequest::setVar('hidemainmenu', true);

		$user		= JFactory::getUser();
		$userId		= $user->get('id');
		$isNew		= ($this->item->id == 0);
		$checkedOut	= !($this->item->checked_out == 0 || $this->item->checked_out == $user->get('id'));
		
		if ($isNew)
			JToolBarHelper::title(JText::_('DEALLAB_TITLE_DEAL_NEW'), 'deallab.png');
		else
			JToolBarHelper::title(JText::_('DEALLAB_TITLE_DEAL_EDIT'), 'deallab.png');
		
		if (!$checkedOut) {
			JToolBarHelper::apply('deal.apply');
			JToolBarHelper::save('deal.save');
		}
		
		if (!$checkedOut)
			JToolBarHelper::save2new('deal.save2new');
		
		if (!$isNew)
			JToolBarHelper::save2copy('deal.save2copy');

		if (empty($this->item->id)) {
			JToolBarHelper::cancel('deal.cancel');
		} else {
			JToolBarHelper::cancel('deal.cancel', 'JTOOLBAR_CLOSE');
		}
	}
}
