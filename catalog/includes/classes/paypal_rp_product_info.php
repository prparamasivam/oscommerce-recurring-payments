<?php
/*
$Id$

PayPal
http://www.paypal.com 

Copyright (c) 2014 PayPal

Released under the GNU General Public License
*/

class paypal_rp_product_info {

    var $product_info;

    var $rp_info;

    var $price;

    function paypal_rp_product_info($product_info, $rp_info, $price){
      $this->product_info = $product_info;
      if(isset($product_info['products_tax_class_id'])) {
        $this->product_info['tax_class_id'] = $product_info['products_tax_class_id'];
      }
      $this->rp_info      = $rp_info;
      $this->price        = $price;
    }

    function getProductInfoFull(){
      global $currencies;
      $prefix1 = '';
      if(($this->rp_info['init_amt']) > 0) {
        $prefix1 = '<br><b>Initial Payment : </b> ' . $currencies->display_price($this->rp_info['init_amt'], 0);
        //Add tax for init_amt : tep_get_tax_rate($this->product_info['tax_class_id'])
      }
      $prefix1 .= "<br><b>Start date : </b>" . $this->getSubscriptionStartDate($this->rp_info);         
      $arr['normal'] = $this->buildRegularDescription();
      $arr['trial'] = $this->buildTrialDescription();    
      $arr['normal'] .= $prefix1;
      return $arr;
    }
    
    function buildTrialDescription() {
      global $currencies;
      $description = '';
      if(!is_null($this->rp_info['trial_billing_period'])){
        $plural1 = '';
        $plural2 = '';
        if($this->rp_info['trial_billing_frequency'] > 1){
          $plural1 = 's';
        }
        switch (strtolower($this->rp_info['trial_billing_period'])){
          case 'day':
          case 'week':
          case 'month':
          case 'year':
            if($this->rp_info['trial_total_billing_cycles'] > 1) {
              $plural2 = 's';
            }
            $description = "<b>Trial payment : </b>".$currencies->display_price($this->rp_info['trial_amt'], tep_get_tax_rate($this->product_info['tax_class_id'])) . ' for '.$this->rp_info['trial_billing_frequency'] . ' ' . ucfirst($this->rp_info['trial_billing_period']) . $plural1 ."<br>";
            $description .= "<b>Trial period : </b>".$this->rp_info['trial_total_billing_cycles'] .' '. ucfirst($this->rp_info['trial_billing_period']) . $plural2;
          break;
          case 'semimonth':
            if($this->rp_info['trial_total_billing_cycles'] != 2) {
              $plural2 = 's';
            }
            $timeSemi = ($this->rp_info['trial_total_billing_cycles'] * $this->rp_info['trial_billing_frequency'])/2;
            $description = "<b>Trial payment : </b>".$currencies->display_price($this->rp_info['trial_amt'], tep_get_tax_rate($this->product_info['tax_class_id'])) . ' Twice a Month';
            $description .= "<br><b>Trial period : </b>".$timeSemi .  ' month' . $plural2;
          break;
        }
      }
      return $description;
    }
    
    function buildRegularDescription() {
      $description = '';
      $plural = '';
      if($this->rp_info['billing_frequency'] > 1){
        $plural = 's';
      }
      switch (strtolower($this->rp_info['billing_period'])){
          case 'day':
          case 'week':
          case 'month':
          case 'year':
            $plural2 = '';
            if(($this->rp_info['total_billing_cycles'] * $this->rp_info['billing_frequency'])>1){
              $plural2 = 's';
            }
            if($this->rp_info['total_billing_cycles'] * $this->rp_info['billing_frequency'] == 0) {
              $period = 'Indefinite';
            }
            else {
              $period = $this->rp_info['total_billing_cycles'] * $this->rp_info['billing_frequency']. ' ' . ucfirst($this->rp_info['billing_period']) . $plural2;
            }
            $description = "<b>Regular Payment : </b>". $this->price . ' Every ' . $this->rp_info['billing_frequency'] . ' ' . ucfirst($this->rp_info['billing_period']) . $plural;
            $description .= "<br><b>Billing Cycle : </b>".($period) ;
          break;
          case 'semimonth':
            if(($this->rp_info['total_billing_cycles']) == 0) {
              $period = 'Indefinite';
            }
            else {
              if($this->rp_info['total_billing_cycles'] != 2) {
                $plural2 = 's';
              }
              $timeSemi = ($this->rp_info['total_billing_cycles'] * $this->rp_info['billing_frequency'])/2;
              $period = $timeSemi .  ' month' . $plural2;
            }
            $description = "<b>Regular Payment : </b>". $this->price . ' Twice a Month';
            $description .= "<br><b>Billing Cycle : </b>".($period) ;
          break;
      }
      return $description;
    }
    
	function getSubscriptionStartDate($rpArr){
    // find it
    list($profieDate,) = explode(' ', $rpArr['profile_start_date']);
    list($y,$m,$d) = explode('-', $profieDate);
    if($y == '0000'){
      $profieStartDate = gmdate("Y-m-d\TH:i:s\Z");
    }else{
      $uPTime = mktime(0,0,0,$m,$d,$y);
      // next one
      while ($uPTime < time()){
        $m = date('m', $uPTime);
        $d = date('d', $uPTime);
        $y = date('Y', $uPTime);
        switch (strtolower($rpArr['billing_period'])){
          case 'month':
            $uPTime = mktime(0,0,0,$m+1,$d,$y);
          break;
          case 'day':
            $uPTime = mktime(0,0,0,$m,$d+1,$y);
          break;
          case 'year':
            $uPTime = mktime(0,0,0,$m,$d,$y+1);
          break;
          case 'semimonth':
            $uPTime = mktime(0,0,0,$m,$d+14,$y);
          break;
          case 'week':
            $uPTime = mktime(0,0,0,$m,$d+7,$y);
          break;
          default:
          break;
        }
      }
      $profieStartDate = gmdate("Y-m-d\TH:i:s\Z", $uPTime);
    }
    // return
    return tep_date_short($profieStartDate);
    }
}