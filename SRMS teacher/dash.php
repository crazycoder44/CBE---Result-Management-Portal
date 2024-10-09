<?php
session_start();
error_reporting(0);

$staffid = $_SESSION['staffid'];
$email = $_SESSION['email'];
$fname = $_SESSION['fname'];

include('includes/config.php');
include('includes/dbConnection.php');
if(strlen($_SESSION['alogin']) == "")
{   
    header("Location: index.php"); 
} else {
    // Your code here to handle the logged-in user
    //echo "Welcome, " . $fname . "!";
}
?>


<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="utf-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
    	<meta name="viewport" content="width=device-width, initial-scale=1">
        <title> SRMS | Staff Dashboard</title>
        <link rel="stylesheet" href="css/bootstrap.min.css" media="screen" >
        <link rel="stylesheet" href="css/font-awesome.min.css" media="screen" >
        <link rel="stylesheet" href="css/animate-css/animate.min.css" media="screen" >
        <link rel="stylesheet" href="css/lobipanel/lobipanel.min.css" media="screen" >
        <link rel="stylesheet" href="css/toastr/toastr.min.css" media="screen" >
        <link rel="stylesheet" href="css/icheck/skins/line/blue.css" >
        <link rel="stylesheet" href="css/icheck/skins/line/red.css" >
        <link rel="stylesheet" href="css/icheck/skins/line/green.css" >
        <link rel="stylesheet" href="css/main.css" media="screen" >
        <script src="js/modernizr/modernizr.min.js"></script>
    </head>
    <body class="top-navbar-fixed">
        <div class="main-wrapper">
              <?php include('includes/topbar.php');?>
            <div class="content-wrapper">
                <div class="content-container">

                    <?php include('includes/leftbar.php');?>  

                    <div class="main-page">
                        <div class="container-fluid">
                            <div class="row page-title-div">
                                <div class="col-sm-6">
                                    <h2 class="title">Dashboard</h2>
                                  
                                </div>
                                <!-- /.col-sm-6 -->
                            </div>
                            <!-- /.row -->
                      
                        </div>
                        <!-- /.container-fluid -->

                        <section class="section">
                            <div class="container-fluid">
                                <div class="row">
                                    <div class="col-lg-6 col-md-6 col-sm-6 col-xs-12">
                                        <a class="dashboard-stat bg-primary" href="subject-students.php">
<?php 

$query = " SELECT DISTINCT s.sid, s.fname, s.lname, s.gender, s.classid, s.email, s.mobile
            FROM students s
            WHERE s.classid IN (
                SELECT DISTINCT stcs.classid
                FROM staff_class_subject stcs
                WHERE stcs.staffid = '$staffid'
            )
            AND (
                EXISTS (
                    SELECT 1
                    FROM student_class_subject scs
                    JOIN staff_class_subject stcs ON scs.sub_id = stcs.sub_id
                    WHERE scs.sid = s.sid AND stcs.staffid = '$staffid'
                )
                OR NOT EXISTS (
                    SELECT 1
                    FROM student_class_subject scs
                    JOIN staff_class_subject stcs ON scs.sub_id = stcs.sub_id
                    WHERE stcs.staffid = '$staffid'
                )
            )
            ";
$query1 = $dbh -> prepare($query);
$query1->execute();
$results1=$query1->fetchAll(PDO::FETCH_OBJ);
$totalstudents=$query1->rowCount();
?>

                                            <span class="number counter"><?php echo htmlentities($totalstudents);?></span>
                                            <span class="name">My Students</span>
                                            <span class="bg-icon"><i class="fa fa-users"></i></span>
                                        </a>
                                        <!-- /.dashboard-stat -->
                                    </div>
                                    <!-- /.col-lg-3 col-md-3 col-sm-6 col-xs-12 -->

                                    <div class="col-lg-6 col-md-6 col-sm-6 col-xs-12" >
                                        <a class="dashboard-stat bg-danger" href="guardian-class.php">

<?php 
$class_query = "SELECT classid FROM class WHERE staffid = '$staffid'";

// Execute the class query
$class_result = mysqli_query($con, $class_query) or die('Error: ' . mysqli_error($con));

