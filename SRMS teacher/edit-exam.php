<?php
session_start();
error_reporting(0);


$staffid = $_SESSION['staffid']; // Retrieve staff ID from session

include('includes/config.php');
include('includes/dbConnection.php');
if(strlen($_SESSION['alogin']) == "") {   
    header("Location: index.php"); 
} else {
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Staff Portal | Create Exam</title>
    <link rel="stylesheet" href="css/bootstrap.min.css" media="screen">
    <link rel="stylesheet" href="css/font-awesome.min.css" media="screen">
    <link rel="stylesheet" href="css/animate-css/animate.min.css" media="screen">
    <link rel="stylesheet" href="css/lobipanel/lobipanel.min.css" media="screen">
    <link rel="stylesheet" href="css/prism/prism.css" media="screen">
    <link rel="stylesheet" type="text/css" href="js/DataTables/datatables.min.css"/>
    <link rel="stylesheet" href="css/main.css" media="screen">
    <script src="js/modernizr/modernizr.min.js"></script>
    <style>
        .errorWrap {
            padding: 10px;
            margin: 0 0 20px 0;
            background: #fff;
            border-left: 4px solid #dd3d36;
            -webkit-box-shadow: 0 1px 1px 0 rgba(0,0,0,.1);
            box-shadow: 0 1px 1px 0 rgba(0,0,0,.1);
        }
        .succWrap {
            padding: 10px;
            margin: 0 0 20px 0;
            background: #fff;
            border-left: 4px solid #5cb85c;
            -webkit-box-shadow: 0 1px 1px 0 rgba(0,0,0,.1);
            box-shadow: 0 1px 1px 0 rgba(0,0,0,.1);
        }
    </style>
</head>
<body class="top-navbar-fixed">
    <div class="main-wrapper">
        <!-- ========== TOP NAVBAR ========== -->
        <?php include('includes/topbar.php');?> 
        <!-- ========== WRAPPER FOR BOTH SIDEBARS & MAIN CONTENT ========== -->
        <div class="content-wrapper">
            <div class="content-container">
                <?php include('includes/leftbar.php');?>  
                <div class="main-page">
                    <div class="container-fluid">
                        <div class="row page-title-div">
                            <div class="col-md-6">
                                <h2 class="title">Create Exam</h2>
                            </div>
                        </div>
                        <!-- /.row -->
                        <div class="row breadcrumb-div">
                            <div class="col-md-6">
                                <ul class="breadcrumb">
                                    <li><a href="dashboard.php"><i class="fa fa-home"></i> Home</a></li>
                                    <li> Exams</li>
                                    <li class="active">Create Exam</li>
                                </ul>
                            </div>
                        </div>
                        <!-- /.row -->
                    </div>
                    <!-- /.container-fluid -->
                    <section class="section">
                        <div class="container-fluid">
                            <div class="row">
                                <div class="col-md-12">
                                    <div class="panel">
                                        <div class="panel-heading">
                                            <div class="panel-title">
                                                <h5>Create Exam Form</h5>
                                            </div>
                                        </div>
                                        <?php if($msg){?>
                                            <div class="alert alert-success left-icon-alert" role="alert">
                                                <strong>Well done!</strong> <?php echo htmlentities($msg); ?>
                                            </div>
                                        <?php } else if($error){?>
                                            <div class="alert alert-danger left-icon-alert" role="alert">
                                                <strong>Oh snap!</strong> <?php echo htmlentities($error); ?>
                                            </div>
                                        <?php } ?>
                                        <div class="panel-body p-20">
                                            <?php
                                            // Get the current staffid from the session and the eid from the URL
                                            $staffid = $_SESSION['staffid'];
                                            $eid = htmlspecialchars(trim($_GET['eid']));

                                            // Fetch distinct subjects associated with the staffid using PDO
                                            $subject_query = "
                                            SELECT DISTINCT s.sub_id, s.subject 
                                            FROM subjects s
                                            INNER JOIN staff_class_subject scs ON s.sub_id = scs.sub_id
                                            WHERE scs.staffid = :staffid";
                                            
                                            $stmt_subject = $dbh->prepare($subject_query);
                                            $stmt_subject->bindParam(':staffid', $staffid, PDO::PARAM_STR);
                                            $stmt_subject->execute();
                                            $subjects = $stmt_subject->fetchAll(PDO::FETCH_ASSOC);

                                            // Fetch distinct classes associated with the staffid using PDO
                                            $class_query = "
                                            SELECT DISTINCT classid 
                                            FROM staff_class_subject
                                            WHERE staffid = :staffid";
                                            
                                            $stmt_class = $dbh->prepare($class_query);
                                            $stmt_class->bindParam(':staffid', $staffid, PDO::PARAM_STR);
                                            $stmt_class->execute();
                                            $classes = $stmt_class->fetchAll(PDO::FETCH_ASSOC);

                                            // Fetch terms from the terms table using PDO
                                            $terms_query = "SELECT termid, term FROM terms";
                                            $stmt_terms = $dbh->prepare($terms_query);
                                            $stmt_terms->execute();
                                            $terms = $stmt_terms->fetchAll(PDO::FETCH_ASSOC);

                                            // Fetch exam details based on the provided eid
                                            $exam_query = "SELECT * FROM exams WHERE eid = :eid";
                                            $stmt_exam = $dbh->prepare($exam_query);
                                            $stmt_exam->bindParam(':eid', $eid, PDO::PARAM_STR);
                                            $stmt_exam->execute();
                                            $exam = $stmt_exam->fetch(PDO::FETCH_ASSOC);

                                            // Split the datetime into date and time components
                                            $exam_date = date('Y-m-d', strtotime($exam['date'])); // Extract the date
                                            $exam_time = date('H:i:s', strtotime($exam['date'])); // Extract the time

                                            echo '
                                                <form class="form-horizontal no-border" name="form" action="update-exam.php" method="POST" onsubmit="return formatDateTime()">

                                                    <!-- Hidden input for eid -->
                                                    <input type="hidden" name="eid" value="' . htmlentities($eid) . '">

                                                    <!-- Subject dropdown -->
                                                    <div class="form-group">
                                                        <label class="col-md-12 control-label" for="subject"></label>
                                                        <div class="col-md-12">
                                                            <select id="subject" name="sub_id" class="form-control input-md" required>
                                                                <option value="" disabled>Select Subject</option>';
                                                                foreach ($subjects as $row) {
                                                                    $selected = ($row['sub_id'] == $exam['sub_id']) ? 'selected' : '';  // Set selected value
                                                                    echo '<option value="' . htmlentities($row['sub_id']) . '" ' . $selected . '>' . htmlentities($row['subject']) . '</option>';
                                                                }
                                            echo '          </select>
                                                        </div>
                                                    </div>

                                                    <!-- Class dropdown -->
                                                    <div class="form-group">
                                                        <label class="col-md-12 control-label" for="classid"></label>
                                                        <div class="col-md-12">
                                                            <select id="classid" name="classid" class="form-control input-md" required>
                                                                <option value="" disabled>Select Class</option>';
                                                                // Fetch class options dynamically based on the class associated with the exam
                                                                foreach ($classes as $row) {
                                                                    $selected = ($row['classid'] == $exam['classid']) ? 'selected' : '';  // Set selected value
                                                                    echo '<option value="' . htmlentities($row['classid']) . '" ' . $selected . '>' . htmlentities($row['classid']) . '</option>';
                                                                }
                                            echo '          </select>
                                                        </div>
                                                    </div>

                                                    <!-- Term dropdown -->
                                                    <div class="form-group">
                                                        <label class="col-md-12 control-label" for="termid"></label>
                                                        <div class="col-md-12">
                                                            <select id="termid" name="termid" class="form-control input-md" required>
                                                                <option value="" disabled>Select Term</option>';
                                                                foreach ($terms as $row) {
                                                                    $selected = ($row['termid'] == $exam['termid']) ? 'selected' : '';  // Set selected value
                                                                    echo '<option value="' . htmlentities($row['termid']) . '" ' . $selected . '>' . htmlentities($row['term']) . '</option>';
                                                                }
                                            echo '          </select>
                                                        </div>
                                                    </div>

                                                    <!-- Session input -->
                                                    <div class="form-group">
                                                        <label class="col-md-12 control-label" for="session"></label>
                                                        <div class="col-md-12">
                                                            <input id="session" name="session" placeholder="Enter Exam Session" class="form-control input-md" type="text" value="' . htmlentities($exam['session']) . '" required>
                                                        </div>
                                                    </div>

                                                    <!-- Marks per right answer -->
                                                    <div class="form-group">
                                                        <label class="col-md-12 control-label" for="sahi"></label>
                                                        <div class="col-md-12">
                                                            <input id="sahi" name="sahi" placeholder="Marks per right answer" class="form-control input-md" type="number" step="0.01" value="' . htmlentities($exam['sahi']) . '" required>
                                                        </div>
                                                    </div>

                                                    <!-- Deduct marks on wrong answer (optional) -->
                                                    <div class="form-group">
                                                        <label class="col-md-12 control-label" for="waam"></label>
                                                        <div class="col-md-12">
                                                            <input id="waam" name="waam" placeholder="Deduct marks on wrong answer (optional)" class="form-control input-md" type="number" step="0.01" value="' . htmlentities($exam['waam']) . '">
                                                        </div>
                                                    </div>

                                                    <!-- Exam Time Limit -->
                                                    <div class="form-group">
                                                        <label class="col-md-12 control-label" for="timelimit"></label>
                                                        <div class="col-md-12">
                                                            <input id="timelimit" name="timelimit" placeholder="Exam Time Limit (minutes)" class="form-control input-md" type="number" value="' . htmlentities($exam['timelimit']) . '" required>
                                                        </div>
                                                    </div>

                                                    <!-- Total Number of Questions -->
                                                    <div class="form-group">
                                                        <label class="col-md-12 control-label" for="tnoq"></label>
                                                        <div class="col-md-12">
                                                            <input id="tnoq" name="tnoq" placeholder="Total Number of Questions" class="form-control input-md" type="number" value="' . htmlentities($exam['tnoq']) . '" required>
                                                        </div>
                                                    </div>

                                                    <!-- Select Exam Date -->
                                                    <div class="form-group">
                                                        <label class="col-md-12 control-label" for="exam_date"></label>
                                                        <div class="col-md-12">
                                                            <input id="exam_date" name="exam_date" placeholder="Select Exam Date" class="form-control input-md" type="date" value="' . $exam_date . '" required>
                                                        </div>
                                                    </div>

                                                    <!-- Select Exam Time -->
                                                    <div class="form-group">
                                                        <label class="col-md-12 control-label" for="exam_time"></label>
                                                        <div class="col-md-12">
                                                            <input id="exam_time" name="exam_time" placeholder="Select Exam Time" class="form-control input-md" type="time" step="1" value="' . $exam_time . '" required>
                                                        </div>
                                                    </div>

                                                    <!-- Exam Instructions (optional) -->
                                                    <div class="form-group">
                                                        <label class="col-md-12 control-label" for="instruction"></label>
                                                        <div class="col-md-12">
                                                            <input id="instruction" name="instruction" placeholder="Exam Instructions (optional)" class="form-control input-md" type="text" value="' . htmlentities($exam['instruction']) . '">
                                                        </div>
                                                    </div>

                                                    <div class="form-group">
                                                        <label class="col-md-12 control-label" for=""></label>
                                                        <div class="col-md-12">
                                                            <input type="submit" style="margin-left:45%" class="btn btn-primary" value="Submit" onclick="return formatDateTime();" class="btn btn-primary"/>
                                                        </div>
                                                    </div>
                                                </form>';
                                            ?>
                                        </div>
                                        <!-- /.col-md-12 -->
                                    </div>
                                </div>
                                <!-- /.col-md-6 -->
                            </div>
                            <!-- /.row -->
                        </div>
                        <!-- /.container-fluid -->
                    </section>
                    <!-- /.section -->
                </div>
                <!-- /.main-page -->
            </div>
            <!-- /.content-container -->
        </div>
        <!-- /.content-wrapper -->
    </div>
    <!-- /.main-wrapper -->

    <!-- ========== COMMON JS FILES ========== -->
    <script src="js/jquery/jquery-2.2.4.min.js"></script>
    <script src="js/bootstrap/bootstrap.min.js"></script>
    <script src="js/pace/pace.min.js"></script>
    <script src="js/lobipanel/lobipanel.min.js"></script>
    <script src="js/iscroll/iscroll.js"></script>

    <!-- ========== PAGE JS FILES ========== -->
    <script src="js/prism/prism.js"></script>
    <script src="js/DataTables/datatables.min.js"></script>
    <script src="js/action/view-class-action.js"></script>

    <!-- ========== THEME JS ========== -->
    <script src="js/main.js"></script>
    <script>
        $(function($) {
            $('#example').DataTable();
        });
    </script>
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
</body>
</html>

<?php }?>
