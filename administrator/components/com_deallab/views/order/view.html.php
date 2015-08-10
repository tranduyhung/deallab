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

class DealLabViewOrder extends JViewLegacy
{
    protected $state;
    protected $item;
    protected $form;

    public function display($tpl = null)
    {
        $this->state    = $this->get('State');
        $this->item     = $this->get('Item');
        $this->form     = $this->get('Form');

        if (count($errors = $this->get('Errors'))) {
            JError::raiseError(500, implode("\n", $errors));
            return false;
        }
		
		$this->status = array(
			0 => JText::_('DEALLAB_STATUS_PENDING'),
			1 => JText::_('DEALLAB_STATUS_CONFIRMED')
		);
		
		$this->manual = array(
			0 => JText::_('DEALLAB_MANUAL_NO'),
			1 => JText::_('DEALLAB_MANUAL_YES')
		);

        $this->addToolbar();
        parent::display($tpl);
    }

    protected function addToolbar()
    {
        JRequest::setVar('hidemainmenu', true);

        JToolBarHelper::title(JText::_('DEALLAB_TITLE_ORDER_EDIT'), 'deallab.png');

        if ($this->item->state) {
            JToolBarHelper::unpublish('order.unpublish', 'DEALLAB_MAKE_NOT_PAYED');
        } else {
        	JToolBarHelper::publish('order.publish', 'DEALLAB_MAKE_PAYED');
        }
		
		JToolBarHelper::divider();
		
		JToolBarHelper::apply('order.apply');
		JToolBarHelper::cancel('order.cancel');
    }
}
