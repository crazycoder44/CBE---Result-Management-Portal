<?php
include('includes/config.php');

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Get the data from the request
    $data = json_decode(file_get_contents("php://input"), true);
    $email = $data['value'];

    // Check if the email exists in the students table
    $sql_students = "SELECT * FROM students WHERE email = :email";
    $query_students = $dbh->prepare($sql_students);
    $query_students->bindParam(':email', $email, PDO::PARAM_STR);
    $query_students->execute();

    // If email exists in students table, return a JSON response
    if($query_students->rowCount() > 0) {
        echo json_encode(['registered' => true, 'table' => 'students']);
    } else {
        // Check if the email exists in the staff table
        $sql_staff = "SELECT * FROM staff WHERE email = :email";
        $query_staff = $dbh->prepare($sql_staff);
        $query_staff->bindParam(':email', $email, PDO::PARAM_STR);
        $query_staff->execute();

        // If email exists in staff table, return a JSON response
        if($query_staff->rowCount() > 0) {
            echo json_encode(['registered' => true, 'table' => 'staff']);
        } else {
            // If the email doesn't exist in both tables, return a not registered response
            echo json_encode(['registered' => false]);
        }
    }
}
?>
