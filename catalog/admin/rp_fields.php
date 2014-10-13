<!--Added for recurring payments[PayPal]-->
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

<script type="text/javascript">

function showMe (it, box) {
var vis = (box.checked) ? "block" : "none";
document.getElementById(it).style.display = vis;
}

</script>
<script type="text/javascript">
$(document).ready(function() {
	SetFocus(); 
	SetAllowedBillingFrequencies('rpBilling', '<?php print $pInfo->billing_frequency;?>'); 
	SetAllowedBillingFrequencies('rpTrialBilling', '<?php print trim($pInfo->trial_billing_frequency);?>');
});
function showFromSelectRP (it, from) {
    if(document.getElementById(from).value == 'recurring'){
        document.getElementById(it).style.display = 'block';
    }
    else{
        document.getElementById(it).style.display = 'none';
    }
}

</script>

<script type="text/javascript">
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

function updateProductNet() {
	var netValue = document.getElementById('rpPrice').value;
    document.forms["new_product"].products_price.value = doRound(netValue, 4);
	
	var taxRate = getTaxRate();
    grossValue = netValue;
	if (taxRate > 0) {
      grossValue = netValue * ((taxRate / 100) + 1);
    }

    document.forms["new_product"].products_price_gross.value = doRound(grossValue, 4);
}

</script>

