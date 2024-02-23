jQuery(document).on( 'click', '.wpe-notice .notice-dismiss', function() {

    jQuery.ajax({
        url: ajaxurl,
        data: {
            action: 'wpe_dismiss_notice',
            nonce: upgrade_notice._nonce
        }
    });

})