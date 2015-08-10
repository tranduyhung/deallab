<?php
/**
 * @version		$Id: checkout.php 2013-07-17 studio4 $
 * @package		DealLab
 * @author		Studio4 http://www.studio4.lt
 * @copyright	Copyright (c) 2010 - 2013 UAB Studio4. All rights reserved.
 * @license		GNU/GPL license: http://shop.studio4.lt/deallab-license
 */

// no direct access
defined('_JEXEC') or die;

jimport('joomla.application.component.modelitem');

class DealLabModelCheckout extends JModelItem
{
	public function getDeal()
	{
		$deal = JRequest::getVar('deal_id', null);
		$deal = explode(':', $deal);
		
		$query = 'SELECT * FROM #__deallab_deals WHERE id = ' . $deal[0];
		
		$this->_db->setQuery($query);
		
		$deal = $this->_db->loadObject();
		
		if (property_exists($deal, 'shipping'))
		{
			$registry = new JRegistry;
			$registry->loadString($deal->shipping);
			$deal->shipping = $registry->toObject();
		}
		
		return $deal;
	}
	
	public function createOrder($data = array())
	{
		$deal    = $this->getTable('Deal', 'DealLabTable');
		$order   = $this->getTable('Order', 'DealLabTable');
		$prices  = $this->getTable('Price', 'DeallabTable');
		$address = $this->getTable('Address', 'DeallabTable');
		$user	 = JFactory::getUser();
		
		if (!$prices->load($data['option_id']))
			return 'DEALLAB_ERROR_NO_PRICE';
		
		$order->bind($data);
		
		$order->gateway = $data['payment_plugin'];
		$order->cdate	= date('Y-m-d H:i:s');
		
		if (!$user->guest && ($user->id > 0))
			$order->user_id = $user->id;
		
		if ($order->address_id == -1) {
			$deal->load($order->deal_id);
			$shipping_info = json_decode($deal->shipping);
			if ($shipping_info->price_type == 1)
				$order->shipping = $shipping_info->shipping_price;
			else if ($shipping_info->price_type == 2)
				$order->shipping = $shipping_info->shipping_price * $data['amount'];
			
			$order_details = array();
			if (isset($data['name']))
				$order_details['name'] = $data['name'];
			if (isset($data['phone']))
				$order_details['phone'] = $data['phone'];
			if (isset($data['address']))
				$order_details['address'] = $data['address'];
			if (isset($data['city']))
				$order_details['city'] = $data['city'];
			if (isset($data['zip']))
				$order_details['zip'] = $data['zip']; 
				  
			$order->details = json_encode($order_details);
		} else {
			$order->shipping = 0;
		}
		
		$order->amount 	 = $prices->price * $data['amount'] + $order->shipping;
		
		if (!$order->check())
			die('error');
		
		if (!$order->store())
			die('error');
		
		for ($i=1;$i<=$data['amount'];$i++) {
			$coupon = $this->getTable('Coupon', 'DealLabTable');
			
			$coupon_data = array();
			$coupon_data['order_id'] = $order->id;
			$coupon_data['deal_id']  = $order->deal_id;
			$coupon_data['code']     = $coupon->generateUniqueCouponCode();
			$coupon_data['cdate']    = date('Y-m-d H:i:s');
			
			$coupon->bind($coupon_data);
			$coupon->check();
			$coupon->store();
		}
		
		if ($data['payment_plugin'] == 'cash-on-delivery') {
			return true;	
		} else {
			$dispatcher	= JDispatcher::getInstance();
			JPluginHelper::importPlugin('deallab', $data['payment_plugin']);
			$result = $dispatcher->trigger('beforeDeallabRedirect', array(&$order));
			echo $result[0];
			die();
		}
		
		return false;
	}

	public function confirmOrder($id)
	{
		$order  = $this->getTable('Order', 'DealLabTable');
		if ($order->load($id))
			return $order->makePaid();
		else
			return false;
	}
}
