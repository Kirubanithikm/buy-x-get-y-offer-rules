<?php defined('ABSPATH') or exit ?>

<?php if (!isset($enabled) || !isset($discount_type) || !isset($discount_value)) exit; ?>

    <div id="bxgx-single-product-eligibility-details" style="color:red">
        <?php
        switch ($enabled) {
            case "yes":
                switch ($discount_type) {
                    case "bxgx_free":
                        esc_html_e("This product is eligible for buy x product and get x product for 100% off");
                        break;
                    case "bxgx_percentage":
                        esc_html_e("This product is eligible for buy x product and get x product for " . $discount_value . "% percentage off");
                        break;
                    case "bxgx_fixed_price":
                        esc_html_e("This product is eligible for buy x product and get x product for " . $discount_value . "₹ off");
                        break;
                }
            case "no":
                break;
        }
        ?>
    </div>