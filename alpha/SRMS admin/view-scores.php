<?php
session_start();
error_reporting(0);
include('includes/config.php');
if(strlen($_SESSION['alogin'])=="")
    {   
    header("Location: index.php"); 
    }
    else{

$session=$_GET['session'];
$termid=$_GET['termid'];
$classid=$_GET['classid'];
$sub_id=$_GET['sub_id'];
?>
<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="utf-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
    	<meta name="viewport" content="width=device-width, initial-scale=1">
        <title> SRMS | View & Approve Scores </title>
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
                                    <h2 class="title">View and Approve Scores</h2>
                                
                                </div>
                                
                                <!-- /.col-md-6 text-right -->
                            </div>
                            <!-- /.row -->
                            <div class="row breadcrumb-div">
                                <div class="col-md-6">
                                    <ul class="breadcrumb">
                                        <li><a href="dashboard.php"><i class="fa fa-home"></i> Home</a></li>
                                
                                        <li><a href="approve_results.php">Approve Results</a></li>

                                        <li class="active">View & Approve Scores</li>
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
                                                <?php
                                                // Fetch the subject name based on sub_id
                                                $sqlSubject = "SELECT subject FROM subjects WHERE sub_id = :sub_id";
                                                $querySubject = $dbh->prepare($sqlSubject);
                                                $querySubject->bindParam(':sub_id', $sub_id, PDO::PARAM_STR);
                                                $querySubject->execute();
                                                $subject = $querySubject->fetch(PDO::FETCH_OBJ)->subject;

                                                // Fetch the term name based on termid
                                                $sqlTerm = "SELECT term FROM terms WHERE termid = :termid";
                                                $queryTerm = $dbh->prepare($sqlTerm);
                                                $queryTerm->bindParam(':termid', $termid, PDO::PARAM_STR);
                                                $queryTerm->execute();
                                                $term = $queryTerm->fetch(PDO::FETCH_OBJ)->term;

                                                // Display heading in the panel title
                                                echo "<h5>" . htmlentities($classid) . " " . strtoupper(htmlentities($subject)) . " Scores for " . htmlentities($term) . " " . htmlentities($session) . " Session</h5>";
                                                ?>
                                            </div>
                                        </div>
                                        <div class="panel-body">
                                            <div class="table-responsive" style="overflow-x: auto;">
                                                <table class="table table-bordered table-striped table-hover">
                                                    <thead>
                                                        <tr>
                                                            <th style="position: sticky; left: 0; background-color: white;">Student Name</th>
                                                            <th>CA</th>
                                                            <th>EXAMOBJ</th>
                                                            <th>EXAMTHEORY</th>
                                                            <th>TOTAL</th>
                                                            <th>GRADE</th>
                                                        </tr>
                                                    </thead>
                                                    <tbody>
                                                        <?php
                                                        // Modify the SQL query to join the tempresult table with the students table based on the email field
                                                        $sql = "SELECT students.fname, students.lname, tempresult.*
                                                                FROM tempresult
                                                                JOIN students ON tempresult.email = students.email
                                                                WHERE tempresult.session = :session 
                                                                AND tempresult.termid = :termid
                                                                AND tempresult.classid = :classid
                                                                AND tempresult.sub_id = :sub_id
                                                                GROUP BY tempresult.session, tempresult.termid, tempresult.classid, tempresult.sub_id, students.fname, students.lname";
                                                        
                                                        $query = $dbh->prepare($sql);
                                                        $query->bindParam(':session', $session, PDO::PARAM_STR);
                                                        $query->bindParam(':termid', $termid, PDO::PARAM_STR);
                                                        $query->bindParam(':classid', $classid, PDO::PARAM_STR);
                                                        $query->bindParam(':sub_id', $sub_id, PDO::PARAM_STR);
                                                        $query->execute();
                                                        $results = $query->fetchAll(PDO::FETCH_OBJ);
                                                        $cnt = 1;

                                                        if ($query->rowCount() > 0) {
                                                            foreach ($results as $result) { 
                                                        ?>
                                                        <tr>
                                                            <!-- Display the student's full name by fetching fname and lname from the students table -->
                                                            <td style="position: sticky; left: 0; background-color: white;"><?php echo htmlentities($result->fname . " " . $result->lname); ?></td>
                                                            <td><?php echo htmlentities($result->ca); ?></td>
                                                            <td><?php echo htmlentities($result->examobj); ?></td>
                                                            <td><?php echo htmlentities($result->examtheory); ?></td>
                                                            <td><?php echo htmlentities($result->total); ?></td>
                                                            <td><?php echo htmlentities($result->grade); ?></td>
                                                        </tr>
                                                        <?php 
                                                                $cnt++;
                                                            } 
                                                        }
                                                        ?>
                                                    </tbody>
                                                </table>
                                            </div>
                                            <!-- Approve and Print buttons -->
                                            <div class="text-right" style="margin-top: 20px; display: flex; justify-content: space-around;">
                                                <form method="post" action="scores_approval.php" id="approveForm">                                                    <input type="hidden" name="session" value="<?php echo htmlentities($session); ?>">
                                                    <input type="hidden" name="termid" value="<?php echo htmlentities($termid); ?>">
                                                    <input type="hidden" name="classid" value="<?php echo htmlentities($classid); ?>">
                                                    <input type="hidden" name="sub_id" value="<?php echo htmlentities($sub_id); ?>">
                                                    
                                                    <button type="button" id="approveButton" class="btn btn-success">Approve</button>                                                </form>
                                                
                                                <!-- <button class="btn btn-primary" onclick="window.print();">Print</button> -->
                                            </div>
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
        <script>
            document.getElementById('approveButton').addEventListener('click', function() {
                // Show confirmation dialog
                if (confirm('Are you sure you wish to approve the scores?')) {
                    // User clicked "Yes", proceed with approval
                    const form = document.getElementById('approveForm');
                    
                    // Gather form data
                    const formData = new FormData(form);

                    // Prepare AJAX request
                    fetch('scores_approval.php', {
                        method: 'POST',
                        body: formData,
                    })
                    .then(response => response.json())
                    .then(data => {
                        if (data.success) {
                            alert('Scores approved successfully!');
                            // Optionally, you could refresh the page or redirect
                            window.location.href = 'approve_results.php';
                        } else {
                            alert('Error: ' + data.message);
                        }
                    })
                    .catch(error => {
                        console.error('Error:', error);
                        alert('An error occurred while processing your request.');
                    });
                } else {
                    // User clicked "No"
                    alert('Approval canceled.'); 
                    // You might implement logic here to restore the previous session if needed.
                }
            });
        </script>
    </body>
</html>
<?PHP } ?>
