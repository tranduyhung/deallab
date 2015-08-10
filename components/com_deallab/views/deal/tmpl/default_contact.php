<?php
/**
 * @version		$Id: default_contact.php 2013-07-17 studio4 $
 * @package		DealLab
 * @author		Studio4 http://www.studio4.lt
 * @copyright	Copyright (c) 2010 - 2013 UAB Studio4. All rights reserved.
 * @license		GNU/GPL license: http://shop.studio4.lt/deallab-license
 */

// no direct access
defined('_JEXEC') or die;
?>
<a id="deal-contact" class="color7" href="#" onclick="showContactForm(); return false;"><?php echo JText::_('DEALLAB_DEAL_QUESTION'); ?></a>
<div class="modal" id="contactModal" tabindex="-1" role="dialog" aria-labelledby="Select an option:" aria-hidden="true" style="display: none;">
	<div class="modal-header">
    	<h3 id="myModalLabel"><?php echo JText::_('DEALLAB_DEAL_QUESTION'); ?></h3>
  	</div>
  	<div class="modal-body">
		<form id="deal-contact-form" action="index.php" method="post">
			<div style="width: 280px; float: left;">
				<label for="contact-name"><?php echo JText::_('DEALLAB_LABEL_NAME'); ?></label>
				<input type="text" name="contact-name" id="contact-name" class="inputbox" />
				<label for="contact-email"><?php echo JText::_('DEALLAB_LABEL_EMAIL'); ?></label>
				<input type="text" name="contact-email" id="contact-email" class="inputbox" />
			</div>
			<div style="width: 350px; float: left;">
				<label for="contact-message"><?php echo JText::_('DEALLAB_LABEL_MESSAGE'); ?></label>
				<textarea id="contact-message" name="contact-message" class="inputbox"></textarea>
  				<button class="btn btn-primary" onclick="sendContact(); return false;"><?php echo JText::_('DEALLAB_BUTTON_SEND'); ?></button>
  				<button class="btn" data-dismiss="modal" aria-hidden="true"><?php echo JText::_('DEALLAB_BUTTON_CANCEL'); ?></button>
			</div>
			<div class="clearfix"></div>
			<?php echo JHTML::_( 'form.token' ); ?>
		</form>
	</div>
</div>
<script type="text/javascript">
	function showContactForm() {
        jQuery('#contactModal').modal({
  			backdrop: true,
  			keyboard: true,
  			show: true,
  		}).css({
  			width: '660px',
  			'margin-left': '-330px'
  		});
	};
</script>
<script type="text/javascript">
	function sendContact() {
		jQuery.ajax({
  			type: 'POST',
  			url: 'index.php?option=com_deallab&task=ajax.contact',
  			data: jQuery('#deal-contact-form').serialize(),
  			success: function(data){
  				alert(data);
  			}
		});
	}
</script>
