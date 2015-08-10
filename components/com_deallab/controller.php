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
		$city = dlDealHelper::getDefaultCity();
		$deal = dlDealHelper::findDeal($city);
		
		if ($deal) {
			$url = "index.php?option=com_deallab&task=deal";
			if ($city)
				$url .= "&city_id=".$city;
			if ($deal)
				$url .= '&deal_id='.$deal;
			$this->setRedirect(JRoute::_($url));
		} else {
			parent::display();
		}
	}
	
	public function deal()
	{
		$deal_id = JRequest::getVar('deal_id', FALSE);
		
		if (!JRequest::getVar('deal_id', FALSE)) {
			$deal = dlDealHelper::findDeal(JRequest::getVar('city_id', false), JRequest::getVar('category_id', false));
			$deal = explode(':', $deal);
			JRequest::setVar('deal_id', $deal[0]);
		}
			
		JRequest::setVar('view', 'deal');
		parent::display();
	}
	
	public function purchases()
	{
		$user = JFactory::getUser();
		
		if ($user->id) {
			JRequest::setVar('view', 'purchases');
			parent::display();
		} else {
			$return = base64_encode(JRoute::_('index.php?option=com_deallab&view=purchases'));
			$link = JRoute::_('index.php?option=com_users&task=login&return='.$return);
			$this->setRedirect($link, 'Please login', 'error');
			$this->redirect();
		}
			
	}
	
	public function checkout()
	{
		$post = JRequest::get('post', false);
		
		if ($post) {
			$model 		= $this->getModel('checkout');
			$order_data = JRequest::getVar('order', array(), 'post', 'array');
			
			if (($msg = $model->createOrder($order_data)) !== true) {
				$link = JRoute::_('index.php?option=com_deallab&task=checkout&deal_id='.$order_data['deal_id']);
				$this->setRedirect($link, $msg, 'error');
				$this->redirect();
			} else if ($order_data['payment_plugin'] == 'cash-on-delivery') {
				JRequest::setVar('view', 'thankyou');
				return parent::display();
			}
		}
		
		JRequest::setVar('view', 'checkout');
		return parent::display();
	}
	
	public function coupon()
	{
		$coupon = JTable::getInstance('Coupon', 'DealLabTable');
		$id   	= JRequest::getVar('id', null);
		$code 	= JRequest::getVar('code', null);
		
		if (!$id || !$code || !$coupon->load($id))
			die('Bad coupon link');
		
		if ($code != substr(md5($coupon->id . $coupon->code . $coupon->order_id), 5, 10))
			die('Incorect security code');
		
		if ($coupon->status != 1)
			die('Coupon has not been paid yet.');
		
		JRequest::setVar('view', 'coupon');
		JRequest::setVar('tmpl', 'component');
			
		parent::display();
	}
	
	public function preview()
	{
		$deal_id = JRequest::getVar('deal_id', false);
		$hash    = JRequest::getVar('hash', false);
		
		if ($deal_id && $hash) {
			JRequest::setVar('view', 'prev');
		} else {
			JRequest::setVar('view', 'deallab');
		}
		parent::display();
	}
	
	public function sales()
	{
		$deal_id = JRequest::getVar('deal_id', false);
		$hash    = JRequest::getVar('hash', false);
		
		if ($deal_id && $hash) {
			JRequest::setVar('view', 'sales');
		} else {
			JRequest::setVar('view', 'deallab');
		}
		parent::display();
	}
	
	public function callback()
	{
		$model		= $this->getModel('checkout');
		$dispatcher	= JDispatcher::getInstance();
		$plugin		= JRequest::getVar('plugin', false);
		
		if (!$plugin)
			die('No plugin defined');
		
		if (!JPluginHelper::importPlugin('deallab', $plugin))
			die('Plugin not found');
		
		$response = $dispatcher->trigger('dealLabCallback', array());

		die();
	}

	public function thankyou()
	{
		JRequest::setVar('view', 'thankyou');
		return parent::display();
	}
}