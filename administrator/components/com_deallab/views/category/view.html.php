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

jimport('joomla.application.component.view');

class DealLabViewCategory extends JView
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

        JToolBarHelper::title(JText::_('DEALLAB_TITLE_CATEGORY_EDIT'), 'deallab.png');

		JToolBarHelper::apply('category.apply');
		JToolBarHelper::save('category.save');
		JToolBarHelper::save2new('category.save2new');

        if (!$isNew) {
            JToolBarHelper::save2copy('category.save2copy');
        }
        if (empty($this->item->id)) {
            JToolBarHelper::cancel('category.cancel');
        } else {
            JToolBarHelper::cancel('category.cancel', 'JTOOLBAR_CLOSE');
        }
    }
}
