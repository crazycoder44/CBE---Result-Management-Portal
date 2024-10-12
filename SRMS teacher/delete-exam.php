<?php
session_start();
include('includes/config.php');

// Ensure the staff is logged in
if(strlen($_SESSION['alogin']) == "") {
    echo json_encode(['status' => 'error', 'message' => 'Unauthorized request']);
    exit;
}

// Check if the exam ID (eid) is provided via POST
if (isset($_POST['eid']) && !empty($_POST['eid'])) {
    $eid = intval($_POST['eid']);

    try {
        // Prepare the DELETE SQL statement
        $sql = "DELETE FROM exams WHERE eid = :eid";
        $query = $dbh->prepare($sql);
        $query->bindParam(':eid', $eid, PDO::PARAM_INT);

        // Execute the query
        if ($query->execute()) {
            // If the query is successful, return a success message
            echo json_encode(['status' => 'success', 'message' => 'Exam deleted successfully']);
        } else {
            // If the query fails, return an error message
            echo json_encode(['status' => 'error', 'message' => 'Failed to delete exam']);
        }
    } catch (PDOException $e) {
        // Handle any database-related exceptions
        echo json_encode(['status' => 'error', 'message' => 'Database error: ' . $e->getMessage()]);
    }
} else {
    // If no valid eid is provided, return an error
    echo json_encode(['status' => 'error', 'message' => 'Invalid exam ID']);
}
?>
