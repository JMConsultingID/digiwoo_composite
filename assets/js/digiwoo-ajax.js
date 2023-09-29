jQuery(document).ready(function($) {
    $('#digiwoo_category_select').on('change', function() {
        var category_id = $(this).val();
        console.log(digiwoo_params.ajax_url);
        console.log(category_id);
        $.ajax({
            url: digiwoo_params.ajax_url,
            type: 'POST',
            data: {
                action: 'digiwoo_get_pages'
            },
            dataType : 'json',
            success: function(response) {
                console.log('Success:', response);
                // Handle the response here, such as displaying the pages in a dropdown
            },
            error: function(jqXHR, textStatus, errorThrown) {
                console.error('AJAX Error:', textStatus, errorThrown);
            }
        });
    });
});