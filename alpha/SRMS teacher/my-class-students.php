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
    <title> SRMS | My Class Students</title>
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
                                    <li> Students</li>
                                    <li class="active">My Class Students</li>
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
                                                <h5>View Students Info</h5>
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
                                            // Define the query to select the classid based on staffid
                                            $class_query = "SELECT classid FROM class WHERE staffid = :staffid";

                                            // Prepare and execute the class query using PDO
                                            $class_stmt = $dbh->prepare($class_query);
                                            $class_stmt->bindParam(':staffid', $staffid, PDO::PARAM_STR);
                                            $class_stmt->execute();

                                            // Check if the staff is associated with any class
                                            if ($class_stmt->rowCount() > 0) {
                                                // Fetch the classid associated with the staffid
                                                $class_row = $class_stmt->fetch(PDO::FETCH_ASSOC);
                                                $classid = $class_row['classid'];

                                                // Define the query to select student information based on the classid
                                                $sql = "SELECT DISTINCT s.sid, s.fname, s.lname, s.gender, s.classid, s.mobile
                                                        FROM students s
                                                        WHERE s.classid = :classid";

                                                // Prepare and execute the student query using PDO
                                                $student_stmt = $dbh->prepare($sql);
                                                $student_stmt->bindParam(':classid', $classid, PDO::PARAM_STR); // Use PDO::PARAM_STR for classid
                                                $student_stmt->execute();

                                                // Fetch the results
                                                $results = $student_stmt->fetchAll(PDO::FETCH_OBJ);
                                                $cnt = 1;

                                                // Check if any students were found
                                                if ($student_stmt->rowCount() > 0) {
                                                    // Display the table if students are found
                                                    ?>
                                                    <table id="example" class="display table table-striped table-bordered" cellspacing="0" width="100%">
                                                        <thead>
                                                            <tr>
                                                                <th>#</th>
                                                                <th>Student Id</th>
                                                                <th>Student Name</th>
                                                                <th>Gender</th>
                                                                <th>Class</th>
                                                                <th>Mobile</th>
                                                            </tr>
                                                        </thead>
                                                        <tbody>
                                                        <?php 
                                                        foreach ($results as $result) {
                                                        ?>
                                                            <tr>
                                                                <td><?php echo htmlentities($cnt); ?></td>
                                                                <td><?php echo htmlentities($result->sid); ?></td>
                                                                <td><?php echo htmlentities($result->fname . " " . $result->lname); ?></td>
                                                                <td><?php echo htmlentities($result->gender); ?></td>
                                                                <td><?php echo htmlentities($result->classid); ?></td>
                                                                <td><?php echo htmlentities($result->mobile); ?></td>
                                                            </tr>
                                                        <?php 
                                                            $cnt++;
                                                        } 
                                                        ?>
                                                        </tbody>
                                                    </table>
                                                    <?php
                                                } else {
                                                    // If no students are found for the class
                                                    echo "<p>No students found for this class.</p>";
                                                }
                                            } else {
                                                // If no class is associated with the staffid
                                                echo "<p>Only Class Guardians can view Class Students.</p>";
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
</body>
</html>

<?php } ?>
