/**
 * Created by horat1us on 17.10.16.
 */
$(document).on('click', '[data-toggle="lightbox"]', function (event) {
    event.preventDefault();
    $(this).ekkoLightbox();
});
$(document).on('click', '[data-toggle=collapse]', function (event) {
    $('input[type=radio]').attr('checked', false);
    $(this).find('input[type=radio]').attr('checked', true);
});
function showError(message, title) {
    if (!title) {
        title = "Error";
    }

    var errorBlock = $('[data-target=errorBlock]');
    errorBlock.find('[data-target=errorName]').text(title);
    errorBlock.find('[data-target=errorText]').text(message);
    errorBlock.show();
}
$(document).on('click', '[data-toggle=generate]', function (event) {
    var codeSampleNumber = $('input[name=codeSample]:checked').val();

    $.ajax({
        url: './generate.php',
        method: 'post',
        data: {
            codeSample: codeSampleNumber
        },
        dataType: 'json'
    })
        .done(function (data) {
            if (!data.success || !(data.generated && data.time)) {
                showError(data.hasOwnProperty('error') ? data.error : 'Unknown error', 'Generation Error');
                console.log(data);
                if (data.trace) {
                    console.log(data.trace);
                }
            }

            $('[data-target=generationResult]').html(data.generated);

            $('[data-target=generationTime]').text(data.time);
        })
        .fail(function (data, textStatus) {
            console.log(data.responseStatus, data.responseText);
            showError(textStatus, "AJAX Error");
        });
});