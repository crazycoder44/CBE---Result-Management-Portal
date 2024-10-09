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
        <title> SRMS | Enter Result</title>
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
                                    <h2 class="title">Enter Result</h2>
                                
                                </div>
                                
                                <!-- /.col-md-6 text-right -->
                            </div>
                            <!-- /.row -->
                            <div class="row breadcrumb-div">
                                <div class="col-md-6">
                                    <ul class="breadcrumb">
                                        <li><a href="dash.php"><i class="fa fa-home"></i> Home</a></li>
                                
                                        <li class="active">Result Entry</li>
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
                                                        <input id="session" name="session" type="text" placeholder="Enter Session" class="form-control input-md" required>
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
                                                        <label for="default" class="col-sm-2 control-label">Subject</label>
                                                        <div class="col-sm-10">
                                                            <select name="subject" class="form-control" id="subject" onChange="updateClassDropdown()" required="required">
                                                                <option value="">Select Subject</option>
                                                                <?php 
                                                                $sql ="SELECT DISTINCT s.sub_id, s.subject 
                                                                FROM subjects s
                                                                JOIN staff_class_subject scs ON s.sub_id = scs.sub_id
                                                                WHERE scs.staffid = '$staffid'";
                                                                
                                                                $query = $dbh->prepare($sql);
                                                                $query->execute();
                                                                $results=$query->fetchAll(PDO::FETCH_OBJ);
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
                                                        <label for="default" class="col-sm-2 control-label">Class</label>
                                                        <div class="col-sm-10">
                                                            <select name="class" class="form-control" id="class"  required="required">
                                                                <option value="" diasabled selected>Select Class</option>
                                                                <!-- javascript will populate this-->
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
                                                            <th>Student</th>
                                                            <!--<th>Email</th>-->
                                                            <th>Session</th>
                                                            <th>Term</th>
                                                            <!--<th>Subject</th>-->
                                                            <th>Class</th>
                                                            <th>CA</th>
                                                            <th>Exam OBJ</th>
                                                            <th>Exam Theory</th>
                                                            <th>Total</th>
                                                            <th>Grade</th>
                                                        </tr>
                                                    </thead>
                                                    <tbody id="resultsTableBody">

                                                    </tbody>
                                                </table>
                                                </div>
                                                <button type="button" class="btn btn-primary" id="postResultBtn">Post Result</button>

                                                

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
document.addEventListener('DOMContentLoaded', function() {
    var subjectElement = document.getElementById('subject');
    if (subjectElement) {
        subjectElement.addEventListener('change', updateClassDropdown);
    }

    var getObjScoresBtn = document.getElementById('getObjScoresBtn');
    if (getObjScoresBtn) {
        getObjScoresBtn.addEventListener('click', function() {
            var rows = document.querySelectorAll('#resultsTableBody tr');

            rows.forEach(function(row) {
                var sub_id = row.querySelector('.sub').textContent;
                var classid = row.querySelector('.class').textContent;
                var termid = row.querySelector('.term').textContent;
                var session = row.querySelector('.session').textContent;
                var email = row.querySelector('.email').textContent;

                if (sub_id && classid && email && termid && session) {
                    populateExamObjFromHistory(row, sub_id, classid, email, termid, session);
                }
            });
        });
    }

    var postResultBtn = document.getElementById('postResultBtn');
    if (postResultBtn) {
        postResultBtn.addEventListener('click', function() {
            var resultsTableBody = document.getElementById('resultsTableBody');
            if (resultsTableBody) {
                var rows = resultsTableBody.querySelectorAll('tr');

                var students = [];
                rows.forEach(function(row, index) {
                    var student = {};
                    student.sid = row.querySelector('input[name^="sid"]').value;
                    student.examobj = row.querySelector('input[name^="examobj"]').value;
                    student.examtheory = row.querySelector('input[name^="examtheory"]').value;
                    student.ca = row.querySelector('input[name^="ca"]').value;
                    student.total = row.querySelector('td.total').textContent.trim();
                    student.grade = row.querySelector('td.grade').textContent.trim();
                    students.push(student);
                });

                var formData = new FormData();
                formData.append('session', document.getElementById('session').value);
                formData.append('term', document.getElementById('term').value);
                formData.append('sub_id', document.getElementById('subject').value);
                formData.append('class', document.getElementById('class').value);
                formData.append('staffid', '<?php echo $staffid; ?>');
                formData.append('students', JSON.stringify(students));

                fetch('post_results.php', {
                    method: 'POST',
                    body: formData
                })
                .then(response => response.text())
                .then(data => {
                    alert(data);
                    location.reload(); // Reload the page after displaying the alert
                })
                .catch(error => {
                    console.error('Error:', error);
                });
            }
        });
    }
});

