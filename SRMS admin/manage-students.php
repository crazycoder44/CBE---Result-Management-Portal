<?php
session_start();
error_reporting(0);
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
    <title> SRMS | Manage Students </title>
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
                                <h2 class="title">Manage Students</h2>
                            </div>
                        </div>
                        <!-- /.row -->
                        <div class="row breadcrumb-div">
                            <div class="col-md-6">
                                <ul class="breadcrumb">
                                    <li><a href="dashboard.php"><i class="fa fa-home"></i> Home</a></li>
                                    <li> Students</li>
                                    <li class="active">Manage Students</li>
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
                                            <table id="example" class="display table table-striped table-bordered" cellspacing="0" width="100%">
                                                <thead>
                                                    <tr>
                                                        <th>#</th>
                                                        <th>Student Name</th>
                                                        <th>Student Id</th>
                                                        <th>Gender</th>
                                                        <th>Class</th>
                                                        <th>Action</th>
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                <?php 
                                                $sql = "SELECT students.fname, students.lname, students.email, students.sid, students.gender, students.classid FROM students";
                                                $query = $dbh->prepare($sql);
                                                $query->execute();
                                                $results = $query->fetchAll(PDO::FETCH_OBJ);
                                                $cnt = 1;
                                                if($query->rowCount() > 0)
                                                {
                                                    foreach($results as $result)
                                                    { 
                                                        // Check if the student is already in the blacklist
                                                        $sqlCheckBlacklist = "SELECT sid FROM blacklist WHERE sid = :sid";
                                                        $queryCheckBlacklist = $dbh->prepare($sqlCheckBlacklist);
                                                        $queryCheckBlacklist->bindParam(':sid', $result->sid, PDO::PARAM_STR);
                                                        $queryCheckBlacklist->execute();
                                                        $isBlacklisted = $queryCheckBlacklist->rowCount() > 0;   
                                                        ?>
                                                    <tr>
                                                        <td><?php echo htmlentities($cnt);?></td>
                                                        <td><?php echo htmlentities($result->fname . " " . $result->lname);?></td>
                                                        <td><?php echo htmlentities($result->sid);?></td>
                                                        <td><?php echo htmlentities($result->gender);?></td>
                                                        <td><?php echo htmlentities($result->classid);?></td>
                                                        <td>
                                                            <a href="edit-student.php?stid=<?php echo htmlentities($result->sid);?>" class="btn btn-primary btn-xs" target="_blank">Edit </a> 
                                                            <a href="edit-result.php?stid=<?php echo htmlentities($result->sid);?>&email=<?php echo htmlentities($result->email);?>" class="btn btn-warning btn-xs" target="_blank">View Result </a>                                                            
                                                            <button class="btn btn-blacklist btn-xs" data-sid="<?php echo htmlentities($result->sid); ?>" style="background-color: #3D0C02; color: white;">
                                                                <?php echo $isBlacklisted ? 'Unblacklist' : 'Blacklist'; ?>
                                                            </button>
                                                        </td>
                                                    </tr>
                                                    <?php $cnt = $cnt + 1; }
                                                } ?>
                                                </tbody>
                                            </table>
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
        document.addEventListener('DOMContentLoaded', function () {
            // Get all blacklist buttons
            const blacklistButtons = document.querySelectorAll('.btn-blacklist');

            blacklistButtons.forEach(button => {
                button.addEventListener('click', function () {
                    const sid = this.getAttribute('data-sid');
                    const isBlacklisted = this.innerText === 'Unblacklist';

                    // Determine the API endpoint and the message based on the button state
                    const endpoint = isBlacklisted ? 'remove_from_blacklist.php' : 'add_to_blacklist.php';
                    const message = isBlacklisted ? 'student successfully removed from blacklist' : 'student successfully added to blacklist';

                    // Make the AJAX request
                    fetch(endpoint, {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json',
                        },
                        body: JSON.stringify({ sid: sid }),
                    })
                    .then(response => response.json())
                    .then(data => {
                        if (data.success) {
                            // Show alert with success message
                            alert(message);
                            // Change button text
                            this.innerText = isBlacklisted ? 'Blacklist' : 'Unblacklist';
                        } else {
                            // Handle error response
                            alert(data.message || 'An error occurred.');
                        }
                    })
                    .catch(error => {
                        // Handle fetch error
                        alert('Network error: ' + error.message);
                    });
                });
            });
        });
    </script>
</body>
</html>
<?php } ?>
