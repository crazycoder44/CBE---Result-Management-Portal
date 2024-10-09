<?php
include_once 'dbConnection.php';

if (isset($_POST['sub_id']) && isset($_POST['staffid'])) {
    $sub_id = $_POST['sub_id'];
    $staffid = $_POST['staffid'];

    $query = mysqli_query($con, "SELECT c.classid, c.class FROM class c JOIN subject_teacher st ON c.classid = st.classid WHERE st.sub_id='$sub_id' AND st.staffid='$staffid'");
    if (mysqli_num_rows($query) > 0) {
        echo '<option value="" disabled selected>Select Class</option>';
        while ($row = mysqli_fetch_assoc($query)) {
            echo '<option value="' . $row['classid'] . '">' . $row['class'] . '</option>';
        }
    } else {
        echo '<option value="" disabled selected>No Classes Available</option>';
    }
} else {
    echo '<option value="" disabled selected>Error Fetching Classes</option>';
}
?>
