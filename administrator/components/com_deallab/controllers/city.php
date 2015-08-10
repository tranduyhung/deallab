<?php
/**
 * @version		$Id: city.php 2013-07-17 studio4 $
 * @package		DealLab
 * @author		Studio4 http://www.studio4.lt
 * @copyright	Copyright (c) 2010 - 2013 UAB Studio4. All rights reserved.
 * @license		GNU/GPL license: http://shop.studio4.lt/deallab-license
 */

// no direct access
defined('_JEXEC') or die;

jimport('joomla.application.component.controllerform');

class DealLabControllerCity extends JControllerForm
{
    protected function allowAdd($data = array())
    {
        $user = JFactory::getUser();
        $allow = null;

        if ($allow === null)
        {
            return parent::allowAdd($data);
        }
        else
        {
            return $allow;
        }
    }

    protected function allowEdit($data = array(), $key = 'id')
    {
        $recordId = (int) isset($data[$key]) ? $data[$key] : 0;
        
        return parent::allowEdit($data, $key);
    }
}
