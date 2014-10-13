<?php

/*
  $Id$

  osCommerce, Open Source E-Commerce Solutions
  http://www.oscommerce.com

  Copyright (c) 2014 osCommerce

  Released under the GNU General Public License
*/

  class hook_admin_catalog_recurring_product {  
  
    function listen_recurringproduct_script() {
	
	$var = "
	 <style>
	table.trialrptable {
	border-width: 1px 1px 1px 1px;
	border-spacing: 1px;
	border-style: solid solid solid solid;
	border-color: gray gray gray gray;
	border-collapse: collapse;
	background-color: white;
	}
	</style>
	<script type=\"text/javascript\">
function SetAllowedBillingFrequencies(spot, selectedVal)
{
    var period = document.getElementById(spot+'Period').value;
	var max;

	switch (period)
	{
		case 'day' :
			{
				max = 365;
				break;
			}

		case 'week' :
			{
				max = 52;
				break;
			}

		case 'semimonth' :
			{
				max = 1;
				break;
			}

		case 'month' :
			{
				max = 12;
				break;
			}

		case 'year' :
			{
				max = 1;
				break;
			}

		default :
			{
				return false;
			}

	}

	var freq = document.getElementById(spot+'Frequency');

	var opts = freq.length;

	while (opts--)
	{
		freq.remove(freq.firstChild);
	}


	var i;
	var p;
    p=0;
	for (i = 1; i <= max; ++i)
	{
		var opt = document.createElement('option');
		opt.text = i;
		opt.value = i;
		if(i == selectedVal){
		    p = i;
		}
		try
		{
			freq.add(opt, null);
		}
		catch (ex)
		{
			freq.add(opt);
		}
	}
    if(p>0){
        var opVal = document.getElementById(spot+'Frequency').options[p-1];
        opVal.selected = true;
    }
	freq.disabled = max == 1;

	return true;
}
function showFromSelectRP (it, from) {
    if(document.getElementById(from).value == 'recurring'){
		document.getElementById('rp_details').style.display = 'block';
    }
    else{		
        document.getElementById('rp_details').style.display = 'none';
    }
}
	
function updateProductNet() {
	var netValue = document.getElementById('rpPrice').value;
    document.forms['new_product'].products_price.value = doRound(netValue, 4);
	
	var taxRate = getTaxRate();
    grossValue = netValue;
	if (taxRate > 0) {
      grossValue = netValue * ((taxRate / 100) + 1);
    }

    document.forms['new_product'].products_price_gross.value = doRound(grossValue, 4);
}
	
	
	$(document).ready(function() {
	$( '#RP_start_date' ).datepicker();
	$( '#rpTrialPeriod' ).click(function() {	
		 $('#trialPaymentTable').toggle(this.checked);
	});
	
	SetFocus(); 
	SetAllowedBillingFrequencies('rpBilling', '". $pInfo->billing_frequency."'); 
	SetAllowedBillingFrequencies('rpTrialBilling', '". trim($pInfo->trial_billing_frequency)."');
	
	});
	
</script>
";
return $var;
}

function listen_recurringproduct_content(){
			$var = "
			<tr>
			<td colspan=\"2\">". tep_draw_separator('pixel_trans.gif', '1', '10')."</td>
			</tr>
		 <!-- product type -->
			<tr>
            <td class=\"main\">Product Type:</td>   
			<td class=\"main\">".tep_draw_separator('pixel_trans.gif', '24', '15') . '&nbsp;' . tep_draw_pull_down_menu('products_type', array(array('id' => 'standard', 'text' => 'Standard'),array('id' => 'recurring', 'text' => 'Recurring')), $pInfo->products_type,'id=\'products_type\' onchange="showFromSelectRP(\'rpProduct\', \'products_type\');"') ."</td>
            </tr>
			</table>
			<table>
			<tr>
			<td colspan=\"2\">".tep_draw_separator('pixel_trans.gif', '1', '10')."</td>
			</tr>
			<tr>
			<td>			
			<fieldset id=\"rp_details\" style='display:none;'><legend>Recurring Payment Profile</legend>
			<table>
          <tr>
            <td colspan=\"2\">". tep_draw_separator('pixel_trans.gif', '1', '10')."</td>
          </tr>
		  <!-- Recurring payments profile -->
          <tr>
            <td colspan=\"2\">". tep_draw_separator('pixel_trans.gif', '1', '10')."</td>
          </tr>
          <tr bgcolor=\"#ebebff\">
            <td colspan=\"2\" class=\"main\"><strong>Recurring Payment Values".tep_draw_separator('pixel_trans.gif', '1', '10')."</strong></td>
          </tr>
		<tr>
            <td colspan=\"2\">".tep_draw_separator('pixel_trans.gif', '1', '10')."</td>
          </tr>
		  <tr>
            <td class=\"main\">Recurring payments start date <br><small>(Leave this field empty if you want the product <br>profile to start when the buyer purchases it)<br>(YYYY-MM-DD)</small></td>
			<td class=\"main\">". tep_draw_separator('pixel_trans.gif', '24', '15') . '&nbsp;' . tep_draw_input_field('RP_start_date', "", 'id="RP_start_date"') . ' <small>(YYYY-MM-DD)</small>'."</td>
          </tr>
		  <tr>
		  <td class=\"main\"></td>
		  <td class=\"main\">". tep_draw_separator('pixel_trans.gif', '24', '15') . '&nbsp;' .		  
		  "<input type='checkbox' name='rpTrialPeriod' id='rpTrialPeriod'/>Include trial payment period</td>
          </tr>
		  </table>
		  <br/>
		  <table class=\"trialrptable\" cellpadding=\"2\" id='trialPaymentTable' style='width:480px;display:none;'>
		  
                <tr bgcolor=\"#ebebff\">
                    <td colspan=\"2\"><B class=\"main\">Trial Period</B></td>
                </tr>
				<tr>
                    <td class=\"main\">Trial payments cycle</td>
                    <td class=\"main\">".tep_draw_separator('pixel_trans.gif', '24', '15') . '&nbsp;' .tep_draw_pull_down_menu('rpTrialBillingPeriod', array(array('id' => 'day', 'text' => 'Day'),array('id' => 'week', 'text' => 'Week'),array('id' => 'semimonth', 'text' => 'SemiMonth'),array('id' => 'month', 'text' => 'Month'),array('id' => 'year', 'text' => 'Year')), $pInfo->trial_billing_period, ' id="rpTrialBillingPeriod" onchange="SetAllowedBillingFrequencies(\'rpTrialBilling\', \'\');"'). '&nbsp;cycle frequency&nbsp;'."<select id=\"rpTrialBillingFrequency\" name=\"rpTrialBillingFrequency\" /></td>
                </tr>
				<tr>
                    <td class=\"main\">Number of Recurring Payments</td><input type=\"hidden\" name=\"rpTrialNumberPaymentsOption\" value=\"true\">
                    
					<td class=\"main\">".tep_draw_separator('pixel_trans.gif', '24', '15') . '&nbsp;' .tep_draw_input_field('rpTrialNumberPayments', $pInfo->trial_total_billing_cycles, '')."</td>
                </tr>
				<tr>
                    <td class=\"main\">Net Amount</td>
                    <td class=\"main\">".tep_draw_separator('pixel_trans.gif', '24', '15') . '&nbsp;' . tep_draw_input_field('rpTrialPrice', $pInfo->trial_amt, '')."</td>
                </tr>
				<tr>
				<td colspan=\"2\">". tep_draw_separator('pixel_trans.gif', '1', '10')."</td>
				</tr>
				</table>
				<table>
		  <tr>
				<td colspan=\"2\"><img src=\"images/pixel_trans.gif\" border=\"0\" alt=\"\" width=\"1\" height=\"10\"></td>
				</tr>
		   <tr bgcolor=\"#ebebff\">
                <td colspan=\"2\" class=\"main\"><B>Recurring Payments</B></td>
                </tr>
				<tr>
                    <td class=\"main\">Payments cycle</td>
                    <td class=\"main\">".tep_draw_separator('pixel_trans.gif', '24', '15') . '&nbsp;' .tep_draw_pull_down_menu('rpBillingPeriod', array(array('id' => 'day', 'text' => 'Day'),array('id' => 'week', 'text' => 'Week'),array('id' => 'semimonth', 'text' => 'SemiMonth'),array('id' => 'month', 'text' => 'Month'),array('id' => 'year', 'text' => 'Year')), $pInfo->billing_period, ' id="rpBillingPeriod" onchange="SetAllowedBillingFrequencies(\'rpBilling\', \'\');"'). '&nbsp;cycle frequency&nbsp;'."<select id=\"rpBillingFrequency\" name=\"rpBillingFrequency\" /></td>
                </tr>
				<tr>
				<td colspan=\"2\"><img src=\"images/pixel_trans.gif\" border=\"0\" alt=\"\" width=\"1\" height=\"10\"></td>
				</tr>
				<tr>
				    <td class=\"main\">Number of Recurring Payments</td>
                    <td class=\"main\">".tep_draw_separator('pixel_trans.gif', '24', '15') . '&nbsp;' . tep_draw_radio_field('rpNumberPaymentsOption', 'enddate', $checked) .  tep_draw_separator('pixel_trans.gif', '24', '15') . '&nbsp;' .tep_draw_input_field('rpNumberPayments', $pInfo->total_billing_cycles, '')."</td>
                </tr>
				<tr>
                    <td>&nbsp;</td>
                    <td class=\"main\">".tep_draw_separator('pixel_trans.gif', '24', '15') . '&nbsp;' . tep_draw_radio_field('rpNumberPaymentsOption', 'noenddate', !($checked)) .  tep_draw_separator('pixel_trans.gif', '24', '15') . '&nbsp;'."No end date <small>(Payments will continue until you suspend them)</small></td>
                </tr>
				<tr>
				<td colspan=\"2\"><img src=\"images/pixel_trans.gif\" border=\"0\" alt=\"\" width=\"1\" height=\"10\"></td>
				</tr>
				<tr>
                    <td class=\"main\">Net Amount</td>
                    <td class=\"main\">".tep_draw_separator('pixel_trans.gif', '24', '15') . '&nbsp;' . tep_draw_input_field('rpPrice', '', ' onkeyup="updateProductNet();" id="rpPrice"')."</td>
                </tr>
				<tr>
				<td colspan=\"2\"><img src=\"images/pixel_trans.gif\" border=\"0\" alt=\"\" width=\"1\" height=\"10\"></td>
				</tr>
				<tr bgcolor=\"#ebebff\">
                    <td colspan=\"2\" class=\"main\"><B>One-time transaction</B></td>
                </tr>
				<tr>
                    <td  class=\"main\" title=\"Select one-time transactions for charges like setup fees. You can only create them when you create a profile and you won't be able to edit or reactivate your one-time transactions later.We process one-time transactions almost immediately and don't wait for the profile start date.Example: If you create a profile on May 15 and the profile's start date is May 29, we will process the one-time transaction on May 15.\">
					&nbsp;<input type='checkbox' id='process_onetime_tx' />Process one-time transaction  &nbsp;</td>
					 <td class=\"main\">Amount &nbsp;". tep_draw_input_field('rpIntAmt', $pInfo->init_amt, '')."</td>
					</tr><tr>
					 <td  class=\"main\" colspan=\"2\" title=\"Create a profile if your one-time transaction fails. One-time transaction failures cancel both the one-time transaction and the profile you were trying to create. Select this option to automatically create a new profile and add the one-time transaction amount to the outstanding balance.\">
					 &nbsp;<input type='checkbox' id='onetime_tx_fails' />Create a profile if your one time transaction fails</td>
					 
				</tr>				
		  </table>
		  </fieldset>
		  </td>
		  </tr>
		  </table>
		  <table cellspacing='0' cellpadding='2'>
			";     
	return $var;
    }
function listen_recurringproduct_insert_update($array){
		$products_type = $array[0];
		$sql_array = $array[1];
		$insert_sql_data = array('products_type' => tep_db_prepare_input($products_type));
        $sql_data_array = array_merge($sql_array, $insert_sql_data);
		return $sql_data_array;
}

function listen_recurringproduct_categories($array){
if (!is_object($logger)) $logger = new logger;
	$action = $array[0];
	$rp_array = $array[1];
	$sql_data_array = $array[2];
	$products_id = $array[3];
	$logger->write($array[0],"action");
	$logger->write(print_r($rp_array,1),'rparray');
	$logger->write(print_r($sql_data_array,1),'sql_data_array');
	$logger->write($array[3],"productid");
	if($rp_array['product_type'] == 'recurring') {
    // rp insert for rp profile data
    $trial = false;
    if(isset($rp_array['rpTrialPeriod']) && $rp_array['rpTrialPeriod'] == 'on'){
      $trial = true;
    }
    if($rp_array['rpBillingPeriod'] == 'semimonth') {
    $freqN = '1';
    }
    else if($rp_array['rpBillingPeriod'] == 'year' && ($rp_array['rpBillingFrequency'] <= 0 || $rp_array['rpBillingFrequency'] > 1)) {
      $freqN = '1';
    }
    else {
      $freqN = $rp_array['rpBillingFrequency'];
    }
    if($rp_array['rpNumberPaymentsOption'] == 'noenddate') {
      $nop = 0;
    }
    else {
    $nop = $rp_array['rpNumberPayments'];
    }
	
    $sql_data_array = array('profile_start_date'          => $rp_array['RP_start_date'],
                              'billing_period'             => $rp_array['rpBillingPeriod'],
                              'billing_frequency'          => $freqN,
                              'total_billing_cycles'        => $nop
    );
      
    if($trial){
      if($rp_array['rpTrialBillingPeriod'] == 'semimonth') {
        $freq = '1';
      }
      else if($rp_array['rpTrialBillingPeriod'] == 'year' && ($rp_array['rpTrialBillingFrequency'] <= 0 || $rp_array['rpTrialBillingFrequency'] > 1)) {
        $freq = '1';
      }
      else {
        $freq = $rp_array['rpTrialBillingFrequency'];
      }  
	$sql_data_array['trial_profile_start_date'] = $rp_array['RP_start_date'];
	$sql_data_array['trial_billing_period'] = $rp_array['rpTrialBillingPeriod'];
	$sql_data_array['trial_billing_frequency'] = $freq;
	$sql_data_array['trial_total_billing_cycles'] = $rp_array['rpTrialNumberPayments'];
	$sql_data_array['trial_amt'] = $rp_array['rpTrialPrice'];
      }
      else {
        $sql_data_array['trial_total_billing_cycles'] = 1;
        $sql_data_array['trial_profile_start_date'] = null;
        $sql_data_array['trial_billing_period'] = null;
        $sql_data_array['trial_amt'] = '0.00'; 
      }
      // check rpOneTimeTransaction
      if(isset($rp_array['rpOneTimeTransaction'])){
        if($rp_array['rpOneTimeTransaction'] == '1'){
        $sql_data_array['init_amt']  = $rp_array['rpIntAmt'];
        }
      }
      else {
        $sql_data_array['init_amt'] = 0;
      }
      if(isset($rp_array['rpCreateProfileTransactionFailure'])){
        if($rp_array['rpCreateProfileTransactionFailure'] == '1'){
          $sql_data_array['failed_init_amt_action']          = 'ContinueOnFailure';
        }
      else{
         $sql_data_array['failed_init_amt_action']          = 'CancelOnFailure';
      }
      }
      else{
         $sql_data_array['failed_init_amt_action']          = 'CancelOnFailure';
      }
	  
      if($action == 'insert_product') {
        $sql_data_array['products_id'] = $products_id;
        tep_db_perform(paypal_rp_product_profile, $sql_data_array);
      }
      else if($action == 'update_product') {
        tep_db_perform(paypal_rp_product_profile, $sql_data_array, 'update', "products_id = '" . (int)$products_id . "'");
      }      
    }	
		$logger->write(print_r($sql_data_array,1),'finalsqlarray');
}


function listen_recurringproduct_copy_to_confirm($array){
if (!is_object($logger)) $logger = new logger;

	$products_id = $array[0];
	$dup_products_id = $array[1];
	$logger->write($products_id,"products_id");
	$logger->write($dup_products_id,"dup_products_id");
	$prod_type_query = tep_db_query("select products_type from " . TABLE_PRODUCTS . " where products_id = '" . (int)$products_id . "'");
	$prod_type = tep_db_fetch_array($prod_type_query);			
	if($prod_type['products_type'] == 'recurring') {
	  tep_db_query("update " . TABLE_PRODUCTS . " set products_type = 'recurring' WHERE products_id = '" . (int)$dup_products_id . "'");
	  $rp_query = tep_db_query("select * from " . paypal_rp_product_profile . " where products_id = '" . (int)$products_id . "'");
	  $rp_product = tep_db_fetch_array($rp_query);
	  tep_db_query("insert into " . paypal_rp_product_profile . " (products_id, profile_start_date, billing_period, billing_frequency, total_billing_cycles, trial_profile_start_date, trial_billing_period, trial_billing_frequency, trial_total_billing_cycles, trial_amt, init_amt, failed_init_amt_action) values ('" . (int)$dup_products_id . "', '" . tep_db_input($rp_product['profile_start_date']) . "', '" . tep_db_input($rp_product['billing_period']) . "', '" . tep_db_input($rp_product['billing_frequency']) . "', '" . tep_db_input($rp_product['total_billing_cycles']) . "', '" . tep_db_input($rp_product['trial_profile_start_date']) . "', '" . tep_db_input($rp_product['trial_billing_period']) . "', '" . tep_db_input($rp_product['trial_billing_frequency']) . "', '" . tep_db_input($rp_product['trial_total_billing_cycles']) . "', '" . tep_db_input($rp_product['trial_amt']) . "', '" . tep_db_input($rp_product['init_amt']) . "', '" . tep_db_input($rp_product['failed_init_amt_action']) . "')");
	}
}

function listen_recurringproduct_params($parameters){
if (!is_object($logger)) $logger = new logger;

	$logger->write(print_r($parameters,1),'parameter_array');
	$rp_params = array('products_type'              => '',
                       'profile_start_date'           => '',
                       'billing_period'              => '',
                       'billing_frequency'           => '',
                       'total_billing_cycles'         => '',
                       'trial_profile_start_date'      => '',
                       'trial_billing_period'         => '',
                       'trial_billing_frequency'      => '',
                       'trial_total_billing_cycles'    => '',
                       'trial_amt'                   => '',
                       'init_amt'                    => '',
                       'failed_init_amt_action'        => '');					   
    $parameters = array_merge($parameters, $rp_params);
	$logger->write(print_r($parameters,1),'parameter_array_final');
	return $parameters;
}

function listen_recurringproduct_add_data($product){
	if($product['products_type'] == 'recurring'){
        $rp_rs = tep_db_query('SELECT * FROM ' . paypal_rp_product_profile . ' WHERE products_id = ' . $product['products_id']);
        if($row = tep_db_fetch_array($rp_rs)){
          $product = array_merge($product, $row);
        }
      }
	return $product;	  
}	
}
?>