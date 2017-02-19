( function() {
    $( "#chr-notice-preview-container" ).tabs();

    $( document ).on( "click", "[href=#chr-notice-preview]", function() {
        // TODO: don't re-render if the 'preview' tab was already active

        var $tabContainer = $( "#chr-notice-preview-container" ),
            $previewContainer = $( "#chr-notice-preview" ),
            $noticeTextarea = $tabContainer.find( ".notice_data-text" ),
            noticeText = $noticeTextarea.val(),
            encodedNoticeText = encodeURIComponent( noticeText );

        // TODO: non-absolute url
        $.post( "/main/render-notice", "raw_content=" + encodedNoticeText + "&profile_id=1&ajax=true",
            function( data ) {
                $previewContainer.html( $( '#chr-rendered-notice', data ).html() );
            }
        )
        .error( function( err ) {
            // TODO: user feedback
            console.log('err');
            console.log( err );
        } );
    } );
}() );

