(function ($) {

    $(document).on('click', '.close-modal',function(){
        $('#danger-alert-cart').hide();
    });

    $(document).on('click', '.single_add_to_cart_button_success', function (e) {
        e.preventDefault();

        var $thisbutton = $(this),
            $form = $thisbutton.closest('form.cart'),
            product_qty = $form.find('input[name=quantity]').val() || 1,
            product_id = $form.find('input[name=product_id]').val() || id,
            variation_id = $form.find('input[name=variation_id]').val() || 0;
            id_reseller = $thisbutton.attr('attr-id');

        var data = {
            action: 'woocommerce_ajax_add_to_cart_custom',
            product_id: product_id,
            product_sku: '',
            quantity: product_qty,
            variation_id: variation_id,
            reseller_id: id_reseller,
        };

        $(document.body).trigger('adding_to_cart', [$thisbutton, data]);

        $.ajax({
            type: 'post',
            url: wc_add_to_cart_params.ajax_url,
            data: data,
            beforeSend: function (response) {
                $thisbutton.removeClass('added').addClass('loading');
            },
            complete: function (response) {
                $thisbutton.addClass('added').removeClass('loading');
            },
            success: function (response) {
                if(response.error === true){
                    let message = response.message;
                    $('#danger-alert-cart').show();
                    $("#danger-alert-cart").html("<div class='modal-content-alert-success'><i class='close-modal'></i> <i class='error-icon'></i>" + message + "</div>");
                }

                if (response.error && response.product_url) {
                    window.location = response.product_url;
                    return;
                } else {
                    $(document.body).trigger('added_to_cart', [response.fragments, response.cart_hash, $thisbutton]);
                }
            },
        });

        return false;
    });
})(jQuery);