<?php
/**
 * @version		$Id: ordering.php 2013-07-17 studio4 $
 * @package		DealLab
 * @author		Studio4 http://www.studio4.lt
 * @copyright	Copyright (c) 2010 - 2013 UAB Studio4. All rights reserved.
 * @license		GNU/GPL license: http://shop.studio4.lt/deallab-license
 */

// no direct access
defined('_JEXEC') or die;

class JFormFieldOrdering extends JFormField
{
    protected $type = 'Ordering';

    protected function getInput()
    {
        // Initialize variables.
        $html = array();
        $attr = '';

        // Initialize some field attributes.
        $attr .= $this->element['class'] ? ' class="'.(string) $this->element['class'].'"' : '';
        $attr .= ((string) $this->element['disabled'] == 'true') ? ' disabled="disabled"' : '';
        $attr .= $this->element['size'] ? ' size="'.(int) $this->element['size'].'"' : '';

        // Initialize JavaScript field attributes.
        $attr .= $this->element['onchange'] ? ' onchange="'.(string) $this->element['onchange'].'"' : '';

        // Get some field values from the form.
        $categoryId   = (int) $this->form->getValue('id');

        // Build the query for the ordering list.
        $query = 'SELECT ordering AS value, title AS text' .
                ' FROM #__deallab_categories' .
                ' ORDER BY ordering';

        // Create a read-only list (no name) with a hidden input to store the value.
        if ((string) $this->element['readonly'] == 'true') {
            $html[] = JHtml::_('list.ordering', '', $query, trim($attr), $this->value, $categoryId ? 0 : 1);
            $html[] = '<input type="hidden" name="'.$this->name.'" value="'.$this->value.'"/>';
        }
        // Create a regular list.
        else {
            $html[] = JHtml::_('list.ordering', $this->name, $query, trim($attr), $this->value, $categoryId ? 0 : 1);
        }

        return implode($html);
    }
}
