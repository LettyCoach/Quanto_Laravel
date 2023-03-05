$(function() {
    $('.gradient_background').css('background', GradientList[$('select[name="gradient_color"]').val()][0]);

    $('#questions-container').find('button').not('.while-btn-add-survey').css("display", "none");
    $('#questions-container').find('.buttonEdit').css("display", "block");
    $('#questions-container').find('.buttonDelete').css("display", "block");
    $('#questions-container').find('input').prop('readonly', true);
    $('#questions-container').find('input[type="file"]').prop('disabled', true);
    $('#questions-container textarea').prop('readonly', true);
});

$('select[name="gradient_color"]').on('change', function() {
    $('.gradient_background').css('background', GradientList[$('select[name="gradient_color"]').val()][0]);
    $('.preview-gradient').css('background', GradientList[$('select[name="gradient_color"]').val()][0]);
});
$('input[name="widgetAlign"]').on('change', function(e) {
    var tmp = $('.embbed-html .iframe-html').text();
    if ($(e.target).val() == '0') {
        $('.embbed-html .iframe-html').text(tmp.replace('right: ', 'left: '));
        $('.embbed-html .iframe-html-clipboard').attr('data-clipboard-text', tmp.replace('right: ', 'left: '));
    } else if ($(e.target).val() == '1') {
        $('.embbed-html .iframe-html').text(tmp.replace('left: ', 'right: '));
        $('.embbed-html .iframe-html-clipboard').attr('data-clipboard-text', tmp.replace('left: ', 'right: '));
    }
});

$('input[name=border_color]').on('change', function() {
    $('#preview-text').css('border', '1px solid ' + $(this).val());
});
$('input[name=char_color]').on('change', function() {
    $('#preview-text').css('color', $(this).val());
});
$('input[name=callout_color]').on('change', function() {
    $('#preview-text').css('background', $(this).val());
});
$('[name=disp_qrcode_dummy]').on('change', function() {
    if ($(this).prop('checked')) $('[name=disp_qrcode]').val(1);
    else $('[name=disp_qrcode]').val(0);
});
loadFile = function (event, showImage){
    $(showImage).attr('src', window.URL.createObjectURL(event.target.files[0]));
    $(showImage).show();
}
