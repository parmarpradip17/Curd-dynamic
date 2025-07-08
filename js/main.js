$(document).ready(function () {
    $('#stud_form').on('submit', function (e) {
        e.preventDefault();

        let isValid = true;

        // Clear previous error messages
        $('.error-message').remove();

        // Validate text/email inputs
        $('.validation').each(function () {
            let value = $.trim($(this).val());
            let id = $(this).attr('id');

            if (value === '') {
                showError($(this), 'This field is required.');
                isValid = false;
            } else {
                if ($(this).attr('type') === 'email') {
                    let emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
                    if (!emailRegex.test(value)) {
                        showError($(this), 'Please enter a valid email address.');
                        isValid = false;
                    }
                }

                if (id === 'phone') {
                    let phoneRegex = /^\d{10}$/;
                    if (!phoneRegex.test(value)) {
                        showError($(this), 'Phone must be exactly 10 digits.');
                        isValid = false;
                    }
                }

                if ($(this).is('select') && value === '') {
                    showError($(this), 'Please select your qualification.');
                    isValid = false;
                }
            }
        });

        // Validate gender radio buttons
        if (!$('input[name="gender"]:checked').val()) {
            showError($('#genderGroup'), 'Please select your gender.');
            isValid = false;
        }

        if (isValid) {
            this.submit(); // or use AJAX here if you prefer
        }
    });

    // Error message function
    function showError(element, message) {
        let error = $('<div class="error-message" style="color:red; font-size:13px; margin-top:4px;"></div>').text(message);

        if (element.hasClass('form-group')) {
            element.append(error);
        } else {
            element.closest('.form-group').append(error);
        }
    }
});


$(document).ready(function () {
    $('input[name="profile"]').on('change', function (e) {
        var reader = new FileReader();
        reader.onload = function (e) {
            $('.img-show').html('<img src="' + e.target.result + '" alt="Profile Photo">');
        }
        reader.readAsDataURL(this.files[0]);
    });
});
