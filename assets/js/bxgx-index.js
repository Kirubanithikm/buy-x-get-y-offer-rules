jQuery(function ($) {
    $('input[name="buy_get_product"]').change(function () {
        var optionValue = $(this).val();
        if (optionValue == 'yes') {
            $("#select_offer_type").show();
            $("#bxgx_discount_type_id").attr('disabled', false);

        } else if (optionValue == 'no') {
            $("#bxgx_discount_type_id").attr('disabled', true);
            $("#bxgx_discount_input_id").attr('disabled', true);
            $("#select_offer_type").hide();
            $("#bxgx_discount_value").hide();
        } else {
            $("#select_offer_type").hide();
            $("#bxgx_discount_value").hide();
        }
    });

    $('#bxgx_discount_type_id').change(function () {
        var selected_option = $(this).find('option:selected').val();
        if (selected_option == "bxgx_free") {
            $("#bxgx_discount_value").hide();
            $("#bxgx_discount_input_id").attr('disabled', true);
        } else {
            $("#bxgx_discount_value").show();
            $("#bxgx_discount_input_id").attr('disabled', false);
        }
    });

    $(document).ready(function () {
        $('input[name="buy_get_product"]').change(function () {
            var optionValue = $(this).val();
            if (optionValue == 'yes') {
                $("#select_offer_type").show();
                $("#bxgx_discount_type_id").attr('disabled', false);

            } else if (optionValue == 'no') {
                $("#bxgx_discount_type_id").attr('disabled', true);
                $("#bxgx_discount_input_id").attr('disabled', true);
                $("#select_offer_type").hide();
                $("#bxgx_discount_value").hide();
            } else {
                $("#select_offer_type").hide();
                $("#bxgx_discount_value").hide();
            }
        });

        $('#bxgx_discount_type_id').change(function () {
            var selected_option = $(this).find('option:selected').val();
            if (selected_option == "bxgx_free") {
                $("#bxgx_discount_value").hide();
                $("#bxgx_discount_input_id").attr('disabled', true);
            } else {
                $("#bxgx_discount_value").show();
                $("#bxgx_discount_input_id").attr('disabled', false);
            }
        });
    });
})