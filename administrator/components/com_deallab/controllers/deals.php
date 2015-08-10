<?php
/**
 * @version		$Id: deals.php 2013-07-17 studio4 $
 * @package		DealLab
 * @author		Studio4 http://www.studio4.lt
 * @copyright	Copyright (c) 2010 - 2013 UAB Studio4. All rights reserved.
 * @license		GNU/GPL license: http://shop.studio4.lt/deallab-license
 */

// no direct access
defined('_JEXEC') or die;

jimport('joomla.application.component.controlleradmin');

class DealLabControllerDeals extends JControllerAdmin
{
    public function getModel($name = 'Deal', $prefix = 'DealLabModel', $config = array('ignore_request' => true))
    {
        $model = parent::getModel($name, $prefix, $config);
        return $model;
    }
}
