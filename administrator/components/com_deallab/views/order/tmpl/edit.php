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

JHtml::_('behavior.tooltip');
JHtml::_('behavior.formvalidation');
?>
<form action="<?php echo JRoute::_('index.php?option=com_deallab&view=order&layout=edit&id='.(int) $this->item->id); ?>" method="post" name="adminForm" id="order-form" class="form-validate">
    <div class="width-60" style="float: left;">
        <fieldset class="adminform">
            <legend><?php echo JText::_('DEALLAB_FIELDSET_ORDER_INFO'); ?></legend>
            <ul class="adminformlist">
                <li><?php echo $this->form->getLabel('id'); ?>
                <?php echo $this->form->getInput('id'); ?></li>

                <li><?php echo $this->form->getLabel('deal_title'); ?>
                <?php echo $this->form->getInput('deal_title'); ?></li>

                <li><?php echo $this->form->getLabel('option_title'); ?>
                <?php echo $this->form->getInput('option_title'); ?></li>
                
                <li><?php echo $this->form->getLabel('amount'); ?>
                <?php echo $this->form->getInput('amount'); ?></li>
                
                <?php if ($this->item->shipping > 0) : ?>
                	<li>
                		<label id="jform_net-lbl" for="jform_net" class="">Price (NET)</label>
                		<input type="text" name="jform[net]" id="jform_net" value="<?php echo ($this->item->amount - $this->item->shipping); ?>" class="readonly" readonly="readonly">
                	</li>
                	<li><?php echo $this->form->getLabel('shipping'); ?>
                	<?php echo $this->form->getInput('shipping'); ?></li>
                <?php endif; ?>
                <li><?php echo $this->form->getLabel('state'); ?>
                <div style="float: left; font-weight: bold; margin: 5px 0; line-height: 14px;"><?php echo $this->status[$this->item->status]; ?></div></li>
            </ul>
        </fieldset>
        <?php if ($this->item->address_id == -1) : ?>
        	<fieldset class="adminform">
        		<legend><?php echo JText::_('DEALLAB_FIELDSET_ORDER_DETAILS'); ?></legend>
        		<?php $order_details = json_decode($this->item->details); ?>
        		<ul class="adminformlist">
        			<?php if(isset($order_details->name)) : ?>
                	<li>
                		<label><?php echo JText::_('DEALLAB_LABEL_NAME'); ?></label>
                		<input type="text" name="name" value="<?php echo $order_details->name; ?>" class="readonly" readonly="readonly">
                	</li>
                	<?php endif; ?>
                	<?php if(isset($order_details->phone)) : ?>
                	<li>
                		<label><?php echo JText::_('DEALLAB_LABEL_PHONE'); ?></label>
                		<input type="text" name="phone" value="<?php echo $order_details->phone; ?>" class="readonly" readonly="readonly">
                	</li>
                	<?php endif; ?>
                	<?php if(isset($order_details->address)) : ?>
                	<li>
                		<label><?php echo JText::_('DEALLAB_LABEL_ADDRESS'); ?></label>
                		<input type="text" name="address" value="<?php echo $order_details->address; ?>" class="readonly" readonly="readonly">
                	</li>
                	<?php endif; ?>
                	<?php if(isset($order_details->city)) : ?>
                	<li>
                		<label><?php echo JText::_('DEALLAB_LABEL_CITY'); ?></label>
                		<input type="text" name="city" value="<?php echo $order_details->city; ?>" class="readonly" readonly="readonly">
                	</li>
                	<?php endif; ?>
                	<?php if(isset($order_details->zip)) : ?>
                	<li>
                		<label><?php echo JText::_('DEALLAB_LABEL_ZIP'); ?></label>
                		<input type="text" name="zip" value="<?php echo $order_details->zip; ?>" class="readonly" readonly="readonly">
                	</li>
                	<?php endif; ?>
                	<?php 
                	$q = ''; 
                	if (isset($order_details->address))
                		$q .= $order_details->address . ' ';
					if (isset($order_details->city))
                		$q .= $order_details->city . ' ';
					if (isset($order_details->zip))
                		$q .= $order_details->zip;
                	?>
                	<?php if(($this->item->address_id == -1) && ($q != '')) : ?>
                	<li>
                		<label><a href="http://maps.google.com/?q=<?php echo $q; ?>" target="_blank">View location in Google Maps</a></label>
                	</li>
                	<?php endif; ?>
                </ul>
        	</fieldset>
        <?php endif; ?>
        <fieldset class="adminform">
            <legend><?php echo JText::_('DEALLAB_FIELDSET_ORDER_COUPONS'); ?></legend>
            <?php if (count($this->item->coupons)) : ?>
            	<table class="adminlist">
            		<thead>
            			<tr>
            				<th width="5%">ID</th>
            				<th width="25%">Code</th>
            				<th>Created</th>
            			</tr>
            		</thead>
            		<tbody>
            		<?php foreach($this->item->coupons as $coupon) : ?>
            			<tr>
            				<td align="center"><?php echo $coupon->id; ?></td>
            				<td align="center"><?php echo $coupon->code; ?></td>
            				<td align="center"><?php echo $coupon->cdate; ?></td>
            			</tr>
            		<?php endforeach;?>
            		</tbody>
            	</table>
            <?php else: ?>
            	<p><?php echo JText::_('DEALLAB_MSG_NO_COUPONS'); ?></p>
            <?php endif; ?>
        </fieldset>
        <fieldset class="adminform">
            <legend><?php echo JText::_('DEALLAB_FIELDSET_PAYMENT_INFO'); ?></legend>
            <?php if (count($this->item->callbacks)) : ?>
            	<table class="adminlist">
            		<thead>
            			<tr>
            				<th width="10%">Status</th>
            				<th width="10%">Manual</th>
            				<th width="20%">Date</th>
            				<th>Info</th>
            			</tr>
            		</thead>
            		<tbody>
            		<?php foreach($this->item->callbacks as $callback) : ?>
            			<tr>
            				<td align="center"><?php echo $this->status[$callback->status]; ?></td>
            				<td align="center"><?php echo $this->manual[$callback->manual]; ?></td>
            				<td align="center"><?php echo $callback->cdate; ?></td>
            				<td align="center"><?php echo $callback->info; ?></td>
            			</tr>
            		<?php endforeach;?>
            		</tbody>
            	</table>
            <?php else: ?>
            	<p><?php echo JText::_('DEALLAB_MSG_NO_CALLBACKS'); ?></p>
            <?php endif; ?>
        </fieldset>
    </div>
    <div class="width-40" style="float: right;">
    	<fieldset class="adminform">
    		<legend>Order notes</legend>
    		<textarea id="order-notes" name="order[notes]"><?php echo $this->item->notes; ?></textarea>
    	</fieldset>
    </div>
    <div class="clr"></div>
	<input type="hidden" name="task" value="" />
	<input type="hidden" name="order[id]" value="<?php echo $this->item->id; ?>" />
	<?php echo JHtml::_('form.token'); ?>
    <div class="clr"></div>
</form>
