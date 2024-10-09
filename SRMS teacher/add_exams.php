<!--How far zozo paste the add quiz step1 to your dash.php like replace the one wey dey there b4 and leave the other php code in this php for admin glyphicon-folder-open?
 then run am   include the javascript for the add quiz-->
<!--Add quiz step1-->
<?php
if (@$_GET['q'] == 4 && !(@$_GET['step'])) {
    // Fetch distinct subjects associated with the staffid
    $subject_query = "
    SELECT DISTINCT s.sub_id, s.subject 
    FROM subjects s
    INNER JOIN staff_class_subject scs ON s.sub_id = scs.sub_id
    WHERE scs.staffid = '$staffid'";
    $subject_result = mysqli_query($con, $subject_query) or die(mysqli_error($con));

    // Fetch terms from the terms table
    $terms_query = "SELECT termid, term FROM terms";
    $terms_result = mysqli_query($con, $terms_query) or die(mysqli_error($con));

    echo '
    <div class="row">
    <span class="title1" style="margin-left:40%;font-size:30px;"><b>Enter Exam Details</b></span><br /><br />
    <div class="col-md-3"></div>
    <div class="col-md-6">
        <form class="form-horizontal title1" name="form" action="add_exams.php" method="POST" onsubmit="return formatDateTime()">
            <fieldset>
                <!-- Subject dropdown -->
                <div class="form-group">
                    <label class="col-md-12 control-label" for="subject"></label>
                    <div class="col-md-12">
                        <select id="subject" name="sub_id" class="form-control input-md" required>
                            <option value="" disabled selected>Select Subject</option>';
                            while ($row = mysqli_fetch_assoc($subject_result)) {
                                echo '<option value="'.$row['sub_id'].'">'.$row['subject'].'</option>';
                            }
    echo '              </select>
                    </div>
                </div>

                <!-- Class dropdown -->
                <div class="form-group">
                    <label class="col-md-12 control-label" for="classid"></label>
                    <div class="col-md-12">
                        <select id="classid" name="classid" class="form-control input-md" required>
                            <option value="" disabled selected>Select Class</option>
                        </select>
                    </div>
                </div>

                <!-- Term dropdown -->
                <div class="form-group">
                    <label class="col-md-12 control-label" for="termid"></label>
                    <div class="col-md-12">
                        <select id="termid" name="termid" class="form-control input-md" required>
                            <option value="" disabled selected>Select Term</option>';
                            while ($row = mysqli_fetch_assoc($terms_result)) {
                                echo '<option value="'.$row['termid'].'">'.$row['term'].'</option>';
                            }
                            echo '
                            </select>
                            </div>
                        </div>
                        
                        <!-- Session input -->
                        <div class="form-group">
                            <label class="col-md-12 control-label" for="session"></label>
                            <div class="col-md-12">
                                <input id="session" name="session" placeholder="Enter Exam Session" class="form-control input-md" type="text" required>
                            </div>
                        </div>
                        
                        <!-- Marks per right answer -->
                        <div class="form-group">
                            <label class="col-md-12 control-label" for="sahi"></label>
                            <div class="col-md-12">
                                <input id="sahi" name="sahi" placeholder="Marks per right answer" class="form-control input-md" type="number" step="0.01" required>
                            </div>
                        </div>
                        
                        <!-- Deduct marks on wrong answer (optional) -->
                        <div class="form-group">
                            <label class="col-md-12 control-label" for="waam"></label>
                            <div class="col-md-12">
                                <input id="waam" name="waam" placeholder="Deduct marks on wrong answer (optional)" class="form-control input-md" type="number" step="0.01">
                            </div>
                        </div>
                        
                        <!-- Exam Time Limit -->
                        <div class="form-group">
                            <label class="col-md-12 control-label" for="timelimit"></label>
                            <div class="col-md-12">
                                <input id="timelimit" name="timelimit" placeholder="Exam Time Limit (minutes)" class="form-control input-md" type="number" required>
                            </div>
                        </div>
                        
                        <!-- Total Number of Questions -->
                        <div class="form-group">
                            <label class="col-md-12 control-label" for="tnoq"></label>
                            <div class="col-md-12">
                                <input id="tnoq" name="tnoq" placeholder="Total Number of Questions" class="form-control input-md" type="number" required>
                            </div>
                        </div>
                        
                        <!-- Select Exam Date -->
                        <div class="form-group">
                            <label class="col-md-12 control-label" for="exam_date"></label>
                            <div class="col-md-12">
                                <input id="exam_date" name="exam_date" placeholder="Select Exam Date" class="form-control input-md" type="date" required>
                            </div>
                        </div>
                        
                        <!-- Select Exam Time -->
                        <div class="form-group">
                            <label class="col-md-12 control-label" for="exam_time"></label>
                            <div class="col-md-12">
                                <input id="exam_time" name="exam_time" placeholder="Select Exam Time" class="form-control input-md" type="time" step="1" required>
                            </div>
                        </div>
                        
                        <!-- Exam Instructions (optional) -->
                        <div class="form-group">
                            <label class="col-md-12 control-label" for="instruction"></label>
                            <div class="col-md-12">
                                <input id="instruction" name="instruction" placeholder="Exam Instructions (optional)" class="form-control input-md" type="text">
                            </div>
                        </div>
                        
                        <div class="form-group">
                            <label class="col-md-12 control-label" for=""></label>
                            <div class="col-md-12">
                                <input type="submit" style="margin-left:45%" class="btn btn-primary" value="Submit" onclick="return formatDateTime();" class="btn btn-primary"/>
                            </div>
                        </div>
                        </fieldset>
                        </form>
                        </div>
                        </div>';
}
?>

