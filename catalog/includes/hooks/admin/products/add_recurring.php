<?php

/*
  $Id$

  osCommerce, Open Source E-Commerce Solutions
  http://www.oscommerce.com

  Copyright (c) 2014 osCommerce

  Released under the GNU General Public License
*/

  class hook_admin_products_add_recurring {  
  
    function listen_product_script() {
	
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
	$( '#RP_start_date' ).datepicker({
			dateFormat: 'yy-mm-dd'
		});
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

    function listen_get_product_info(){
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
					 &nbsp;<input type='checkbox' id='rpCreateProfileTransactionFailure' name='rpCreateProfileTransactionFailure'/>Create a profile if your one time transaction fails</td>
					 
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
	
    function listen_save_product_info($newproduct){
	$action = $newproduct[0];
	$http_array = $newproduct[1];
	$sql_data_array = $newproduct[2];
	$products_id = $newproduct[3];
	
	
	if($http_array['products_type'] == 'recurring') {
	
	$insert_sql_data = array('products_type' => tep_db_prepare_input($http_array['products_type']));
	tep_db_perform(TABLE_PRODUCTS, $insert_sql_data, 'update', "products_id = '" . (int)$products_id . "'");
	
	// rp insert for rp profile data
    $trial = false;
    if(isset($http_array['rpTrialPeriod']) && $http_array['rpTrialPeriod'] == 'on'){
      $trial = true;
    }
    if($http_array['rpBillingPeriod'] == 'semimonth') {
    $freqN = '1';
    }
    else if($http_array['rpBillingPeriod'] == 'year' && ($http_array['rpBillingFrequency'] <= 0 || $http_array['rpBillingFrequency'] > 1)) {
      $freqN = '1';
    }
    else {
      $freqN = $http_array['rpBillingFrequency'];
    }
    if($http_array['rpNumberPaymentsOption'] == 'noenddate') {
      $nop = 0;
    }
    else {
    $nop = $http_array['rpNumberPayments'];
    }
	
	
	$rp_data_array = array();
    $insert_sql_data = array('profile_start_date'          => tep_db_prepare_input(date_format(new DateTime($http_array['RP_start_date']), 'Y-m-d H:i:s')),
                              'billing_period'             => tep_db_prepare_input($http_array['rpBillingPeriod']),
                              'billing_frequency'          => tep_db_prepare_input($freqN),
                              'total_billing_cycles'        => tep_db_prepare_input($nop));
     $rp_data_array = array_merge($rp_data_array, $insert_sql_data);
	
    if($trial){
	
      if($http_array['rpTrialBillingPeriod'] == 'semimonth') {
        $freq = '1';
      }
      else if($http_array['rpTrialBillingPeriod'] == 'year' && ($http_array['rpTrialBillingFrequency'] <= 0 || $http_array['rpTrialBillingFrequency'] > 1)) {
        $freq = '1';
      }
      else {
        $freq = $http_array['rpTrialBillingFrequency'];
      }  
	  
	$insert_sql_data = array('trial_profile_start_date' => tep_db_prepare_input(date_format(new DateTime($http_array['RP_start_date']), 'Y-m-d H:i:s')),
							'trial_billing_period' => tep_db_prepare_input($http_array['rpTrialBillingPeriod']),
							'trial_billing_frequency' => tep_db_prepare_input($freq),
							'trial_total_billing_cycles' => tep_db_prepare_input($http_array['rpTrialNumberPayments']),
							'trial_amt' => tep_db_prepare_input($http_array['rpTrialPrice']));
							
	$rp_data_array = array_merge($rp_data_array, $insert_sql_data);
      }
      else {
	  $insert_sql_data = array('trial_profile_start_date' => null,
							'trial_billing_period' => null,
							'trial_total_billing_cycles' => 1,
							'trial_amt' => '0.00');
	$rp_data_array = array_merge($rp_data_array, $insert_sql_data);
    
      }
      // check rpOneTimeTransaction
	 
      if(isset($http_array['rpIntAmt'])){
       
		$insert_sql_data = array('init_amt' => $http_array['rpIntAmt']);
		$rp_data_array = array_merge($rp_data_array, $insert_sql_data);  
      }
      else {
       
	    $insert_sql_data = array('init_amt' => 0);
		$rp_data_array = array_merge($rp_data_array, $insert_sql_data);
      }
      if(isset($http_array['rpCreateProfileTransactionFailure'])){
        if($http_array['rpCreateProfileTransactionFailure'] == 'on'){
       
		$insert_sql_data = array('failed_init_amt_action' => 'ContinueOnFailure');
		$rp_data_array = array_merge($rp_data_array, $insert_sql_data);
        }
      else{
      
		$insert_sql_data = array('failed_init_amt_action' => 'CancelOnFailure');
		$rp_data_array = array_merge($rp_data_array, $insert_sql_data);
      }
      }
      else{
       
		$insert_sql_data = array('failed_init_amt_action' => 'CancelOnFailure');
		$rp_data_array = array_merge($rp_data_array, $insert_sql_data);
      }
	  
      if($action == 'insert_product') {
       
		$insert_sql_data = array('products_id' => $products_id);
		$rp_data_array = array_merge($rp_data_array, $insert_sql_data);
        tep_db_perform(paypal_rp_product_profile, $rp_data_array);
      }
      else if($action == 'update_product') {
        tep_db_perform(paypal_rp_product_profile, $rp_data_array, 'update', "products_id = '" . (int)$products_id . "'");
      }      
    }
	
	
	return $sql_data_array;
}


    function listen_init_product_parameters(){
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
   return $rp_params;
}
}
?>