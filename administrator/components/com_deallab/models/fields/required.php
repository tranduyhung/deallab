<?php
/**
 * @version		$Id: required.php 2013-07-17 studio4 $
 * @package		DealLab
 * @author		Studio4 http://www.studio4.lt
 * @copyright	Copyright (c) 2010 - 2013 UAB Studio4. All rights reserved.
 * @license		GNU/GPL license: http://shop.studio4.lt/deallab-license
 */

// no direct access
defined('_JEXEC') or die;

class JFormFieldRequired extends JFormField
{
    protected $type = 'Required';

    protected function getInput()
    {
        $html = array();
        $attr = '';
		$html[] = '<ul>';
		$html[] = '<li><input class="deallab-checkbox" type="checkbox" name="jform[required][zip]" value="1" />';
		$html[] = JText::_('DEALLAB_ZIP').'</li>';
		$html[] = '<li><input class="deallab-checkbox" type="checkbox" name="jform[required][city]" value="1" />';
		$html[] = JText::_('DEALLAB_CITY').'</li>';
		$html[] = '<li><input class="deallab-checkbox" type="checkbox" name="jform[required][addr]" value="1" />';
		$html[] = JText::_('DEALLAB_ADDRESS').'</li>';
		$html[] = '<li><input class="deallab-checkbox" type="checkbox" name="jform[required][name]" value="1" />';
		$html[] = JText::_('DEALLAB_NAME').'</li>';
		$html[] = '<li><input class="deallab-checkbox" type="checkbox" name="jform[required][phone]" value="1" />';
		$html[] = JText::_('DEALLAB_PHONE').'</li>';
		$html[] = '<li><input class="deallab-checkbox" type="checkbox" name="jform[required][email]" value="1" />';
		$html[] = JText::_('DEALLAB_EMAIL').'</li>';
		$html[] = '</ul>';
        
        return implode($html);
    }
}
