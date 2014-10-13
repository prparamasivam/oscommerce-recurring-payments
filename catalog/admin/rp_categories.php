<?php
	if($HTTP_POST_VARS['products_type'] == 'recurring') {
    // rp insert for rp profile data
    $trial = false;
    if(isset($HTTP_POST_VARS['rpTrialPeriod']) && $HTTP_POST_VARS['rpTrialPeriod'] == 'on'){
      $trial = true;
    }
    if($HTTP_POST_VARS['rpBillingPeriod'] == 'semimonth') {
    $freqN = '1';
    }
    else if($HTTP_POST_VARS['rpBillingPeriod'] == 'year' && ($HTTP_POST_VARS['rpBillingFrequency'] <= 0 || $HTTP_POST_VARS['rpBillingFrequency'] > 1)) {
      $freqN = '1';
    }
    else {
      $freqN = $HTTP_POST_VARS['rpBillingFrequency'];
    }
    if($HTTP_POST_VARS['rpNumberPaymentsOption'] == 'noenddate') {
      $nop = 0;
    }
    else {
    $nop = $HTTP_POST_VARS['rpNumberPayments'];
    }
    $sql_data_array = array('profile_start_date'          => $HTTP_POST_VARS['RP_start_date'],
                              'billing_period'             => $HTTP_POST_VARS['rpBillingPeriod'],
                              'billing_frequency'          => $freqN,
                              'total_billing_cycles'        => $nop
    );
      
    if($trial){
      if($HTTP_POST_VARS['rpTrialBillingPeriod'] == 'semimonth') {
        $freq = '1';
      }
      else if($HTTP_POST_VARS['rpTrialBillingPeriod'] == 'year' && ($HTTP_POST_VARS['rpTrialBillingFrequency'] <= 0 || $HTTP_POST_VARS['rpTrialBillingFrequency'] > 1)) {
        $freq = '1';
      }
      else {
        $freq = $HTTP_POST_VARS['rpTrialBillingFrequency'];
      }         
      $sql_data_array['trial_profile_start_date'] = $HTTP_POST_VARS['RP_start_date'];
      $sql_data_array['trial_billing_period'] = $HTTP_POST_VARS['rpTrialBillingPeriod'];
      $sql_data_array['trial_billing_frequency'] = $freq;
      $sql_data_array['trial_total_billing_cycles'] = $HTTP_POST_VARS['rpTrialNumberPayments'];
      $sql_data_array['trial_amt'] = $HTTP_POST_VARS['rpTrialPrice'];
      }
      else {
        $sql_data_array['trial_total_billing_cycles'] = 1;
        $sql_data_array['trial_profile_start_date'] = null;
        $sql_data_array['trial_billing_period'] = null;
        $sql_data_array['trial_amt'] = '0.00';
      }
      // check rpOneTimeTransaction
      if(isset($HTTP_POST_VARS['rpOneTimeTransaction'])){
        if($HTTP_POST_VARS['rpOneTimeTransaction'] == '1'){
          $sql_data_array['init_amt']                      = $HTTP_POST_VARS['rpIntAmt'];
        }
      }
      else {
        $sql_data_array['init_amt'] = 0;
      }
      if(isset($HTTP_POST_VARS['rpCreateProfileTransactionFailure'])){
        if($HTTP_POST_VARS['rpCreateProfileTransactionFailure'] == '1'){
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
        tep_db_perform(TABLE_RP_PAYPAL_PRODUCT_PROFILE, $sql_data_array);
      }
      else if($action == 'update_product') {
        tep_db_perform(TABLE_RP_PAYPAL_PRODUCT_PROFILE, $sql_data_array, 'update', "products_id = '" . (int)$products_id . "'");
      }
    }
?>