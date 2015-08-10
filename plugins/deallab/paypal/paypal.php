<?php
defined('_JEXEC') or die;
		
jimport('joomla.plugin.plugin');
include_once(JPATH_ADMINISTRATOR . DS . 'components/com_deallab/helpers/plugins.php');

class plgDeallabPaypal extends JPlugin {
		
	public function __construct(&$subject, $params ) {
		parent::__construct( $subject, $params );
	}
	
	public function beforeDeallabRedirect($order)
	{
		$request = $this->buildPayPalRequest($order);
		$html = $this->buildHtmlForm($request);
		
		return $html;
	}
	
	private function buildPayPalRequest($order)
	{
		$componentParams = &JComponentHelper::getParams('com_deallab');
		
		$request = array(
			'cmd' => '_xclick',
			'charset' => 'utf-8',
			'business' => $componentParams->get('paypal_username'),
			'currency_code' => $order->currency,
			'amount' => $order->amount,
			'item_name' => 'Order #' . $order->id,
			'return' => JUri::root(),
			'cancel_return' => JUri::root(),
			'rm' => 2,
			'notify_url' => JRoute::_(JUri::root().'index.php?option=com_deallab&task=callback&plugin=paypal'),
			'item_number' => $order->id,
			'custom' => ''
		);
		
		return $request;
	}
	
	private function buildHtmlForm($request)
	{
		$componentParams = &JComponentHelper::getParams('com_deallab');
		
		if ($componentParams->get('paypal_sandbox'))
			$confirm_url = 'https://www.sandbox.paypal.com/cgi-bin/webscr';
		else
			$confirm_url = 'https://www.paypal.com/cgi-bin/webscr';
		
		$html = '';
		$html .= '<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">';
		$html .= '<html>';
		$html .= '<head>';
		$html .= '<meta http-equiv="content-type" content="text/html; charset=utf-8" />';
		$html .= '<title></title>';
		$html .= '</head>';
		$html .= '<body>';
		$html .= '<p>Redirecting to PayPal...</p>';
		$html .= '<form method="post" action="'.$confirm_url.'" id="paymentform">';
		
		foreach ($request as $key => $val):
			$html .= '<input type="hidden" name="'.$key.'" value="'.$val.'" />';
		endforeach;
		
		$html .= '<input type="submit" value="Continue" />';
		$html .= '<script type="text/javascript">';
		$html .= 'function submitForm(){document.getElementById("paymentform").submit();}';
		$html .= 'window.onload = submitForm;';	
		$html .= '</script>';
		$html .= '</form>';
		$html .= '</body>';
		$html .= '</html>';
		
		return $html;
	}

	public function dealLabCallback()
	{
		$componentParams = &JComponentHelper::getParams('com_deallab');
		$paypalEmail = $componentParams->get('paypal_username');
		
		if ($componentParams->get('paypal_sandbox'))
			$paypal_url = 'https://www.sandbox.paypal.com/cgi-bin/webscr';
		else
			$paypal_url = 'https://www.paypal.com/cgi-bin/webscr';
			
        
        $req = 'cmd=_notify-validate';
        
        foreach ($_POST as $key => $value) {
            $value = urlencode(stripslashes($value));
            $req .= "&$key=$value";
        }
        
        $header = "POST /cgi-bin/webscr HTTP/1.0\r\n";
        $header .= "Content-Type: application/x-www-form-urlencoded\r\n";
        $header .= "Content-Length: " . strlen($req) . "\r\n\r\n";
        $response = file_get_contents($paypal_url . '?' . $req);
        
        // assign posted variables to local variables
        
        $orderId  = isset($_POST['item_number'])?$_POST['item_number']:null;
		
		$order = DealLabPluginsHelper::getOrder($orderId);

        $item_name = $_POST['item_name'];
        $payment_status = $_POST['payment_status'];
        $payment_amount = $_POST['mc_gross'];
        $payment_currency = $_POST['mc_currency'];
        $txn_id = $_POST['txn_id'];
        $receiver_email = $_POST['receiver_email'];
        $payer_email = $_POST['payer_email'];
        $custom = explode('||', $_POST['custom']);
        $email = $custom[0];
        $valid_months = $custom[1];
        
        if (!$response) {
            $clb->note = 'SOCKET ERR';              
        } else {
            $clb->note = 'SOCKET OK: ' . $response;
            
            if (strcmp ($response, "VERIFIED") == 0) {
				DealLabPluginsHelper::saveCallback($orderId, 1, json_encode($_POST));
				DealLabPluginsHelper::makePaid($orderId);
            }
            else if (strcmp ($response, "INVALID") == 0) {
            	DealLabPluginsHelper::saveCallback($orderId, 0, json_encode($_POST));
            }
        }
	}
	
	public function paymentPluginList()
	{
		return array('paypal', 'PayPal');
	}
}
