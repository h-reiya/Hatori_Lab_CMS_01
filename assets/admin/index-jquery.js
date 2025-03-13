jQuery(document).ready(function ($) {
    var mediaUploader;

    $('#upload_image_button').click(function (e) {
        e.preventDefault();
        if (mediaUploader) {
            mediaUploader.open();
            return;
        }
        mediaUploader = wp.media.frames.file_frame = wp.media({
            title: '画像を選択',
            button: {
                text: '画像を選択',
            },
            multiple: false,
        });
        mediaUploader.on('select', function () {
            var attachment = mediaUploader.state().get('selection').first().toJSON();
            $('#category_image').val(attachment.id);
            $('#category_image_preview').attr('src', attachment.url);
        });
        mediaUploader.open();
    });

    $('#remove_image_button').click(function (e) {
        e.preventDefault();
        $('#category_image').val('');
        $('#category_image_preview').attr('src', '');
    });
});

jQuery(document).ready(function ($) {
    // 投稿タイプセレクターの変更イベント
    $('.post-type-selector').on('change', function () {
        var section = $(this).data('section');
        var customSelector = $(this).closest('.form-table').find('.custom-posts-selector');

        if ($(this).val() === 'custom') {
            customSelector.show();
        } else {
            customSelector.hide();
        }
    });
});
