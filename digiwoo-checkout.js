jQuery(document).ready(function($) {
    // Ketika kategori produk berubah
    $('select[name=product_category]').change(function() {
        var category_id = $(this).val();

        // AJAX request untuk mendapatkan produk berdasarkan kategori
        $.ajax({
            url: digiwoo_vars.ajax_url,
            method: 'GET',
            data: {
                action: 'get_products_by_category',
                category_id: category_id
            },
            success: function(response) {
                var productSelect = $('select[name=product_choice]');
                productSelect.empty(); // Bersihkan opsi sebelumnya
                
                $.each(response.data, function(index, product) {
                    productSelect.append($('<option>', {
                        value: product.id,
                        text: product.name
                    }));
                });
            }
        });
    });
});
