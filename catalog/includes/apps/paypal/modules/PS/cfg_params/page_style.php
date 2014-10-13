<?php
/*
  $Id$

  osCommerce, Open Source E-Commerce Solutions
  http://www.oscommerce.com

  Copyright (c) 2014 osCommerce

  Released under the GNU General Public License
*/

  class OSCOM_PayPal_PS_Cfg_page_style {
    var $default = '';
    var $sort_order = 200;

    function getSetField() {
      $input = tep_draw_input_field('page_style', OSCOM_APP_PAYPAL_PS_PAGE_STYLE, 'id="inputPsPageStyle"');

      $result = <<<EOT
<div>
  <p>
    <label for="inputPsPageStyle">Page Style</label>

    Add the Page Style defined in your PayPal account profile to apply the style to the checkout flow.
  </p>

  <div>
    {$input}
  </div>
</div>
EOT;

      return $result;
    }
  }
?>
