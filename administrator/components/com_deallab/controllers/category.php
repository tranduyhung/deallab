<?php
/**
 * @version		$Id: category.php 2013-07-17 studio4 $
 * @package		DealLab
 * @author		Studio4 http://www.studio4.lt
 * @copyright	Copyright (c) 2010 - 2013 UAB Studio4. All rights reserved.
 * @license		GNU/GPL license: http://shop.studio4.lt/deallab-license
 */

// no direct access
defined('_JEXEC') or die;

jimport('joomla.application.component.controllerform');

class DealLabControllerCategory extends JControllerForm
{
    protected function allowAdd($data = array())
    {
        // Initialise variables.
        $user = JFactory::getUser();
        $allow = null;

        if ($allow === null)
        {
            // In the absense of better information, revert to the component permissions.
            return parent::allowAdd($data);
        }
        else
        {
            return $allow;
        }
    }

    protected function allowEdit($data = array(), $key = 'id')
    {
        // Initialise variables.
        $recordId = (int) isset($data[$key]) ? $data[$key] : 0;
        
        // Since there is no asset tracking, revert to the component permissions.
        return parent::allowEdit($data, $key);
    }
}
