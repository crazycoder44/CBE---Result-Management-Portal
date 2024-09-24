<?php
session_start();
error_reporting(0);
include('includes/config.php');

if(strlen($_SESSION['alogin']) == "") {   
    header("Location: index.php"); 
    exit();
}

if(isset($_POST['Update'])) {
    $sub_ids = $_POST['sub_ids'];
    $staffids = $_POST['staffids'];
    $classids = $_POST['classids'];

    // Begin transaction
    $dbh->beginTransaction();
    
    try {
        for ($i = 0; $i < count($sub_ids); $i++) {
            $sub_id = intval($sub_ids[$i]);
            $staffid = $_POST['staffids'][$i];
            $classid = $_POST['classids'][$i];

            // Check if the row has changes
            $sql_check = "SELECT staffid, classid FROM staff_class_subject WHERE sub_id = :sub_id";
            $query_check = $dbh->prepare($sql_check);
            $query_check->bindParam(':sub_id', $sub_id, PDO::PARAM_STR);
            $query_check->execute();
            $existing = $query_check->fetch(PDO::FETCH_ASSOC);

            if ($existing['staffid'] !== $staffid || $existing['classid'] !== $classid) {
                $sql_update = "UPDATE staff_class_subject SET staffid=:staffid, classid=:classid WHERE sub_id=:sub_id";
                $query_update = $dbh->prepare($sql_update);
                $query_update->bindParam(':staffid', $staffid, PDO::PARAM_STR);
                $query_update->bindParam(':classid', $classid, PDO::PARAM_STR);
                $query_update->bindParam(':sub_id', $sub_id, PDO::PARAM_STR);
                $query_update->execute();
            }
        }
        
        // Commit transaction
        $dbh->commit();
        $msg = "Subject Info updated successfully";
    } catch (Exception $e) {
        // Rollback transaction on error
        $dbh->rollBack();
        $error = "An error occurred: " . $e->getMessage();
    }
}
?>

<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="utf-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <title>SMS Admin Update Subject</title>
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
                                    <h2 class="title">Update Subject</h2>
                                </div>
                            </div>
                            <!-- /.row -->
                            <div class="row breadcrumb-div">
                                <div class="col-md-6">
                                    <ul class="breadcrumb">
                                        <li><a href="dashboard.php"><i class="fa fa-home"></i> Home</a></li>
                                        <li>Subjects</li>
                                        <li class="active">Update Subject</li>
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
                                                <h5>Update Subject</h5>
                                            </div>
                                        </div>
                                        <div class="panel-body">
                                            <?php if(isset($msg)){ ?>
                                                <div class="alert alert-success left-icon-alert" role="alert">
                                                    <strong>Well done!</strong> <?php echo htmlentities($msg); ?>
                                                </div>
                                            <?php } else if(isset($error)){ ?>
                                                <div class="alert alert-danger left-icon-alert" role="alert">
                                                    <strong>Oh snap!</strong> <?php echo htmlentities($error); ?>
                                                </div>
                                            <?php } ?>

                                            <form class="form-horizontal" method="post">
                                                <?php
                                                $sid = intval($_GET['subjectid']);
                                                $sql = "SELECT DISTINCT scs.sub_id, scs.staffid, scs.classid, a.fname, s.subject
                                                        FROM staff_class_subject scs
                                                        JOIN admin a ON scs.staffid = a.staffid
                                                        JOIN subjects s ON scs.sub_id = s.sub_id
                                                        WHERE scs.sub_id = :sid";
                                                $query = $dbh->prepare($sql);
                                                $query->bindParam(':sid', $sid, PDO::PARAM_STR);
                                                $query->execute();
                                                $results = $query->fetchAll(PDO::FETCH_OBJ);

                                                if ($query->rowCount() > 0) {
                                                    foreach ($results as $result) {
                                                ?>
                                                <div class="form-group">
                                                    <input type="hidden" name="sub_ids[]" value="<?php echo htmlentities($result->sub_id); ?>">
                                                    <label for="subjectname" class="col-sm-2 control-label">Subject Name</label>
                                                    <div class="col-sm-10">
                                                        <input type="text" name="subjects[]" value="<?php echo htmlentities($result->subject); ?>" class="form-control" readonly>
                                                    </div>
                                                </div>
                                                <div class="form-group">
                                                    <label for="staffid" class="col-sm-2 control-label">Staff ID</label>
                                                    <div class="col-sm-10">
                                                        <input type="text" name="staffids[]" value="<?php echo htmlentities($result->staffid); ?>" class="form-control">
                                                    </div>
                                                </div>
                                                <div class="form-group">
                                                    <label for="classid" class="col-sm-2 control-label">Class</label>
                                                    <div class="col-sm-10">
                                                        <input type="text" name="classids[]" value="<?php echo htmlentities($result->classid); ?>" class="form-control" readonly>
                                                    </div>
                                                </div>
                                                <?php
                                                    }
                                                }
                                                ?>
                                                <div class="form-group">
                                                    <div class="col-sm-offset-2 col-sm-10">
                                                        <button type="submit" name="Update" class="btn btn-primary">Update</button>
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
