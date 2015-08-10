<?php
/**
 * @version		$Id: image.php 2013-07-17 studio4 $
 * @package		DealLab
 * @author		Studio4 http://www.studio4.lt
 * @copyright	Copyright (c) 2010 - 2013 UAB Studio4. All rights reserved.
 * @license		GNU/GPL license: http://shop.studio4.lt/deallab-license
 */

// no direct access
defined('_JEXEC') or die;

class DealLabTableImage extends JTable
{
	var $id    	   = null;         
	var $deal_id   = null;
	var $original  = null;
	var $ordering  = null;
	
	protected $suffixes = array(
		'thumb', 'small', 'medium', 'large'
	);
	
    public function __construct($db)
    {
        parent::__construct('#__deallab_images', 'id', $db);
    }
	
	public function resizeImage()
	{
		jimport('joomla.filesystem.file');
		if (!JFolder::exists($path))
			JFolder::create(DL_IMG_ROOT, 0777);
		$params = JComponentHelper::getParams('com_deallab');		
		$src = DL_IMG_ROOT . $this->original;
		$ext = strtolower(JFile::getExt($src));
		
		if (in_array($ext, array('jpg', 'jpeg', 'png', 'gif'))) {
			list($srcW, $srcH, $srcType) = GetImageSize($src);
			
			switch ($ext) {
				case 'jpg':
				case 'jpeg':
					$srcRes = imagecreatefromjpeg($src);
					break;
				case 'png':
					$srcRes = imagecreatefrompng($src);
					break;
				case 'gif':
					$srcRes = imagecreatefromgif($src);
					break;
			}
			
			foreach ($this->suffixes as $suffix) {
				$w = $params->get($suffix.'_w');
				$h = $params->get($suffix.'_h');
				$newFilename = $suffix . '_' . $this->original;
				
				if ($srcW/$srcH > $w/$h) {
					$newH = $srcH;
					$newY = 0;
							
					$newW = floor($srcH * $w/$h);
					$newX = floor(($srcW - $newW) / 2);
				} else {
					$newW = $srcW;
					$newX = 0;
							
					$newH = floor($srcW * $h/$w);
					$newY = floor(($srcH - $h) / 2);
				}
				
				$destRes = imagecreatetruecolor($w, $h);
				imagecopyresampled ($destRes, $srcRes, 0, 0, $newX, $newY, $w, $h, $newW, $newH);
				
				switch ($ext) {
					case 'jpg':
					case 'jpeg':
						imagejpeg($destRes, DL_IMG_ROOT . $newFilename, 100);
						break;
					case 'png':
						imagepng($destRes, DL_IMG_ROOT . $newFilename, 1);
						break;
					case 'gif':
						imagegif($destRes, DL_IMG_ROOT . $newFilename, 100);
						break;
				}
				
				imagedestroy($destRes);
			}
			
			imagedestroy($srcRes);
				
		}		
	}

	public function removeImages()
	{
		jimport('joomla.filesystem.file');
		JFile::delete(DL_IMG_ROOT . $this->original);
		foreach ($this->suffixes as $suffix) {
			JFile::delete(DL_IMG_ROOT . $suffix . '_' . $this->original);
		}		
	}
}