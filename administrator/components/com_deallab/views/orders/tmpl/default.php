<?php
/**
 * @version		$Id: default.php 2013-07-17 studio4 $
 * @package		DealLab
 * @author		Studio4 http://www.studio4.lt
 * @copyright	Copyright (c) 2010 - 2013 UAB Studio4. All rights reserved.
 * @license		GNU/GPL license: http://shop.studio4.lt/deallab-license
 */

// no direct access
defined('_JEXEC') or die;

JHtml::_('behavior.tooltip');
JHtml::_('behavior.multiselect');

$user       = JFactory::getUser();
$userId     = $user->get('id');
$listOrder  = $this->escape($this->state->get('list.ordering'));
$listDirn   = $this->escape($this->state->get('list.direction'));
$db			= JFactory::getDBO();

$statuses = array(
	0 => 'Pending',
	1 => 'Confirmed',
	2 => 'Cancelled'
);

$status_colors = array (
	0 => 'orange',
	1 => 'green',
	2 => 'red'
);

$q = 'SELECT DISTINCT(gateway) FROM #__deallab_orders WHERE gateway != ""';
$db->setQuery($q);
$gateways = $db->loadObjectList();
foreach ($gateways as $key => $gt) {
	$gateway[$gt->gateway] = $gt->gateway;
}
?>

<form action="<?php echo JRoute::_('index.php?option=com_deallab&view=orders'); ?>" method="post" name="adminForm" id="adminForm">
    <fieldset id="filter-bar">
        <div class="filter-search fltlft">
            <label class="filter-search-lbl" for="filter_search"><?php echo JText::_('JSEARCH_FILTER_LABEL'); ?></label>
            <input type="text" name="filter_search" id="filter_search" value="<?php echo $this->escape($this->state->get('filter.search')); ?>" title="<?php echo JText::_('COM_DEALLAB_SEARCH_IN_TITLE'); ?>" />
            <button type="submit"><?php echo JText::_('JSEARCH_FILTER_SUBMIT'); ?></button>
            <button type="button" onclick="document.id('filter_search').value='';this.form.submit();"><?php echo JText::_('JSEARCH_FILTER_CLEAR'); ?></button>
        </div>
        <div class="filter-select fltrt">
            <select name="filter_published" class="inputbox" onchange="this.form.submit()">
                <option value=""><?php echo JText::_('JOPTION_SELECT_PUBLISHED');?></option>
                <?php echo JHtml::_('select.options', $statuses, 'value', 'text', $this->state->get('filter.state'), true);?>
            </select>
        </div>
        <div class="filter-select fltrt">
			<select name="filter_gateway" class="inputbox" onchange="this.form.submit()">
				<option value="">- Select payment method -</option>
				<?php echo JHtml::_('select.options', $gateway, 'value', 'text', $this->state->get('filter.gateway'), true);?>
			</select>
		</div>
    </fieldset>
    <div class="clr"> </div>
    <table class="adminlist">
        <thead>
            <tr>
                <th width="1%">
                    <input type="checkbox" name="checkall-toggle" value="" title="<?php echo JText::_('JGLOBAL_CHECK_ALL'); ?>" onclick="Joomla.checkAll(this)" />
                </th>
                <th class="title">
                    <?php echo JHtml::_('grid.sort',  '#', 'O.id', $listDirn, $listOrder); ?>
                </th>
                <th width="15%">
                    <?php echo JHtml::_('grid.sort',  'Email', 'O.email', $listDirn, $listOrder); ?>
                </th>
                <th width="20%">
                    <?php echo JHtml::_('grid.sort',  'Deal', 'O.deal_id', $listDirn, $listOrder); ?>
                </th>
                <th width="10%">
                    <?php echo JHtml::_('grid.sort',  'Amount', 'O.amount', $listDirn, $listOrder); ?>
                </th>
                <th width="10%">
                    <?php echo JHtml::_('grid.sort',  'Status', 'O.state', $listDirn, $listOrder); ?>
                </th>
                <th width="10%">
                    <?php echo JHtml::_('grid.sort',  'Coupons', 'coupons', $listDirn, $listOrder); ?>
                </th>           
                <th width="10%" class="nowrap">
                    <?php echo JHtml::_('grid.sort',  'Order date', 'O.cdate', $listDirn, $listOrder); ?>
                </th>
            </tr>
        </thead>
        <tfoot>
            <tr>
                <td colspan="8">
                    <?php echo $this->pagination->getListFooter(); ?>
                </td>
            </tr>
        </tfoot>
        <tbody>
        <?php foreach ($this->items as $i => $item) :
            $ordering   = ($listOrder == 'O.cdate');
        ?>
            <tr class="row<?php echo $i % 2; ?>">
                <td class="center">
                    <?php echo JHtml::_('grid.id', $i, $item->id); ?>
                </td>
                <td>
                    <a href="<?php echo JRoute::_('index.php?option=com_deallab&task=order.edit&id='.(int) $item->id); ?>">
                        Order #<?php echo $this->escape($item->id); ?>
                    </a>
                </td>
                <td>
					<?php echo $this->escape($item->email); ?>
                </td>
                <td>
                	<a href="<?php echo JRoute::_('index.php?option=com_deallab&task=deal.edit&id='.(int) $item->deal_id); ?>">
                    	<?php echo $this->escape($item->deal_name); ?>
                   	</a>
                   	<p class="smallsub">Option: <?php echo $this->escape($item->option_name); ?></p>
                </td>
                <td class="center">
                    <?php echo $this->escape($item->amount); ?>
                </td>    
                <td class="center">
                    <span style="color: <?php echo $status_colors[$item->status]; ?>;"><?php echo $statuses[$item->status]; ?></span>
                </td>
                <td class="center">
                	<?php echo $item->coupons; ?>
                </td>                               
                <td class="center">
                    <?php echo $item->cdate; ?>
                </td>
            </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
    <div>
        <input type="hidden" name="task" value="" />
        <input type="hidden" name="boxchecked" value="0" />
        <input type="hidden" name="filter_order" value="<?php echo $listOrder; ?>" />
        <input type="hidden" name="filter_order_Dir" value="<?php echo $listDirn; ?>" />
        <?php echo JHtml::_('form.token'); ?>
    </div>
</form>