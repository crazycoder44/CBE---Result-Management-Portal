<?php
include('includes/dbConnection.php');

if (isset($_POST['sid'])) {
    $sid = mysqli_real_escape_string($con, $_POST['sid']);
    $query = "SELECT 1 FROM blacklist WHERE sid = '$sid'";
    $result = mysqli_query($con, $query);

    if (mysqli_num_rows($result) > 0) {
        echo 'blacklisted';
    } else {
        echo 'not_blacklisted';
    }
}
?>
