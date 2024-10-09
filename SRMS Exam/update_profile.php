<?php 

extract($_REQUEST);

// Select old data
$sql = mysqli_query($con, "SELECT * FROM students WHERE email='".$_SESSION['email']."'");
$res = mysqli_fetch_assoc($sql);

// Get classid from student record
$classid = $res['classid'];

// Fetch class information
$class_query = mysqli_query($con, "SELECT class FROM class WHERE classid = '$classid'");
$class_res = mysqli_fetch_assoc($class_query);

// Check if the class is ss1, ss2, or ss3
$is_ss_class = in_array($class_res['class'], ['ss1', 'ss2', 'ss3']);

if(isset($update)) {
    // Begin transaction
    mysqli_begin_transaction($con);

    try {
        // Remove existing references in student_class_subject
        $clear_subjects_query = "DELETE FROM student_class_subject WHERE sid='".$_SESSION['sid']."'";
        mysqli_query($con, $clear_subjects_query);

        // Update sid in students table
        $query1 = "UPDATE students SET fname='$fname', lname='$lname', sid='$sid', gender='$gender', mobile='$mobile' WHERE email='".$_SESSION['email']."'";
        mysqli_query($con, $query1);

        // Reinsert the references in student_class_subject with the new sid
        if (isset($subjects)) {
            foreach ($subjects as $subject_id) {
                $insert_subject_query = "INSERT INTO student_class_subject (sid, classid, sub_id) VALUES ('$sid', '$classid', '$subject_id')";
                mysqli_query($con, $insert_subject_query);
            }
        }

        // Commit transaction
        mysqli_commit($con);

        $err = "<font color='blue'>Profile updated successfully !!</font>";
    } catch (Exception $e) {
        // Rollback transaction in case of error
        mysqli_rollback($con);
        $err = "<font color='red'>Error updating profile: " . $e->getMessage() . "</font>";
    }
}

// Fetch subjects associated with the student's classid
$subjects_query = "
    SELECT s.sub_id, s.subject 
    FROM subjects s 
    JOIN class_subject cs ON s.sub_id = cs.sub_id 
    WHERE cs.classid = '$classid'
";
$subjects_result = mysqli_query($con, $subjects_query);

// Fetch the subjects the student is currently associated with
$student_subjects_query = "
    SELECT sub_id 
    FROM student_class_subject 
    WHERE sid = '".$_SESSION['sid']."'
";
$student_subjects_result = mysqli_query($con, $student_subjects_query);

$student_subjects = [];
while ($row = mysqli_fetch_assoc($student_subjects_result)) {
    $student_subjects[] = $row['sub_id'];
}
?>

<div class="panel title">
    <h2 align="center">Update Your Profile</h2>

    <form method="post">
        <table class="table table-bordered">
            <tr>
                <td colspan="2"><?php echo @$err; ?></td>
            </tr>
            <tr>
                <td>Enter Your New Name [First Name]</td>
                <td><input class="form-control" value="<?php echo $res['fname']; ?>" type="text" name="fname"/></td>
            </tr>
            <tr>
                <td>Enter Your New Name [Last Name]</td>
                <td><input class="form-control" value="<?php echo $res['lname']; ?>" type="text" name="lname"/></td>
            </tr>
            <tr>
                <td>Enter Your Email </td>
                <td><input class="form-control" type="email" readonly="true" value="<?php echo $res['email']; ?>" name="email"/></td>
            </tr>
            <tr>
                <td>Enter Your ID Number </td>
                <td><input class="form-control" type="text" value="<?php echo $res['sid']; ?>" name="sid"/></td>
            </tr>
            <tr>
                <td>Enter Your Gender </td>
                <td><input class="form-control" type="text" value="<?php echo $res['gender']; ?>" name="gender"/></td>
            </tr>
            <tr>
                <td>Enter Your Mobile Number </td>
                <td><input class="form-control" type="text" value="<?php echo $res['mobile']; ?>" name="mobile"/></td>
            </tr>
            
            <tr>
                <td>Select Your Subjects</td>
                <td>
                    <?php 
                    while ($subject = mysqli_fetch_assoc($subjects_result)) {
                        $checked = in_array($subject['sub_id'], $student_subjects) ? 'checked' : '';
                        echo '<input type="checkbox" name="subjects[]" value="' . $subject['sub_id'] . '" ' . $checked . '> ' . $subject['subject'] . '<br>';
                    }
                    ?>
                </td>
            </tr>
            
            <tr>
                <td colspan="2" align="center">
                    <input type="submit" class="btn btn-default" value="Update My Profile" name="update"/>
                    <input type="reset" class="btn btn-default" value="Reset"/>
                </td>
            </tr>
        </table>
    </form>
</div>
