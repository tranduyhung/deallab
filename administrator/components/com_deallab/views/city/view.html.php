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

class DealLabViewCity extends JViewLegacy
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

        $this->addToolbar();
        parent::display($tpl);
    }

    protected function addToolbar()
    {
        $isNew      = ($this->item->id == 0);

        JToolBarHelper::title(JText::_('DEALLAB_TITLE_CITY_EDIT'), 'deallab.png');

		JToolBarHelper::apply('city.apply');
		JToolBarHelper::save('city.save');
		JToolBarHelper::save2new('city.save2new');

        if (!$isNew) {
            JToolBarHelper::save2copy('city.save2copy');
        }
		
        if (empty($this->item->id)) {
            JToolBarHelper::cancel('city.cancel');
        } else {
            JToolBarHelper::cancel('city.cancel', 'JTOOLBAR_CLOSE');
        }
    }
}
