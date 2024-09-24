<?php
session_start();
error_reporting(0);
include('includes/config.php');

if(strlen($_SESSION['alogin'])=="") {   
    header("Location: index.php"); 
} else {
    if(isset($_POST['submit'])) {
        $sid = $_POST['sid'];
        $fname = $_POST['fname'];
        $lname = $_POST['lname']; 
        $gender = $_POST['gender']; 
        $dob = $_POST['dob']; 
        $classid = $_POST['classid']; 
        $email = $_POST['email']; 
        $mobile = $_POST['mobile']; 
        $password = $_POST['password']; 

        // Hash the password using MD5
        $passcode = md5($password);

        // Prepare SQL query
        $sql = "INSERT INTO students(sid, fname, lname, gender, dob, classid, email, mobile, password) 
                VALUES(:sid, :fname, :lname, :gender, :dob, :classid, :email, :mobile, :passcode)";
        $query = $dbh->prepare($sql);

        // Bind parameters
        $query->bindParam(':sid', $sid, PDO::PARAM_STR);
        $query->bindParam(':fname', $fname, PDO::PARAM_STR);
        $query->bindParam(':lname', $lname, PDO::PARAM_STR);
        $query->bindParam(':gender', $gender, PDO::PARAM_STR);
        $query->bindParam(':dob', $dob, PDO::PARAM_STR);
        $query->bindParam(':classid', $classid, PDO::PARAM_STR);
        $query->bindParam(':email', $email, PDO::PARAM_STR);
        $query->bindParam(':mobile', $mobile, PDO::PARAM_STR);
        $query->bindParam(':password', $hashed_password, PDO::PARAM_STR);

        // Execute the query and check if successful
        if($query->execute()) {
            $msg = "Student info added successfully";
        } else {
            $error = "Something went wrong. Please try again";
        }
    }
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>SMS Admin | Student Admission</title>
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
                                <h2 class="title">Student Admission</h2>
                            </div>
                        </div>

                        <div class="row breadcrumb-div">
                            <div class="col-md-6">
                                <ul class="breadcrumb">
                                    <li><a href="dashboard.php"><i class="fa fa-home"></i> Home</a></li>
                                    <li class="active">Student Admission</li>
                                </ul>
                            </div>
                        </div>
                    </div>

                    <div class="container-fluid">
                        <div class="row">
                            <div class="col-md-12">
                                <div class="panel">
                                    <div class="panel-heading">
                                        <div class="panel-title">
                                            <h5>Fill the Student info</h5>
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
                                                <label for="fname" class="col-sm-2 control-label">First Name</label>
                                                <div class="col-sm-10">
                                                    <input type="text" name="fname" class="form-control" id="fname" required="required" autocomplete="off">
                                                </div>
                                            </div>

                                            <div class="form-group">
                                                <label for="lname" class="col-sm-2 control-label">Last Name</label>
                                                <div class="col-sm-10">
                                                    <input type="text" name="lname" class="form-control" id="lname" required="required" autocomplete="off">
                                                </div>
                                            </div>

                                            <div class="form-group">
                                                <label for="sid" class="col-sm-2 control-label">Student ID</label>
                                                <div class="col-sm-10">
                                                    <input type="text" name="sid" class="form-control" id="sid" required="required" autocomplete="off">
                                                </div>
                                            </div>

                                            <div class="form-group">
                                                <label class="col-sm-2 control-label">Gender</label>
                                                <div class="col-sm-10">
                                                    <input type="radio" name="gender" value="Male" required="required" checked=""> Male 
                                                    <input type="radio" name="gender" value="Female" required="required"> Female 
                                                </div>
                                            </div>

                                            <div class="form-group">
                                                <label for="default" class="col-sm-2 control-label">Class</label>
                                                <div class="col-sm-10">
                                                    <select name="classid" class="form-control" id="default" required="required">
                                                        <option value="">Select Class</option>
                                                        <?php
                                                        $sql = "SELECT classid FROM class";
                                                        $query = $dbh->prepare($sql);
                                                        $query->execute();
                                                        $results = $query->fetchAll(PDO::FETCH_OBJ);

                                                        if ($query->rowCount() > 0) {
                                                            foreach ($results as $result) {
                                                                echo '<option value="'.htmlentities($result->classid).'">'.htmlentities($result->classid).'</option>';
                                                            }
                                                        }
                                                        ?>
                                                    </select>
                                                </div>
                                            </div>

                                            <div class="form-group">
                                                <label for="email" class="col-sm-2 control-label">Email address</label>
                                                <div class="col-sm-10">
                                                    <input type="email" name="email" class="form-control" id="email" required="required" autocomplete="off">
                                                </div>
                                            </div>

                                            <div class="form-group">
                                                <label for="mobile" class="col-sm-2 control-label">Mobile Number</label>
                                                <div class="col-sm-10">
                                                    <input type="text" name="mobile" class="form-control" id="mobile" required="required" autocomplete="off">
                                                </div>
                                            </div>

                                            <div class="form-group">
                                                <label for="password" class="col-sm-2 control-label">Password</label>
                                                <div class="col-sm-10">
                                                    <input type="text" name="password" class="form-control" id="password" required="required" autocomplete="off">
                                                </div>
                                            </div>

                                            <div class="form-group">
                                                <label for="dob" class="col-sm-2 control-label">DOB</label>
                                                <div class="col-sm-10">
                                                    <input type="date" name="dob" class="form-control" id="dob">
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
    </body>
</html>

<!-- Modal HTML -->
<div class="modal fade" id="emailModal" tabindex="-1" aria-labelledby="emailModalLabel" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="emailModalLabel">Email Status</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body" id="modalBody">
        <!-- This will be filled dynamically -->
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
      </div>
    </div>
  </div>
</div>


<script>
// Get the email input element
const input = document.getElementById('email');

// Add an event listener for the email input field's "blur" event
input.addEventListener('blur', checkValue);

function checkValue() {
  // Get the email input value
  const value = input.value;

  console.log('Checking value for:', value); // Log to ensure function is triggered

  // Use AJAX to send a request to the server
  const xhr = new XMLHttpRequest();
  xhr.open('POST', 'check-value.php', true);
  xhr.setRequestHeader('Content-Type', 'application/json');

  // Send the email value to the server
  xhr.send(JSON.stringify({ value }));

  // Handle the response from the server
  xhr.onload = function() {
    if (xhr.status === 200) {
      const response = JSON.parse(xhr.responseText);
      console.log('Response received:', response); // Log response for debugging

      // Update the modal content based on the server response
      const modalBody = document.getElementById('modalBody');
      if (response.registered) {
        if (response.table === 'students') {
          modalBody.innerHTML = 'This email is already registered under a student.';
        } else if (response.table === 'staff') {
          modalBody.innerHTML = 'This email is already registered under a staff member.';
        }
      } else {
        modalBody.innerHTML = 'This email is available.';
      }

      // Show the modal
      $('#emailModal').modal('show');
    }
  };

  xhr.onerror = function() {
    console.error('Error with the request.');
  };
}


</script>

<?php } ?>
