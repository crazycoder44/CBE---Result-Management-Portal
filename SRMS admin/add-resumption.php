<?php
session_start();
error_reporting(0);
include('includes/config.php');
if(strlen($_SESSION['alogin'])==""){  
    header("Location: index.php");
     }else{
if (isset($_POST['submit'])) {
    // Get form data
    $session = $_POST['session'];
    $termid = intval($_POST['term']);
    $resumptiondate = $_POST['resumptiondate'];

    // Check if the resumption table exists
    $tableCheckSql = "SHOW TABLES LIKE 'resumption'";
    $tableCheckQuery = $dbh->prepare($tableCheckSql);
    $tableCheckQuery->execute();

    if ($tableCheckQuery->rowCount() == 0) {
        // If the table doesn't exist, create it
        $createTableSql = "CREATE TABLE resumption (
                                session VARCHAR(255) NOT NULL,
                                termid INT NOT NULL,
                                date VARCHAR(255) NOT NULL
                            )";
        $dbh->exec($createTableSql);
        
        // Insert the first row after creating the table
        $insertSql = "INSERT INTO resumption (session, termid, date) 
                        VALUES (:session, :termid, :resumptiondate)";
        $query = $dbh->prepare($insertSql);
        $query->bindParam(':session', $session, PDO::PARAM_STR);
        $query->bindParam(':termid', $termid, PDO::PARAM_INT);
        $query->bindParam(':resumptiondate', $resumptiondate, PDO::PARAM_STR);
        $query->execute();
        
        echo '<script>alert("Resumption info added successfully")</script>';
        echo "<script>window.location.href ='add-resumption.php'</script>";
    } else {
        // If the table exists, check if a row with the same session and termid exists
        $checkRowSql = "SELECT * FROM resumption WHERE session = :session AND termid = :termid";
        $checkRowQuery = $dbh->prepare($checkRowSql);
        $checkRowQuery->bindParam(':session', $session, PDO::PARAM_STR);
        $checkRowQuery->bindParam(':termid', $termid, PDO::PARAM_INT);
        $checkRowQuery->execute();

        if ($checkRowQuery->rowCount() > 0) {
            // If such a row exists, update the date
            $updateSql = "UPDATE resumption SET date = :resumptiondate 
                            WHERE session = :session AND termid = :termid";
            $updateQuery = $dbh->prepare($updateSql);
            $updateQuery->bindParam(':session', $session, PDO::PARAM_STR);
            $updateQuery->bindParam(':termid', $termid, PDO::PARAM_INT);
            $updateQuery->bindParam(':resumptiondate', $resumptiondate, PDO::PARAM_STR);
            $updateQuery->execute();
            
            echo '<script>alert("Resumption date updated successfully")</script>';
            echo "<script>window.location.href ='add-resumption.php'</script>";
        } else {
            // If no such row exists, insert a new row
            $insertSql = "INSERT INTO resumption (session, termid, date) 
                            VALUES (:session, :termid, :resumptiondate)";
            $query = $dbh->prepare($insertSql);
            $query->bindParam(':session', $session, PDO::PARAM_STR);
            $query->bindParam(':termid', $termid, PDO::PARAM_INT);
            $query->bindParam(':resumptiondate', $resumptiondate, PDO::PARAM_STR);
            $query->execute();
            
            echo '<script>alert("Resumption info added successfully")</script>';
            echo "<script>window.location.href ='add-resumption.php'</script>";
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
        <title>SRMS Admin | Add Resumption Date</title>
        <link rel="stylesheet" href="css/bootstrap.css" media="screen" >
        <link rel="stylesheet" href="css/font-awesome.min.css" media="screen" >
        <link rel="stylesheet" href="css/animate-css/animate.min.css" media="screen" >
        <link rel="stylesheet" href="css/lobipanel/lobipanel.min.css" media="screen" >
        <link rel="stylesheet" href="css/prism/prism.css" media="screen" > <!-- USED FOR DEMO HELP - YOU CAN REMOVE IT -->
        <link rel="stylesheet" href="css/main.css" media="screen" >
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
       
            <div class="content-wrapper">
                <div class="content-container">


<?php include('includes/leftbar.php');?>                   


                    <div class="main-page">
                        <div class="container-fluid">
                            <div class="row page-title-div">
                                <div class="col-md-6">
                                    <h2 class="title">Add Resumption Date</h2>
                                </div>
                                
                            </div>
                      
                            <div class="row breadcrumb-div">
                                <div class="col-md-6">
                                    <ul class="breadcrumb">
            							<li><a href="dashboard.php"><i class="fa fa-home"></i> Home</a></li>
            							<li><a href="#">Notices</a></li>
            							<li class="active">Add Resumption Date</li>
            						</ul>
                                </div>
                               
                            </div>
                      
                        </div>
                        <section class="section">
                            <div class="container-fluid">
                                <div class="row">
                                    <div class="col-md-8 col-md-offset-2">
                                        <div class="panel">
                                            <div class="panel-heading">
                                                <div class="panel-title">
                                                    <h5>Add Notice</h5>
                                                </div>
                                            </div>  
                                            <div class="panel-body">
                                            <form method="post">
                                                <!-- Enter Session Input -->
                                                <div class="form-group has-success">
                                                    <label for="session" class="control-label">Enter Session</label>
                                                    <div class="">
                                                        <input type="text" name="session" class="form-control" required="required" id="session" placeholder="e.g., 2023/2024">
                                                    </div>
                                                </div>
                                                <!-- Select Term Dropdown -->
                                                <div class="form-group has-success">
                                                    <label for="term" class="control-label">Select Term</label>
                                                    <div class="">
                                                        <select name="term" class="form-control" required="required">
                                                            <option value="">Select Term</option>
                                                            <?php 
                                                            // Fetch terms from the terms table
                                                            $sql = "SELECT termid, term FROM terms";
                                                            $query = $dbh->prepare($sql);
                                                            $query->execute();
                                                            $results = $query->fetchAll(PDO::FETCH_OBJ);

                                                            // Check if terms are found
                                                            if ($query->rowCount() > 0) {
                                                                foreach ($results as $result) { ?>
                                                                    <option value="<?php echo htmlentities($result->termid); ?>">
                                                                        <?php echo htmlentities($result->term); ?>
                                                                    </option>
                                                            <?php } } ?>
                                                        </select>
                                                    </div>
                                                </div>
                                                <!-- Enter Resumption Date Input (Text Type) -->
                                                <div class="form-group has-success">
                                                    <label for="resumptiondate" class="control-label">Enter Resumption Date</label>
                                                    <div class="">
                                                        <input type="text" name="resumptiondate" class="form-control" required="required" id="resumptiondate" placeholder="e.g., 8th January, 2030">
                                                    </div>
                                                </div>

                                                <!-- Submit Button -->
                                                <div class="form-group has-success">
                                                    <div class="">
                                                        <button type="submit" name="submit" class="btn btn-success btn-labeled">Submit
                                                            <span class="btn-label btn-label-right"><i class="fa fa-check"></i></span>
                                                        </button>
                                                    </div>
                                                </div>
                                            </form>                                              
                                            </div>
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
