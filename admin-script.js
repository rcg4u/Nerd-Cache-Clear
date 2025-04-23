jQuery(document).ready(function($) {
    $("#cache-order-list").sortable({
        update: function(event, ui) {
            var order = [];
            $('#cache-order-list li').each(function() {
                order.push($(this).attr('data-value'));
            });
            $('#cache_clear_order').val(order.join(','));
        }
    });
    $("#cache-order-list").disableSelection();
});