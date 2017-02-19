( function() {
    /**
     * We can't just enhance the main Notice Form with
     * jQueryUI tabs since GNU social clones that element
     * and uses it for replies. Cloning the tab-enhanced form
     * breaks the jQueryUI functionality.
     *
     * Instead, what we do is clone the main Notice Form, hide
     * the original and display our clone in the next container.
     * This way, GS can clone the original, unenhanced form
     * all it wants.
     *
     * We enhance the reply forms cloned by GS via monkey-patch
     * later (see below)
     */
    var $noticeFormContainer = $( "#input_form_status" )
        $noticeForm = $noticeFormContainer.children( "form" )
        .clone()
        .insertAfter( $noticeFormContainer )
        .tabs();

    /**
     * When clicking on the "Preview" tab, ask the backend to render the
     * content inside the notice textarea and display the results
     */
    $( document ).on( "click", "[href=#chr-notice-preview]", function() {
        // TODO: don't re-render if the 'preview' tab was already active

        var $previewTab = $( this ),
            $tabContainer = $previewTab.closest( ".chr-notice-preview-container" ),
            $previewContainer = $tabContainer.find( "#chr-notice-preview" ),
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

    /**
     * Here, we monkey-patch the GS function that clones the main
     * Notice Form to use in replies. We cache the original function,
     * call it as-is, and after GS created the proper reply form, we
     * enhance it with jQueryUI tabs.
     */
    var o = SN.U.NoticeInlineReplyTrigger;

    SN.U.NoticeInlineReplyTrigger = function(notice, initialText) {
        o(notice, initialText);

        $( ".chr-tabs" )
            .not( ".ui-tabs-nav" )
            .closest( ".chr-notice-preview-container" )
            .tabs();
    };
}() );

