<?php
/**
 * @version		$Id: edit.php 2013-07-17 studio4 $
 * @package		DealLab
 * @author		Studio4 http://www.studio4.lt
 * @copyright	Copyright (c) 2010 - 2013 UAB Studio4. All rights reserved.
 * @license		GNU/GPL license: http://shop.studio4.lt/deallab-license
 */

// no direct access
defined('_JEXEC') or die;

require_once (DL_HELPERS . DS . 'html.php');
require_once (DL_HELPERS . DS . 'data.php');

dlHtmlHelper::addCss(false, false, true, false);
dlHtmlHelper::addJs();

JHtml::_('behavior.tooltip');
JHtml::_('behavior.formvalidation');
?>
<script type="text/javascript">
	Joomla.submitbutton = function(task) {
		if (task == 'deal.cancel' || document.formvalidator.isValid(document.id('deal-form'))) {
			Joomla.submitform(task, document.getElementById('deal-form'));
		} else {
			alert('<?php echo $this->escape(JText::_('JGLOBAL_VALIDATION_FORM_FAILED'));?>');
		}
	}
</script>
<form action="<?php echo JRoute::_('index.php?option=com_deallab&layout=edit&id='.(int)$this->item->id); ?>" method="post" name="adminForm" id="deal-form" class="form-validate">
	<div class="width-70 fltlft">
		<fieldset class="adminform">
			<ul class="adminformlist">
				<li>
					<?php echo $this->form->getLabel('title'); ?>
					<?php echo $this->form->getInput('title'); ?>
				</li>
				<li>
					<?php echo $this->form->getLabel('alias'); ?>
					<?php echo $this->form->getInput('alias'); ?>
				</li>
			</ul>
		</fieldset>
		<fieldset class="adminform">
			<legend><?php echo JText::_('DEALLAB_FIELDSET_IMAGES'); ?></legend>
			<?php echo $this->loadTemplate('images'); ?>
		</fieldset>
		<fieldset class="adminform">
			<legend><?php echo JText::_('DEALLAB_FIELDSET_DEAL_DESCRIPTION'); ?></legend>
			<?php echo $this->form->getInput('description'); ?>
		</fieldset>
		<fieldset class="adminform">	
			<legend><?php echo JText::_('DEALLAB_FIELDSET_PRICING'); ?></legend>		
			<?php echo $this->loadTemplate('prices'); ?>
		</fieldset>
		<fieldset class="adminform" style="float: left; width: 40%;">
			<legend><?php echo JText::_('DEALLAB_FIELDSET_CITIES'); ?></legend>
			<?php echo $this->loadTemplate('cities'); ?>
		</fieldset>
		<fieldset class="adminform" style="float: right; width: 40%;">
			<legend><?php echo JText::_('DEALLAB_FIELDSET_CATEGORIES'); ?></legend>
			<?php echo $this->loadTemplate('categories'); ?>
		</fieldset>
		<div style="clear: both;"></div>
		<fieldset class="adminform">			
			<legend><?php echo JText::_('DEALLAB_FIELDSET_COUPON_REDEMPTION'); ?></legend>
			<?php echo $this->loadTemplate('addresses'); ?>						
		</fieldset>
		<fieldset class="adminform">
			<?php echo $this->loadTemplate('merchant'); ?>
		</fieldset>
		<fieldset class="adminform">
			<legend><?php echo JText::_('DEALLAB_FIELDSET_CUSTOMFIELDS'); ?></legend>
			<?php echo $this->loadTemplate('customfield'); ?>
		</fieldset>
	</div>
	<div class="width-30 fltrt">
		<?php 
		echo JHtml::_('sliders.start', 'deallab-sliders-'.$this->item->id, array('useCookie'=>1));
		echo $this->loadTemplate('publishing_details');		
		echo $this->loadTemplate('params');
		// echo $this->loadTemplate('metadata');
		echo JHtml::_('sliders.end'); ?>
	</div>
	<div style="clear: both;"></div>
	<input type="hidden" name="task" value="" />
	<?php echo JHtml::_('form.token'); ?>
</form>
<script type="text/javascript">
	jQuery(document).ready(function(){
		jQuery('#images-list').sortable({
			update: function(event, ui) {
				var i = 1;
				jQuery('#images-list').children('li').each(function(){
					var id = jQuery(this).attr('id')+'-ordering';
					jQuery('#'+id).val(i);
					i += 1;
				})
			}
		});
		
		jQuery('#prices-list').sortable({
			update: function(event, ui) {
				var i = 1;
				jQuery('#prices-list').children('li').each(function(){
					jQuery(this).find('input.ordering').val(i);
					i += 1;
				})
			}
		});
		
		jQuery('#address-list').sortable({
			update: function(event, ui) {
				var i = 1;
				jQuery('#address-list').children('li').each(function(){
					jQuery(this).find('input.ordering').val(i);
					i += 1;
				})
			}
		});
		
		jQuery('#custom-fields').sortable({
			update: function(event, ui) {
				var i = 1;
				jQuery('#custom-fields').children('li').each(function(){
					jQuery(this).find('input.ordering').val(i);
					console.log(i);
					i += 1;
				})
			}
		});
		
		jQuery('#images-list').disableSelection();
	});
</script>