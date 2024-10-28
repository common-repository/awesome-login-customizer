jQuery(document).ready(function($) {
    var customUploader = wp.media({
        title: 'Select or Upload Image',
        button: {
            text: 'Use this image'
        },
        multiple: false
    });

    // Background Image Uploader
    $('#alcwp-custom-bg-image-button').click(function(e) {
        e.preventDefault();
        customUploader.on('select', function() {
            var attachment = customUploader.state().get('selection').first().toJSON();
            $('#alcwp-custom-bg-image').val(attachment.url);
            showPreview('#alcwp-custom-bg-image-preview', attachment.url);
        }).open();
    });

    // Logo Image Uploader
    $('#alcwp-logo-image-url-button').click(function(e) {
        e.preventDefault();
        customUploader.on('select', function() {
            var attachment = customUploader.state().get('selection').first().toJSON();
            $('#alcwp-logo-image-url').val(attachment.url);
            showPreview('#alcwp-logo-image-url-preview', attachment.url);
        }).open();
    });

    // Remove Image button
    $('.alcwp-remove-image').on('click', function(e) {
        e.preventDefault();
        var inputId = $(this).data('input-id');
        $(inputId).val('');
        hidePreview(inputId + '-preview');
    });

    function showPreview(previewId, imageUrl) {
        var preview = $(previewId);
        preview.attr('src', imageUrl);
        preview.show();
    }

    function hidePreview(previewId) {
        var preview = $(previewId);
        preview.attr('src', '');
        preview.hide();
    }
});
