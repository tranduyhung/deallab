<?php
/**
 * @version		$Id: view.pdf.php 2013-07-17 studio4 $
 * @package		DealLab
 * @author		Studio4 http://www.studio4.lt
 * @copyright	Copyright (c) 2010 - 2013 UAB Studio4. All rights reserved.
 * @license		GNU/GPL license: http://shop.studio4.lt/deallab-license
 */

// no direct access
defined('_JEXEC') or die;

class DealLabViewCoupon extends JViewLegacy
{
	function display($tpl = null)
	{
		$tpl = 'pdf';
		parent::display();
	}
}
