<?php
/**
 * Voguepay Payment Plugin for Joomla J2Store Component
 * Joomla! version 3.x
 * J2store version 3.x
 * @author Malik Raji @ VoguePay <amiraj0000@gmail.com>
 * @copyright 2017 VoguePay.com
 * @version 1.0.0
 * @license      GNU/GPL license: http://www.gnu.org/copyleft/gpl.html
  * @link http://www.voguepay.com
 */

defined('_JEXEC') or die('Restricted access');
require_once (JPATH_ADMINISTRATOR.'/components/com_j2store/library/plugins/payment.php');
require_once (JPATH_ADMINISTRATOR.'/components/com_j2store/helpers/j2store.php');
require_once (JPATH_ADMINISTRATOR.'/components/com_j2store/helpers/utilities.php');

class plgJ2StorePayment_voguepay extends J2StorePaymentPlugin
{
    /**
     * @var $_element  string  Should always correspond with the plugin's filename,
     *                         forcing it to be unique
     */
    var $_element    = 'payment_voguepay';
    var $login_id    = '';
    var $tran_key    = '';
    var $_isLog      = false;

    public function plgJ2StorePayment_voguepay(& $subject, $config)
    {
        parent::__construct($subject, $config);
        $this->loadLanguage( '', JPATH_ADMINISTRATOR );

    }

    /**
     * Form in Confirm order page
     *
     * @param $data     array       form post data
     * @return string   HTML to display
     */
    public function _prePayment( $data )
    {
        $order = F0FTable::getInstance('Order', 'J2StoreTable');
        $order->load($data['orderpayment_id']);
        //$amount = $order->order_total;
        
        // prepare the payment form
        $vars = new JObject();
        //Order details
        $vars->store_id = $this->params->get('my_store_name');
        $vars->voguepay_merchant_id   = ($this->params->get('trans_state') == 'demo') ? 'demo' : $this->params->get('my_merchant_id') ;

        $vars->voguepay_merchant_ref   = $data['order_id'];
        $vars->voguepay_currency       = $this->params->get('my_store_currency');
        $vars->voguepay_memo      = "Order Memo"; 
        //$vars->voguepay_memod  ="Order "+$order->order_id+" made by "+$order->user_email+" on"+ $order->created_on+'.';
        $vars->voguepay_developer_code = ($this->params->get('my_developer_code') == '') ? '594240ec3130d' : $this->params->get('my_developer_code');
        
        $vars->voguepay_total = $order->order_total;
        // Voguepay form req and res items.
        $rootURL = rtrim(JURI::base(),'/');
        $subpathURL = JURI::base(true);
        if(!empty($subpathURL) && ($subpathURL != '/')) {
            $rootURL = substr($rootURL, 0, -1 * strlen($subpathURL));
        }        
        $vars->form_url = "https://voguepay.com/pay";
        $vars->root_url = $rootURL;    
        $vars->success_url =$rootURL.JRoute::_("index.php?option=com_j2store&view=checkout&task=confirmPayment&orderpayment_type=".$this->_element."&paction=display_message");
        $vars->fail_url =$rootURL.JRoute::_("index.php?option=com_j2store&view=checkout&task=confirmPayment&orderpayment_type=".$this->_element."&paction=cancel_message&order_id=".$order->order_id."&token=".md5($order->order_id.$order->order_total));

        //lets check the values submitted
        $html = $this->_getLayout('prepayment', $vars);
        return $html;
    }

    /**
     * Processes the payment form
     * and returns HTML to be displayed to the user
     * generally with a success/failed message
     *
     * @param $data     array       form post data
     * @return string   HTML to display
     */
    public function _postPayment( $data )
    {
        // Process the payment after response
        $vars = new JObject();

        $app =JFactory::getApplication();
        $paction = JRequest::getVar( 'paction' );

        switch ($paction)
        {
            case "display_message":
                $session = JFactory::getSession();
                $session->set('j2store_cart', array());

                $vars->message = 
                ($this->params->get('afterpayment') == '') ? 'Your Payment was 
Successful. Your order will be processed' : JText::_($this->params->get('afterpayment'));
                $html = $this->_getLayout('message', $vars);
                $html .= $this->_displayArticle();

              break;
            case "cancel_message":
                $vars->message = 
                ($this->params->get('cancelpayment') == '') ? 'Payment process cancelled' : JText::_($this->params->get('cancelpayment'));
                $html = $this->_getLayout('message', $vars);
                break;
            default:
                $vars->message = ($this->params->get('errorpayment') == '') ? 'Error processing your payment. Please try again. If error persists contact our store' : JText::_($this->params->get('errorpayment'));

                $html = $this->_getLayout('message', $vars);
                break;
        }
        return $html;
    }

    /**
     * Prepares variables and
     * Renders the form for collecting payment info
     *
     * @return unknown_type
     */
    public function _renderForm( $data )
    {
        $vars = new JObject();
        $html = $this->_getLayout('form', $vars);

        return $html;
    }
}