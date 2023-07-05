jQuery(document).ready(function($) {
    // Check if desktop image is uploaded
    if ($('#desktop_image').val() !== '') {
        $('.img-render-desktop').show(); // Show the image container
        $('#upload_desktop_image_button, #desktop_image').hide(); // Hide the upload button and input
    }

    // Check if tablet image is uploaded
    if ($('#tablet_image').val() !== '') {
        $('.img-render-tablet').show(); // Show the image container
        $('#upload_tablet_image_button, #tablet_image').hide(); // Hide the upload button and input
    }

    // Upload button click event for desktop image
    $('#upload_desktop_image_button').click(function() {
        var customUploader = wp.media({
            title: 'Select Desktop Image',
            library: {
                type: 'image'
            },
            button: {
                text: 'Use Image'
            },
            multiple: false
        }).on('select', function() {
            var attachment = customUploader.state().get('selection').first().toJSON();
            $('#desktop_image').val(attachment.url);
            $('.img-render-desktop .has-preview').attr('src', attachment.url);
            $('#preview-desktop').css({display: 'block'});
            $('#preview-desktop').attr('src', attachment.url);
            $('.img-render-desktop').show();
            $('#upload_desktop_image_button, #desktop_image').hide();
        }).open();
    });

    // Upload button click event for tablet image
    $('#upload_tablet_image_button').click(function() {
        var customUploader = wp.media({
            title: 'Select Tablet Image',
            library: {
                type: 'image'
            },
            button: {
                text: 'Use Image'
            },
            multiple: false
        }).on('select', function() {
            var attachment = customUploader.state().get('selection').first().toJSON();
            $('#tablet_image').val(attachment.url);
            $('.img-render-tablet .has-preview').attr('src', attachment.url);
            $('#preview-tablet').css({display: 'block'});
            $('#preview-tablet').attr('src', attachment.url);
            $('.img-render-tablet').show();
            $('#upload_tablet_image_button, #tablet_image').hide();
        }).open();
    });

    // Remove button click event for desktop image
    $('#remove_desktop_image_button').click(function() {
        $('#desktop_image').val('');
        $('.img-render-desktop').hide();
        $('#preview-desktop').hide();
        $('#upload_desktop_image_button, #desktop_image').show();
        $(this).remove();
    });

    // Remove button click event for tablet image
    $('#remove_tablet_image_button').click(function() {
        $('#tablet_image').val('');
        $('.img-render-tablet').hide();
        $('#preview-tablet').hide();
        $('#upload_tablet_image_button, #tablet_image').show();
        $(this).remove();
    });
});
