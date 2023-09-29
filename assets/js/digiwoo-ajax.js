jQuery(document).ready(function($) {
    $('#digiwoo_category_select').on('change', function() {
        var category_id = $(this).val();
        console.log(digiwoo_params.ajax_url);
        console.log(category_id);
        $.ajax({
            url: digiwoo_params.ajax_url,
            type: 'POST',
            data: {
                action: 'digiwoo_get_products',
                category_id: category_id
            },
            success: function(response) {
                try {
                    var products = JSON.parse(response);
                    var options = '';
                    products.forEach(function(product) {
                        options += '<option value="' + product.id + '">' + product.name + '</option>';
                    });
                    $('#digiwoo_product_select').html(options).show();

                    // Log successful response to console
                    console.log('Success:', response);
                } catch (e) {
                    console.error('Parsing error:', e);
                }
            },
            error: function(jqXHR, textStatus, errorThrown) {
                // Log any AJAX request errors with more details
                console.error('AJAX Error:', textStatus, errorThrown, jqXHR.responseText);
            }

        });
    });
});
