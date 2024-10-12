<?php
session_start();
error_reporting(0);


$staffid = $_SESSION['staffid']; // Retrieve staff ID from session

include('includes/config.php');
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
    <title> SRMS | Manage Exams</title>
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
                                <h2 class="title">Class Students</h2>
                            </div>
                        </div>
                        <!-- /.row -->
                        <div class="row breadcrumb-div">
                            <div class="col-md-6">
                                <ul class="breadcrumb">
                                    <li><a href="dash.php"><i class="fa fa-home"></i> Home</a></li>
                                    <li> Exams</li>
                                    <li class="active">Manage Exams</li>
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
                                            <!-- <div class="panel-title">
                                                <h5>View Students Info</h5>
                                            </div> -->
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
                                            // Step 1: Query the staff_class_subject table for classid and sub_id where staffid = :staffid
                                            $staff_class_subject_query = "SELECT DISTINCT classid, sub_id FROM staff_class_subject WHERE staffid = :staffid";
                                            $stmt = $dbh->prepare($staff_class_subject_query);
                                            $stmt->bindParam(':staffid', $staffid, PDO::PARAM_STR);
                                            $stmt->execute();

                                            // Fetch the results
                                            if ($stmt->rowCount() > 0) {
                                                $class_subject_rows = $stmt->fetchAll(PDO::FETCH_OBJ);

                                                // Initialize a variable to track if any exams are found
                                                $exam_found = false;

                                                // Prepare an array to store all exam results
                                                $all_exam_results = [];

                                                // Iterate over the results (multiple classid and sub_id)
                                                foreach ($class_subject_rows as $row) {
                                                    $classid = $row->classid;
                                                    $sub_id = $row->sub_id;

                                                    // Step 2: Query the exams table for exams matching the classid and sub_id
                                                    $exam_query = "SELECT * FROM exams WHERE classid = :classid AND sub_id = :sub_id";
                                                    $exam_stmt = $dbh->prepare($exam_query);
                                                    $exam_stmt->bindParam(':classid', $classid, PDO::PARAM_STR);
                                                    $exam_stmt->bindParam(':sub_id', $sub_id, PDO::PARAM_INT);
                                                    $exam_stmt->execute();

                                                    // Fetch the exam results
                                                    $exam_results = $exam_stmt->fetchAll(PDO::FETCH_OBJ);

                                                    // If any exams are found, add them to the $all_exam_results array
                                                    if ($exam_stmt->rowCount() > 0) {
                                                        $exam_found = true; // Set this to true if any exams are found
                                                        $all_exam_results = array_merge($all_exam_results, $exam_results);
                                                    }
                                                }

                                                // Step 3: If exams are found, display the table
                                                if ($exam_found) {
                                                    ?>
                                                    <table id="example" class="display table table-striped table-bordered" cellspacing="0" width="100%">
                                                        <thead>
                                                            <tr>
                                                                <th>S/N</th>
                                                                <th>Subject</th>
                                                                <th>No. of Questions</th>
                                                                <th>Class</th>
                                                                <th>Session</th>
                                                                <th>Term</th>
                                                                <th>Date & Time</th>
                                                                <th>Action</th>
                                                            </tr>
                                                        </thead>
                                                        <tbody>
                                                        <?php 
                                                        $cnt = 1;
                                                        foreach ($all_exam_results as $exam) {
                                                            // Step 4: Query subjects table to get the subject name
                                                            $subject_query = "SELECT subject FROM subjects WHERE sub_id = :sub_id";
                                                            $subject_stmt = $dbh->prepare($subject_query);
                                                            $subject_stmt->bindParam(':sub_id', $exam->sub_id, PDO::PARAM_INT);
                                                            $subject_stmt->execute();
                                                            $subject_result = $subject_stmt->fetch(PDO::FETCH_OBJ);
                                                            $subject_name = $subject_result->subject;

                                                            // Step 5: Query terms table to get the term name
                                                            $term_query = "SELECT term FROM terms WHERE termid = :termid";
                                                            $term_stmt = $dbh->prepare($term_query);
                                                            $term_stmt->bindParam(':termid', $exam->termid, PDO::PARAM_INT);
                                                            $term_stmt->execute();
                                                            $term_result = $term_stmt->fetch(PDO::FETCH_OBJ);
                                                            $term_name = $term_result->term;
                                                            ?>
                                                            <tr>
                                                                <td><?php echo htmlentities($cnt); ?></td>
                                                                <td><?php echo htmlentities($subject_name); ?></td>
                                                                <td><?php echo htmlentities($exam->tnoq); ?></td>
                                                                <td><?php echo htmlentities($exam->classid); ?></td>
                                                                <td><?php echo htmlentities($exam->session); ?></td>
                                                                <td><?php echo htmlentities($term_name); ?></td>
                                                                <td><?php echo htmlentities($exam->date); ?></td>
                                                                <td>
                                                                    <!-- Action buttons -->
                                                                    <a href="edit-exam.php?eid=<?php echo htmlentities($exam->eid); ?>" class="btn btn-warning btn-sm">Edit</a>
                                                                    <a href="update.php?q=rmquiz&eid=<?php echo htmlentities($exam->eid); ?>" class="btn btn-danger btn-sm">Delete</a>                                                                
                                                                </td>                                                            
                                                            </tr>
                                                            <?php 
                                                            $cnt++;
                                                        } 
                                                        ?>
                                                        </tbody>
                                                    </table>
                                                    <?php
                                                } else {
                                                    // Step 6: If no exams are found, display the message
                                                    echo "<p>You have no set exams.</p>";
                                                }
                                            } else {
                                                // No classes or subjects found for the staffid
                                                echo "<p>There are no classes or subjects assigned to you.</p>";
                                            }
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
        function confirmDelete(eid) {
            // Display a confirmation dialog
            if (confirm("Are you sure you wish to delete this exam?")) {
                // If the user clicks 'Yes', redirect to the update.php with the eid
                window.location.href = 'delete-exam.php?eid=' + eid;
            } else {
                // If the user clicks 'Cancel', show an alert
                alert("Deletion canceled.");
            }
        }


        function deleteExam() {
            // Create an XMLHttpRequest object
            var xhr = new XMLHttpRequest();

            // Define what happens on successful data submission
            xhr.onload = function() {
                if (xhr.status === 200) {
                    var response = JSON.parse(xhr.responseText);

                    if (response.status === 'success') {
                        // Display success alert
                        alert('Exam deleted successfully!');
                        // Optionally reload the page or remove the deleted row
                        location.reload();
                    } else {
                        // Display error alert
                        alert('Error deleting exam: ' + response.message);
                    }
                } else {
                    // Handle general server errors
                    alert('Error processing request. Please try again.');
                }
            };

            // Define the request parameters
            xhr.open("POST", "delete-exam.php", true);
            xhr.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");

            // Send the request with the eid
            xhr.send("eid=" + eid);
        }
    </script>
</body>
</html>

<?php } ?>
