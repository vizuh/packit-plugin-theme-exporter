jQuery(document).ready(function($) {
    $('.ept-tab').on('click', function(e) {
        e.preventDefault();
        
        $('.ept-tab').removeClass('active');
        
        $(this).addClass('active');
        
        $('.ept-tab-content').removeClass('active');
        
        var target = $(this).data('target');
        $('#' + target).addClass('active');
    });
    
    $('.ept-export-button').on('click', function(e) {
        var itemName = $(this).data('item-name') || 'este item';
        var itemType = $(this).data('item-type') || 'item';
        
        if (!confirm('Tem certeza que deseja exportar ' + itemName + '?')) {
            e.preventDefault();
        }
    });
    
    $('#ept-search').on('keyup', function() {
        var searchTerm = $(this).val().toLowerCase();
        
        $('.ept-table tbody tr').each(function() {
            var itemName = $(this).find('td:first').text().toLowerCase();
            
            if (itemName.indexOf(searchTerm) === -1) {
                $(this).hide();
            } else {
                $(this).show();
            }
        });
    });
});