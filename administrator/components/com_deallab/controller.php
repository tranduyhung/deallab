<?php
/**
 * @version		$Id: controller.php 2013-07-17 studio4 $
 * @package		DealLab
 * @author		Studio4 http://www.studio4.lt
 * @copyright	Copyright (c) 2010 - 2013 UAB Studio4. All rights reserved.
 * @license		GNU/GPL license: http://shop.studio4.lt/deallab-license
 */

// no direct access
defined('_JEXEC') or die;

class DealLabController extends JControllerLegacy
{
	public function display($cachable = false, $urlparams = false)
	{
		if (!JRequest::getVar('view', null))
			JRequest::setVar('view', 'deals');
		
		parent::display();
		
		return $this;		
	}
	
    public function ajaxReturnEditor(){
        $name   = $_POST['name'];
        $value  = $_POST['value'];
        $width  = $_POST['width'] ? $_POST['width'] : 500;
        $height = $_POST['height'] ? $_POST['height'] : 300;
        $editor =& JFactory::getEditor();
        
        if($name){
            echo $editor->display($name, $value, $width, $height, null, null, false);
        }
        die();
    }
}
