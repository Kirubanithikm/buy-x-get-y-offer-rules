<?php defined('ABSPATH') or exit ?>

<?php global $post; ?>

<div id="_bxgx_data" class="panel woocommerce_options_panel hidden">
    <p class="form-field">
        <label ><?php esc_html_e('Is buy x get x product', 'woocommerce');?></label>

    <form method="post" id="_bxgx_data" class="selected_value" >
        <?php
        $bxgx_data = get_post_meta($post->ID, '_bxgx_data', true);
        if (!empty($bxgx_data)) {
            $enabled = $bxgx_data['enabled'];
            $discount_type = $bxgx_data['discount_type'];
            $discount_value = $bxgx_data['discount_value'];
        }
        ?>

        <input type="radio" id="buy_get_product"
            <?php if (!empty($bxgx_data) && $enabled == "yes") {
                echo esc_html("checked");
            } ?>
               name="buy_get_product" value="yes" > Yes

        <input type="radio" id="buy_get_product"
            <?php if (!empty($bxgx_data) && $enabled == "no") {
                echo esc_html("checked");
            } ?>
               name="buy_get_product" value="no"> No
        <br>
        <div id="select_offer_type" style="display: <?php if (!empty($enabled) == "yes") {echo "block";} else {echo "none";}?>;">
            <h4> <?php esc_html_e('Please select the offer type', 'woocommerce');?> </h4>
            <select name="buy_get_discount_type" id="bxgx_discount_type_id">
                <option value="" hidden >choose</option>
                <option
                    <?php if (!empty($bxgx_data) && $discount_type == "bxgx_free") {
                        echo esc_html("selected");
                    } ?>
                        value="bxgx_free">Free</option>
                <option
                    <?php if (!empty($bxgx_data) && $discount_type == "bxgx_percentage") {
                        echo esc_html("selected");
                    } ?>
                        value="bxgx_percentage">percentage</option>
                <option
                    <?php if (!empty($bxgx_data) && $discount_type == "bxgx_fixed_price") {
                        echo esc_html("selected");
                    } ?>
                        value="bxgx_fixed_price">Fixed price</option>
            </select>
        </div>

        <div id="bxgx_discount_value" style="display: <?php if ($discount_type == "bxgx_free" || $discount_type == "" || $enabled == "no") {echo "none";} else {echo "block";}?>;">
            <br>
            <h4> <?php esc_html_e('Enter discount value', 'woocommerce');?> </h4>
            <?php
            $default_discount_input = get_option('buy_get_discount_value');
            if ($default_discount_input == "" && !empty($discount_value)) {
                $default_discount_input = $discount_value;
            }
            ?>
            <input id="bxgx_discount_input_id" type="number" name="buy_get_discount_value" placeholder="Enter discount value" min="1"
                <?php if (!empty($bxgx_data) && $discount_type == "bxgx_percentage") {
                    ?>
                    max="100"
                    <?php
                } ?>
                   value="<?php echo $default_discount_input ?>" >
        </div>
    </form>
    </p>
</div>