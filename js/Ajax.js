document.addEventListener('DOMContentLoaded', function () {
    // Attach click event to all delete buttons
    document.querySelectorAll('.btn-delete').forEach(button => {
        button.addEventListener('click', function (e) {
            e.preventDefault();

            if (confirm('Are you sure you want to delete this record?')) {
                const row = this.closest('tr');
                const id = this.getAttribute('data-id');

                // Show loading state
                const originalText = this.innerHTML;
                this.innerHTML = '<span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span> Deleting...';
                this.disabled = true;

                // AJAX request
                fetch(this.href, {
                    method: 'GET'
                })
                    .then(response => response.json())
                    .then(data => {
                        if (data.success) {
                            // Remove row from table
                            row.style.backgroundColor = '#ffcccc';
                            setTimeout(() => {
                                row.remove();

                                // Show success message
                                const alertDiv = document.createElement('div');
                                alertDiv.className = 'alert alert-success';
                                alertDiv.textContent = data.message;
                                document.querySelector('.container').prepend(alertDiv);

                                // Auto-hide message after 3 seconds
                                setTimeout(() => {
                                    alertDiv.remove();
                                }, 3000);
                            }, 500);
                        } else {
                            // Show error message
                            const alertDiv = document.createElement('div');
                            alertDiv.className = 'alert alert-danger';
                            alertDiv.textContent = data.message;
                            document.querySelector('.container').prepend(alertDiv);

                            // Auto-hide message after 3 seconds
                            setTimeout(() => {
                                alertDiv.remove();
                            }, 3000);
                        }
                    })
                    .catch(error => {
                        console.error('Error:', error);
                        alert('An error occurred while deleting the record');
                    })
                    .finally(() => {
                        // Reset button state
                        this.innerHTML = originalText;
                        this.disabled = false;
                    });
            }
        });
    });
});

$(document).ready(function () {
    // On click of edit button
    $(document).on('click', '.edit-btn', function () {
        var studentId = $(this).data('id');

        // Optional: show loading spinner
        $('#edit-container').html(
            '<div class="text-center p-3">' +
            '<div class="spinner-border text-primary" role="status">' +
            '<span class="visually-hidden">Loading...</span>' +
            '</div></div>'
        );

        // Load form via AJAX GET request
        $.ajax({
            url: 'update_student.php',
            type: 'GET',
            data: { id: studentId },
            success: function (response) {
                // Inject form HTML into the container
                $('#edit-container').html(response);
            },
            error: function (xhr, status, error) {
                $('#edit-container').html(
                    '<div class="alert alert-danger">Error loading form: ' + error + '</div>'
                );
            }
        });
    });
});
