$(document).ready(function () {
    $('#update-form').on('submit', function (e) {
        e.preventDefault();

        // Clear previous messages
        $('#ajax-message').html('').removeClass('alert alert-success alert-danger');

        // Show loading state
        $('button[type="submit"]').prop('disabled', true).html(
            '<span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span> Updating...'
        );

        // Get form data
        const formData = new FormData(this);

        // AJAX request
        $.ajax({
            url: 'update_student.php',
            type: 'POST',
            data: formData,
            processData: false,
            contentType: false,
            dataType: 'json',
            success: function (response) {
                if (response.success) {
                    $('#ajax-message').html(
                        '<div class="alert alert-success">' + response.message + '</div>'
                    );

                    // Redirect after 3 seconds
                    setTimeout(function () {
                        window.location.href = 'curd.php';
                    }, 3000);
                } else {
                    $('#ajax-message').html(
                        '<div class="alert alert-danger">' + response.message + '</div>'
                    );
                    $('button[type="submit"]').prop('disabled', false).text('Update');
                }
            },
            error: function (xhr, status, error) {
                $('#ajax-message').html(
                    '<div class="alert alert-danger">Error: ' + error + '</div>'
                );
                $('button[type="submit"]').prop('disabled', false).text('Update');
            }
        });
    });
});