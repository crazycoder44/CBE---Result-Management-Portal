<?php
session_start();
include('includes/config.php');

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $session = htmlspecialchars(trim($_POST['session']));
    $termid = htmlspecialchars(trim($_POST['termid']));
    $classid = htmlspecialchars(trim($_POST['classid']));
    $sub_id = htmlspecialchars(trim($_POST['sub_id']));
    $baseclassid = substr($classid, 0, -1); // Remove the last character

    try {
        // Fetch the data from tempresult
        $sqlSelect = "SELECT email, ca, examobj, examtheory FROM tempresult WHERE session = :session AND termid = :termid AND classid = :classid AND sub_id = :sub_id";
        $querySelect = $dbh->prepare($sqlSelect);
        $querySelect->bindParam(':session', $session, PDO::PARAM_STR);
        $querySelect->bindParam(':termid', $termid, PDO::PARAM_INT);
        $querySelect->bindParam(':classid', $classid, PDO::PARAM_STR);
        $querySelect->bindParam(':sub_id', $sub_id, PDO::PARAM_INT);
        $querySelect->execute();
        $results = $querySelect->fetchAll(PDO::FETCH_ASSOC);

        if (count($results) > 0) {
            // Count all students in the students table with classid like baseclassid
            $sqlCount = "SELECT COUNT(*) as noinclass FROM students WHERE classid LIKE :baseclassid";
            $queryCount = $dbh->prepare($sqlCount);
            $likeBaseClassId = $baseclassid . '%'; // Using LIKE for matching
            $queryCount->bindParam(':baseclassid', $likeBaseClassId, PDO::PARAM_STR);
            $queryCount->execute();
            $countResult = $queryCount->fetch(PDO::FETCH_ASSOC);
            $noinclass = $countResult['noinclass'];

            // Prepare the insert statement for results table
            $sqlInsert = "INSERT INTO results (email, session, termid, classid, sub_id, ca, examobj, examtheory, noinclass) VALUES (:email, :session, :termid, :classid, :sub_id, :ca, :examobj, :examtheory, :noinclass)";            
            $queryInsert = $dbh->prepare($sqlInsert);

            // Insert each record into results
            foreach ($results as $row) {
                $queryInsert->bindParam(':email', $row['email'], PDO::PARAM_STR);
                $queryInsert->bindParam(':session', $session, PDO::PARAM_STR);
                $queryInsert->bindParam(':termid', $termid, PDO::PARAM_INT);
                $queryInsert->bindParam(':classid', $classid, PDO::PARAM_STR);
                $queryInsert->bindParam(':sub_id', $sub_id, PDO::PARAM_INT);
                $queryInsert->bindParam(':ca', (float)$row['ca'], PDO::PARAM_STR); 
                $queryInsert->bindParam(':examobj', (float)$row['examobj'], PDO::PARAM_STR);
                $queryInsert->bindParam(':examtheory', (float)$row['examtheory'], PDO::PARAM_STR);
                $queryInsert->bindParam(':noinclass', $noinclass, PDO::PARAM_INT);
                $queryInsert->execute();
            }

            // Delete from tempresult
            $sqlDelete = "DELETE FROM tempresult WHERE session = :session AND termid = :termid AND classid = :classid AND sub_id = :sub_id";
            $queryDelete = $dbh->prepare($sqlDelete);
            $queryDelete->bindParam(':session', $session, PDO::PARAM_STR);
            $queryDelete->bindParam(':termid', $termid, PDO::PARAM_INT);
            $queryDelete->bindParam(':classid', $classid, PDO::PARAM_STR);
            $queryDelete->bindParam(':sub_id', $sub_id, PDO::PARAM_INT);
            $queryDelete->execute();

            echo json_encode(['success' => true]);
        } else {
            echo json_encode(['success' => false, 'message' => 'No records found to approve.']);
        }
    } catch (PDOException $e) {
        echo json_encode(['success' => false, 'message' => 'Database error: ' . $e->getMessage()]);
    }
}
?>
