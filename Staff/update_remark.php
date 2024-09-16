<?php
// Start session
session_start();

include_once "dbConnection.php"; // Ensure this file contains the database connection $con

$message = '';

// Check if form is submitted
if (isset($_POST['submit'])) {
    // Check if term, session, and sid are set in the form submission
    if (isset($_POST['term']) && isset($_POST['session']) && isset($_POST['sid'])) {
        $term = mysqli_real_escape_string($con, $_POST['term']);
        $session = mysqli_real_escape_string($con, $_POST['session']);
        $staffid = mysqli_real_escape_string($con, $_POST['staffid']);
        $sid = mysqli_real_escape_string($con, $_POST['sid']);
        
        // Sanitize the inputs
        $status = mysqli_real_escape_string($con, $_POST['status']);
        $guardian_comment = mysqli_real_escape_string($con, $_POST['comment']);
        $criteria = mysqli_real_escape_string($con, $_POST['criteria']);

        // Insert the remarks into the database
        $insertQuery = "
            INSERT INTO comments (sid, term, session, status, Guardian_Comments, Criteria, staffid) 
            VALUES ('$sid', '$term', '$session', '$status', '$guardian_comment', '$criteria', '$staffid')
            ON DUPLICATE KEY UPDATE
            status = '$status',
            Guardian_Comments = '$guardian_comment',
            Criteria = '$criteria',
            staffid = '$staffid'
        ";

        if (mysqli_query($con, $insertQuery)) {
            $message = "Remarks added successfully for student ID: $sid";
        } else {
            $message = "Error adding remarks for student ID: $sid - " . mysqli_error($con);
        }
    } else {
        $message = "Term, session, and student ID are required.";
    }
}


// Close the database connection
mysqli_close($con);

// Display the message in a modal
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Remarks Submission</title>
    <link href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">
    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.9.3/dist/umd/popper.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
</head>
<body>
    <!-- Modal -->
    <div class="modal fade" id="myModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="myModalLabel">Notification</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <!-- Success or error message will go here -->
                    <span id="modalMessage"></span>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                    <a href="dash.php" class="btn btn-primary">Home</a>
                </div>
            </div>
        </div>
    </div>

    <?php if (!empty($message)): ?>
        <script>
            // jQuery to show the modal
            $(document).ready(function() {
                $('#myModal').modal('show');
            });
            // Pass PHP message to modal
            document.getElementById('modalMessage').innerHTML = <?php echo json_encode($message); ?>;
        </script>
    <?php endif; ?>
</body>
</html>
