<?php
include 'dbConnection.php';

// Sanitize and retrieve POST data
$sub_id = isset($_POST['sub_id']) ? $_POST['sub_id'] : '';
$class = isset($_POST['class']) ? $_POST['class'] : '';
$email = isset($_POST['email']) ? $_POST['email'] : '';

// Query to fetch score from history table
$sql = "SELECT score FROM history WHERE sub_id = $sub_id AND classid = $class AND email = $email";
$stmt = $con->prepare($sql);
$stmt->bind_param("sss", $sub_id, $class, $email);

$stmt->execute();
$stmt->bind_result($score);

// Fetch the score
if ($stmt->fetch()) {
    echo $score; // Output the score
} else {
    echo ""; // Return empty string if no score found (or handle appropriately)
}

$stmt->close();
$con->close();
?>
