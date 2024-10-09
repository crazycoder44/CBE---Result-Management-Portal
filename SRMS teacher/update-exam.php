<?php
session_start();

// Include the database connection file
include('includes/config.php');

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Retrieve the form values
    $eid = htmlspecialchars(trim($_POST['eid']));  // Assuming eid is also passed as a hidden input or from the URL
    $sub_id = htmlspecialchars(trim($_POST['sub_id']));
    $classid = htmlspecialchars(trim($_POST['classid']));
    $termid = htmlspecialchars(trim($_POST['termid']));
    $session = htmlspecialchars(trim($_POST['session']));
    $sahi = htmlspecialchars(trim($_POST['sahi']));
    $waam = htmlspecialchars(trim($_POST['waam']));
    $timelimit = htmlspecialchars(trim($_POST['timelimit']));
    $tnoq = htmlspecialchars(trim($_POST['tnoq']));
    $exam_date = htmlspecialchars(trim($_POST['exam_date']));
    $exam_time = htmlspecialchars(trim($_POST['exam_time']));
    $instruction = htmlspecialchars(trim($_POST['instruction']));

    // Combine the exam date and time into a single datetime format
    $exam_datetime = $exam_date . ' ' . $exam_time;

    // Prepare the SQL update statement
    $update_query = "
        UPDATE exams
        SET sub_id = :sub_id,
            classid = :classid,
            termid = :termid,
            session = :session,
            sahi = :sahi,
            waam = :waam,
            timelimit = :timelimit,
            tnoq = :tnoq,
            date = :exam_datetime,
            instruction = :instruction
        WHERE eid = :eid";

    // Prepare the statement using PDO
    $stmt = $dbh->prepare($update_query);

    // Bind the form values to the query
    $stmt->bindParam(':sub_id', $sub_id, PDO::PARAM_STR);
    $stmt->bindParam(':classid', $classid, PDO::PARAM_STR);
    $stmt->bindParam(':termid', $termid, PDO::PARAM_STR);
    $stmt->bindParam(':session', $session, PDO::PARAM_STR);
    $stmt->bindParam(':sahi', $sahi, PDO::PARAM_STR);
    $stmt->bindParam(':waam', $waam, PDO::PARAM_STR);
    $stmt->bindParam(':timelimit', $timelimit, PDO::PARAM_INT);
    $stmt->bindParam(':tnoq', $tnoq, PDO::PARAM_INT);
    $stmt->bindParam(':exam_datetime', $exam_datetime, PDO::PARAM_STR);
    $stmt->bindParam(':instruction', $instruction, PDO::PARAM_STR);
    $stmt->bindParam(':eid', $eid, PDO::PARAM_INT);

    // Execute the update query
    if ($stmt->execute()) {
        // On success, redirect to a confirmation page or back to the exam list page
        $_SESSION['success'] = "Exam updated successfully!";
        header("Location: edit-exam-questions.php?eid=" . urlencode($eid) . "&n=" . urlencode($tnoq));        
        exit();
    } else {
        // On failure, show an error message
        $_SESSION['error'] = "Failed to update exam.";
        header("Location: edit_exam.php?eid=" . $eid);
        exit();
    }
} else {
    // If the request is not a POST request, redirect to the edit page
    header("Location: edit_exam.php?eid=" . $eid);
    exit();
}
?>
