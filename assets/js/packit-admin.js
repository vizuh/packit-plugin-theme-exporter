jQuery(document).ready(function($) {
    $('.packit-tab').on('click', function(e) {
        e.preventDefault();
        
        $('.packit-tab').removeClass('active');
        
        $(this).addClass('active');
        
        $('.packit-tab-content').removeClass('active');
        
        var target = $(this).data('target');
        $('#' + target).addClass('active');
    });
    
    $('.packit-export-button').on('click', function(e) {
        var itemName = $(this).data('item-name') || 'este item';
        var itemType = $(this).data('item-type') || 'item';
        
        // Use localized string with sprintf-style replacement
        var confirmMessage = packitL10n.confirmExport.replace('%s', itemName);
        
        if (!confirm(confirmMessage)) {
            e.preventDefault();
        }
    });

    
    $('#packit-search').on('keyup', function() {
        var searchTerm = $(this).val().toLowerCase();
        
        $('.packit-table tbody tr').each(function() {
            var itemName = $(this).find('td:first').text().toLowerCase();
            
            if (itemName.indexOf(searchTerm) === -1) {
                $(this).hide();
            } else {
                $(this).show();
            }
        });
    });
});
