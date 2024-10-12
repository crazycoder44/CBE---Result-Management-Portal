<?php
session_start();
include('includes/config.php');

if (strlen($_SESSION['alogin']) == "") {
    header("Location: index.php");
} else {
    // Get values from URL
    $eid = $_GET['eid'];
    $n = $_GET['n'];

    try {
        for ($i = 1; $i <= $n; $i++) {
            // Fetch question ID
            $query_question = $dbh->prepare("SELECT qid FROM questions WHERE eid = :eid AND sn = :sn");
            $query_question->bindParam(':eid', $eid, PDO::PARAM_STR);
            $query_question->bindParam(':sn', $i, PDO::PARAM_INT);
            $query_question->execute();
            $question = $query_question->fetch(PDO::FETCH_ASSOC);
            $qid = $question['qid'];

            if (!$qid) {
                continue; // Skip if no question found for this question number
            }

            // Update question text
            $qns = $_POST['qns' . $i];
            $update_question = $dbh->prepare("UPDATE questions SET qns = :qns WHERE eid = :eid AND sn = :sn");
            $update_question->bindParam(':qns', $qns, PDO::PARAM_STR);
            $update_question->bindParam(':eid', $eid, PDO::PARAM_STR);
            $update_question->bindParam(':sn', $i, PDO::PARAM_INT);
            $update_question->execute();

            // Handle image upload
            if (isset($_FILES['image' . $i]) && $_FILES['image' . $i]['error'] == 0) {
                $image = file_get_contents($_FILES['image' . $i]['tmp_name']);
                $update_image = $dbh->prepare("UPDATE questions SET image = :image WHERE eid = :eid AND sn = :sn");
                $update_image->bindParam(':image', $image, PDO::PARAM_LOB);
                $update_image->bindParam(':eid', $eid, PDO::PARAM_STR);
                $update_image->bindParam(':sn', $i, PDO::PARAM_INT);
                $update_image->execute();
            }

            // Update options
            $options = ['a', 'b', 'c', 'd'];
            foreach ($options as $index => $option_label) {
                $option_text = $_POST[$i . $option_label];

                // Fetch option ID
                $query_option = $dbh->prepare("SELECT optionid FROM options WHERE qid = :qid ORDER BY optionid LIMIT :index, 1");
                $query_option->bindParam(':qid', $qid, PDO::PARAM_STR);
                $query_option->bindParam(':index', $index, PDO::PARAM_INT);
                $query_option->execute();
                $option = $query_option->fetch(PDO::FETCH_ASSOC);
                $optionid = $option['optionid'];

                // Update option text
                $update_option = $dbh->prepare("UPDATE options SET option = :option WHERE optionid = :optionid");
                $update_option->bindParam(':option', $option_text, PDO::PARAM_STR);
                $update_option->bindParam(':optionid', $optionid, PDO::PARAM_STR);
                $update_option->execute();
            }

            // Update correct answer
            $correct_answer = $_POST['ans' . $i];
            $correct_optionid = '';

            // Fetch the option ID for the correct answer
            $query_correct_option = $dbh->prepare("SELECT optionid FROM options WHERE qid = :qid ORDER BY optionid LIMIT :index, 1");
            $query_correct_option->bindParam(':qid', $qid, PDO::PARAM_STR);
            $query_correct_option->bindParam(':index', array_search($correct_answer, $options), PDO::PARAM_INT);
            $query_correct_option->execute();
            $correct_option = $query_correct_option->fetch(PDO::FETCH_ASSOC);
            $correct_optionid = $correct_option['optionid'];

            // Update correct answer in the answer table
            $update_answer = $dbh->prepare("UPDATE answer SET ansid = :ansid WHERE qid = :qid");
            $update_answer->bindParam(':ansid', $correct_optionid, PDO::PARAM_STR);
            $update_answer->bindParam(':qid', $qid, PDO::PARAM_STR);
            $update_answer->execute();
        }

        // Redirect back after success
        $_SESSION['msg'] = "Questions updated successfully!";
        header("Location: manage-exams.php");
    } catch (Exception $e) {
        // Handle any errors
        $_SESSION['error'] = "Error updating questions: " . $e->getMessage();
        header("Location: manage-exams.php");
    }
}
?>
