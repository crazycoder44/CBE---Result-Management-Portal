<?php
// get_students_scores.php

// Include your database connection file
include 'db_connection.php';

// Fetch POST data
$postData = json_decode(file_get_contents('php://input'), true);

if ($postData) {
    $session = $postData['session'];
    $term = $postData['term'];
    $sub_id = $postData['sub_id'];
    $class = $postData['class'];
    $staffid = $postData['staffid'];

    // Query to fetch students and their scores from exams and history tables
    $query = "SELECT e.sid, e.student_name, e.email, e.ca, 
                     IFNULL(h.score, 0) AS history_score,
                     e.examobj, e.examtheory
              FROM exams e
              LEFT JOIN history h ON e.email = h.email AND e.eid = h.eid
              WHERE e.sub_id = '$sub_id' AND e.classid = '$class'";

    $result = mysqli_query($con, $query);

    if ($result) {
        $students = [];
        while ($row = mysqli_fetch_assoc($result)) {
            $students[] = [
                'sid' => $row['sid'],
                'student_name' => $row['student_name'],
                'email' => $row['email'],
                'ca' => $row['ca'],
                'examobj' => $row['history_score'], // Populate with history score if exists
                'examtheory' => $row['examtheory']
            ];
        }

        // Return JSON response
        echo json_encode([
            'success' => true,
            'students' => $students
        ]);
    } else {
        echo json_encode([
            'success' => false,
            'message' => 'Failed to fetch student data.'
        ]);
    }
} else {
    echo json_encode([
        'success' => false,
        'message' => 'Invalid request.'
    ]);
}
?>
