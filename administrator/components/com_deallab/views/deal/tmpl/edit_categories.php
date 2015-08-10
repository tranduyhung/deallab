<?php
/**
 * @version		$Id: edit_categories.php 2013-07-17 studio4 $
 * @package		DealLab
 * @author		Studio4 http://www.studio4.lt
 * @copyright	Copyright (c) 2010 - 2013 UAB Studio4. All rights reserved.
 * @license		GNU/GPL license: http://shop.studio4.lt/deallab-license
 */

// no direct access
defined('_JEXEC') or die;

$categories = dlDataHelper::categoryList();
$dealCats   = dlDataHelper::getDealData($this->item->id, 'deal_categories', 'category_id', 'array');
if (!count($dealCats))
	$dealCats = array();
?>
<ul class="adminformlist" id="categoriesList">
<?php if (count($categories)) : ?>
	<?php foreach ($categories as $category) : ?>
		<li><input type="checkbox" class="deallab-checkbox" name="categories[<?php echo $category->id; ?>]" value="1" <?php echo (in_array($category->id, $dealCats))?'checked="checked"':''; ?> /><?php echo $category->title; ?></li>
	<?php endforeach; ?>
<?php endif; ?>
</ul>
<input type="text" value="" id="addNewCategory" /><input type="button" value="<?php echo JText::_('DEALLAB_BUTTON_ADD'); ?>" onclick="addCategory();" />
<span class="errorMsg" id="category-error"></span>
<script type="text/javascript">
	function addCategory() {
		jQuery('#category-error').html('');
		if (jQuery('#addNewCategory').val() > '') {
			addFieldToList(jQuery('#addNewCategory'), jQuery('#categoriesList'), '?option=com_deallab&task=ajax.addCategory', 'categories');
		} else {
			jQuery('#category-error').html('<p>Please enter a name of category.</p>');
		}
	}
</script>