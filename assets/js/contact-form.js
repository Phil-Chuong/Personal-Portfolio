$(document).ready(function () {
    const $form = $('.php-email-form');

    $form.on('submit', function (e) {
        e.preventDefault();

        const $loading = $form.find('.loading');
        const $errorMessage = $form.find('.error-message');
        const $sentMessage = $form.find('.sent-message');

        // Reset states
        $loading.show();
        $errorMessage.hide().text('');
        $sentMessage.hide();

        const formData = {};
        $form.serializeArray().forEach(({ name, value }) => {
            formData[name] = value;
        });

        $.ajax({
            url: $form.attr('action'),
            method: 'POST',
            contentType: 'application/json',
            dataType: 'json',
            data: JSON.stringify(formData),
            success: function (response) {
                $loading.hide();

                if (response.success) {
                    $sentMessage.text(response.message).show();
                    $form[0].reset();

                    setTimeout(() => {
                        $sentMessage.fadeOut();
                    }, 5000);
                } else {
                    $errorMessage.text(response.message || 'Request failed').show();
                }
            },
            error: function (jqXHR) {
                $loading.hide();
                let message = 'Failed to send message. Please try again.';
                if (jqXHR.responseJSON && jqXHR.responseJSON.message) {
                    message = jqXHR.responseJSON.message;
                }
                $errorMessage.text(message).show();
            }
        });
    });
});
