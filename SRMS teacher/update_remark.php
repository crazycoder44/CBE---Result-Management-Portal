<?php
session_start();
include('includes/config.php');
include('includes/dbConnection.php');

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Collecting form data
    $staffid = $_SESSION['staffid'];
    $studentEmail = $_POST['studentEmail'];
    $session = $_POST['session'];
    $term = $_POST['term'];
    $punctuality = $_POST['punctuality'];
    $attendance = $_POST['attendance'];
    $attentiveness = $_POST['attentiveness'];
    $neatness = $_POST['neatness'];
    $writing = $_POST['writing'];
    $honesty = $_POST['honesty'];
    $staffRelationship = $_POST['staff'];
    $studentsRelationship = $_POST['students'];
    $selfControl = $_POST['self_control'];
    $responsibility = $_POST['responsibility'];
    $willingnessToLearn = $_POST['learn'];
    $leadership = $_POST['leadership'];
    $initiative = $_POST['initiative'];
    $publicSpeaking = $_POST['speaking'];
    $verbalSkills = $_POST['verbalSkills'];
    $gamesSport = $_POST['gamesSport'];
    $artisticCreativity = $_POST['artisticCreativity'];
    $comments = $_POST['comments'];
    $status = $_POST['status'];


    try {
        // Check if the table exists, if not, create it
        $createTableQuery = "CREATE TABLE IF NOT EXISTS remarks (
            id INT(11) AUTO_INCREMENT PRIMARY KEY,
            staffid VARCHAR(50) NOT NULL,
            studentEmail VARCHAR(100) NOT NULL,
            session VARCHAR(50) NOT NULL,
            term VARCHAR(50) NOT NULL,
            punctuality INT(1) NOT NULL,
            attendance INT(1) NOT NULL,
            attentiveness INT(1) NOT NULL,
            neatness INT(1) NOT NULL,
            writing INT(1) NOT NULL,
            honesty INT(1) NOT NULL,
            staffRelationship INT(1) NOT NULL,
            studentsRelationship INT(1) NOT NULL,
            selfControl INT(1) NOT NULL,
            responsibility INT(1) NOT NULL,
            willingnessToLearn INT(1) NOT NULL,
            leadership INT(1) NOT NULL,
            initiative INT(1) NOT NULL,
            publicSpeaking INT(1) NOT NULL,
            verbalSkills INT(1) NOT NULL,
            gamesSport INT(1) NOT NULL,
            artisticCreativity INT(1) NOT NULL,
            comments TEXT NOT NULL,
            status TEXT NOT NULL,
            created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
        )";
        
        // Execute table creation query
        $dbh->exec($createTableQuery);

        // Check if a record exists for the given session and term
        $checkQuery = "SELECT id FROM remarks WHERE session = :session AND term = :term AND studentEmail = :studentEmail";
        $checkStmt = $dbh->prepare($checkQuery);
        $checkStmt->bindParam(':session', $session);
        $checkStmt->bindParam(':term', $term);
        $checkStmt->bindParam(':studentEmail', $studentEmail);
        $checkStmt->execute();

        if ($checkStmt->rowCount() > 0) {
            // Record exists, perform update
            $record = $checkStmt->fetch(PDO::FETCH_ASSOC);
            $updateQuery = "UPDATE remarks SET
                punctuality = :punctuality,
                attendance = :attendance,
                attentiveness = :attentiveness,
                neatness = :neatness,
                writing = :writing,
                honesty = :honesty,
                staffRelationship = :staffRelationship,
                studentsRelationship = :studentsRelationship,
                selfControl = :selfControl,
                responsibility = :responsibility,
                willingnessToLearn = :willingnessToLearn,
                leadership = :leadership,
                initiative = :initiative,
                publicSpeaking = :publicSpeaking,
                verbalSkills = :verbalSkills,
                gamesSport = :gamesSport,
                artisticCreativity = :artisticCreativity,
                comments = :comments,
                status =:status
            WHERE id = :id";

            $updateStmt = $dbh->prepare($updateQuery);
            $updateStmt->bindParam(':id', $record['id']);
            $updateStmt->bindParam(':punctuality', $punctuality);
            $updateStmt->bindParam(':attendance', $attendance);
            $updateStmt->bindParam(':attentiveness', $attentiveness);
            $updateStmt->bindParam(':neatness', $neatness);
            $updateStmt->bindParam(':writing', $writing);
            $updateStmt->bindParam(':honesty', $honesty);
            $updateStmt->bindParam(':staffRelationship', $staffRelationship);
            $updateStmt->bindParam(':studentsRelationship', $studentsRelationship);
            $updateStmt->bindParam(':selfControl', $selfControl);
            $updateStmt->bindParam(':responsibility', $responsibility);
            $updateStmt->bindParam(':willingnessToLearn', $willingnessToLearn);
            $updateStmt->bindParam(':leadership', $leadership);
            $updateStmt->bindParam(':initiative', $initiative);
            $updateStmt->bindParam(':publicSpeaking', $publicSpeaking);
            $updateStmt->bindParam(':verbalSkills', $verbalSkills);
            $updateStmt->bindParam(':gamesSport', $gamesSport);
            $updateStmt->bindParam(':artisticCreativity', $artisticCreativity);
            $updateStmt->bindParam(':comments', $comments);
            $updateStmt->bindParam(':status', $status);

            $updateStmt->execute();

            $response = array(
                'success' => true,
                'message' => 'Remarks successfully updated.'
            );
        } else {
            // Record does not exist, perform insert
            $insertQuery = "INSERT INTO remarks (
                staffid, studentEmail, session, term, punctuality, attendance, attentiveness,
                neatness, writing, honesty, staffRelationship, studentsRelationship, selfControl,
                responsibility, willingnessToLearn, leadership, initiative, publicSpeaking, 
                verbalSkills, gamesSport, artisticCreativity, comments, status
            ) VALUES (
                :staffid, :studentEmail, :session, :term, :punctuality, :attendance, :attentiveness,
                :neatness, :writing, :honesty, :staffRelationship, :studentsRelationship, :selfControl,
                :responsibility, :willingnessToLearn, :leadership, :initiative, :publicSpeaking, 
                :verbalSkills, :gamesSport, :artisticCreativity, :comments, :status
            )";

            $stmt = $dbh->prepare($insertQuery);
            $stmt->bindParam(':staffid', $staffid);
            $stmt->bindParam(':studentEmail', $studentEmail);
            $stmt->bindParam(':session', $session);
            $stmt->bindParam(':term', $term);
            $stmt->bindParam(':punctuality', $punctuality);
            $stmt->bindParam(':attendance', $attendance);
            $stmt->bindParam(':attentiveness', $attentiveness);
            $stmt->bindParam(':neatness', $neatness);
            $stmt->bindParam(':writing', $writing);
            $stmt->bindParam(':honesty', $honesty);
            $stmt->bindParam(':staffRelationship', $staffRelationship);
            $stmt->bindParam(':studentsRelationship', $studentsRelationship);
            $stmt->bindParam(':selfControl', $selfControl);
            $stmt->bindParam(':responsibility', $responsibility);
            $stmt->bindParam(':willingnessToLearn', $willingnessToLearn);
            $stmt->bindParam(':leadership', $leadership);
            $stmt->bindParam(':initiative', $initiative);
            $stmt->bindParam(':publicSpeaking', $publicSpeaking);
            $stmt->bindParam(':verbalSkills', $verbalSkills);
            $stmt->bindParam(':gamesSport', $gamesSport);
            $stmt->bindParam(':artisticCreativity', $artisticCreativity);
            $stmt->bindParam(':comments', $comments);
            $stmt->bindParam(':status', $status);

            $stmt->execute();

            $response = array(
                'success' => true,
                'message' => 'Remarks successfully saved.'
            );
        }

        echo json_encode($response);

    } catch (PDOException $e) {
        // Handle database error
        $response = array(
            'success' => false,
            'message' => 'Error: ' . $e->getMessage()
        );
        echo json_encode($response);
    }
}
?>
