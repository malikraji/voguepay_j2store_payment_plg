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
?>
<?php defined('_JEXEC') or die('Restricted access'); ?>
<?php echo JText::_('MAL_VOGUEPAY_PREPAY_MSG');?>
<form method="post" action="<?php echo $vars->form_url; ?>">
<input type="hidden" name="v_merchant_id" value="<?php echo $vars->voguepay_merchant_id;?>">
<input type="hidden" name="merchant_ref" value="<?php echo $vars->voguepay_merchant_ref;?>">
<input type="hidden" name="success_url" value="<?php echo $vars->success_url;?>">    
<input type='hidden' name='fail_url' value='<?php echo $vars->fail_url; ?>' />
<input type="hidden" name="currency"   value="<?php  echo $vars->voguepay_currency;?>"> 		
    <input type="hidden" name="memo" value="<?php echo $vars->store_id.' '.$vars->voguepay_merchant_ref;?>">    
<input type="hidden" name="root_url" value="<?php echo $vars->root_url;?>">  
<input type="hidden" name="store_id" value="<?php echo $vars->store_id;?>">  
<input type="hidden" name="developer_code" value="<?php echo $vars->voguepay_developer_code;?>" >

    <input type="hidden" name="total" value="<?php echo $vars->voguepay_total;?>">
    <input type="submit" class="btn btn-primary button" value="<?php echo JText::_('MAL_VOGUEPAY_PREPAY_BUTTON'); ?>" />
</form>