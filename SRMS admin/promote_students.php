<?php

error_reporting(0);

include('includes/config.php');; // Include your database configuration

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Decode the JSON input
    $data = json_decode(file_get_contents('php://input'), true);

    // Validate the input data
    if (!isset($data['classid']) || !isset($data['students']) || !is_array($data['students'])) {
        echo json_encode(['success' => false, 'message' => 'Invalid input']);
        exit;
    }

    $classid = $data['classid'];
    $students = $data['students'];

    try {
        $dbh->beginTransaction();

        foreach ($students as $student) {
            // Debugging: Ensure data integrity
            if (!isset($student['email']) || !isset($student['sid'])) {
                throw new Exception('Invalid student data');
            }

            // Update the classid for the student
            $sql = "UPDATE students SET classid = :classid WHERE email = :email";
            $query = $dbh->prepare($sql);
            $query->bindParam(':classid', $classid, PDO::PARAM_STR);
            $query->bindParam(':email', $student['email'], PDO::PARAM_STR);

            if (!$query->execute()) {
                throw new Exception('Failed to update student classid');
            }

            // Delete from student_class_subject where sid = student sid
            $sql = "DELETE FROM student_class_subject WHERE sid = :sid";
            $query = $dbh->prepare($sql);
            $query->bindParam(':sid', $student['sid'], PDO::PARAM_STR);

            if (!$query->execute()) {
                throw new Exception('Failed to delete from student_class_subject');
            }
        }

        $dbh->commit();
        echo json_encode(['success' => true]);
    } catch (Exception $e) {
        $dbh->rollBack();

        // Log the error to a file or output it for debugging
        error_log('Error promoting students: ' . $e->getMessage());

        // Output the error message for the client
        echo json_encode(['success' => false, 'message' => $e->getMessage()]);
    }
}
?>