// Check if the staff is associated with any class
if (mysqli_num_rows($class_result) > 0) {
// Fetch the classid associated with the staffid
$class_row = mysqli_fetch_assoc($class_result);
$classid = $class_row['classid'];

// Define the query to select student information based on the given conditions
$query1 = " SELECT DISTINCT s.sid, s.fname, s.lname, s.gender, s.classid, s.email, s.mobile
            FROM students s
            WHERE s.classid = '$classid'
        ";

$query = $dbh -> prepare($query1);
$query->execute();
$results=$query->fetchAll(PDO::FETCH_OBJ);
$classstudents=$query->rowCount();
?>
                                            <span class="number counter"><?php echo htmlentities($classstudents);?></span>
                                            <span class="name">My Class Students</span>
                                            <span class="bg-icon"><i class="fa fa-ticket"></i></span>
                                        </a>
                                        <!-- /.dashboard-stat -->
                                    </div>
                                    <!-- /.col-lg-3 col-md-3 col-sm-6 col-xs-12 -->

                                    <div class="col-lg-6 col-md-6 col-sm-6 col-xs-12" style="margin-top:1%;">
                                        <a class="dashboard-stat bg-warning" href="manage-classes.php">
                                        <?php 
$exam_query = "SELECT e.eid, s.subject AS subject, c.class AS class, e.tnoq AS total_questions, e.sahi AS mark, 
                e.timelimit AS time_limit, t.term AS term, e.session, e.date
                FROM exams e 
                INNER JOIN staff_class_subject scs ON e.sub_id = scs.sub_id AND e.classid = scs.classid 
                INNER JOIN subjects s ON e.sub_id = s.sub_id
                INNER JOIN class c ON e.classid = c.classid
                INNER JOIN terms t ON e.termid = t.termid
                WHERE scs.staffid = '$staffid' 
                ORDER BY e.date DESC";

$query2 = $dbh -> prepare($exam_query);
$query2->execute();
$results2=$query2->fetchAll(PDO::FETCH_OBJ);
$totalexams=$query2->rowCount();
?>
                                            <span class="number counter"><?php echo htmlentities($totalexams);?></span>
                                            <span class="name">Exams</span>
                                            <span class="bg-icon"><i class="fa fa-bank"></i></span>
                                        </a>
                                        <!-- /.dashboard-stat -->
                                    </div>
                                    <!-- /.col-lg-3 col-md-3 col-sm-6 col-xs-12 -->

                                    <div class="col-lg-6 col-md-6 col-sm-6 col-xs-12"  style="margin-top:1%">
                                        <a class="dashboard-stat bg-success" href="manage-results.php">
                                        <?php 
$sql3="SELECT  distinct email from  results ";
$query3 = $dbh -> prepare($sql3);
$query3->execute();
$results3=$query3->fetchAll(PDO::FETCH_OBJ);
$totalresults=$query3->rowCount();
?>

                                            <span class="number counter"><?php echo htmlentities($totalresults);?></span>
                                            <span class="name">Results Declared</span>
                                            <span class="bg-icon"><i class="fa fa-file-text"></i></span>
                                        </a>
                                        <!-- /.dashboard-stat -->
                                    </div>
                                    <!-- /.col-lg-3 col-md-3 col-sm-6 col-xs-12 -->

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
        <script src="js/jquery-ui/jquery-ui.min.js"></script>
        <script src="js/bootstrap/bootstrap.min.js"></script>
        <script src="js/pace/pace.min.js"></script>
        <script src="js/lobipanel/lobipanel.min.js"></script>
        <script src="js/iscroll/iscroll.js"></script>

        <!-- ========== PAGE JS FILES ========== -->
        <script src="js/prism/prism.js"></script>
        <script src="js/waypoint/waypoints.min.js"></script>
        <script src="js/counterUp/jquery.counterup.min.js"></script>
        <script src="js/amcharts/amcharts.js"></script>
        <script src="js/amcharts/serial.js"></script>
        <script src="js/amcharts/plugins/export/export.min.js"></script>
        <link rel="stylesheet" href="js/amcharts/plugins/export/export.css" type="text/css" media="all" />
        <script src="js/amcharts/themes/light.js"></script>
        <script src="js/toastr/toastr.min.js"></script>
        <script src="js/icheck/icheck.min.js"></script>

        <!-- ========== THEME JS ========== -->
        <script src="js/main.js"></script>
        <script src="js/production-chart.js"></script>
        <script src="js/traffic-chart.js"></script>
        <script src="js/task-list.js"></script>
        <script>
            $(function(){

                // Counter for dashboard stats
                $('.counter').counterUp({
                    delay: 10,
                    time: 1000
                });

                // Welcome notification
                toastr.options = {
                  "closeButton": true,
                  "debug": false,
                  "newestOnTop": false,
                  "progressBar": false,
                  "positionClass": "toast-top-right",
                  "preventDuplicates": false,
                  "onclick": null,
                  "showDuration": "300",
                  "hideDuration": "1000",
                  "timeOut": "5000",
                  "extendedTimeOut": "1000",
                  "showEasing": "swing",
                  "hideEasing": "linear",
                  "showMethod": "fadeIn",
                  "hideMethod": "fadeOut"
                }
                toastr["success"]( "Welcome to student Result Management System!");

            });
        </script>
    </body>
</html>
<?php } ?>
