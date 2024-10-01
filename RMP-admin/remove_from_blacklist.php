<?php
require_once('includes/config.php'); // Replace with your actual database connection file

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $data = json_decode(file_get_contents('php://input'), true);
    $sid = isset($data['sid']) ? $data['sid'] : '';

    if ($sid) {
        // Delete student from blacklist
        $sql = "DELETE FROM blacklist WHERE sid = :sid";
        $query = $dbh->prepare($sql);
        $query->bindParam(':sid', $sid, PDO::PARAM_STR);
        $success = $query->execute();

        if ($success) {
            echo json_encode(['success' => true]);
        } else {
            echo json_encode(['success' => false, 'message' => 'Failed to remove student from blacklist.']);
        }
    } else {
        echo json_encode(['success' => false, 'message' => 'Invalid SID.']);
    }
}
?>
