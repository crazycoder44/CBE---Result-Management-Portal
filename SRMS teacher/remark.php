<?php
session_start();
error_reporting(0);

$staffid = $_SESSION['staffid']; // Retrieve staff ID from session

include('includes/config.php');
include('includes/dbConnection.php');

if(strlen($_SESSION['alogin'])=="")
    {   
    header("Location: index.php"); 
    }
    else{

?>

<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="utf-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
    	<meta name="viewport" content="width=device-width, initial-scale=1">
        <title> SRMS | Remarks & Comments </title>
        <link rel="stylesheet" href="css/bootstrap.min.css" media="screen" >
        <link rel="stylesheet" href="css/font-awesome.min.css" media="screen" >
        <link rel="stylesheet" href="css/animate-css/animate.min.css" media="screen" >
        <link rel="stylesheet" href="css/lobipanel/lobipanel.min.css" media="screen" >
        <link rel="stylesheet" href="css/prism/prism.css" media="screen" >
        <link rel="stylesheet" href="css/select2/select2.min.css" >
        <link rel="stylesheet" href="css/main.css" media="screen" >
        <link rel="stylesheet" href="css/tableresponsive.css">
        <script src="js/modernizr/modernizr.min.js"></script>
        <style>
                                                        #resultsTable {
                                                            display: none; /* Hide the table initially */
                                                        }
                                                        #postResultBtn {
                                                            display: none; /* Hide the button initially */
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
                    <!-- /.left-sidebar -->

                    <div class="main-page">

                     <div class="container-fluid">
                            <div class="row page-title-div">
                                <div class="col-md-6">
                                    <h2 class="title">Remarks and Comments</h2>
                                
                                </div>
                                
                                <!-- /.col-md-6 text-right -->
                            </div>
                            <!-- /.row -->
                            <div class="row breadcrumb-div">
                                <div class="col-md-6">
                                    <ul class="breadcrumb">
                                        <li><a href="dash.php"><i class="fa fa-home"></i> Home</a></li>
                                
                                        <li class="active"> Remarks and Comments </li>
                                    </ul>
                                </div>
                             
                            </div>
                            <!-- /.row -->
                        </div>
                        <div class="container-fluid">
                           
                        <div class="row">
                                    <div class="col-md-12">
                                        <div class="panel">
                                           
                                            <div class="panel-body">
<?php if($msg){?>
<div class="alert alert-success left-icon-alert" role="alert">
 <strong>Well done!</strong><?php echo htmlentities($msg); ?>
 </div><?php } 
