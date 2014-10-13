<?php


include(DIR_FS_CATALOG . 'includes/apps/recurring_payments/paypal_rp_product_info.php');
  
class hook_shop_product_info_recurring_product {
	function listen_recurringProductInfo($array) {
	$products_id = $array[0];
	$product_info = $array[1];
	$rp_query = tep_db_query("select p.products_type from " . TABLE_PRODUCTS . " p where p.products_id = '" . (int)$products_id . "'");
    $rp_info = tep_db_fetch_array($rp_query);
    if($rp_info['products_type'] == 'recurring'){
      // recurring product
      $rp_array = array();
      $rp_product_query = tep_db_query('SELECT profile_start_date, billing_period, billing_frequency, total_billing_cycles, trial_billing_period, trial_billing_frequency, trial_total_billing_cycles,init_amt, trial_amt FROM ' . paypal_rp_product_profile . ' WHERE products_id=' . (int)$products_id);
      if($rp_product = tep_db_fetch_array($rp_product_query)){
         $rp_array = $rp_product;
      }

      $rpPinfo = new paypal_rp_product_info($product_info, $rp_array, $products_price);
      $rpPInfoArr = $rpPinfo->getProductInfoFull();
      $rp_desc = '<p>';
      if(strlen($rpPInfoArr['trial']) > 0){
        $rp_desc .= $rpPInfoArr['trial'] . "<br>\n";
      }
      $rp_desc .= substr($rpPInfoArr['normal'], 0, strpos($rpPInfoArr['normal'], "<br><b>Start"));
      $rp_desc .= "</p>";
      $product_info['products_description'] .= $rp_desc;
    	
	}
	return $product_info;
}
}

?>