<script>
document.getElementById('exam_date').addEventListener('change', function() {
    const date = new Date(this.value);
    const formattedDate = date.toISOString().slice(0, 10);
    this.value = formattedDate;
});

document.getElementById('exam_time').addEventListener('change', function() {
    const time = this.value;
    if (time.length === 5) {
        this.value = time + ':00';
    }
});

function formatDateTime() {
    const dateInput = document.getElementById('exam_date');
    const timeInput = document.getElementById('exam_time');

    const date = new Date(dateInput.value);
    const formattedDate = date.toISOString().slice(0, 10);
    dateInput.value = formattedDate;

    let time = timeInput.value;
    if (time.length === 5) {
        time += ':00';
    }
    timeInput.value = time;

    return true;
}

document.getElementById('subject').addEventListener('change', function() {
    var sub_id = this.value;
    console.log("Selected subject id: " + sub_id);

    var xhr = new XMLHttpRequest();
    xhr.open("POST", "get_classes.php", true);
    xhr.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");

    xhr.onreadystatechange = function() {
        if (this.readyState === XMLHttpRequest.DONE && this.status === 200) {
            console.log("Received data: " + this.responseText);
            document.getElementById('classid').innerHTML = this.responseText;
        }
    };

    xhr.send("sub_id=" + encodeURIComponent(sub_id));
});
</script>




<?php

require_once 'dbConnection.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Capture form inputs
    $sub_id = $_POST['sub_id'];
    $classid = $_POST['classid'];
    $termid = $_POST['termid'];
    $session = $_POST['session'];
    $sahi = $_POST['sahi'];
    $waam = $_POST['waam'];
    $timelimit = $_POST['timelimit'];
    $tnoq = $_POST['tnoq'];
    $exam_date = $_POST['exam_date'];
    $exam_time = $_POST['exam_time'];
    $instruction = $_POST['instruction'];
    $id = uniqid(); // Generate unique examid

    // Combine exam date and time
    $exam_datetime = $exam_date . ' ' . $exam_time;

    // Debugging output to check the captured values
    //echo "sub_id: $sub_id, classid: $classid, termid: $termid, session: $session, sahi: $sahi, waam: $waam, timelimit: $timelimit, tnoq: $tnoq, exam_datetime: $exam_datetime, instruction: $instruction<br>";

    // Prepared statement to insert data into exams table
    $stmt = $con->prepare("INSERT INTO exams (eid, sub_id, classid, termid, session, sahi, waam, timelimit, tnoq, date, instruction) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)");
    $stmt->bind_param("sssssdddiss", $id, $sub_id, $classid, $termid, $session, $sahi, $waam, $timelimit, $tnoq, $exam_datetime, $instruction);

    // Execute the query
    if ($stmt->execute()) {
        echo "Exam details entered successfully.";
        // Redirect to dashboard with additional parameters
        header("location:add-examquestions.php?eid=$id&n=$tnoq");
    } else {
        echo "Error: " . $stmt->error;
    }

    // Close statement and connection
    $stmt->close();    
}
?>