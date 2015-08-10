<?php
/**
 * @version		$Id: coupon.php 2013-07-17 studio4 $
 * @package		DealLab
 * @author		Studio4 http://www.studio4.lt
 * @copyright	Copyright (c) 2010 - 2013 UAB Studio4. All rights reserved.
 * @license		GNU/GPL license: http://shop.studio4.lt/deallab-license
 */

// no direct access
defined('_JEXEC') or die;

class DealLabTableCoupon extends JTable
{
	var $id		     = null;         
	var $deal_id     = null;
	var $order_id    = null;                  
	var $code	     = null;         
	var $cdate	     = null;
	var $pdf 	     = null;  
	
    public function __construct($db)
    {
        parent::__construct('#__deallab_coupons', 'id', $db);
    }
	
	function generateUniqueCouponCode()
	{
		$isUnique = false;
		
		while (!$isUnique)
		{
			$code = md5(time() . rand(10000, 99999));
			$code = strtoupper(substr($code, rand(0, strlen($code) - 10), 10));
			
			$query = 'SELECT COUNT(*) FROM #__deallab_coupons WHERE `code` = "' . $code . '"';
			$this->_db->setQuery($query);
			
			if ($this->_db->loadResult() == 0)
				$isUnique = true;
		}
		
		return $code;
	}
	
	function getCouponPdf()
	{
		if (!$this->pdf) {
			echo 'pdf???';
		} else {
			
		}
	}
}