else if($error){?>
    <div class="alert alert-danger left-icon-alert" role="alert">
                                            <strong>Oh snap!</strong> <?php echo htmlentities($error); ?>
                                        </div>
                                        <?php } ?>
                                                <form class="form-horizontal" id="resultForm">

                                                    <div class="form-group">
                                                        <label for="default" class="col-sm-2 control-label">Session</label>
                                                        <div class="col-sm-10">
                                                            <select id="session" name="session" class="form-control input-md" required>
                                                                <option value="">Select Session</option>
                                                                <?php
                                                                // Fetch distinct session values from the results table
                                                                $sqlSession = "SELECT DISTINCT session FROM results";
                                                                $querySession = $dbh->prepare($sqlSession);
                                                                $querySession->execute();
                                                                $sessions = $querySession->fetchAll(PDO::FETCH_OBJ);

                                                                // Populate the dropdown with the distinct session values
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
                                                        <label for="default" class="col-sm-2 control-label">Term</label>
                                                        <div class="col-sm-10">
                                                            <select name="term" class="form-control clid" id="term" onChange="getStudent(this.value);" required="required">
                                                                <option value="" disabled selected>Select Term</option>
                                                                <?php $sql = "SELECT termid, term from terms";
                                                                $query = $dbh->prepare($sql);
                                                                $query->execute();
                                                                $results=$query->fetchAll(PDO::FETCH_OBJ);
                                                                if($query->rowCount() > 0)
                                                                {
                                                                foreach($results as $result)
                                                                {   ?>
                                                                <option value="<?php echo htmlentities($result->termid); ?>"><?php echo htmlentities($result->term); ?></option>
                                                                <?php }} ?>
                                                            </select>
                                                        </div>
                                                    </div>                                                    
                                                    
                                                    <div class="form-group">
                                                        <div class="col-sm-offset-2 col-sm-10">
                                                            <button name="submit" id="submit" class="btn btn-primary" onclick="populateTable(event)">Submit</button>
                                                        </div>
                                                    </div>
                                                </form>
                                                <div class="table-responsive-fixed">
                                                    <table id="resultsTable" class="display table table-striped table-bordered" cellspacing="0" width="100%">
                                                        <thead>
                                                            <tr>
                                                                <th>S/N</th>
                                                                <th>Student Name</th>
                                                                <th>Class</th>
                                                                <th>Action</th>
                                                            </tr>
                                                        </thead>
                                                        <tbody id="resultsTableBody">

                                                        </tbody>
                                                    </table>
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
<!-- Modal for Remarks -->
<div class="modal fade" id="remarksModal" tabindex="-1" role="dialog" aria-labelledby="remarksModalLabel" aria-hidden="true">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="remarksModalLabel">Enter Remarks for Student</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body" style="max-height: 700px; overflow-y: auto;">
        <form id="remarksForm">
          <!-- Affective Traits -->
          <h2>Development Assessment - Affective Traits (Behaviour)</h2>
          
          <!-- Punctuality -->
          <div class="form-group row">
            <label for="punctuality" class="col-sm-6 col-form-label">Punctuality</label>
            <div class="col-sm-6">
              <input type="number" class="form-control" id="punctuality" name="punctuality" min="0" max="5" required>
            </div>
          </div>
          
          <!-- Attendance -->
          <div class="form-group row">
            <label for="attendance" class="col-sm-6 col-form-label">Attendance In Class</label>
            <div class="col-sm-6">
              <input type="number" class="form-control" id="attendance" name="attendance" min="0" max="5" required>
            </div>
          </div>
          
          <!-- Attentiveness -->
          <div class="form-group row">
            <label for="attentiveness" class="col-sm-6 col-form-label">Attentiveness</label>
            <div class="col-sm-6">
              <input type="number" class="form-control" id="attentiveness" name="attentiveness" min="0" max="5" required>
            </div>
          </div>
          
          <!-- Neatness -->
          <div class="form-group row">
            <label for="neatness" class="col-sm-6 col-form-label">Neatness</label>
            <div class="col-sm-6">
              <input type="number" class="form-control" id="neatness" name="neatness" min="0" max="5" required>
            </div>
          </div>
          
          <!-- Writing -->
          <div class="form-group row">
            <label for="writing" class="col-sm-6 col-form-label">Writing</label>
            <div class="col-sm-6">
              <input type="number" class="form-control" id="writing" name="writing" min="0" max="5" required>
            </div>
          </div>
          
          <!-- Honesty -->
          <div class="form-group row">
            <label for="honesty" class="col-sm-6 col-form-label">Honesty</label>
            <div class="col-sm-6">
              <input type="number" class="form-control" id="honesty" name="honesty" min="0" max="5" required>
            </div>
          </div>

          <!-- Relationship with Staff -->
          <div class="form-group row">
            <label for="honesty" class="col-sm-6 col-form-label">Relationship With Staff</label>
            <div class="col-sm-6">
              <input type="number" class="form-control" id="staff" name="staff" min="0" max="5" required>
            </div>
          </div>

          <!-- Reletionship with Students -->
          <div class="form-group row">
            <label for="honesty" class="col-sm-6 col-form-label">Relationship With Students</label>
            <div class="col-sm-6">
              <input type="number" class="form-control" id="students" name="students" min="0" max="5" required>
            </div>
          </div>

          <!-- Self control -->
          <div class="form-group row">
            <label for="honesty" class="col-sm-6 col-form-label">Self Control</label>
            <div class="col-sm-6">
              <input type="number" class="form-control" id="self_control" name="self_control" min="0" max="5" required>
            </div>
          </div>

           <!-- Spirit of Responsibility -->
           <div class="form-group row">
            <label for="honesty" class="col-sm-6 col-form-label">Spirit of Responsibility</label>
            <div class="col-sm-6">
              <input type="number" class="form-control" id="responsibility" name="responsibility" min="0" max="5" required>
            </div>
          </div>

          <!-- Spirit of Responsibility -->
          <div class="form-group row">
            <label for="honesty" class="col-sm-6 col-form-label">Willingness To Learn</label>
            <div class="col-sm-6">
              <input type="number" class="form-control" id="learn" name="learn" min="0" max="5" required>
            </div>
          </div>

          <!-- Spirit of Responsibility -->
          <div class="form-group row">
            <label for="honesty" class="col-sm-6 col-form-label">Leadership Ability</label>
            <div class="col-sm-6">
              <input type="number" class="form-control" id="leadership" name="leadership" min="0" max="5" required>
            </div>
          </div>

          
          <!-- Initiative -->
          <div class="form-group row">
            <label for="honesty" class="col-sm-6 col-form-label">Initiative</label>
            <div class="col-sm-6">
              <input type="number" class="form-control" id="initiative" name="initiative" min="0" max="5" required>
            </div>
          </div>

          
          <!-- Public Speaking -->
          <div class="form-group row">
            <label for="honesty" class="col-sm-6 col-form-label">Public Speaking</label>
            <div class="col-sm-6">
              <input type="number" class="form-control" id="speaking" name="speaking" min="0" max="5" required>
            </div>
          </div>
          
          <!-- Psychomotor Traits -->
          <h2>Psychomotor Traits (Skills) Ratings</h2>
          
          <!-- Verbal Skills -->
          <div class="form-group row">
            <label for="verbalSkills" class="col-sm-6 col-form-label">Verbal Skills</label>
            <div class="col-sm-6">
              <input type="number" class="form-control" id="verbalSkills" name="verbalSkills" min="0" max="5" required>
            </div>
          </div>
          
          <!-- Participation in Games and Sport -->
          <div class="form-group row">
            <label for="gamesSport" class="col-sm-6 col-form-label">Participation In Games And Sport</label>
            <div class="col-sm-6">
              <input type="number" class="form-control" id="gamesSport" name="gamesSport" min="0" max="5" required>
            </div>
          </div>

          
          <!-- Artistic Creativity -->
          <div class="form-group row">
            <label for="artisticCreativity" class="col-sm-6 col-form-label">Artistic Creativity</label>
            <div class="col-sm-6">
              <input type="number" class="form-control" id="artisticCreativity" name="artisticCreativity" min="0" max="5" required>
            </div>
          </div>

          <!--Status-->
          <div class="form-group row">
            <label for="artisticCreativity" class="col-sm-6 col-form-label">PASS or FAIL<i>(Please use either format)</i></label>
            <div class="col-sm-6">
              <input type="text" class="form-control" id="status" name="status" required>
            </div>
          </div>

          <!-- Teacher Comments -->
          <div class="form-group">
            <label for="comments">Teacher Comments</label>
            <textarea class="form-control" id="comments" name="comments" rows="3" required></textarea>
          </div>


          <input type="hidden" id="studentEmail" name="studentEmail" value="student_email_here">
          <input type="hidden" id="selectedSession" name="session" />
                    <input type="hidden" id="selectedTerm" name="term" />

          <div class="modal-footer">
            <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
            <button type="submit" class="btn btn-primary" onclick="submitRemarks()">Submit</button>
          </div>
        </form>
      </div>
    </div>
  </div>
