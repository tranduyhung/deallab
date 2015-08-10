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

jimport('joomla.application.component.controlleradmin');

class DealLabControllerAjax extends JControllerAdmin
{
	public function addCity()
	{
		$title = JRequest::getVar('fieldValue', null);
		$model = $this->getModel('Ajax');
		$user  = JFactory::getUser();
		
		$city_id = $model->addCity($title, $user->id);
		
		echo $city_id;
		die();
	}
	
	public function addCategory()
	{
		$title = JRequest::getVar('fieldValue', null);
		$model = $this->getModel('Ajax');
		$user  = JFactory::getUser();
		
		$cat_id = $model->addCategory($title, $user->id);
		
		echo $cat_id;
		die();
	}
	
	public function dealImageUpload()
    {
    	jimport('joomla.filesystem.file');
		
		$file = DL_IMG_ROOT . "temp_".basename($_FILES['uploadfile']['name']);
		$file_name = "temp_".basename($_FILES['uploadfile']['name']);
		
		if(!JFolder::exists(DL_IMG_ROOT))
			JFolder::create(DL_IMG_ROOT);
		
		if (move_uploaded_file($_FILES['uploadfile']['tmp_name'], $file)) {			
			$orig_filename = JFile::makeSafe($file_name);
			$src = $file;
			$extension = strtolower(JFile::getExt($file));
			
			if (in_array($extension, array('jpg', 'jpeg', 'png', 'gif'))) {
				$filename = rand(1000, 9999) . time() . rand(1000, 9999) . '.' . $extension;
				$dest = DL_IMG_ROOT . $filename;
				if (JFile::copy($file, $dest)) {
					list($w, $h, $type) = GetImageSize($dest);
					
					switch ($extension)
					{
						case 'jpg':
						case 'jpeg':
							$srcRes = imagecreatefromjpeg($dest);
							break;
						case 'png':
							$srcRes = imagecreatefrompng($dest);
							break;
						case 'gif':
							$srcRes = imagecreatefromgif($dest);
							break;
					}
					
					$newFilename = $filename;					
					
					$destRes = imagecreatetruecolor($w, $h);
					imagecopyresampled ($destRes, $srcRes, 0, 0, 0, 0, $w, $h, $w, $h);
					
					switch ($extension)
					{
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
					JFile::delete($file);
					imagedestroy($destRes);
					die($filename);
				}
			}
			
		} else {
			die('error');
		}
    }
}
