<?php
/**
 * @version		$Id: ajax.php 2013-07-17 studio4 $
 * @package		DealLab
 * @author		Studio4 http://www.studio4.lt
 * @copyright	Copyright (c) 2010 - 2013 UAB Studio4. All rights reserved.
 * @license		GNU/GPL license: http://shop.studio4.lt/deallab-license
 */

// no direct access
defined('_JEXEC') or die;

class DealLabControllerAjax extends JControllerLegacy
{
	public function contact() {
			
		JRequest::checkToken() or die('Invalid Token');
		$data = JRequest::get('post', false);
		
		if (($data['contact-name'] == "") || ($data['contact-email'] == "") || ($data['contact-message'] == ""))
			die('Fill all fields');
		
		$mailer =& JFactory::getMailer();
		$config =& JFactory::getConfig();
		
		$sender = array( 
    		$data['contact-email'],
    		$data['contact-name']);
 
		$mailer->setSender($sender);
		$mailer->addRecipient($config->getValue('config.mailfrom'));
		$mailer->setBody($data['contact-message']);
		$mailer->isHTML(false);
		
		if ($mailer->send())
			die('OK');
		else
			die('Error when sending e-mail. Please try again.');
	}
}