</div>


<!--Modal for Notification-->
<div class="modal fade" id="myModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="myModalLabel">Notification</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
        <p id="modalMessage"></p>
      </div>
    </div>
  </div>
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
document.addEventListener('DOMContentLoaded', function() {
    var subjectElement = document.getElementById('subject');
    if (subjectElement) {
        subjectElement.addEventListener('change', updateClassDropdown);
    }
});

function populateTable(event) {
    event.preventDefault();

    var session = document.getElementById('session').value;
    var term = document.getElementById('term').value;
    var staffId = '<?php echo $staffid; ?>';

    if (session && term) {
        var xhr = new XMLHttpRequest();
        xhr.open('POST', 'pre-remarks.php', true);
        xhr.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');
        console.log('session:', session, 'term:', term, 'staffid:', staffId);
        xhr.onreadystatechange = function () {
            if (xhr.readyState == 4 && xhr.status == 200) {
                var resultsTableBody = document.getElementById('resultsTableBody');
                var resultsTable = document.getElementById('resultsTable');
                var resultForm = document.getElementById('resultForm');
                if (resultsTableBody) {
                    resultsTableBody.innerHTML = xhr.responseText;
                    
                    // Hide the form, show the table, and show the "Post Result" button
                    if (resultForm) {
                        resultForm.style.display = 'none';
                    }
                    if (resultsTable) {
                        resultsTable.style.display = 'table';
                    }
                }
            }
        };
        xhr.send('session=' + session + '&term=' + term + '&staffid=' + staffId);
    } else {
        alert('Please fill in all fields');
    }
}

function enterRemarks(email, name) {
    // Populate the hidden email field
    document.getElementById('studentEmail').value = email;

    // Update the modal title with the student's name
    document.getElementById('remarksModalLabel').textContent = 'Enter Remarks for ' + name;

    // Set the session and term values in the modal
    document.getElementById('selectedSession').value = document.getElementById('session').value;
    document.getElementById('selectedTerm').value = document.getElementById('term').value;

    // Show the modal
    $('#remarksModal').modal('show');
}




document.getElementById('remarksForm').addEventListener('submit', function(event) {
    event.preventDefault(); // Prevent form submission

    // Create a new FormData object and append form data
    var formData = new FormData(this);

    // Use fetch API to send form data to the PHP script
    fetch('update_remark.php', {
        method: 'POST',
        body: formData
    })
    .then(response => response.json())
    .then(data => {
        var message = data.message || 'An error occurred.';
        if (data.success) {
            // Show success message
            document.getElementById('modalMessage').innerHTML = message;
        } else {
            // Show error message
            document.getElementById('modalMessage').innerHTML = message;
        }
        // Show the notification modal
        $('#myModal').modal('show');
    })
    .catch(error => {
        console.error('Error:', error);
        document.getElementById('modalMessage').innerHTML = "An error occurred while submitting remarks.";
        $('#myModal').modal('show');
    });
});

</script>
    </body>
</html>

<?php } ?>
