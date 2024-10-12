<?php
session_start();
error_reporting(0);
include('includes/config.php');

if (strlen($_SESSION['alogin']) == "") {   
    header("Location: index.php"); 
} else {
    if (isset($_POST['update'])) {
        // Sanitize input data
        $classname = htmlspecialchars(trim($_POST['classname']));
        $classarm = htmlspecialchars(trim($_POST['classarm'])); 
        $guardian = htmlspecialchars(trim($_POST['guardian']));
        $cid = htmlspecialchars(trim($_GET['classid']));

        // Prepare the UPDATE query
        $sql = "UPDATE class SET class = :classname, arm = :arm, staffid = :staffid WHERE classid = :cid";
        $query = $dbh->prepare($sql);
        $query->bindParam(':classname', $classname, PDO::PARAM_STR);
        $query->bindParam(':arm', $classarm, PDO::PARAM_STR);
        $query->bindParam(':staffid', $guardian, PDO::PARAM_STR);
        $query->bindParam(':cid', $cid, PDO::PARAM_STR);

        // Execute the query and set messages
        if ($query->execute()) {
            $msg = "Data has been updated successfully";
        } else {
            $error = "Something went wrong. Please try again.";
        }
    }
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
	<meta name="viewport" content="width=device-width, initial-scale=1">
    <title> SRMS | Update Class </title>
    <link rel="stylesheet" href="css/bootstrap.css" media="screen" >
    <link rel="stylesheet" href="css/font-awesome.min.css" media="screen" >
    <link rel="stylesheet" href="css/animate-css/animate.min.css" media="screen" >
    <link rel="stylesheet" href="css/lobipanel/lobipanel.min.css" media="screen" >
    <link rel="stylesheet" href="css/prism/prism.css" media="screen" >
    <link rel="stylesheet" href="css/main.css" media="screen" >
    <script src="js/modernizr/modernizr.min.js"></script>
</head>
<body class="top-navbar-fixed">
    <div class="main-wrapper">

        <!-- ========== TOP NAVBAR ========== -->
        <?php include('includes/topbar.php');?>   
        <!-- End Top bar -->

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
                                <h2 class="title">Update Student Class</h2>
                            </div>
                        </div>
                        <!-- /.row -->
                        <div class="row breadcrumb-div">
                            <div class="col-md-6">
                                <ul class="breadcrumb">
                                    <li><a href="dashboard.php"><i class="fa fa-home"></i> Home</a></li>
                                    <li><a href="manage-classes.php">Classes</a></li>
                                    <li class="active">Update Class</li>
                                </ul>
                            </div>
                        </div>
                        <!-- /.row -->
                    </div>
                    <!-- /.container-fluid -->

                    <section class="section">
                        <div class="container-fluid">

                            <div class="row">
                                <div class="col-md-8 col-md-offset-2">
                                    <div class="panel">
                                        <div class="panel-heading">
                                            <div class="panel-title">
                                                <h5>Update Student Class Info</h5>
                                            </div>
                                        </div>
                                        <?php if ($msg) { ?>
                                        <div class="alert alert-success left-icon-alert" role="alert">
                                            <strong>Well done!</strong> <?php echo htmlentities($msg); ?>
                                        </div>
                                        <?php } else if ($error) { ?>
                                        <div class="alert alert-danger left-icon-alert" role="alert">
                                            <strong>Oh snap!</strong> <?php echo htmlentities($error); ?>
                                        </div>
                                        <?php } ?>

                                        <form method="post">
                                        <?php 
                                        // Sanitize classid
                                        $cid = htmlspecialchars(trim($_GET['classid']));

                                        // Prepare the SELECT query
                                        $sql = "SELECT * FROM class WHERE classid = :cid";
                                        $query = $dbh->prepare($sql);
                                        $query->bindParam(':cid', $cid, PDO::PARAM_STR);
                                        $query->execute();
                                        $results = $query->fetchAll(PDO::FETCH_OBJ);

                                        if ($query->rowCount() > 0) {
                                            foreach ($results as $result) {   
                                        ?>
                                            
                                                <div class="form-group has-success">
                                                    <label for="classname" class="control-label">Class Name</label>
                                                    <div class="">
                                                        <input type="text" name="classname" value="<?php echo htmlentities($result->class); ?>" readonly required="required" class="form-control" id="classname">
                                                    </div>
                                                </div>
                                            
                                                <div class="form-group has-success">
                                                    <label for="classarm" class="control-label">Arm</label>
                                                    <div class="">
                                                        <input type="text" name="classarm" value="<?php echo htmlentities($result->arm); ?>" readonly required="required" class="form-control" id="classarm">
                                                    </div>
                                                </div>
                                            
                                                <div class="form-group has-success">
                                                    <label for="guardian" class="control-label">Guardian ID</label>
                                                    <div class="">
                                                        <select name="guardian" class="form-control" required="required" id="guardian">
                                                            <?php 
                                                            // Fetch all staff members
                                                            $sqlStaff = "SELECT staffid, fname, lname FROM staff";
                                                            $queryStaff = $dbh->prepare($sqlStaff);
                                                            $queryStaff->execute();
                                                            $staffResults = $queryStaff->fetchAll(PDO::FETCH_OBJ);

                                                            if ($queryStaff->rowCount() > 0) {
                                                                foreach ($staffResults as $staff) { 
                                                                    // Show selected guardian as default
                                                                    $selected = ($staff->staffid == $result->staffid) ? 'selected' : ''; 
                                                                    ?>
                                                                    <option value="<?php echo htmlentities($staff->staffid); ?>" <?php echo $selected; ?>>
                                                                        <?php echo htmlentities($staff->fname . " " . $staff->lname . " (" . $staff->staffid . ")"); ?>
                                                                    </option>
                                                                <?php }
                                                            } ?>
                                                        </select>
                                                    </div>
                                                </div>
                                            
                                        <?php 
                                            }
                                        } else {
                                            echo "<p>No class found with the provided ID.</p>";
                                        }
                                        ?>
                                                <div class="form-group has-success">
                                                    <div class="">
                                                        <button type="submit" name="update" class="btn btn-success btn-labeled">
                                                            Update
                                                            <span class="btn-label btn-label-right">
                                                                <i class="fa fa-check"></i>
                                                            </span>
                                                        </button>
                                                    </div>
                                                </div>
                                        </form>
                                        
                                    </div>
                                </div>
                                <!-- /.col-md-8 col-md-offset-2 -->
                            </div>
                            <!-- /.row -->
                        </div>
                        <!-- /.container-fluid -->
                    </section>
                    <!-- /.section -->
                </div>
                <!-- /.main-page -->
                <!-- /.right-sidebar -->
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

    <!-- ========== THEME JS ========== -->
    <script src="js/main.js"></script>

    <!-- ========== ADD custom.js FILE BELOW WITH YOUR CHANGES ========== -->
</body>
</html>
<?php  } ?>
