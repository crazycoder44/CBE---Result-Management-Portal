<?php
session_start();
error_reporting(0);
include('includes/config.php');
if(strlen($_SESSION['alogin'])=="")
    {   
    header("Location: index.php"); 
    }
    else{

$sid=$_GET['stid'];
$email=$_GET['email'];
?>
<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="utf-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
    	<meta name="viewport" content="width=device-width, initial-scale=1">
        <title> SRMS | Student result </title>
        <link rel="stylesheet" href="css/bootstrap.min.css" media="screen" >
        <link rel="stylesheet" href="css/font-awesome.min.css" media="screen" >
        <link rel="stylesheet" href="css/animate-css/animate.min.css" media="screen" >
        <link rel="stylesheet" href="css/lobipanel/lobipanel.min.css" media="screen" >
        <link rel="stylesheet" href="css/prism/prism.css" media="screen" >
        <link rel="stylesheet" href="css/select2/select2.min.css" >
        <link rel="stylesheet" href="css/main.css" media="screen" >
        <script src="js/modernizr/modernizr.min.js"></script>
    </head>
    <body class="top-navbar-fixed">
        <div class="main-wrapper">

            <!-- ========== TOP NAVBAR ========== -->
  <?php include('includes/topbar.php');?> 
            <!-- ========== WRAPPER FOR BOTH SIDEBARS & MAIN CONTENT ========== -->
            <div class="content-wrapper">
                <div class="content-container">

                    <!-- ========== LEFT SIDEBAR ========== -->
                   <?php include('includes/leftbar.php');?>  
                    <!-- /.left-sidebar -->

                    <div class="main-page">

                     <div class="container-fluid">
                            <div class="row page-title-div">
                                <div class="col-md-6">
                                    <h2 class="title">Student Result Form</h2>
                                
                                </div>
                                
                                <!-- /.col-md-6 text-right -->
                            </div>
                            <!-- /.row -->
                            <div class="row breadcrumb-div">
                                <div class="col-md-6">
                                    <ul class="breadcrumb">
                                        <li><a href="dashboard.php"><i class="fa fa-home"></i> Home</a></li>
                                
                                        <li class="active">Result Info</li>
                                    </ul>
                                </div>
                             
                            </div>
                            <!-- /.row -->
                        </div>
                        <div class="container-fluid">
                           
                        <div class="row">
                                    <div class="col-md-12">
                                        <div class="panel">
                                            <div class="panel-heading">
                                                <div class="panel-title">
                                                    <h5>Fill the form below to view the student result</h5>
                                                </div>
                                            </div>
                                            <div class="panel-body">
                                                <?php if($msg){ ?>
                                                <div class="alert alert-success left-icon-alert" role="alert">
                                                    <strong>Well done!</strong><?php echo htmlentities($msg); ?>
                                                </div>
                                                <?php } else if($error){ ?>
                                                <div class="alert alert-danger left-icon-alert" role="alert">
                                                    <strong>Oh snap!</strong> <?php echo htmlentities($error); ?>
                                                </div>
                                                <?php } ?>
                                                
                                                <form class="form-horizontal" method="post" action="prep_arm.php">
                                                    <h4>Student Result Form</h4>

                                                    <div class="form-group">
                                                        <label for="session" class="col-sm-2 control-label">Select Session</label>
                                                        <div class="col-sm-10">
                                                            <select name="session" class="form-control" id="session" required>
                                                                <option value="">Select Session</option>
                                                                <?php
                                                                $sqlSession = "SELECT DISTINCT session FROM results";
                                                                $querySession = $dbh->prepare($sqlSession);
                                                                $querySession->execute();
                                                                $sessions = $querySession->fetchAll(PDO::FETCH_OBJ);
                                                                if ($querySession->rowCount() > 0) {
                                                                    foreach ($sessions as $session) {
                                                                        echo '<option value="' . htmlentities($session->session) . '">' . htmlentities($session->session) . '</option>';
                                                                    }
                                                                }
                                                                ?>
                                                            </select>
                                                        </div>
                                                    </div>

                                                    <div class="form-group">
                                                        <label for="term" class="col-sm-2 control-label">Select Term</label>
                                                        <div class="col-sm-10">
                                                            <select name="term" class="form-control" id="term" required>
                                                                <option value="">Select Term</option>
                                                                <?php
                                                                $sqlTerm = "SELECT * FROM terms";
                                                                $queryTerm = $dbh->prepare($sqlTerm);
                                                                $queryTerm->execute();
                                                                $terms = $queryTerm->fetchAll(PDO::FETCH_OBJ);
                                                                if ($queryTerm->rowCount() > 0) {
                                                                    foreach ($terms as $term) {
                                                                        echo '<option value="' . htmlentities($term->termid) . '">' . htmlentities($term->term) . '</option>';
                                                                    }
                                                                }
                                                                ?>

                                                            </select>
                                                        </div>
                                                    </div>

                                                    <div class="form-group">
                                                        <div class="col-sm-offset-2 col-sm-10">
                                                            <input type="hidden" name="sid" value="<?= htmlentities($sid); ?>">
                                                            <input type="hidden" name="email" value="<?= htmlentities($email); ?>">
                                                            <button type="submit" name="submit" class="btn btn-primary">View Result</button>
                                                        </div>
                                                    </div>
                                                </form>
                                            </div>
                                        </div>
                                    </div>
                                    <!-- /.col-md-12 -->
                                </div>
                    </div>
                </div>
                <!-- /.content-container -->
            </div>
            <!-- /.content-wrapper -->
        </div>
        <!-- /.main-wrapper -->
        <script src="js/jquery/jquery-2.2.4.min.js"></script>
        <script src="js/bootstrap/bootstrap.min.js"></script>
        <script src="js/pace/pace.min.js"></script>
        <script src="js/lobipanel/lobipanel.min.js"></script>
        <script src="js/iscroll/iscroll.js"></script>
        <script src="js/prism/prism.js"></script>
        <script src="js/select2/select2.min.js"></script>
        <script src="js/main.js"></script>
        <script>
            $(function($) {
                $(".js-states").select2();
                $(".js-states-limit").select2({
                    maximumSelectionLength: 2
                });
                $(".js-states-hide").select2({
                    minimumResultsForSearch: Infinity
                });
            });
        </script>
    </body>
</html>
<?PHP } ?>
