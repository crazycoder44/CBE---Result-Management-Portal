<?php
session_start();
error_reporting(0);
include('includes/config.php');


if(strlen($_SESSION['alogin']) == 0) {   
    header("Location: index.php"); 
} else {
    if (isset($_POST['submit'])) {
        $classid = $_POST['classid'];
        $sub_id = $_POST['sub_id'];
        $staffid = $_POST['staffid'];

        // Check if the combination already exists in staff_class_subject
        $sqlCheck = "SELECT * FROM staff_class_subject WHERE classid = :classid AND sub_id = :sub_id";
        $queryCheck = $dbh->prepare($sqlCheck);
        $queryCheck->bindParam(':classid', $classid, PDO::PARAM_STR);
        $queryCheck->bindParam(':sub_id', $sub_id, PDO::PARAM_INT);
        $queryCheck->execute();
    
        if ($queryCheck->rowCount() > 0) {
            // Row exists, show message
            $error = "Subject combination already exists. Please visit the Manage Subjects page to update the staff teaching this subject for this class.";
        } else {
            // No existing row, insert into class_subject table
            $sqlInsertClassSubject = "INSERT INTO class_subject(classid, sub_id) VALUES(:classid, :sub_id)";
            $queryInsertClassSubject = $dbh->prepare($sqlInsertClassSubject);
            $queryInsertClassSubject->bindParam(':classid', $classid, PDO::PARAM_STR);
            $queryInsertClassSubject->bindParam(':sub_id', $sub_id, PDO::PARAM_INT);
            $queryInsertClassSubject->execute();
    
            // Insert into staff_class_subject table
            $sqlInsertStaffClassSubject = "INSERT INTO staff_class_subject(staffid, classid, sub_id) VALUES(:staffid, :classid, :sub_id)";
            $queryInsertStaffClassSubject = $dbh->prepare($sqlInsertStaffClassSubject);
            $queryInsertStaffClassSubject->bindParam(':staffid', $staffid, PDO::PARAM_STR);
            $queryInsertStaffClassSubject->bindParam(':classid', $classid, PDO::PARAM_STR);
            $queryInsertStaffClassSubject->bindParam(':sub_id', $sub_id, PDO::PARAM_INT);
            $queryInsertStaffClassSubject->execute();
    
            // Check if the insertion was successful by checking the number of affected rows
            if ($queryInsertClassSubject->rowCount() > 0) {
                $msg = "Combination added successfully";
            } else {
                $error = "Something went wrong. Please try again";
            }
        }
    }    
}
?>


<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title> SRMS | Subject Combination</title>
    <link rel="stylesheet" href="css/bootstrap.min.css" media="screen">
    <link rel="stylesheet" href="css/font-awesome.min.css" media="screen">
    <link rel="stylesheet" href="css/animate-css/animate.min.css" media="screen">
    <link rel="stylesheet" href="css/lobipanel/lobipanel.min.css" media="screen">
    <link rel="stylesheet" href="css/prism/prism.css" media="screen">
    <link rel="stylesheet" href="css/select2/select2.min.css">
    <link rel="stylesheet" href="css/main.css" media="screen">
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
                                <h2 class="title">Add Subject Combination</h2>
                            </div>
                        </div>
                        <!-- /.row -->
                        <div class="row breadcrumb-div">
                            <div class="col-md-6">
                                <ul class="breadcrumb">
                                    <li><a href="dashboard.php"><i class="fa fa-home"></i> Home</a></li>
                                    <li>Subjects</li>
                                    <li class="active">Add Subject Combination</li>
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
                                            <h5>Add Subject Combination</h5>
                                        </div>
                                    </div>
                                    <div class="panel-body">
                                    <?php if($msg){ ?>
                                    <div class="alert alert-success left-icon-alert" role="alert">
                                        <strong>Well done!</strong> <?php echo htmlentities($msg); ?>
                                    </div>
                                    <?php } else if($error){ ?>
                                        <div class="alert alert-danger left-icon-alert" role="alert">
                                            <strong>Oh snap!</strong> <?php echo htmlentities($error); ?>
                                        </div>
                                    <?php } ?>
                                        
                                        <form class="form-horizontal" method="post">
                                            <div class="form-group">
                                                <label for="default" class="col-sm-2 control-label">Class</label>
                                                <div class="col-sm-10">
                                                    <select name="classid" class="form-control" id="default" required="required">
                                                        <option value="">Select Class</option>
                                                        <?php 
                                                        $sql = "SELECT * from class";
                                                        $query = $dbh->prepare($sql);
                                                        $query->execute();
                                                        $results = $query->fetchAll(PDO::FETCH_OBJ);
                                                        if($query->rowCount() > 0)
                                                        {
                                                            foreach($results as $result)
                                                            {   ?>
                                                                <option value="<?php echo htmlentities(strtoupper($result->classid)); ?>">
                                                                <?php echo htmlentities($result->class); ?>&nbsp; <?php echo htmlentities($result->arm); ?></option>
                                                            <?php }} ?>
                                                    </select>
                                                </div>
                                            </div>

                                            <div class="form-group">
                                                <label for="default" class="col-sm-2 control-label">Subject</label>
                                                <div class="col-sm-10">
                                                    <select name="sub_id" class="form-control" id="default" required="required">
                                                        <option value="">Select Subject</option>
                                                        <?php 
                                                        $sql = "SELECT * from subjects";
                                                        $query = $dbh->prepare($sql);
                                                        $query->execute();
                                                        $results = $query->fetchAll(PDO::FETCH_OBJ);
                                                        if($query->rowCount() > 0)
                                                        {
                                                            foreach($results as $result)
                                                            {   ?>
                                                                <option value="<?php echo htmlentities($result->sub_id); ?>"><?php echo htmlentities($result->subject); ?></option>
                                                            <?php }} ?>
                                                    </select>
                                                </div>
                                            </div>

                                            <div class="form-group">
                                                <label for="teacher" class="col-sm-2 control-label">Teacher</label>
                                                <div class="col-sm-10">
                                                    <select name="staffid" class="form-control" id="teacher" required="required">
                                                        <option value="">Select Teacher</option>
                                                        <?php 
                                                        $sql = "SELECT staffid, fname, lname FROM staff";
                                                        $query = $dbh->prepare($sql);
                                                        $query->execute();
                                                        $staffResults = $query->fetchAll(PDO::FETCH_OBJ);
                                                        if ($query->rowCount() > 0) {
                                                            foreach ($staffResults as $staff) { ?>
                                                                <option value="<?php echo htmlentities($staff->staffid); ?>">
                                                                    <?php echo htmlentities($staff->fname . " " . $staff->lname); ?>
                                                                </option>
                                                            <?php }
                                                        } ?>
                                                    </select>
                                                </div>
                                            </div>

                                            <div class="form-group">
                                                <div class="col-sm-offset-2 col-sm-10">
                                                    <button type="submit" name="submit" class="btn btn-primary">Add</button>
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
<?PHP  ?>
