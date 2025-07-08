$(document).ready(function () {
    $(".delete-btn").click(function (e) {
        e.preventDefault();
        var $button = $(this);
        var id = $button.data("id");
        var $row = $button.closest("tr");

        // Hide row content and show undo button
        var originalRow = $row.html();
        $row.html(`
            <td colspan="8" class="text-center text-danger">
                Deleting in <span class="countdown">3</span> seconds...
                <button class="btn btn-sm btn-warning ms-2 undo-delete">Undo</button>
            </td>
        `);

        let countdown = 3;
        const interval = setInterval(() => {
            countdown--;
            $row.find(".countdown").text(countdown);
        }, 1000);

        // Start 3-second timeout
        const timeout = setTimeout(() => {
            clearInterval(interval);
            $.ajax({
                url: "delete.php",
                type: "POST",
                data: { id: id },
                success: function (response) {
                    if (response.trim() === "Success") {
                        $row.fadeOut(300, function () {
                            $(this).remove();
                        });
                    } else {
                        alert("Server response: " + response);
                        $row.html(originalRow); // Restore original row on error
                    }
                },
                error: function () {
                    alert("An error occurred while deleting.");
                    $row.html(originalRow); // Restore on failure
                }
            });
        }, 3000);

        // Cancel deletion on Undo
        $row.on("click", ".undo-delete", function () {
            clearTimeout(timeout);
            clearInterval(interval);
            $row.html(originalRow); // Restore original row
        });
    });
});




