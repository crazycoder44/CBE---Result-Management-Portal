<?php
session_start();
error_reporting(0);
include('includes/config.php');

// Check if user is logged in
if(strlen($_SESSION['alogin']) == "") {   
    header("Location: index.php"); 
    exit;
} else {
    if(isset($_POST['submit'])) {
        $staffid = $_GET['staffid']; // Retrieve student ID from query string
        $fname = $_POST['fname'];
        $midname = $_POST['midname'];
        $lname = $_POST['lname'];
        $gender = $_POST['gender']; 
        $phone = $_POST['phone']; 
        $address = $_POST['address']; 
        $state = $_POST['state']; 
        $lga = $_POST['lga']; 
        $country = $_POST['country']; 
        $email = $_POST['email']; 
        $jobtitle = $_POST['jobtitle']; 
        $qualifications = $_POST['qualifications']; 
        $schoolattended = $_POST['schoolatended']; 
        $password = $_POST['password'];

        // Fetch the current password from the database
        $sql = "SELECT password FROM staff WHERE staffid = :staffid";
        $query = $dbh->prepare($sql);
        $query->bindParam(':staffid', $staffid, PDO::PARAM_STR);
        $query->execute();
        $result = $query->fetch(PDO::FETCH_OBJ);

        if($result) {
            $existingPassword = $result->password;

            // Hash the new password if provided, otherwise keep the existing one
            if(!empty($password)) {
                $passwordHash = $password; // Use MD5 to hash the new password
            } else {
                $passwordHash = $existingPassword; // Retain the existing password hash
            }

            // Update student info
            $sql = "UPDATE staff SET fname = :fname, midname = :midname, lname = :lname, gender = :gender, phone = :phone, address = :address, state = :state, lga = :lga, country = :country, email = :email, jobtitle = :jobtitle, qualifications = :qualifications, schoolattended = :schoolattended, password = :password WHERE staffid = :staffid";
            $query = $dbh->prepare($sql);
            $query->bindParam(':fname', $fname, PDO::PARAM_STR);
            $query->bindParam(':midname', $midname, PDO::PARAM_STR);
            $query->bindParam(':lname', $lname, PDO::PARAM_STR);
            $query->bindParam(':gender', $gender, PDO::PARAM_STR);
            $query->bindParam(':phone', $phone, PDO::PARAM_STR);
            $query->bindParam(':address', $address, PDO::PARAM_STR);
            $query->bindParam(':state', $state, PDO::PARAM_STR);
            $query->bindParam(':lga', $lga, PDO::PARAM_STR);
            $query->bindParam(':country', $country, PDO::PARAM_STR);
            $query->bindParam(':email', $email, PDO::PARAM_STR);
            $query->bindParam(':jobtitle', $jobtitle, PDO::PARAM_STR);
            $query->bindParam(':qualifications', $qualifications, PDO::PARAM_STR);
            $query->bindParam(':schoolattended', $schoolattended, PDO::PARAM_STR);
            $query->bindParam(':password', $passwordHash, PDO::PARAM_STR);
            $query->bindParam(':staffid', $staffid, PDO::PARAM_STR);
            $query->execute();

            $msg = "Staff info updated successfully";
        } else {
            $error = "Staff not found.";
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
    <title> SRMS | Edit Staff </title>
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
        <?php include('includes/topbar.php'); ?> 
        <!-- ========== WRAPPER FOR BOTH SIDEBARS & MAIN CONTENT ========== -->
        <div class="content-wrapper">
            <div class="content-container">
                <!-- ========== LEFT SIDEBAR ========== -->
                <?php include('includes/leftbar.php'); ?>  
                <!-- /.left-sidebar -->

                <div class="main-page">
                    <div class="container-fluid">
                        <div class="row page-title-div">
                            <div class="col-md-6">
                                <h2 class="title">Staff Admission</h2>
                            </div>
                            <!-- /.col-md-6 text-right -->
                        </div>
                        <!-- /.row -->
                        <div class="row breadcrumb-div">
                            <div class="col-md-6">
                                <ul class="breadcrumb">
                                    <li><a href="dashboard.php"><i class="fa fa-home"></i> Home</a></li>
                                    <li class="active">Staff Admission</li>
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
                                            <h5>Fill the Staff info</h5>
                                        </div>
                                    </div>
                                    <div class="panel-body">
                                        <?php if($msg) { ?>
                                        <div class="alert alert-success left-icon-alert" role="alert">
                                            <strong>Well done!</strong> <?php echo htmlentities($msg); ?>
                                        </div>
                                        <?php } else if($error) { ?>
                                        <div class="alert alert-danger left-icon-alert" role="alert">
                                            <strong>Oh snap!</strong> <?php echo htmlentities($error); ?>
                                        </div>
                                        <?php } ?>
                                        <form class="form-horizontal" method="post">
                                            <?php 
                                            $staffid = $_GET['staffid'];
                                            $sql = "SELECT * FROM staff WHERE staffid = :staffid";
                                            $query = $dbh->prepare($sql);
                                            $query->bindParam(':staffid', $staffid, PDO::PARAM_STR);
                                            $query->execute();

                                            $result = $query->fetch(PDO::FETCH_OBJ);

                                            if ($result) {
                                                $existingPassword = $result->password; // Store existing hashed password
                                            ?>

                                            <div class="form-group">
                                                <label for="sid" class="col-sm-2 control-label">Staff ID</label>
                                                <div class="col-sm-10">
                                                    <input type="text" name="sid" class="form-control" id="sid" value="<?php echo htmlentities($result->staffid); ?>" maxlength="10" required="required" autocomplete="off">
                                                </div>
                                            </div>

                                            <div class="form-group">
                                                <label for="fname" class="col-sm-2 control-label">First Name</label>
                                                <div class="col-sm-10">
                                                    <input type="text" name="fname" class="form-control" id="fname" value="<?php echo htmlentities($result->fname); ?>" required="required" autocomplete="off">
                                                </div>
                                            </div>

                                            <div class="form-group">
                                                <label for="midname" class="col-sm-2 control-label">Middle Name</label>
                                                <div class="col-sm-10">
                                                    <input type="text" name="midname" class="form-control" id="midname" value="<?php echo htmlentities($result->midname); ?>" autocomplete="off">
                                                </div>
                                            </div>

                                            <div class="form-group">
                                                <label for="lname" class="col-sm-2 control-label">Last Name</label>
                                                <div class="col-sm-10">
                                                    <input type="text" name="lname" class="form-control" id="lname" value="<?php echo htmlentities($result->lname); ?>" required="required" autocomplete="off">
                                                </div>
                                            </div>

                                            <div class="form-group">
                                                <label for="gender" class="col-sm-2 control-label">Gender</label>
                                                <div class="col-sm-10">
                                                    <?php  
                                                    $gndr = $result->gender;
                                                    $genders = ['Male', 'Female'];
                                                    foreach ($genders as $gender) {
                                                        $checked = ($gndr == $gender) ? 'checked' : '';
                                                        echo "<input type='radio' name='gender' value='$gender' $checked required='required'> $gender ";
                                                    }
                                                    ?>
                                                </div>
                                            </div>


                                            <div class="form-group">
                                                <label for="mobile" class="col-sm-2 control-label">Mobile</label>
                                                <div class="col-sm-10">
                                                    <input type="text" name="phone" class="form-control" id="phone" value="<?php echo htmlentities($result->phone); ?>">
                                                </div>
                                            </div>

                                            <div class="form-group">
                                                <label for="address" class="col-sm-2 control-label">Address</label>
                                                <div class="col-sm-10">
                                                    <input type="text" name="address" class="form-control" id="address" value="<?php echo htmlentities($result->address); ?>">
                                                </div>
                                            </div>

                                            <div class="form-group">
                                                <label for="state" class="col-sm-2 control-label">State of Origin</label>
                                                <div class="col-sm-10">
                                                    <input type="text" name="state" class="form-control" id="state" value="<?php echo htmlentities($result->state); ?>">
                                                </div>
                                            </div>

                                            <div class="form-group">
                                                <label for="lga" class="col-sm-2 control-label">Local Government Area</label>
                                                <div class="col-sm-10">
                                                    <input type="text" name="lga" class="form-control" id="lga" value="<?php echo htmlentities($result->lga); ?>">
                                                </div>
                                            </div>

                                            <div class="form-group">
                                                <label for="country" class="col-sm-2 control-label">Nationality</label>
                                                <div class="col-sm-10">
                                                    <input type="text" name="country" class="form-control" id="country" value="<?php echo htmlentities($result->country); ?>">
                                                </div>
                                            </div>

                                            <div class="form-group">
                                                <label for="email" class="col-sm-2 control-label">Email Address</label>
                                                <div class="col-sm-10">
                                                    <input type="email" name="email" class="form-control" id="email" value="<?php echo htmlentities($result->email); ?>" required="required" autocomplete="off">
                                                </div>
                                            </div>

                                            <div class="form-group">
                                                <label for="classid" class="col-sm-2 control-label">Job Title</label>
                                                <div class="col-sm-10">
                                                    <input type="text" name="jobtitle" class="form-control" id="jobtitle" value="<?php echo htmlentities($result->jobtitle); ?>">
                                                </div>
                                            </div>

                                            <div class="form-group">
                                                <label for="pname" class="col-sm-2 control-label">Qualification</label>
                                                <div class="col-sm-10">
                                                    <input type="text" name="qualifications" class="form-control" id="qualifications" value="<?php echo htmlentities($result->qualifications); ?>">
                                                </div>
                                            </div>

                                            <div class="form-group">
                                                <label for="poccu" class="col-sm-2 control-label">School Attended</label>
                                                <div class="col-sm-10">
                                                    <input type="text" name="schoolattended" class="form-control" id="schoolattended" value="<?php echo htmlentities($result->schoolattended); ?>">
                                                </div>
                                            </div>

                                            
                                            <div class="form-group">
                                                <label for="password" class="col-sm-2 control-label">New Password</label>
                                                <div class="col-sm-10">
                                                    <input type="password" name="password" class="form-control" id="password" placeholder="Enter new password">
                                                    <small class="form-text text-muted">Leave blank if you do not want to change the password.</small>
                                                </div>
                                            </div>

                                            <div class="form-group">
                                                <div class="col-sm-offset-2 col-sm-10">
                                                    <button type="submit" name="submit" class="btn btn-primary">Update</button>
                                                </div>
                                            </div>
                                            <?php } ?>
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
<?php  ?>
