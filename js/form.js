$(document).ready(function () {
    let countdownInterval;
    let submitTimeout;

    $("#stud_form").on("submit", function (e) {
        e.preventDefault();

        $("#ajax-message").html('');
        $(".error-message").remove();
        $(".validation").removeClass('error-border');

        let isValid = true;

        $(".validation").each(function () {
            const $field = $(this);
            const value = $field.val().trim();

            if (!value) {
                isValid = false;
                showError($field, 'This field is required.');
            } else if ($field.attr('type') === 'email' && !/^[^\s@]+@[^\s@]+\.[^\s@]+$/.test(value)) {
                isValid = false;
                showError($field, 'Enter a valid email.');
            } else if ($field.attr('id') === 'phone' && !/^\d{10}$/.test(value)) {
                isValid = false;
                showError($field, 'Phone must be 10 digits.');
            } else if ($field.is('select') && $field.val() === '') {
                isValid = false;
                showError($field, 'Select an option.');
            }
        });

        if (!$("input[name='gender']:checked").length) {
            isValid = false;
            showError($("#male").closest('.form-group'), 'Select gender.');
        }

        if (!isValid) return;

        let countdown = 3;
        $("#ajax-message").html(
            `<div class="alert alert-info">
                Submitting in <span class="countdown">${countdown}</span> sec...
                <button type="button" class="btn btn-sm btn-danger ms-2" id="cancel-submit">Cancel</button>
            </div>`
        );

        countdownInterval = setInterval(() => {
            countdown--;
            $(".countdown").text(countdown);
        }, 1000);

        const $submitBtn = $(this).find('button[type="submit"]');
        const originalText = $submitBtn.text();
        $submitBtn.prop('disabled', true).text('Processing...');

        submitTimeout = setTimeout(() => {
            clearInterval(countdownInterval);
            $("#ajax-message").html('');

            const formData = new FormData(this);
            const actionUrl = $("#form-action").val();

            $.ajax({
                url: actionUrl,
                type: "POST",
                data: formData,
                contentType: false,
                processData: false,
                dataType: 'json',
                success: function (response) {
                    if (response.success) {
                        $("#ajax-message").html(
                            `<div class="alert alert-success">${response.message}</div>`
                        );
                        if (response.redirect) {
                            setTimeout(() => {
                                window.location.href = response.redirect;
                            }, 2000);
                        }
                    } else {
                        $("#ajax-message").html(
                            `<div class="alert alert-danger">${response.message}</div>`
                        );
                        if (response.message.includes('email')) {
                            $("#email").addClass('error-border');
                        }
                    }
                },
                error: function (xhr, status, error) {
                    $("#ajax-message").html(
                        `<div class="alert alert-danger">Error: ${error}</div>`
                    );
                },
                complete: function () {
                    $submitBtn.prop('disabled', false).text(originalText);
                }
            });

        }, 3000);
    });

    $(document).on("click", "#cancel-submit", function () {
        clearTimeout(submitTimeout);
        clearInterval(countdownInterval);
        $("#ajax-message").html('');
        $("#stud_form button[type='submit']").prop('disabled', false).text('Submit');
    });

    function showError($element, message) {
        $('<div class="error-message" style="color:red; font-size:13px; margin-top:4px;"></div>')
            .text(message)
            .insertAfter($element);
        $element.addClass('error-border');
    }
});
