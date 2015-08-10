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

class DealLabViewCategories extends JViewLegacy
{
    protected $items;
    protected $pagination;
    protected $state;

    public function display($tpl = null)
    {
        $this->state        = $this->get('State');
        $this->items        = $this->get('Items');
        $this->pagination   = $this->get('Pagination');

        if (count($errors = $this->get('Errors'))) {
            JError::raiseError(500, implode("\n", $errors));
            return false;
        }

        $this->addToolbar();
        parent::display($tpl);
    }

    protected function addToolbar()
    {
        JToolBarHelper::title(JText::_('DEALLAB_TITLE_CATEGORIES'), 'deallab.png');
        
		JToolBarHelper::addNew('category.add');
		JToolBarHelper::editList('category.edit');

		JToolBarHelper::divider();
		
		JToolBarHelper::publish('categories.publish', 'JTOOLBAR_PUBLISH', true);
		JToolBarHelper::unpublish('categories.unpublish', 'JTOOLBAR_UNPUBLISH', true);
		
		JToolBarHelper::divider();

		JToolBarHelper::trash('categories.trash');
        
        JToolBarHelper::divider();
        
		JToolBarHelper::preferences('com_deallab');
    }
}
