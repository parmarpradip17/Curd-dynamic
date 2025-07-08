$(document).ready(function () {
    // Form submission handler
    $('#stud_form').on('submit', function (e) {
        // Clear previous error states
        $('.form-group').removeClass('has-error');
        $('.error-message').remove();

        let isValid = true;

        // Validate required fields
        $('.validation').each(function () {
            if ($(this).is(':radio')) {
                if (!$('input[name="' + $(this).attr('name') + '"]:checked').length) {
                    isValid = false;
                    $(this).closest('.form-group').addClass('has-error')
                        .append('<span class="error-message">This field is required</span>');
                }
            } else if ($(this).val().trim() === '') {
                isValid = false;
                $(this).closest('.form-group').addClass('has-error')
                    .append('<span class="error-message">This field is required</span>');
            }
        });

        // Validate email format
        const email = $('#email').val().trim();
        if (email && !/^[^\s@]+@[^\s@]+\.[^\s@]+$/.test(email)) {
            isValid = false;
            $('#email').closest('.form-group').addClass('has-error')
                .append('<span class="error-message">Invalid email format</span>');
        }

        // Validate phone number
        const phone = $('#phone').val().trim();
        if (phone && !/^\d{10}$/.test(phone)) {
            isValid = false;
            $('#phone').closest('.form-group').addClass('has-error')
                .append('<span class="error-message">Phone must be 10 digits</span>');
        }

        if (!isValid) {
            e.preventDefault();
            $('html, body').animate({
                scrollTop: $('.has-error').first().offset().top - 100
            }, 500);
        }
    });

    // Real-time validation for phone number
    $('#phone').on('input', function () {
        $(this).val($(this).val().replace(/[^0-9]/g, '').substring(0, 10));
    });
});


if (!isValid) {
    e.preventDefault();
    return;
}

// Check if duplicate email error is present
if ($('.email-error').text().includes('already exists')) {
    e.preventDefault();
    alert("Please fix the errors before submitting.");
    return;
}



$('#email').on('blur', function () {
    var email = $(this).val().trim();
    $('.email-error').remove(); // remove previous message

    if (email !== '') {
        $.ajax({
            url: 'check_email.php',
            type: 'POST',
            data: { email: email },
            success: function (response) {
                if (response === 'exists') {
                    $('#email').after('<div class="email-error" style="color:red; margin-top:4px;">❌ Email already exists.</div>');
                } else if (response === 'available') {
                    $('#email').after('<div class="email-error" style="color:green; margin-top:4px;">✅ Email is available.</div>');
                } else {
                    $('#email').after('<div class="email-error" style="color:red; margin-top:4px;">Error checking email.</div>');
                }
            },
            error: function () {
                $('#email').after('<div class="email-error" style="color:red; margin-top:4px;">Error checking email.</div>');
            }
        });
    }
});

$(document).ready(function () {
    $('#stud_form').on('submit', function (e) {
        e.preventDefault();

        var formData = new FormData(this);

        $.ajax({
            url: 'curd.php',
            type: 'POST',
            data: formData,
            contentType: false,
            processData: false,
            success: function (response) {
                $('#form-response').html(response);

                // After 3 seconds, redirect to curd.php
                setTimeout(function () {
                    window.location.href = 'curd.php';
                }, 3000);
            },
            error: function () {
                $('#form-response').html("<div class='alert alert-danger'>Something went wrong.</div>");
            }
        });
    });
});