<tr>
            <td colspan="2"><?php echo tep_draw_separator('pixel_trans.gif', '1', '10'); ?></td>
          </tr>
          <?php
          // only if recurring payments are enabled
          if($rp_enabled){
          ?>
          <!-- product type -->
          <tr>
            <td class="main">Product Type:</td>
            <td class="main"><?php echo tep_draw_separator('pixel_trans.gif', '24', '15') . '&nbsp;' . tep_draw_pull_down_menu('products_type', array(array('id' => 'standard', 'text' => 'Standard'),array('id' => 'recurring', 'text' => 'Recurring')), $pInfo->products_type,'id=\'products_type\' onchange="showFromSelectRP(\'rpProduct\', \'products_type\');"'); ?></td>
          </tr>
            <tr><td colspan="2">
            <?php
            if($pInfo->products_type == 'recurring') {
            ?>
            <div id="rpProduct" style="display:block;">
            <?php
            }else{
            ?>
            <div id="rpProduct" style="display:none;">
            <?php
            }
            ?>
          <table width="100%">
          <tr>
            <td colspan="2"><?php echo tep_draw_separator('pixel_trans.gif', '1', '10'); ?></td>
          </tr>

          <!-- Recurring payments profile -->
          <tr>
            <td colspan="2"><?php echo tep_draw_separator('pixel_trans.gif', '1', '10'); ?></td>
          </tr>
          <tr bgcolor="#ebebff">
            <td colspan="2" class="main"><strong>Recurring Payment Values<?php echo tep_draw_separator('pixel_trans.gif', '1', '10'); ?></strong></td>
          </tr>
          <tr>
            <td colspan="2"><?php echo tep_draw_separator('pixel_trans.gif', '1', '10'); ?></td>
          </tr>
          <tr>
            <td class="main">Recurring payments start date <br><small>(Leave this field empty if you want the product <br>profile to start when the buyer purchases it)<br>(YYYY-MM-DD)</small></td>
			<td class="main"><?php echo tep_draw_separator('pixel_trans.gif', '24', '15') . '&nbsp;' . tep_draw_input_field('RP_start_date', "", 'id="RP_start_date"') . ' <small>(YYYY-MM-DD)</small>'; ?></td>
            
          </tr>
          <tr>
            <td class="main" align="right">
            <?php
            if(strlen($pInfo->trial_billing_period)>0){
            ?>
            <input type="checkbox" name="rpTrialPeriod" onclick="showMe('trialRpDiv', this)" checked>
            <?php
            }else{
            ?>
            <input type="checkbox" name="rpTrialPeriod" onclick="showMe('trialRpDiv', this)">
            <?php
            }
            ?>
            </td>
            <td class="main">Include trial payment period</td>
          </tr>
          <!-- trial rp -->
          <tr>
            <td colspan="2">
            <?php
            if(strlen($pInfo->trial_billing_period) > 0){
            ?>
            <div id="trialRpDiv" style="display:block;">
            <?php
            }else{
            ?>
            <div id="trialRpDiv" style="display:none;">
            <?php
            }
            ?>
            <table width="100%" align="center" class="trialrptable" cellpadding="2">
                <tr>
                    <td colspan="2"><B class="main">Trial Period</B></td>
                </tr>
                <tr>
                    <td class="main">Trial payments cycle</td>
                    <td class="main"><?php print tep_draw_separator('pixel_trans.gif', '36', '15') . '&nbsp;' .tep_draw_pull_down_menu('rpTrialBillingPeriod', array(array('id' => 'day', 'text' => 'Day'),array('id' => 'week', 'text' => 'Week'),array('id' => 'semimonth', 'text' => 'SemiMonth'),array('id' => 'month', 'text' => 'Month'),array('id' => 'year', 'text' => 'Year')), $pInfo->trial_billing_period, ' id="rpTrialBillingPeriod" onchange="SetAllowedBillingFrequencies(\'rpTrialBilling\', \'\');"'). '&nbsp;cycle frequency&nbsp;';?><select id="rpTrialBillingFrequency" name="rpTrialBillingFrequency" /></td>
                </tr>
                <tr>
                    <td class="main">Number of Recurring Payments</td><INPUT type="hidden" name="rpTrialNumberPaymentsOption" value="true">
                    <td class="main"><?php print tep_draw_separator('pixel_trans.gif', '24', '15') . '&nbsp;' .tep_draw_input_field('rpTrialNumberPayments', $pInfo->trial_total_billing_cycles, '');?></td>
                </tr>

                <tr>
                    <td class="main">Net Amount</td>
                    <td class="main"><?php echo tep_draw_separator('pixel_trans.gif', '24', '15') . '&nbsp;' . tep_draw_input_field('rpTrialPrice', $pInfo->trial_amt, ''); ?></td>
                </tr>

            </table>
            </div>
            </td>
          </tr>
                <tr>
                    <td colspan="2" class="main"><B>Recurring Payments</B></td>
                </tr>
                <tr>
                    <td class="main">Payments cycle</td>
                    <td class="main"><?php print tep_draw_separator('pixel_trans.gif', '36', '15') . '&nbsp;' .tep_draw_pull_down_menu('rpBillingPeriod', array(array('id' => 'day', 'text' => 'Day'),array('id' => 'week', 'text' => 'Week'),array('id' => 'semimonth', 'text' => 'SemiMonth'),array('id' => 'month', 'text' => 'Month'),array('id' => 'year', 'text' => 'Year')), $pInfo->billing_period, ' id="rpBillingPeriod" onchange="SetAllowedBillingFrequencies(\'rpBilling\', \'\');"'). '&nbsp;cycle frequency&nbsp;';?><select id="rpBillingFrequency" name="rpBillingFrequency" /></td>
                </tr>
				<?php
				  $checked = true;
				  if($pInfo->total_billing_cycles == 0) {
				    $checked = false;
				  }
				?>
                <tr>
                    <td class="main">Number of Recurring Payments</td>
                    <td class="main"><?php print tep_draw_separator('pixel_trans.gif', '24', '15') . '&nbsp;' . tep_draw_radio_field('rpNumberPaymentsOption', 'enddate', $checked) .  tep_draw_separator('pixel_trans.gif', '24', '15') . '&nbsp;' .tep_draw_input_field('rpNumberPayments', $pInfo->total_billing_cycles, '');?></td>
                </tr>
                <tr>
                    <td>&nbsp;</td>
                    <td class="main"><?php print tep_draw_separator('pixel_trans.gif', '24', '15') . '&nbsp;' . tep_draw_radio_field('rpNumberPaymentsOption', 'noenddate', !($checked)) .  tep_draw_separator('pixel_trans.gif', '24', '15') . '&nbsp;';?>No end date <small>(Payments will continue until you suspend them)</small></td>
                </tr>
                <tr>
                    <td class="main">Net Amount</td>
                    <td class="main"><?php echo tep_draw_separator('pixel_trans.gif', '24', '15') . '&nbsp;' . tep_draw_input_field('rpPrice', '', ' onkeyup="updateProductNet();" id="rpPrice"'); ?></td>
                </tr>
                <tr>
                    <td colspan="2" class="main"><B>One-time transaction</B></td>
                </tr>
                <tr>
                    <td  class="main" title="Select one-time transactions for charges like setup fees. You can only create them when you create a profile and you won't be able to edit or reactivate your one-time transactions later.

We process one-time transactions almost immediately and don't wait for the profile start date.

Example: If you create a profile on May 15 and the profile's start date is May 29, we will process the one-time transaction on May 15.">
	<?php
	if($pInfo->init_amt == 0){
	  print tep_draw_checkbox_field('rpOneTimeTransaction', '1', false) ;
	}
	else{
	  print tep_draw_checkbox_field('rpOneTimeTransaction', '1', true) ;
	}
	?>&nbsp;Process one-time transaction  &nbsp;</td>
                    <td class="main">Amount &nbsp;<?php print tep_draw_input_field('rpIntAmt', $pInfo->init_amt, '');?></td>
                </tr>
                <tr>
                <?php
                if($pInfo->failed_init_amt_action == 'ContinueOnFailure'){
                    $chk = true;
                }else{
                    $chk = false;
                }
                ?>
                    <td  class="main" colspan="2" title="Create a profile if your one-time transaction fails. One-time transaction failures cancel both the one-time transaction and the profile you were trying to create. Select this option to automatically create a new profile and add the one-time transaction amount to the outstanding balance."><?php print tep_draw_checkbox_field('rpCreateProfileTransactionFailure', '1', $chk) ;?>&nbsp;Create a profile if your one time transaction fails</td>
                </tr>
          </table>
          </div>
          </td>
          </tr>
          <!-- end trial rp -->
          <?php
          }
		  //End [PayPal]
          ?>
		  
<script>
$('#RP_start_date').datepicker({
  dateFormat: 'yy-mm-dd'
});
</script>