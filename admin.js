jQuery(document).ready(function($) {
    $('#cache-order-list').sortable({
        update: function(event, ui) {
            var order = $(this).sortable('toArray', { attribute: 'data-cache-type' });
            $('#cache_clear_order').val(order.join(','));
        }
    });
});
