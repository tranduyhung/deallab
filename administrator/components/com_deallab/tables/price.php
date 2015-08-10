<?php
/**
 * @version		$Id: price.php 2013-07-17 studio4 $
 * @package		DealLab
 * @author		Studio4 http://www.studio4.lt
 * @copyright	Copyright (c) 2010 - 2013 UAB Studio4. All rights reserved.
 * @license		GNU/GPL license: http://shop.studio4.lt/deallab-license
 */

// no direct access
defined('_JEXEC') or die;

class DealLabTablePrice extends JTable
{
    public function __construct($db)
    {
        parent::__construct('#__deallab_prices', 'id', $db);
    }
}