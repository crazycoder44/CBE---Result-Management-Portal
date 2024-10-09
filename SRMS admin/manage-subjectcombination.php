<?php
session_start();
error_reporting(0);
include('includes/config.php');

if(strlen($_SESSION['alogin']) == 0) {   
    header("Location: index.php"); 
    exit();
} else {
    // for activating Subject
    if(isset($_GET['acid'])) {
        $acid = intval($_GET['acid']);
        $status = 1;
        $sql = "UPDATE tblsubjectcombination SET status=:status WHERE id=:acid";
        $query = $dbh->prepare($sql);
        $query->bindParam(':acid', $acid, PDO::PARAM_INT);
        $query->bindParam(':status', $status, PDO::PARAM_INT);
        $query->execute();
        $msg = "Subject activated successfully";
    }

    // for deactivating Subject
    if (isset($_GET['sub_id']) && isset($_GET['classid'])) {
        $sub_id = intval($_GET['sub_id']);
        $classid = $_GET['classid'];
        
        try {
            // Delete from class_subject table
            $sql = "DELETE FROM class_subject WHERE classid = :classid AND sub_id = :sub_id";
            $query = $dbh->prepare($sql);
            $query->bindParam(':classid', $classid, PDO::PARAM_STR);
            $query->bindParam(':sub_id', $sub_id, PDO::PARAM_INT);
            $query->execute();
    
            // Delete from staff_class_subject table
            $sqlDeleteStaff = "DELETE FROM staff_class_subject WHERE classid = :classid AND sub_id = :sub_id";
            $queryDeleteStaff = $dbh->prepare($sqlDeleteStaff);
            $queryDeleteStaff->bindParam(':classid', $classid, PDO::PARAM_STR);
            $queryDeleteStaff->bindParam(':sub_id', $sub_id, PDO::PARAM_INT);
            $queryDeleteStaff->execute();
    
            $msg = "Class and Subject combo deactivated successfully!!!";
        } catch (PDOException $e) {
            // Handle any potential errors
            $msg = "Error: " . $e->getMessage();
        }
    }
    
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title> SRMS | Manage Subject Combination </title>
    <link rel="stylesheet" href="css/bootstrap.min.css" media="screen">
    <link rel="stylesheet" href="css/font-awesome.min.css" media="screen">
    <link rel="stylesheet" href="css/animate-css/animate.min.css" media="screen">
    <link rel="stylesheet" href="css/lobipanel/lobipanel.min.css" media="screen">
    <link rel="stylesheet" href="css/prism/prism.css" media="screen">
    <link rel="stylesheet" href="css/select2/select2.min.css">
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
.succWrap{
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

                <!-- ========== LEFT SIDEBAR ========== -->
                <?php include('includes/leftbar.php');?>  
                <div class="main-page">
                    <div class="container-fluid">
                        <!-- Page title and breadcrumbs here -->
                        <section class="section">
                            <div class="container-fluid">
                                <div class="row">
                                    <div class="col-md-12">
                                        <div class="panel">
                                            <div class="panel-heading">
                                                <div class="panel-title">
                                                    <h5>View Subjects Combination Info</h5>
                                                </div>
                                            </div>
                                            <?php if($msg){ ?>
                                                <div class="alert alert-success left-icon-alert" role="alert">
                                                    <strong>Well done!</strong> <?php echo htmlentities($msg); ?>
                                                </div>
                                            <?php } else if($error){ ?>
                                                <div class="alert alert-danger left-icon-alert" role="alert">
                                                    <strong>Oh snap!</strong> <?php echo htmlentities($error); ?>
                                                </div>
                                            <?php } ?>
                                            <div class="panel-body p-20">
                                                <table id="example" class="display table table-striped table-bordered" cellspacing="0" width="100%">
                                                    <thead>
                                                        <tr>
                                                            <th>#</th>
                                                            <th>Class</th>
                                                            <th>Arm</th>
                                                            <th>Subject</th>
                                                            <th>Action</th>
                                                        </tr>
                                                    </thead>
                                                    <tbody>
                                                        <?php 
                                                        $sql = "SELECT DISTINCT cs.classid, cs.sub_id, c.arm, c.class, s.subject
                                                                FROM class_subject cs
                                                                JOIN class c ON c.classid = cs.classid
                                                                JOIN subjects s ON cs.sub_id = s.sub_id";
                                                        $query = $dbh->prepare($sql);
                                                        $query->execute();
                                                        $results = $query->fetchAll(PDO::FETCH_OBJ);
                                                        $cnt = 1;

                                                        if ($query->rowCount() > 0) {
                                                            foreach ($results as $result) { ?>
                                                                <tr>
                                                                    <td><?php echo htmlentities($cnt); ?></td>
                                                                    <td><?php echo htmlentities($result->class); ?></td>
                                                                    <td><?php echo htmlentities($result->arm); ?></td>
                                                                    <td><?php echo htmlentities($result->subject); ?></td>                        
                                                                    <td>
                                                                        <a href="manage-subjectcombination.php?sub_id=<?php echo htmlentities($result->sub_id); ?>&classid=<?php echo htmlentities($result->classid); ?>" onclick="return confirm('Do you really want to deactivate this subject?');">
                                                                            <i class="fa fa-times" title="Deactivate Record"></i>
                                                                        </a>
                                                                    </td>
                                                                </tr>
                                                        <?php $cnt++; }
                                                        } ?>
                                                       
                                                    
                                                    </tbody>
                                                </table>

                                         
                                                <!-- /.col-md-12 -->
                                            </div>
                                        </div>
                                    </div>
                                    <!-- /.col-md-6 -->

                                                               
                                                </div>
                                                <!-- /.col-md-12 -->
                                            </div>
                                        </div>
                                        <!-- /.panel -->
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
        <script src="js/jquery/jquery-2.2.4.min.js"></script>
        <script src="js/bootstrap/bootstrap.min.js"></script>
        <script src="js/pace/pace.min.js"></script>
        <script src="js/lobipanel/lobipanel.min.js"></script>
        <script src="js/iscroll/iscroll.js"></script>
        <script src="js/prism/prism.js"></script>
        <script src="js/DataTables/datatables.min.js"></script>
        <script src="js/main.js"></script>
        <script>
            $(function($) {
                $('#example').DataTable();

                $('#example2').DataTable( {
                    "scrollY":        "300px",
                    "scrollCollapse": true,
                    "paging":         false
                } );

                $('#example3').DataTable();
            });
        </script>
    </body>
</html>
<?php } ?>

