jQuery(document).ready(function($) {
    $('#digiwoo_category_select').on('change', function() {
        var category_id = $(this).val();

        $.ajax({
            url: digiwoo_params.ajax_url,
            type: 'POST',
            data: {
                action: 'digiwoo_get_products',
                category_id: category_id
            },
            success: function(response) {
                var products = JSON.parse(response);
                var options = '';
                products.forEach(function(product) {
                    options += '<option value="' + product.id + '">' + product.name + '</option>';
                });
                $('#digiwoo_product_select').html(options).show();
            }
        });
    });
});
