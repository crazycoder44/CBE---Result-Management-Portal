<?php
session_start();
include('includes/config.php');

// Check if the request method is POST
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Get the raw POST data
    $data = json_decode(file_get_contents('php://input'), true);

    // Check if 'sid' is present in the data
    if (isset($data['sid'])) {
        $sid = htmlspecialchars(trim($data['sid']));

        try {
            // Prepare the SQL statement to insert the sid into the blacklist table
            $sql = "INSERT INTO blacklist (sid) VALUES (:sid)";
            $query = $dbh->prepare($sql);
            $query->bindParam(':sid', $sid, PDO::PARAM_STR);

            // Execute the query
            if ($query->execute()) {
                // Return a success response
                echo json_encode(['success' => true]);
            } else {
                // Return a failure response
                echo json_encode(['success' => false, 'message' => 'Failed to add student to blacklist.']);
            }
        } catch (PDOException $e) {
            // Return an error response with the exception message
            echo json_encode(['success' => false, 'message' => 'Database error: ' . $e->getMessage()]);
        }
    } else {
        // Return an error response if 'sid' is not provided
        echo json_encode(['success' => false, 'message' => 'Student ID is missing.']);
    }
} else {
    // Return an error response if the request method is not POST
    echo json_encode(['success' => false, 'message' => 'Invalid request method.']);
}
?>
