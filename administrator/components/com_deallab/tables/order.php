<?php
/**
 * @version		$Id: order.php 2013-07-17 studio4 $
 * @package		DealLab
 * @author		Studio4 http://www.studio4.lt
 * @copyright	Copyright (c) 2010 - 2013 UAB Studio4. All rights reserved.
 * @license		GNU/GPL license: http://shop.studio4.lt/deallab-license
 */

// no direct access
defined('_JEXEC') or die;

class DealLabTableOrder extends JTable
{
	var $id		     = null;         
	var $deal_id     = null;
	var $option_id   = null;                  
	var $amount	     = null;         
	var $cdate	     = null;  
	
    public function __construct($db)
    {
        parent::__construct('#__deallab_orders', 'id', $db);
    }
	
	public function makePaid()
	{
		$db = JFactory::getDBO();
		$query = 'UPDATE #__deallab_coupons SET status = 1 WHERE order_id = ' . $this->id;
		$db->setQuery($query);
		$db->query();
		
		$query = 'SELECT COUNT(id) FROM #__deallab_coupons WHERE deal_id = ' . $this->deal_id . ' AND status = 1';
		$db->setQuery($query);
		
		$coupons_sold = $db->loadResult();
		
		$query = 'SELECT min_coupons FROM #__deallab_deals WHERE id = ' . $this->deal_id;
		$db->setQuery($query);
		
		$min_coupons = $db->loadResult();
		
		$this->status = 1;
		$this->store();
		
		if ($min_coupons <= $coupons_sold)
			$this->sendCouponMails();
		else {
			$toBuy = $min_coupons - $coupons_sold;
			$this->sendWaitingMail($toBuy);
		}
			
		
		return true;
	}

	public function sendCouponMails()
	{
		$db		= JFactory::getDBO();
		$config = JFactory::getConfig();
		
		$sender = array( 
    		$config->getValue('config.mailfrom'),
    		$config->getValue('config.fromname')
		);
		
		$query = 'SELECT * FROM #__deallab_orders WHERE mail_sent = 0 AND status = 1 AND deal_id = ' . $this->deal_id;
		$db->setQuery($query);
		$orders = $db->loadObjectList();
		
		$ids = array();
		
		foreach ($orders as $order)
		{
			$mailer = JFactory::getMailer();
			
			$query = 'SELECT * FROM #__deallab_coupons WHERE order_id = ' . $order->id . ' AND status = 1';
			$db->setQuery($query);
			$coupons = $db->loadObjectList();
			
			$links = '';
			
			foreach ($coupons as $coupon) {
				$coupon_link = JRoute::_(JUri::root().'index.php?option=com_deallab&task=coupon&id='.$coupon->id.'&code='.substr(md5($coupon->id . $coupon->code . $coupon->order_id), 5, 10));
				$links .= $coupon->code . " -- " . $coupon_link . "\n";
			}
			
			$mailer->setSender($sender);
			$mailer->addRecipient($order->email);

			$mailer->isHTML(false);
			$mailer->Encoding = 'base64';
		
			$mailer->setSubject(JText::_('DEALLAB_ORDER_CONFIRMED_MAIL_TITLE'));
			$mailer->setBody(JText::sprintf('DEALLAB_ORDER_CONFIRMED_MAIL_BODY', $links));
			
			if ($mailer->Send())
				$ids[] = $order->id;
		}

		$query = 'UPDATE #__deallab_orders SET mail_sent = 1 WHERE id IN('.implode(',', $ids).')';
		$db->setQuery($query);
		$db->query();
		
		return true;
	}
	
	public function sendWaitingMail($toBuy)
	{
		$mailer = JFactory::getMailer();
		$config = JFactory::getConfig();
		
		$sender = array( 
    		$config->getValue('config.mailfrom'),
    		$config->getValue('config.fromname')
		);

		$mailer->setSender($sender);
		$mailer->addRecipient($this->email);

		$mailer->isHTML(false);
		$mailer->Encoding = 'base64';
		
		$mailer->setSubject(JText::_('DEALLAB_ORDER_PENDING_MAIL_TITLE'));
		$mailer->setBody(JText::sprintf('DEALLAB_ORDER_PENDING_MAIL_BODY', $toBuy));
			
		$mailer->Send();
	}
	
	public function makeNotPaid()
	{
		$db = JFactory::getDBO();
		$query = 'UPDATE #__deallab_coupons SET status = 0 WHERE order_id = ' . $this->id;
		$db->setQuery($query);
		$db->query();
		
		$this->status = 0;
		return $this->store();
	}
}