function updateClassDropdown() {
    var subjectId = document.getElementById('subject').value;
    var staffId = <?php echo json_encode($staffid); ?>;

    if (subjectId) {
        var xhr = new XMLHttpRequest();
        xhr.open('POST', 'get_classes.php', true);
        xhr.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');
        xhr.onreadystatechange = function () {
            if (xhr.readyState == 4 && xhr.status == 200) {
                var classElement = document.getElementById('class');
                if (classElement) {
                    classElement.innerHTML = xhr.responseText;
                }
            }
        };
        xhr.send('sub_id=' + subjectId + '&staffid=' + staffId);
    } else {
        var classElement = document.getElementById('class');
        if (classElement) {
            classElement.innerHTML = '<option value="" disabled selected>Select Class</option>';
        }
    }
}

function populateTable(event) {
    event.preventDefault();

    var session = document.getElementById('session').value;
    var term = document.getElementById('term').value;
    var subjectId = document.getElementById('subject').value;
    var classId = document.getElementById('class').value;
    var staffId = '<?php echo $staffid; ?>';

    if (session && term && subjectId && classId) {
        var xhr = new XMLHttpRequest();
        xhr.open('POST', 'get_students.php', true);
        xhr.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');
        xhr.onreadystatechange = function () {
            if (xhr.readyState == 4 && xhr.status == 200) {
                var resultsTableBody = document.getElementById('resultsTableBody');
                var resultsTable = document.getElementById('resultsTable');
                var resultForm = document.getElementById('resultForm');
                var postResultBtn = document.getElementById('postResultBtn');
                if (resultsTableBody) {
                    resultsTableBody.innerHTML = xhr.responseText;
                    addEventListenersToInputs();
                    
                    // Hide the form, show the table, and show the "Post Result" button
                    if (resultForm) {
                        resultForm.style.display = 'none';
                    }
                    if (resultsTable) {
                        resultsTable.style.display = 'table';
                    }
                    if (postResultBtn) {
                        postResultBtn.style.display = 'inline-block';
                    }
                }
            }
        };
        xhr.send('session=' + session + '&term=' + term + '&subject=' + subjectId + '&class=' + classId + '&staffid=' + staffId);
    } else {
        alert('Please fill in all fields');
    }
}


function addEventListenersToInputs() {
    var inputs = document.querySelectorAll('input[name^="rt"], input[name^="hass"], input[name^="ass1"], input[name^="ass2"], input[name^="cl1"], input[name^="cl2"], input[name^="cl3"], input[name^="mtt"], input[name^="nt1"], input[name^="nt2"], input[name^="nt3"], input[name^="proj"], input[name^="examobj"], input[name^="examtheory"]');
    inputs.forEach(function(input) {
        input.addEventListener('input', updateTable);
        input.addEventListener('input', function (event) {
                const maxValue = parseInt(event.target.max, 10);
                if (parseInt(event.target.value, 10) > maxValue) {
                    alert(`The value entered exceeds the maximum allowed value of ${maxValue}`);
                    event.target.value = maxValue;
                }
            });
    });
}

function updateTable() {
    var row = this.closest('tr');
    if (row) {
        // Get values
        var ca = parseFloat(row.querySelector('input[name^="ca"]').value) || 0;
        var examobj = parseFloat(row.querySelector('input[name^="examobj"]').value) || 0;
        var examtheory = parseFloat(row.querySelector('input[name^="examtheory"]').value) || 0;


        // Calculate total
        var total = (ca + examobj + examtheory).toFixed(1);
        row.querySelector('.total').textContent = total;

        // Update grade
        var gradeCell = row.querySelector('.grade');
        if (gradeCell) {
            var classValue = document.getElementById('class').value.toLowerCase();

            var grade;

            if (classValue.includes('jss1') || classValue.includes('jss2') || classValue.includes('jss3')) {
                if (total >= 80) {
                    grade = 'A';
                    gradeCell.style.color = 'green';
                } else if (total >= 70) {
                    grade = 'B';
                    gradeCell.style.color = 'green';
                } else if (total >= 60) {
                    grade = 'C';
                    gradeCell.style.color = 'green';
                } else if (total >= 50) {
                    grade = 'P';
                    gradeCell.style.color = 'black';
                } else {
                    grade = 'F';
                    gradeCell.style.color = 'red';
                }
            } else if (classValue.includes('ss1') || classValue.includes('ss2') || classValue.includes('ss3')) {
                if (total >= 85) {
                    grade = 'A1';
                    gradeCell.style.color = 'green';
                } else if (total >= 80) {
                    grade = 'B2';
                    gradeCell.style.color = 'green';
                } else if (total >= 75) {
                    grade = 'B3';
                    gradeCell.style.color = 'green';
                } else if (total >= 70) {
                    grade = 'C4';
                    gradeCell.style.color = 'green';
                } else if (total >= 65) {
                    grade = 'C5';
                    gradeCell.style.color = 'green';
                } else if (total >= 60) {
                    grade = 'C6';
                    gradeCell.style.color = 'green';
                } else if (total >= 55) {
                    grade = 'D7';
                    gradeCell.style.color = 'black';
                } else if (total >= 50) {
                    grade = 'E8';
                    gradeCell.style.color = 'black';
                } else {
                    grade = 'F9';
                    gradeCell.style.color = 'red';
                }
            }

            gradeCell.textContent = grade;
        }
    }
}
</script>
    </body>
</html>

<?php } ?>

