<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
<meta name="viewport" content="width=device-width, initial-scale=1">
<title><?php
include_once 'dbConnection.php';
session_start();
                if(isset($_SESSION['fname']))
                {
                  echo $_SESSION['fname'];
                }
                else
                {
                  echo '|| Dominican Portal';
                }
              ?> || Staff Portal </title>
<link  rel="stylesheet" href="css/bootstrap.min.css"/>
 <link  rel="stylesheet" href="css/bootstrap-theme.min.css"/>
 <link rel="stylesheet" href="css/main.css">
 <link  rel="icon" href="image/logo.png">
 <link  rel="stylesheet" href="css/font.css">
 <script src="js/jquery.js" type="text/javascript"></script>
 <script src="https://cdn.ckeditor.com/4.16.2/standard/ckeditor.js"></script>

  <script src="js/bootstrap.min.js"  type="text/javascript"></script>
 	<link href='http://fonts.googleapis.com/css?family=Roboto:400,700,300' rel='stylesheet' type='text/css'>

<script>
$(function () {
    $(document).on( 'scroll', function(){
        console.log('scroll top : ' + $(window).scrollTop());
        if($(window).scrollTop()>=$(".logo").height())
        {
             $(".navbar").addClass("navbar-fixed-top");
        }

        if($(window).scrollTop()<$(".logo").height())
        {
             $(".navbar").removeClass("navbar-fixed-top");
        }
    });
});</script>
</head>

<body  style="background:#eee;">
<div class="header">
<div class="row">
<div class="col-lg-6">
<span class="logo">Staff Portal</span></div>
<?php
 include_once 'dbConnection.php';
//$email=$_SESSION['email'];
  if(!(isset($_SESSION['email']))){
header("location:index.php");

}
else
{
$fname = $_SESSION['fname'];
$email=$_SESSION['email'];
$staffid = $_SESSION['staffid'];

include_once 'dbConnection.php';
echo '<span class="pull-right top title1" ><span class="log1"><span class="glyphicon glyphicon-user" aria-hidden="true"></span>&nbsp;&nbsp;<b>Hello,</b></span>
<a href="dash.php" class="log log1" title="You Are Logged In As '.$fname.'"><b>'.$fname.'</b></a>
|&nbsp;<a href="logout.php?q=account.php" class="log"><span class="glyphicon glyphicon-log-out" aria-hidden="true"></span>&nbsp;<b>Log Out</b></button></a></span>';
}
?>

</div></div>
<!-- admin start-->
<!--navigation menu-->
<nav class="navbar navbar-default title1">
  <div class="container-fluid">
    <!-- Brand and toggle get grouped for better mobile display -->
    <div class="navbar-header">
      <button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#bs-example-navbar-collapse-1" aria-expanded="false">
        <span class="sr-only">Toggle navigation</span>
        <span class="icon-bar"></span>
        <span class="icon-bar"></span>
        <span class="icon-bar"></span>
      </button>
      <a class="navbar-brand" href="dash.php?q=0"><b>Dashboard</b></a>
    </div>
    <!-- Collect the nav links, forms, and other content for toggling -->
    <div class="collapse navbar-collapse" id="bs-example-navbar-collapse-1">
      <ul class="nav navbar-nav">
        <li <?php if(@$_GET['q']==0) echo'class="active"'; ?>><a href="dash.php?q=0">Home<span class="sr-only">(current)</span></a></li>
        <li <?php if(@$_GET['q']==1) echo'class="active"'; ?>><a href="dash.php?q=1">My Students</a></li>
        <li <?php if(@$_GET['q']==7) echo'class="active"'; ?>><a href="dash.php?q=7">My Class Students</a></li>
		<!-- <li <?/*php if(@$_GET['q']==2) echo'class="active"'; */?>><a href="dash.php?q=2">Ranking</a></li> -->
		<!-- <li <?/*php if(@$_GET['q']==3) echo'class="active"'; */?>><a href="dash.php?q=3">Feedback</a></li> -->
        <li class="dropdown <?php if(@$_GET['q']==4 || @$_GET['q']==5) echo'active"'; ?>">
          <a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-haspopup="true" aria-expanded="false">Exams<span class="caret"></span></a>
          <ul class="dropdown-menu">
            <li><a href="dash.php?q=4">Add Exam</a></li>
            <li><a href="dash.php?q=5">Remove Exam</a></li>


          </ul>
        </li>
        <li class="dropdown <?php if(@$_GET['q']==2 || @$_GET['q']==3) echo'active"'; ?>">
          <a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-haspopup="true" aria-expanded="false">Results<span class="caret"></span></a>
          <ul class="dropdown-menu">
            <li><a href="dash.php?q=2">Enter Result</a></li>
            <li><a href="dash.php?q=3">View Result</a></li>
            <li><a href="dash.php?q=6">Broadsheet</a></li>
            <li><a href="term.php?q=6">Download Term Broadsheet</a></li>
            <li><a href="cummulative.php?q=6">Download Cummulative Broadsheet</a></li>
            <li><a href="remark.php?q=8">Remarks and Comments</a></li>

          </ul>
        </li>
        <li class="pull-right"> <a href="logout.php?q=account.php"><span class="glyphicon glyphicon-log-out" aria-hidden="true"></span>&nbsp;Signout</a></li>

      </ul>
          </div><!-- /.navbar-collapse -->
  </div><!-- /.container-fluid -->
</nav>
<!--navigation menu closed-->
<div class="container"><!--container start-->
<div class="row">
<div class="col-md-12">
  <!--Default Page-->
  <?php if(@$_GET['q']==0.5) {
    echo '<div class="panel"><center><h1 class="title" style="color:#660033">Welcome Back, '.$fname.'!!</h1><center><br /></div>';
    }?>
    <!--Default Page Ends-->
<!--home start-->

<?php 
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

if (@$_GET['q'] == 0) {
    $staff_id = $_SESSION['staffid']; // Retrieve staff ID from session

    // Query to fetch exams with subject and term details
    $query = "SELECT e.eid, s.subject AS subject, c.class AS class, e.tnoq AS total_questions, e.sahi AS mark, 
                     e.timelimit AS time_limit, t.term AS term, e.session, e.date
              FROM exams e 
              INNER JOIN staff_class_subject scs ON e.sub_id = scs.sub_id AND e.classid = scs.classid 
              INNER JOIN subjects s ON e.sub_id = s.sub_id
              INNER JOIN class c ON e.classid = c.classid
              INNER JOIN terms t ON e.termid = t.termid
              WHERE scs.staffid = '$staff_id' 
              ORDER BY e.date DESC";

    $result = mysqli_query($con, $query) or die('Error fetching exams');

    echo '<div class="panel"><div class="table-responsive"><table class="table table-striped title1">
          <tr><td><b>S.N.</b></td><td><b>Subject</b></td><td><b>Class</b></td><td><b>Total Questions</b></td><td><b>Mark</b></td><td><b>Time Limit</b></td><td><b>Term</b></td><td><b>Session</b></td><td><b>Exam Date</b></td></tr>';

    $c = 1;

    while ($row = mysqli_fetch_array($result)) {
        $subject = $row['subject'];
        $class = $row['class'];
        $total_questions = $row['total_questions'];
        $mark = $row['mark'];
        $time_limit = $row['time_limit'];
        $term = $row['term'];
        $session = $row['session'];
        $exam_date = $row['date'];

        // Display exam information
        echo '<tr><td>' . $c++ . '</td><td>' . $subject . '</td><td>' . $class . '</td><td>' . $total_questions . '</td><td>' . $mark . '</td><td>' . $time_limit . '&nbsp;min</td><td>' . $term . '</td><td>' . $session . '</td><td>' . $exam_date . '</td></tr>';
    }

    $c = 0;
    echo '</table></div></div>';
}

//enter result start
if (@$_GET['q'] == 2) {
    echo '<div class="panel title"><div class="table-responsive">';
    echo '<form id="resultForm" class="form-horizontal">';

    // Enter Session Input
    echo '<div class="form-group">';
    echo '<label class="col-md-4 control-label" for="session">Enter Session:</label>';
    echo '<div class="col-md-4">';
    echo '<input id="session" name="session" type="text" placeholder="Enter Session" class="form-control input-md" required>';
    echo '</div>';
    echo '</div>';

    // Select Term Dropdown
    echo '<div class="form-group">';
    echo '<label class="col-md-4 control-label" for="term">Select Term:</label>';
    echo '<div class="col-md-4">';
    echo '<select id="term" name="term" class="form-control" required>';
    echo '<option value="" disabled selected>Select Term</option>';

    $termQuery = mysqli_query($con, "SELECT termid, term FROM terms");
    while ($termRow = mysqli_fetch_array($termQuery)) {
        $termid = $termRow['termid'];
        $term = $termRow['term'];
        echo '<option value="' . $termid . '">' . $term . '</option>';
    }

    echo '</select>';
    echo '</div>';
    echo '</div>';

    // Select Subject Dropdown
    echo '<div class="form-group">';
    echo '<label class="col-md-4 control-label" for="subject">Select Subject:</label>';
    echo '<div class="col-md-4">';
    echo '<select id="subject" name="subject" class="form-control" required onchange="updateClassDropdown()">';
    echo '<option value="" disabled selected>Select Subject</option>';

    $subjectQuery = mysqli_query($con, "
        SELECT DISTINCT s.sub_id, s.subject 
        FROM subjects s
        JOIN staff_class_subject scs ON s.sub_id = scs.sub_id
        WHERE scs.staffid = '$staffid'
    ");
    while ($subjectRow = mysqli_fetch_array($subjectQuery)) {
        $sub_id = $subjectRow['sub_id'];
        $subject = $subjectRow['subject'];
        echo '<option value="' . $sub_id . '">' . $subject . '</option>';
    }

    echo '</select>';
    echo '</div>';
    echo '</div>';

    // Select Class Dropdown
    echo '<div class="form-group">';
    echo '<label class="col-md-4 control-label" for="class">Select Class:</label>';
    echo '<div class="col-md-4">';
    echo '<select id="class" name="class" class="form-control" required>';
    echo '<option value="" disabled selected>Select Class</option>';
    // This will be populated dynamically using JavaScript
    echo '</select>';
    echo '</div>';
    echo '</div>';

    // Submit Button
    echo '<div class="form-group">';
    echo '<label class="col-md-4 control-label" for="submit"></label>';
    echo '<div class="col-md-4">';
    echo '<button id="submit" name="submit" class="btn btn-primary" onclick="populateTable(event)">Submit</button>';
    echo '</div>';
    echo '</div>';

    echo '</form>';
    echo '</div></div>';

    // Add the div with the table
    echo '<div class="panel title"><div class="table-responsive" id="resultsDiv">';
    echo '<table class="table table-striped title1">';
    echo '<thead>';
    echo '<tr>';
    echo '<th>S/N</th>';
    echo '<th>Student</th>';
    echo '<th>Email</th>';
    echo '<th>Session</th>';
    echo '<th>Term</th>';
    echo '<th>Sub</th>';
    echo '<th>Class</th>';
    echo '<th>R.T</th>';
    echo '<th>H. ASS</th>';
    echo '<th>ASS1</th>';
    echo '<th>ASS2</th>';
    echo '<th>ASS AVG</th>';
    echo '<th>CL1</th>';
    echo '<th>CL2</th>';
    echo '<th>CL3</th>';
    echo '<th>CL AVG</th>';
    echo '<th>M.T.T</th>';
    echo '<th>N.T1</th>';
    echo '<th>N.T2</th>';
    echo '<th>N.T3</th>';
    echo '<th>N.T AVG</th>';
    echo '<th>PROJ</th>';
    echo '<th>CA</th>';
    echo '<th>Exam OBJ</th>';
    echo '<th>Exam Theory</th>';
    echo '<th>Total</th>';
    echo '<th>Grade</th>';
    echo '</tr>';
    echo '</thead>';
    echo '<tbody id="resultsTableBody">';
    echo '</tbody>';
    echo '</table>';
    echo '</div>';

    echo '<button type="button" class="btn btn-primary" id="postResultBtn">Post Result</button>';
    echo '</div>';
}
?>

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
                    student.rt = row.querySelector('input[name^="rt"]').value;
                    student.hass = row.querySelector('input[name^="hass"]').value;
                    student.ass1 = row.querySelector('input[name^="ass1"]').value;
                    student.ass2 = row.querySelector('input[name^="ass2"]').value;
                    student.cl1 = row.querySelector('input[name^="cl1"]').value;
                    student.cl2 = row.querySelector('input[name^="cl2"]').value;
                    student.cl3 = row.querySelector('input[name^="cl3"]').value;
                    student.mtt = row.querySelector('input[name^="mtt"]').value;
                    student.nt1 = row.querySelector('input[name^="nt1"]').value;
                    student.nt2 = row.querySelector('input[name^="nt2"]').value;
                    student.nt3 = row.querySelector('input[name^="nt3"]').value;
                    student.proj = row.querySelector('input[name^="proj"]').value;
                    student.examobj = row.querySelector('input[name^="examobj"]').value;
                    student.examtheory = row.querySelector('input[name^="examtheory"]').value;
                    student.ca = row.querySelector('input[name^="ca"]').value;
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
    var subjectId = document.getElementById('subject').value; // Updated variable name
    var classId = document.getElementById('class').value; // Updated variable name
    var staffId = '<?php echo $staffid; ?>';

    if (session && term && subjectId && classId) {
        var xhr = new XMLHttpRequest();
        xhr.open('POST', 'get_students.php', true);
        xhr.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');
        xhr.onreadystatechange = function () {
            if (xhr.readyState == 4 && xhr.status == 200) {
                var resultsTableBody = document.getElementById('resultsTableBody');
                if (resultsTableBody) {
                    resultsTableBody.innerHTML = xhr.responseText;
                    addEventListenersToInputs(); // Add event listeners to new inputs
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
        var rt = parseFloat(row.querySelector('input[name^="rt"]').value) || 0;
        var hass = parseFloat(row.querySelector('input[name^="hass"]').value) || 0;
        var ass1 = parseFloat(row.querySelector('input[name^="ass1"]').value) || 0;
        var ass2 = parseFloat(row.querySelector('input[name^="ass2"]').value) || 0;
        var cl1 = parseFloat(row.querySelector('input[name^="cl1"]').value) || 0;
        var cl2 = parseFloat(row.querySelector('input[name^="cl2"]').value) || 0;
        var cl3 = parseFloat(row.querySelector('input[name^="cl3"]').value) || 0;
        var mtt = parseFloat(row.querySelector('input[name^="mtt"]').value) || 0;
        var nt1 = parseFloat(row.querySelector('input[name^="nt1"]').value) || 0;
        var nt2 = parseFloat(row.querySelector('input[name^="nt2"]').value) || 0;
        var nt3 = parseFloat(row.querySelector('input[name^="nt3"]').value) || 0;
        var proj = parseFloat(row.querySelector('input[name^="proj"]').value) || 0;
        var examobj = parseFloat(row.querySelector('input[name^="examobj"]').value) || 0;
        var examtheory = parseFloat(row.querySelector('input[name^="examtheory"]').value) || 0;

        // Calculate averages
        var assAvg = ((hass + ass1 + ass2) / 3).toFixed(1);
        var clAvg = ((cl1 + cl2 + cl3) / 3).toFixed(1);
        var ntAvg = ((nt1 + nt2 + nt3) / 3).toFixed(1);

        // Update average cells
        row.querySelector('.ass-avg').textContent = assAvg;
        row.querySelector('.cl-avg').textContent = clAvg;
        row.querySelector('.nt-avg').textContent = ntAvg;

        // Calculate CA
        var ca = (rt + parseFloat(assAvg) + parseFloat(clAvg) + mtt + parseFloat(ntAvg) + proj).toFixed(1);
        if (ca > 40) {
            alert('The sum of R.T, ASS AVG, CL AVG, M.T.T, N.T AVG, and PROJ must not exceed 40.');
            row.querySelector('input[name^="ca"]').value = '';
            return;
        }

        // Update CA cell
        row.querySelector('input[name^="ca"]').value = ca;

        // Calculate total
        var total = (parseFloat(ca) + examobj + examtheory).toFixed(1);
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
<script>
    setInterval(function() {
    // Make a simple request to the server to keep the session alive
    fetch('keep_alive.php');
}, 600000); // 600,000 milliseconds = 10 minutes
</script>


<!--home closed-->
<!--users start-->
<?php 
if (@$_GET['q'] == 1) {
    // Get the current staffid from the session
    $staffid = $_SESSION['staffid'];

    // Define the query to select student information based on the given conditions
    $query = "
        SELECT DISTINCT s.sid, s.fname, s.lname, s.gender, s.classid, s.email, s.mobile
        FROM students s
        WHERE s.classid IN (
            SELECT DISTINCT stcs.classid
            FROM staff_class_subject stcs
            WHERE stcs.staffid = '$staffid'
        )
        AND (
            EXISTS (
                SELECT 1
                FROM student_class_subject scs
                JOIN staff_class_subject stcs ON scs.sub_id = stcs.sub_id
                WHERE scs.sid = s.sid AND stcs.staffid = '$staffid'
            )
            OR NOT EXISTS (
                SELECT 1
                FROM student_class_subject scs
                JOIN staff_class_subject stcs ON scs.sub_id = stcs.sub_id
                WHERE stcs.staffid = '$staffid'
            )
        )
    ";

    // Execute the query
    $result = mysqli_query($con, $query) or die('Error: ' . mysqli_error($con));

    // Output the table structure
    echo  '<div class="panel"><div><table class="table table-striped title1">
    <tr><td><b>S.N.</b></td><td><b>First Name</b></td><td><b>Last Name</b></td><td><b>Gender</b></td><td><b>Class</b></td><td><b>Email</b></td><td><b>Mobile</b></td><td></td></tr>';
    
    // Initialize the counter
    $c = 1;
    
    // Fetch and display each row of the result
    while ($row = mysqli_fetch_array($result)) {
        $fname = $row['fname'];
        $lname = $row['lname'];
        $mob = $row['mobile'];
        $gender = $row['gender'];
        $email = $row['email'];
        $classid = $row['classid'];
        
        echo '<tr><td>' . $c++ . '</td><td>' . $fname . '</td><td>' . $lname . '</td><td>' . $gender . '</td><td>' . $classid . '</td><td>' . $email . '</td><td>' . $mob . '</td></tr>';
    }
    
    // Close the table structure
    echo '</table></div></div>';
}
?>

<?php 
if (@$_GET['q'] == 7) {
    // Get the current staffid from the session
    $staffid = $_SESSION['staffid'];

    // Define the query to check if the staff is associated with any class in the class table
    $class_query = "
        SELECT classid 
        FROM class 
        WHERE staffid = '$staffid'
    ";

    // Execute the class query
    $class_result = mysqli_query($con, $class_query) or die('Error: ' . mysqli_error($con));

    // Check if the staff is associated with any class
    if (mysqli_num_rows($class_result) > 0) {
        // Fetch the classid associated with the staffid
        $class_row = mysqli_fetch_assoc($class_result);
        $classid = $class_row['classid'];

        // Define the query to select student information based on the given conditions
        $query = "
            SELECT DISTINCT s.sid, s.fname, s.lname, s.gender, s.classid, s.email, s.mobile
            FROM students s
            WHERE s.classid = '$classid'
        ";

        // Execute the query
        $result = mysqli_query($con, $query) or die('Error: ' . mysqli_error($con));

        // Output the table structure
        echo '<div class="panel"><div><table class="table table-striped title1">
        <tr><td><b>S.N.</b></td><td><b>Student ID</b></td><td><b>First Name</b></td><td><b>Last Name</b></td><td><b>Gender</b></td><td><b>Class</b></td><td><b>Email</b></td><td><b>Mobile</b></td><td></td></tr>';
        
        // Initialize the counter
        $c = 1;
        
        // Fetch and display each row of the result
        while ($row = mysqli_fetch_array($result)) {
            $sid = $row['sid'];
            $fname = $row['fname'];
            $lname = $row['lname'];
            $mob = $row['mobile'];
            $gender = $row['gender'];
            $email = $row['email'];
            $classid = $row['classid'];
            
            echo '<tr><td>' . $c++ . '</td><td>' . $sid . '</td><td>' . $fname . '</td><td>' . $lname . '</td><td>' . $gender . '</td><td>' . $classid . '</td><td>' . $email . '</td><td>' . $mob . '</td></tr>';
        }
        
        // Close the table structure
        echo '</table></div></div>';
    } else {
        // Display the message if the staff is not associated with any class
        echo '<div class="panel title"><h2>Only Class Guardians can view class students</h2></div>';
    }
}
?>
<!--user end-->

<!--view result start-->
<?php 
if(@$_GET['q']==3) {
    echo '<div class="panel title"><div class="table-responsive">';
    echo '<form id="resultForm" class="form-horizontal" method="POST" action="">';

    // Enter Session Input
    echo '<div class="form-group">';
    echo '<label class="col-md-4 control-label" for="session">Enter Session:</label>';
    echo '<div class="col-md-4">';
    echo '<input id="session" name="session" type="text" placeholder="Enter Session" class="form-control input-md" required>';
    echo '</div>';
    echo '</div>';

    // Select Term Dropdown
    echo '<div class="form-group">';
    echo '<label class="col-md-4 control-label" for="term">Select Term:</label>';
    echo '<div class="col-md-4">';
    echo '<select id="term" name="term" class="form-control" required>';
    echo '<option value="" disabled selected>Select Term</option>';

    $termQuery = mysqli_query($con, "SELECT termid, term FROM terms");
    while ($termRow = mysqli_fetch_array($termQuery)) {
        $termid = $termRow['termid'];
        $term = $termRow['term'];
        echo '<option value="' . $termid . '">' . $term . '</option>';
    }

    echo '</select>';
    echo '</div>';
    echo '</div>';

    // Select Subject Dropdown
    echo '<div class="form-group">';
    echo '<label class="col-md-4 control-label" for="subject">Select Subject:</label>';
    echo '<div class="col-md-4">';
    echo '<select id="subject" name="subject" class="form-control" required onchange="updateClassDropdown()">';
    echo '<option value="" disabled selected>Select Subject</option>';

    $subjectQuery = mysqli_query($con, "
        SELECT DISTINCT s.sub_id, s.subject 
        FROM subjects s
        JOIN staff_class_subject scs ON s.sub_id = scs.sub_id
        WHERE scs.staffid = '$staffid'
    ");
    while ($subjectRow = mysqli_fetch_array($subjectQuery)) {
        $sub_id = $subjectRow['sub_id'];
        $subject = $subjectRow['subject'];
        echo '<option value="' . $sub_id . '">' . $subject . '</option>';
    }

    echo '</select>';
    echo '</div>';
    echo '</div>';

    // Select Class Dropdown
    echo '<div class="form-group">';
    echo '<label class="col-md-4 control-label" for="class">Select Class:</label>';
    echo '<div class="col-md-4">';
    echo '<select id="class" name="class" class="form-control" required>';
    echo '<option value="" disabled selected>Select Class</option>';
    // This will be populated dynamically using JavaScript
    echo '</select>';
    echo '</div>';
    echo '</div>';

    // Submit Button
    echo '<div class="form-group">';
    echo '<label class="col-md-4 control-label" for="submit"></label>';
    echo '<div class="col-md-4">';
    echo '<button id="submit" name="submit" class="btn btn-primary">Submit</button>';
    echo '</div>';
    echo '</div>';

    echo '</form>';
    echo '</div></div>';

    // Table div
    $tableVisible = ($_SERVER['REQUEST_METHOD'] == 'POST') ? '' : 'style="display:none;"';
    echo '<div id="resultTable" class="panel title" ' . $tableVisible . '><div class="table-responsive">';
    echo '<table class="table table-bordered">';
    echo '<thead>';
    echo '<tr>';
    echo '<th>S/N</th>';
    echo '<th>Student ID</th>';
    echo '<th>Student Name</th>';
    echo '<th>CA</th>';
    echo '<th>Exam Obj</th>';
    echo '<th>Exam Theory</th>';
    echo '<th>Total</th>';
    echo '<th>Grade</th>';
    echo '<th>Remark</th>';
    echo '<th>Position</th>';
    echo '</tr>';
    echo '</thead>';
    echo '<tbody>';

    if ($_SERVER['REQUEST_METHOD'] == 'POST') {
        $session = mysqli_real_escape_string($con, $_POST['session']);
        $term = mysqli_real_escape_string($con, $_POST['term']);
        $sub_id = mysqli_real_escape_string($con, $_POST['subject']);
        $class = mysqli_real_escape_string($con, $_POST['class']);
        //$staffid = mysqli_real_escape_string($con, $_POST['staffid']);
        
        // Define baseclass variable
        $baseclass = substr($class, 0, -1); // Remove the last character from the classid

        $resultQuery = mysqli_query($con, "
            SELECT DISTINCT s.sid, r.email, r.ca, r.examobj, r.examtheory, s.fname, s.lname 
            FROM results r
            JOIN students s ON r.email = s.email
            JOIN student_class_subject scs ON s.sid = scs.sid
            WHERE r.session = '$session' 
            AND r.termid = '$term' 
            AND r.sub_id = '$sub_id'
            AND scs.classid = '$class'
        ");

        $results = [];
        while ($row = mysqli_fetch_assoc($resultQuery)) {
            $row['total'] = $row['ca'] + $row['examobj'] + $row['examtheory'];
            $results[] = $row;
        }

        // Sort results by total in descending order
        usort($results, function($a, $b) {
            return $b['total'] - $a['total'];
        });

        // Populate table rows
        $sn = 1;
        foreach ($results as $row) {
            $sid = $row['sid'];
            $studentName = $row['fname'] . ' ' . $row['lname'];
            $ca = $row['ca'];
            $examobj = $row['examobj'];
            $examtheory = $row['examtheory'];
            $total = $row['total'];
            $grade = '';
            $gradeColor = '';
            $remark = '';
            $remarkColor = '';
            $position = $sn . 'th'; // Default position

            // Determine grade and remark
            if (strpos($class, 'jss1') !== false || strpos($class, 'jss2') !== false || strpos($class, 'jss3') !== false) {
                if ($total >= 80) {
                    $grade = 'A';
                    $gradeColor = 'green';
                    $remark = 'Distinction';
                    $remarkColor = 'green';
                } elseif ($total >= 70) {
                    $grade = 'B';
                    $gradeColor = 'green';
                    $remark = 'V. Good';
                    $remarkColor = 'green';
                } elseif ($total >= 60) {
                    $grade = 'C';
                    $gradeColor = 'green';
                    $remark = 'Credit';
                    $remarkColor = 'green';
                } elseif ($total >= 50) {
                    $grade = 'P';
                    $gradeColor = 'black';
                    $remark = 'Pass';
                    $remarkColor = 'black';
                } else {
                    $grade = 'F';
                    $gradeColor = 'red';
                    $remark = 'Fail';
                    $remarkColor = 'red';
                }
            } elseif (strpos($class, 'ss1') !== false || strpos($class, 'ss2') !== false || strpos($class, 'ss3') !== false) {
                if ($total >= 85) {
                    $grade = 'A1';
                    $gradeColor = 'green';
                    $remark = 'Alpha';
                    $remarkColor = 'green';
                } elseif ($total >= 80) {
                    $grade = 'B2';
                    $gradeColor = 'green';
                    $remark = 'V. Good';
                    $remarkColor = 'green';
                } elseif ($total >= 75) {
                    $grade = 'B3';
                    $gradeColor = 'green';
                    $remark = 'Good';
                    $remarkColor = 'green';
                } elseif ($total >= 70) {
                    $grade = 'C4';
                    $gradeColor = 'green';
                    $remark = 'Credit';
                    $remarkColor = 'green';
                } elseif ($total >= 65) {
                    $grade = 'C5';
                    $gradeColor = 'green';
                    $remark = 'Credit';
                    $remarkColor = 'green';
                } elseif ($total >= 60) {
                    $grade = 'C6';
                    $gradeColor = 'green';
                    $remark = 'Credit';
                    $remarkColor = 'green';
                } elseif ($total >= 55) {
                    $grade = 'D7';
                    $gradeColor = 'black';
                    $remark = 'Pass';
                    $remarkColor = 'black';
                } elseif ($total >= 50) {
                    $grade = 'E8';
                    $gradeColor = 'black';
                    $remark = 'Pass';
                    $remarkColor = 'black';
                } else {
                    $grade = 'F9';
                    $gradeColor = 'red';
                    $remark = 'Fail';
                    $remarkColor = 'red';
                }
            }

            // Determine position suffix
            if ($sn == 1) {
                $position = '1st';
            } elseif ($sn == 2) {
                $position = '2nd';
            } elseif ($sn == 3) {
                $position = '3rd';
            } else {
                $position = $sn . 'th';
            }

            echo '<tr>';
            echo '<td>' . $sn . '</td>';
            echo '<td>' . $sid . '</td>';
            echo '<td>' . $studentName . '</td>';
            echo '<td>' . $ca . '</td>';
            echo '<td>' . $examobj . '</td>';
            echo '<td>' . $examtheory . '</td>';
            echo '<td>' . $total . '</td>';
            echo '<td style="color: ' . $gradeColor . ';">' . $grade . '</td>';
            echo '<td style="color: ' . $remarkColor . ';">' . $remark . '</td>';
            echo '<td>' . $position . '</td>';
            echo '</tr>';

            $sn++;
        }
    }

    echo '</tbody>';
    echo '</table>';
    echo '</div></div>';
}
?>
<script>
document.getElementById('resultForm').onsubmit = function() {
    document.getElementById('resultTable').style.display = 'block';
};
</script>




<!--view result closed-->

<!-- view broadsheet start -->
<?php 
if(@$_GET['q']==6) {
    // Display Broadsheet Form
echo '<div class="panel title">';
echo '<form id="broadsheetForm" class="form-horizontal" method="GET" action="">';
echo '<input type="hidden" name="q" value="broadsheet">';

// Select Session Dropdown
echo '<div class="form-group">';
echo '<label class="col-md-4 control-label" for="session">Select Session:</label>';
echo '<div class="col-md-4">';
echo '<select id="session" name="session" class="form-control" required>';
echo '<option value="" disabled selected>Select Session</option>';

// Fetch distinct sessions from the exams table
$session_query = mysqli_query($con, "SELECT DISTINCT session FROM results ORDER BY session");
while ($row = mysqli_fetch_array($session_query)) {
    $session = $row['session'];
    echo '<option value="'.$session.'">'.$session.'</option>';
}

echo '</select>';
echo '</div>';
echo '</div>';

// Select Term Dropdown
echo '<div class="form-group">';
echo '<label class="col-md-4 control-label" for="term">Select Term:</label>';
echo '<div class="col-md-4">';
echo '<select id="term" name="term" class="form-control" required>';
echo '<option value="" disabled selected>Select Term</option>';

$termQuery = mysqli_query($con, "SELECT termid, term FROM terms");
while ($termRow = mysqli_fetch_array($termQuery)) {
    $termid = $termRow['termid'];
    $term = $termRow['term'];
    echo '<option value="' . $termid . '">' . $term . '</option>';
}

echo '</select>';
echo '</div>';
echo '</div>';

// Submit Button
echo '<div class="form-group">';
echo '<label class="col-md-4 control-label" for="submit"></label>';
echo '<div class="col-md-4">';
echo '<button id="submit" name="submit" class="btn btn-primary">Generate Broadsheet</button>';
echo '</div>';
echo '</div>';

echo '</form>';
echo '</div>';
}

// Check if form is submitted
if (isset($_GET['q']) && $_GET['q'] == 'broadsheet' && isset($_GET['session']) && isset($_GET['term'])) {
    // Process form data
    $sessionvalue = $_GET['session'];
    $termvalue = $_GET['term'];

    // Check if session and term are valid (you may add more validation as needed)
    if (!empty($sessionvalue) && !empty($termvalue)) {
        // Assuming you have already connected to your database using $con

        // Get the staff ID from session (replace with your actual way of getting staff ID)
        $staffid = $_SESSION['staffid'];

        // Check if the staff is a class guardian and fetch associated classid
        $classQuery = mysqli_query($con, "
            SELECT DISTINCT classid 
            FROM class 
            WHERE staffid = '$staffid'
        ");

        // Fetch the term name
        $termQuery = mysqli_query($con, "
            SELECT term 
            FROM terms 
            WHERE termid = '$termvalue'
        ");

        if (mysqli_num_rows($termQuery) > 0) {
            $termRow = mysqli_fetch_assoc($termQuery);
            $termname = $termRow['term'];
        } else {
            $termname = 'Unknown Term';
        }

        if (mysqli_num_rows($classQuery) > 0) {
            // Staff is a class guardian, proceed to generate broadsheet
            $classRow = mysqli_fetch_assoc($classQuery);
            $classid = $classRow['classid'];
            $baseclassid = substr($classid, 0, -1);
            $class = $baseclassid;

            // Fetch distinct subjects associated with the class
            $subject_query = mysqli_query($con, "
                SELECT DISTINCT s.sub_id, s.subject 
                FROM subjects s
                INNER JOIN class_subject cs ON s.sub_id = cs.sub_id
                WHERE cs.classid LIKE '$baseclassid%'
            ");

            $subjects = [];
            while ($row = mysqli_fetch_assoc($subject_query)) {
                $sub_id = $row['sub_id'];
                $subject_name = $row['subject'];
                $subjects[$sub_id] = $subject_name;
            }

            // Fetch students based on selected term and session
            $student_query = mysqli_query($con, "
                SELECT s.email, s.sid, s.fname, s.lname, r.sub_id
                FROM students s
                LEFT JOIN results r ON s.email = r.email AND r.session = '$sessionvalue' AND r.termid = '$termvalue'
                WHERE s.sid IN (
                    SELECT DISTINCT s.sid
                    FROM students s
                    INNER JOIN results r ON s.email = r.email
                    WHERE r.classid LIKE '$class%'
                    AND r.session = '$sessionvalue'
                    AND r.termid = '$termvalue'
                )
            ");

            $arm_student_query = mysqli_query($con, "
                SELECT s.email, s.sid, s.fname, s.lname, r.sub_id
                FROM students s
                LEFT JOIN results r ON s.email = r.email AND r.session = '$sessionvalue' AND r.termid = '$termvalue'
                WHERE s.sid IN (
                    SELECT DISTINCT s.sid
                    FROM students s
                    INNER JOIN results r ON s.email = r.email
                    WHERE r.classid LIKE '$classid%'
                    AND r.session = '$sessionvalue'
                    AND r.termid = '$termvalue'
                )
            ");

            if (mysqli_num_rows($student_query) > 0) {
                // Organize students and their marks into an associative array
                $students_data = [];

                while ($student_row = mysqli_fetch_assoc($student_query)) {
                    $sid = $student_row['sid'];
                    $fname = $student_row['fname'];
                    $lname = $student_row['lname'];
                    $sub_id = $student_row['sub_id'];
                    $email = $student_row['email'];

                    // Check if student already exists in array
                    if (!isset($students_data[$sid])) {
                        $students_data[$sid] = [
                            'fname' => $fname,
                            'lname' => $lname,
                            'email' => $email,
                            'marks' => [] // Initialize marks array
                        ];
                    }

                    // Add marks for the subject
                    foreach ($subjects as $subject_id => $subject_name) {
                        $result_query = mysqli_query($con, "
                            SELECT ca, examobj, examtheory
                            FROM results
                            WHERE email = '$email'
                            AND sub_id = '$subject_id'
                            AND termid = '$termvalue'
                            AND session = '$sessionvalue'
                        ");
                        
                        if ($result_row = mysqli_fetch_assoc($result_query)) {
                            $ca = $result_row['ca'];
                            $examobj = $result_row['examobj'];
                            $examtheory = $result_row['examtheory'];
                            $totalscore = $ca + $examobj + $examtheory;
                            $totalscore = number_format($totalscore, 1);
                            $students_data[$sid]['marks'][$subject_id] = $totalscore;
                        } else {
                            // Initialize as empty if no marks found
                            if (!isset($students_data[$sid]['marks'][$subject_id])) {
                                $students_data[$sid]['marks'][$subject_id] = '';
                            }
                        }
                    }
                }

                while ($student_row = mysqli_fetch_assoc($arm_student_query)) {
                    $sid = $student_row['sid'];
                    $fname = $student_row['fname'];
                    $lname = $student_row['lname'];
                    $sub_id = $student_row['sub_id'];
                    $email = $student_row['email'];

                    // Check if student already exists in array
                    if (!isset($arm_students_data[$sid])) {
                        $arm_students_data[$sid] = [
                            'fname' => $fname,
                            'lname' => $lname,
                            'email' => $email,
                            'marks' => [] // Initialize marks array
                        ];
                    }

                    // Add marks for the subject
                    foreach ($subjects as $subject_id => $subject_name) {
                        $result_query = mysqli_query($con, "
                            SELECT ca, examobj, examtheory
                            FROM results
                            WHERE email = '$email'
                            AND sub_id = '$subject_id'
                            AND termid = '$termvalue'
                            AND session = '$sessionvalue'
                        ");
                        
                        if ($result_row = mysqli_fetch_assoc($result_query)) {
                            $ca = $result_row['ca'];
                            $examobj = $result_row['examobj'];
                            $examtheory = $result_row['examtheory'];
                            $totalscore = $ca + $examobj + $examtheory;
                            $totalscore = number_format($totalscore, 1);
                            $arm_students_data[$sid]['marks'][$subject_id] = $totalscore;
                        } else {
                            // Initialize as empty if no marks found
                            if (!isset($arm_students_data[$sid]['marks'][$subject_id])) {
                                $arm_students_data[$sid]['marks'][$subject_id] = '';
                            }
                        }
                    }
                }

                // Calculate totalsubjects, total, and average for each student
                foreach ($students_data as $sid => &$student_data) {
                    $totalsubjects = 0;
                    $total = 0;
                    foreach ($student_data['marks'] as $mark) {
                        if ($mark !== '') {
                            $totalsubjects++;
                            $total += $mark;
                        }
                    }

                    $student_data['totalsubjects'] = $totalsubjects;
                    $student_data['totalmarksobtainable'] = $totalsubjects * 100;
                    $student_data['total'] = $total;
                    $student_data['average'] = $totalsubjects > 0 ? $total / $totalsubjects : 0;
                }
                unset($student_data); // Break the reference with the last element

                // Sort students by average in descending order
                uasort($students_data, function($a, $b) {
                    return $b['average'] <=> $a['average'];
                });

                // Assign positions based on sorted averages
                $position = 1;
                foreach ($students_data as &$student_data) {
                    $student_data['position'] = $position++;
                }
                unset($student_data); // Break the reference with the last element

                // Calculate totalsubjects, total, and average for each student
                foreach ($arm_students_data as $sid => &$arm_student_data) {
                    $totalsubjects = 0;
                    $total = 0;
                    foreach ($arm_student_data['marks'] as $mark) {
                        if ($mark !== '') {
                            $totalsubjects++;
                            $total += $mark;
                        }
                    }

                    $arm_student_data['totalsubjects'] = $totalsubjects;
                    $arm_student_data['totalmarksobtainable'] = $totalsubjects * 100;
                    $arm_student_data['total'] = $total;
                    $arm_student_data['average'] = $totalsubjects > 0 ? $total / $totalsubjects : 0;
                }
                unset($arm_student_data); // Break the reference with the last element

                // Sort students by average in descending order
                uasort($arm_students_data, function($a, $b) {
                    return $b['average'] <=> $a['average'];
                });

                // Assign positions based on sorted averages
                $position = 1;
                foreach ($arm_students_data as &$arm_student_data) {
                    $arm_student_data['position'] = $position++;
                }
                unset($arm_student_data); // Break the reference with the last element

                // Display Class Broadsheet table within a responsive div
                echo '<div class="panel title" id="class">';
                echo '<img src="uploads/logo.jpg" alt="Dominican Logo" style="width: 100px; height: auto; display: block; margin: 0 auto;">';
                echo "<h3 style='text-align: center; color: navy;'>DOMINICAN COLLEGE MAFOLUKU</h3>";
                echo "<p style='text-align: center; color: navy;'>BROADSHEET REPORT FOR $sessionvalue SESSION | TERM: $termname | CLASS: $class</p>";
                echo '<div class="table-responsive">';
                echo '<table class="table">';
                echo '<thead>';
                echo '<tr style="color: navy;">
                        <th style="position: sticky; left: 0; background: white; z-index: 1;">S/N</th>
                        <th style="position: sticky; left: 40px; background: white; z-index: 1;">Admission No</th>
                        <th style="position: sticky; left: 140px; background: white; z-index: 1;">Name of Students</th>';

                // Display columns for each subject
                foreach ($subjects as $sub_id => $subject_name) {
                    echo "<th>$subject_name</th>";
                }

                echo '<th>Total No. of Subjects</th>
                      <th>Total Marks Obtainable</th>
                      <th>Total</th>
                      <th>Average</th>
                      <th>Percentage</th>
                      <th>Position</th>
                      <th>Result Status</th>';

                echo '</tr>';
                echo '</thead>';
                echo '<tbody>';

                $serial_number = 1;
                foreach ($students_data as $sid => $student_data) {
                    $fname = $student_data['fname'];
                    $lname = $student_data['lname'];

                    // Output the student details and marks in one row
                    echo "<tr style='color: navy;'>
                            <td style='position: sticky; left: 0; background: white; z-index: 1;'>$serial_number</td>
                            <td style='position: sticky; left: 40px; background: white; z-index: 1;'>$sid</td>
                            <td style='position: sticky; left: 140px; background: white; z-index: 1;'>$fname $lname</td>";

                    // Output marks for each subject
                    foreach ($subjects as $subject_id => $subject_name) {
                        $mark = $student_data['marks'][$subject_id];
                        $mark_display = $mark !== '' ? $mark : '';
                        $mark_color = $mark !== '' && $mark < 50 ? 'style="color: red;"' : '';
                        echo "<td $mark_color>$mark_display</td>";
                    }

                    echo '<td>' . $student_data["totalsubjects"] . '</td>
                          <td>' . $student_data["totalmarksobtainable"] . '</td>
                          <td>' . $student_data["total"] . '</td>
                          <td>' . number_format($student_data['average'], 1) . '</td>
                          <td>' . number_format($student_data['average'], 0) . '%</td>
                          <td>' . $student_data["position"] . '</td>
                          <td>Result Status</td>';

                    echo '</tr>';
                    $serial_number++;
                }

                echo '</tbody>';
                echo '</table>';
                echo '</div>'; // Close table-responsive div
                echo '</div>'; // Close panel div

                // Add the download button
                echo '<div style="text-align: center; margin-top: 20px;">';
                echo '<button id="download-btn" style="padding: 10px 20px; margin: 20px 0; background-color: navy; color: white; border: none; cursor: pointer;" onclick="generateClassPDF()">Download</button>';
                echo '</div>';

                // Display Arm Broadsheet table within a responsive div
                echo '<div class="panel title" style="margin-top: 20px;" id="arm">';
                echo '<img src="uploads/logo.jpg" alt="Dominican Logo" style="width: 100px; height: auto; display: block; margin: 0 auto;">';
                echo "<h3 style='text-align: center; color: navy;'>DOMINICAN COLLEGE MAFOLUKU</h3>";
                echo "<p style='text-align: center; color: navy;'>BROADSHEET REPORT FOR $sessionvalue SESSION | TERM: $termname | CLASS: $classid</p>";
                echo '<div class="table-responsive">';
                echo '<table class="table">';
                echo '<thead>';
                echo '<tr style="color: navy;">
                        <th style="position: sticky; left: 0; background: white; z-index: 1;">S/N</th>
                        <th style="position: sticky; left: 40px; background: white; z-index: 1;">Admission No</th>
                        <th style="position: sticky; left: 140px; background: white; z-index: 1;">Name of Students</th>';

                // Display columns for each subject
                foreach ($subjects as $sub_id => $subject_name) {
                    echo "<th>$subject_name</th>";
                }

                echo '<th>Total No. of Subjects</th>
                      <th>Total Marks Obtainable</th>
                      <th>Total</th>
                      <th>Average</th>
                      <th>Percentage</th>
                      <th>Position</th>
                      <th>Result Status</th>';

                echo '</tr>';
                echo '</thead>';
                echo '<tbody>';

                $serial_number = 1;
                foreach ($arm_students_data as $sid => $arm_student_data) {
                    $fname = $arm_student_data['fname'];
                    $lname = $arm_student_data['lname'];

                    // Output the student details and marks in one row
                    echo "<tr style='color: navy;'>
                            <td style='position: sticky; left: 0; background: white; z-index: 1;'>$serial_number</td>
                            <td style='position: sticky; left: 40px; background: white; z-index: 1;'>$sid</td>
                            <td style='position: sticky; left: 140px; background: white; z-index: 1;'>$fname $lname</td>";

                    // Output marks for each subject
                    foreach ($subjects as $subject_id => $subject_name) {
                        $mark = $arm_student_data['marks'][$subject_id];
                        $mark_display = $mark !== '' ? $mark : '';
                        $mark_color = $mark !== '' && $mark < 50 ? 'style="color: red;"' : '';
                        echo "<td $mark_color>$mark_display</td>";
                    }

                    echo '<td>' . $arm_student_data["totalsubjects"] . '</td>
                          <td>' . $arm_student_data["totalmarksobtainable"] . '</td>
                          <td>' . $arm_student_data["total"] . '</td>
                          <td>' . number_format($arm_student_data['average'], 1) . '</td>
                          <td>' . number_format($arm_student_data['average'], 0) . '%</td>
                          <td>' . $arm_student_data["position"] . '</td>
                          <td>Result Status</td>';

                    echo '</tr>';
                    $serial_number++;
                }

                echo '</tbody>';
                echo '</table>';
                echo '</div>'; // Close table-responsive div
                echo '</div>'; // Close panel div
                 // Add the download button
                echo '<div style="text-align: center; margin-top: 20px;">';
                echo '<button id="download-btn" style="padding: 10px 20px; margin: 20px 0; background-color: navy; color: white; border: none; cursor: pointer;" onclick="generateArmPDF()">Download</button>';
                echo '</div>';

                // Fetch term scores and store in $students_data
                foreach ($students_data as $sid => &$student_data) {
                    $email = $student_data['email']; // Make sure this field exists in your student_data
                    $student_data['marks']['1st_term_total'] = null; // Initialize total scores for each term
                    $student_data['marks']['2nd_term_total'] = null;
                    $student_data['marks']['3rd_term_total'] = null;

                    foreach ($subjects as $subject_id => $subject_name) {
                        // 1st Term
                        $query = "SELECT SUM(ca + examobj + examtheory) AS total_mark 
                                FROM results 
                                WHERE email = '$email' 
                                AND session = '$sessionvalue' 
                                AND termid = 1 
                                AND sub_id = '$subject_id'";
                        $result = mysqli_query($con, $query);
                        $row = mysqli_fetch_assoc($result);
                        $ft_mark = $row['total_mark'] !== null ? round($row['total_mark'], 1) : '-'; // Use '-' if no marks are found
                        if ($ft_mark !== '-') {
                            $student_data['marks']['1st_term'][$subject_id] = $ft_mark;
                            $student_data['marks']['1st_term_total'] += $ft_mark;
                        } else {
                            $student_data['marks']['1st_term'][$subject_id] = '-';
                        }

                        // 2nd Term
                        $query = "SELECT SUM(ca + examobj + examtheory) AS total_mark 
                                FROM results 
                                WHERE email = '$email' 
                                AND session = '$sessionvalue' 
                                AND termid = 2 
                                AND sub_id = '$subject_id'";
                        $result = mysqli_query($con, $query);
                        $row = mysqli_fetch_assoc($result);
                        $st_mark = $row['total_mark'] !== null ? round($row['total_mark'], 1) : '-'; // Use '-' if no marks are found
                        if ($st_mark !== '-') {
                            $student_data['marks']['2nd_term'][$subject_id] = $st_mark;
                            $student_data['marks']['2nd_term_total'] += $st_mark;
                        } else {
                            $student_data['marks']['2nd_term'][$subject_id] = '-';
                        }

                        // 3rd Term
                        $query = "SELECT SUM(ca + examobj + examtheory) AS total_mark 
                                FROM results 
                                WHERE email = '$email' 
                                AND session = '$sessionvalue' 
                                AND termid = 3 
                                AND sub_id = '$subject_id'";
                        $result = mysqli_query($con, $query);
                        $row = mysqli_fetch_assoc($result);
                        $tt_mark = $row['total_mark'] !== null ? round($row['total_mark'], 1) : '-'; // Use '-' if no marks are found
                        if ($tt_mark !== '-') {
                            $student_data['marks']['3rd_term'][$subject_id] = $tt_mark;
                            $student_data['marks']['3rd_term_total'] += $tt_mark;
                        } else {
                            $student_data['marks']['3rd_term'][$subject_id] = '-';
                        }
                    }
                }

                

                // Display Cumulative Broadsheet table within a responsive div
                echo '<div class="panel title" id="cummulative">';
                echo '<img src="uploads/logo.jpg" alt="Dominican Logo" style="width: 100px; height: auto; display: block; margin: 0 auto;">';
                echo "<h3 style='text-align: center; color: navy;'>DOMINICAN COLLEGE MAFOLUKU</h3>";
                echo "<p style='text-align: center; color: navy;'>EXAMINATION MASTERSHEET FOR $sessionvalue SESSION | CLASS: $class | CUMULATIVE/MASTER SHEET</p>";
                echo '<div class="table-responsive">';
                echo '<table class="table" style="border: 3px solid black;">';
                echo '<thead>';
                echo '<tr style="color: navy;">
                        <th rowspan="2" style="position: sticky; left: 0; background: white; z-index: 1; border-left: 1px solid black; border-right: 3px solid black;">S/N</th>
                        <th rowspan="2" style="position: sticky; left: 40px; background: white; z-index: 1; border-left: 1px solid black; border-right: 3px solid black;">Admission No</th>
                        <th rowspan="2" style="position: sticky; left: 140px; background: white; z-index: 1; border-left: 1px solid black; border-right: 3px solid black;">Name of Students</th>';

                // Display columns for each subject
                foreach ($subjects as $sub_id => $subject_name) {
                    echo "<th colspan='4' style='border-left: 1px solid black; border-right: 3px solid black;'>$subject_name</th>";
                }

                echo '<th rowspan="2" style="border-left: 1px solid black; border-right: 3px solid black;">1ST TERM SCORE</th>
                      <th rowspan="2" style="border-left: 1px solid black; border-right: 3px solid black;">2ND TERM SCORE</th>
                      <th rowspan="2" style="border-left: 1px solid black; border-right: 3px solid black;">3RD TERM SCORE</th>
                      <th rowspan="2" style="border-left: 1px solid black; border-right: 3px solid black;">Total No. of Subjects</th>
                      <th rowspan="2" style="border-left: 1px solid black; border-right: 3px solid black;">Total Average Obtainable</th>
                      <th rowspan="2" style="border-left: 1px solid black; border-right: 3px solid black;">Cum. Sum Total</th>
                      <th rowspan="2" style="border-left: 1px solid black; border-right: 3px solid black;">Cum. Average Total</th>
                      <th rowspan="2" style="border-left: 1px solid black; border-right: 3px solid black;">Cum. Percentage</th>
                      <th rowspan="2" style="border-left: 1px solid black; border-right: 3px solid black;">Cum. Position</th>
                      <th rowspan="2" style="border-left: 1px solid black; border-right: 3px solid black;">Cum. Status</th>';

                echo '</tr>';
                echo '<tr style="color: navy;">';

                // Sub-columns for each term score under each subject
                foreach ($subjects as $sub_id => $subject_name) {
                    echo '<th>F.T</th>';
                    echo '<th>S.T</th>';
                    echo '<th>T.T</th>';
                    echo '<th style="border-right: 3px solid black;">AVG</th>';
                }

                echo '</tr>';
                echo '</thead>';
                echo '<tbody>';

                $serial_number = 1;
                foreach ($students_data as $sid => $student_data) {
                    $fname = $student_data['fname'];
                    $lname = $student_data['lname'];

                    // Initialize the cumulative average sum
                    $cum_avg_sum = 0;

                    // Output the student details and marks in one row
                    echo "<tr style='color: navy;'>
                            <td style='position: sticky; left: 0; background: white; z-index: 1; border-left: 1px solid black; border-right: 3px solid black;'>$serial_number</td>
                            <td style='position: sticky; left: 40px; background: white; z-index: 1; border-left: 1px solid black; border-right: 3px solid black;'>$sid</td>
                            <td style='position: sticky; left: 140px; background: white; z-index: 1; border-left: 1px solid black; border-right: 3px solid black;'>$fname $lname</td>";

                    // Output marks for each subject
                    foreach ($subjects as $subject_id => $subject_name) {
                        $ft_mark = $student_data['marks']['1st_term'][$subject_id] ?? '-';
                        $st_mark = $student_data['marks']['2nd_term'][$subject_id] ?? '-';
                        $tt_mark = $student_data['marks']['3rd_term'][$subject_id] ?? '-';
                        
                        // Initialize variables
                        $total_mark = 0;
                        $count = 0;

                        // Add non-null values and count them
                        if ($ft_mark !== '-') {
                            $total_mark += $ft_mark;
                            $count++;
                        }
                        if ($st_mark !== '-') {
                            $total_mark += $st_mark;
                            $count++;
                        }
                        if ($tt_mark !== '-') {
                            $total_mark += $tt_mark;
                            $count++;
                        }

                        // Calculate the average mark
                        $avg_mark = $count > 0 ? $total_mark / $count : 0;
                        $avg_mark = number_format($avg_mark, 1);
                        $ft_mark_color = $ft_mark !== '-' && $ft_mark < 50 ? 'style="color: red;"' : '';
                        $st_mark_color = $st_mark !== '-' && $st_mark < 50 ? 'style="color: red;"' : '';
                        $tt_mark_color = $tt_mark !== '-' && $tt_mark < 50 ? 'style="color: red;"' : '';
                        $avg_mark_color = $avg_mark !== '-' && $avg_mark < 50 ? 'style="color: red; border-right: 3px solid black;"' : '';
                
                        echo "<td $ft_mark_color>$ft_mark</td>";
                        echo "<td $st_mark_color>$st_mark</td>";
                        echo "<td $tt_mark_color>$tt_mark</td>";
                        echo "<td $avg_mark_color style='border-right: 3px solid black;'>$avg_mark</td>";

                        // Sum the non-null average marks for cumulative average calculation
                        if ($avg_mark !== 0) {
                            $cum_avg_sum += $avg_mark;
                        }
                    }

                    $ft_score = $student_data['marks']['1st_term_total'];
                    $ft_score = number_format($ft_score, 1);
                    $st_score = $student_data['marks']['2nd_term_total'];
                    $st_score = number_format($st_score, 1);
                    $tt_score = $student_data['marks']['3rd_term_total'];
                    $tt_score = number_format($tt_score, 1);

                    // Calculate cumulative values
                    $total_subjects = $student_data['totalsubjects'];
                    $total_average_obtainable = $total_subjects * 100;
                    $cum_sum_total = $ft_score + $st_score + $tt_score;
                    $cum_sum_total = number_format($cum_sum_total, 1);

                    // Calculate cumulative average total
                    $cum_average_total = $cum_avg_sum;
                    // Round up to one decimal place
                    $cum_average_total = number_format($cum_average_total, 1);

                    $cum_percentage = $cum_average_total / $total_subjects;
                    $cum_percentage = number_format($cum_percentage, 0) . '%';
                    $cum_position = '';
                    $cum_status = ''; // Update with actual status if available

                    echo '<td style="border-left: 1px solid black; border-right: 3px solid black;">' . $ft_score . '</td>
                          <td style="border-left: 1px solid black; border-right: 3px solid black;">' . $st_score . '</td>
                          <td style="border-left: 1px solid black; border-right: 3px solid black;">' . $tt_score . '</td>
                          <td style="border-left: 1px solid black; border-right: 3px solid black;">' . $total_subjects . '</td>
                          <td style="border-left: 1px solid black; border-right: 3px solid black;">' . $total_average_obtainable . '</td>
                          <td style="border-left: 1px solid black; border-right: 3px solid black;">' . $cum_sum_total . '</td>
                          <td style="border-left: 1px solid black; border-right: 3px solid black;">' . $cum_average_total . '</td>
                          <td style="border-left: 1px solid black; border-right: 3px solid black;">' . $cum_percentage . '</td>
                          <td style="border-left: 1px solid black; border-right: 3px solid black;">' . $cum_position . '</td>
                          <td style="border-left: 1px solid black; border-right: 3px solid black;">' . $cum_status . '</td>';
                    echo '</tr>';
                    $serial_number++;
                }

                echo '</tbody>';
                echo '</table>';
                echo '</div>'; // Close table-responsive div
                echo '</div>'; // Close panel div

                // Add the download button
                echo '<div style="text-align: center; margin-top: 20px;">';
                echo '<button id="download-btn" style="padding: 10px 20px; margin: 20px 0; background-color: navy; color: white; border: none; cursor: pointer;" onclick="generateCummulativePDF()">Download</button>';
                echo '</div>';

                echo '</div>'; // Close panel div
            } else {
                echo '<p>No students found for the selected session and term.</p>';
            }
        } else {
            // Staff is not a class guardian
            echo '<p>Only Class Guardians can generate broadsheets.</p>';
        }
    } else {
        echo '<p>Session and Term selection is required.</p>';
    }
}
?>
<script src="https://cdnjs.cloudflare.com/ajax/libs/html2pdf.js/0.9.2/html2pdf.bundle.min.js"></script>
<script>
        function generateClassPDF() {
            const element = document.getElementById('class');
            
            const tableResponsiveElements = document.querySelectorAll('.table-responsive');
            const originalWidth = element.style.width;
            const originalTransformOrigin = element.style.transformOrigin;

            // Save original styles for table-responsive elements
            const originalStyles = Array.from(tableResponsiveElements).map(el => ({
                element: el,
                maxHeight: el.style.maxHeight,
                overflow: el.style.overflow
            }));

            // Temporarily set styles to capture all content
            element.style.transformOrigin = 'top left'; // Change transform-origin to top left
            element.style.width = 'fit-content';
            tableResponsiveElements.forEach(el => {
                el.style.maxHeight = 'none';
                el.style.overflow = 'visible';
            });

            element.style.transform = 'scale(0.7)'; // Scale down for fitting into PDF

            var opt = {
                filename: "<?php echo $class . '_' . $termname . '_broadsheet'; ?>.pdf",
                margin: [0, 0, 0, 0], // top, left, bottom, right
                image: { type: 'jpeg', quality: 0.98 },
                html2canvas: {
                    scale: 2, // Adjust scale for better quality
                    logging: true,
                    useCORS: true, // Use CORS if images are loaded from other domains
                },
                jsPDF: {
                    unit: 'pt',
                    format: 'a3', // Correct format to A3
                    orientation: 'landscape'
                }
            };

           html2pdf().from(element).set(opt).save().then(() => {
                // Reset the styles after generating the PDF
                element.style.transformOrigin = originalTransformOrigin; // Reset transform-origin
                element.style.width = originalWidth;
                originalStyles.forEach(({element, maxHeight, overflow}) => {
                    element.style.maxHeight = maxHeight;
                    element.style.overflow = overflow;
                });
        })
        }        
        
        function generateArmPDF() {
            const element = document.getElementById('arm');
            
            const tableResponsiveElements = document.querySelectorAll('.table-responsive');
            const originalWidth = element.style.width;
            const originalTransformOrigin = element.style.transformOrigin;

            // Save original styles for table-responsive elements
            const originalStyles = Array.from(tableResponsiveElements).map(el => ({
                element: el,
                maxHeight: el.style.maxHeight,
                overflow: el.style.overflow
            }));

            // Temporarily set styles to capture all content
            element.style.transformOrigin = 'top left'; // Change transform-origin to top left
            element.style.width = 'fit-content';
            tableResponsiveElements.forEach(el => {
                el.style.maxHeight = 'none';
                el.style.overflow = 'visible';
            });

            element.style.transform = 'scale(0.7)'; // Scale down for fitting into PDF

            var opt = {
                filename: "<?php echo $classid . '_' . $termname . '_broadsheet'; ?>.pdf",
                margin: [0, 0, 0, 0], // top, left, bottom, right
                image: { type: 'jpeg', quality: 0.98 },
                html2canvas: {
                    scale: 2, // Adjust scale for better quality
                    logging: true,
                    useCORS: true, // Use CORS if images are loaded from other domains
                },
                jsPDF: {
                    unit: 'pt',
                    format: 'a3', // Correct format to A3
                    orientation: 'landscape'
                }
            };

           html2pdf().from(element).set(opt).save().then(() => {
                // Reset the styles after generating the PDF
                element.style.transformOrigin = originalTransformOrigin; // Reset transform-origin
                element.style.width = originalWidth;
                originalStyles.forEach(({element, maxHeight, overflow}) => {
                    element.style.maxHeight = maxHeight;
                    element.style.overflow = overflow;
                });
        })
        }

        function generateCummulativePDF() {
            const element = document.getElementById('cummulative');
            
            const tableResponsiveElements = document.querySelectorAll('.table-responsive');
            const originalWidth = element.style.width;
            const originalTransformOrigin = element.style.transformOrigin;

            // Save original styles for table-responsive elements
            const originalStyles = Array.from(tableResponsiveElements).map(el => ({
                element: el,
                maxHeight: el.style.maxHeight,
                overflow: el.style.overflow
            }));

            // Temporarily set styles to capture all content
            element.style.transformOrigin = 'top left'; // Change transform-origin to top left
            element.style.width = 'fit-content';
            tableResponsiveElements.forEach(el => {
                el.style.maxHeight = 'none';
                el.style.overflow = 'visible';
            });

            element.style.transform = 'scale(0.7)'; // Scale down for fitting into PDF

            var opt = {
                filename: "<?php echo $class . '_' . $termname . '_cummulative_broadsheet'; ?>.pdf",
                margin: [0, 0, 0, 0], // top, left, bottom, right
                image: { type: 'jpeg', quality: 0.98 },
                html2canvas: {
                    scale: 2, // Adjust scale for better quality
                    logging: true,
                    useCORS: true, // Use CORS if images are loaded from other domains
                },
                jsPDF: {
                    unit: 'pt',
                    format: 'a1', // Correct format to A3
                    orientation: 'landscape'
                }
            };

           html2pdf().from(element).set(opt).save().then(() => {
                // Reset the styles after generating the PDF
                element.style.transformOrigin = originalTransformOrigin; // Reset transform-origin
                element.style.width = originalWidth;
                originalStyles.forEach(({element, maxHeight, overflow}) => {
                    element.style.maxHeight = maxHeight;
                    element.style.overflow = overflow;
                });
        })
        }
    </script>
<style>
        .container {
            width: 100%;
        }

        .panel.title {
            height: 100%;
        }

        .panel.title .h3 {
            text-align: center;
            font-size: 0.9em; /* Slightly reduced font size */
        }

        .table {
            border: 1px solid #D3D3D3; /* Border thickness */
            border-collapse: collapse;
            font-size: 0.7em; /* Slightly reduced font size */
        }

        .table th, .table td {
            border: 1px solid #D3D3D3; /* Border thickness */
            padding: 5px; /* Slightly reduced padding */
            text-align: center;
        }

        .table-responsive {
            max-height: 600px;
            overflow: auto;
        }

        #arm, #class, #cummulative {
            overflow: auto;
            width: 100%;
            height: 100%; /* Adjust as needed */
            transform-origin: center;
            transform: scale(0.75); /* Slightly reduced scale */
        }

        @media print {
            .container {
                width: auto;
            }

            .panel.title .h3 {
                font-size: 0.9em; /* Consistent reduced font size for print */
            }

            .table {
                font-size: 0.7em; /* Consistent reduced font size for print */
            }
        }
    </style>
<!-- view broadsheet closed -->




<!--add quiz start-->
<?php
if (@$_GET['q'] == 4 && !(@$_GET['step'])) {
    // Fetch distinct subjects associated with the staffid
    $subject_query = "
    SELECT DISTINCT s.sub_id, s.subject 
    FROM subjects s
    INNER JOIN staff_class_subject scs ON s.sub_id = scs.sub_id
    WHERE scs.staffid = '$staffid'";
    $subject_result = mysqli_query($con, $subject_query) or die(mysqli_error($con));

    // Fetch terms from the terms table
    $terms_query = "SELECT termid, term FROM terms";
    $terms_result = mysqli_query($con, $terms_query) or die(mysqli_error($con));

    echo '
    <div class="row examform" style="margin: 20px;">
    <span class="title1" style="margin-left:40%;font-size:30px;"><b>Enter Exam Details</b></span><br /><br />
    <div class="col-md-3"></div>
    <div class="col-md-6">
        <form class="form-horizontal" title1" name="form" action="add_exams.php" method="POST" onsubmit="return formatDateTime()">
            <fieldset>
                <!-- Subject dropdown -->
                <div class="form-group">
                    <label class="col-md-12 control-label" for="subject"></label>
                    <div class="col-md-12">
                        <select id="subject" name="sub_id" class="form-control input-md" required>
                            <option value="" disabled selected>Select Subject</option>';
                            while ($row = mysqli_fetch_assoc($subject_result)) {
                                echo '<option value="'.$row['sub_id'].'">'.$row['subject'].'</option>';
                            }
    echo '              </select>
                    </div>
                </div>

                <!-- Class dropdown -->
                <div class="form-group">
                    <label class="col-md-12 control-label" for="classid"></label>
                    <div class="col-md-12">
                        <select id="classid" name="classid" class="form-control input-md" required>
                            <option value="" disabled selected>Select Class</option>
                        </select>
                    </div>
                </div>

                <!-- Term dropdown -->
                <div class="form-group">
                    <label class="col-md-12 control-label" for="termid"></label>
                    <div class="col-md-12">
                        <select id="termid" name="termid" class="form-control input-md" required>
                            <option value="" disabled selected>Select Term</option>';
                            while ($row = mysqli_fetch_assoc($terms_result)) {
                                echo '<option value="'.$row['termid'].'">'.$row['term'].'</option>';
                            }
                            echo '
                            </select>
                            </div>
                        </div>
                        
                        <!-- Session input -->
                        <div class="form-group">
                            <label class="col-md-12 control-label" for="session"></label>
                            <div class="col-md-12">
                                <input id="session" name="session" placeholder="Enter Exam Session" class="form-control input-md" type="text" required>
                            </div>
                        </div>
                        
                        <!-- Marks per right answer -->
                        <div class="form-group">
                            <label class="col-md-12 control-label" for="sahi"></label>
                            <div class="col-md-12">
                                <input id="sahi" name="sahi" placeholder="Marks per right answer" class="form-control input-md" type="number" step="0.01" required>
                            </div>
                        </div>
                        
                        <!-- Deduct marks on wrong answer (optional) -->
                        <div class="form-group">
                            <label class="col-md-12 control-label" for="waam"></label>
                            <div class="col-md-12">
                                <input id="waam" name="waam" placeholder="Deduct marks on wrong answer (optional)" class="form-control input-md" type="number" step="0.01">
                            </div>
                        </div>
                        
                        <!-- Exam Time Limit -->
                        <div class="form-group">
                            <label class="col-md-12 control-label" for="timelimit"></label>
                            <div class="col-md-12">
                                <input id="timelimit" name="timelimit" placeholder="Exam Time Limit (minutes)" class="form-control input-md" type="number" required>
                            </div>
                        </div>
                        
                        <!-- Total Number of Questions -->
                        <div class="form-group">
                            <label class="col-md-12 control-label" for="tnoq"></label>
                            <div class="col-md-12">
                                <input id="tnoq" name="tnoq" placeholder="Total Number of Questions" class="form-control input-md" type="number" required>
                            </div>
                        </div>
                        
                        <!-- Select Exam Date -->
                        <div class="form-group">
                            <label class="col-md-12 control-label" for="exam_date"></label>
                            <div class="col-md-12">
                                <input id="exam_date" name="exam_date" placeholder="Select Exam Date" class="form-control input-md" type="date" required>
                            </div>
                        </div>
                        
                        <!-- Select Exam Time -->
                        <div class="form-group">
                            <label class="col-md-12 control-label" for="exam_time"></label>
                            <div class="col-md-12">
                                <input id="exam_time" name="exam_time" placeholder="Select Exam Time" class="form-control input-md" type="time" step="1" required>
                            </div>
                        </div>
                        
                        <!-- Exam Instructions (optional) -->
                        <div class="form-group">
                            <label class="col-md-12 control-label" for="instruction"></label>
                            <div class="col-md-12">
                                <input id="instruction" name="instruction" placeholder="Exam Instructions (optional)" class="form-control input-md" type="text">
                            </div>
                        </div>
                        
                        <div class="form-group">
                            <label class="col-md-12 control-label" for=""></label>
                            <div class="col-md-12">
                                <input type="submit" style="margin-left:45%" class="btn btn-primary" value="Submit" onclick="return formatDateTime();" class="btn btn-primary"/>
                            </div>
                        </div>
                        </fieldset>
                        </form>
                        </div>
                        </div>';
}
?>

<script>
document.getElementById('exam_date').addEventListener('change', function() {
    const date = new Date(this.value);
    const formattedDate = date.toISOString().slice(0, 10);
    this.value = formattedDate;
});

document.getElementById('exam_time').addEventListener('change', function() {
    const time = this.value;
    if (time.length === 5) {
        this.value = time + ':00';
    }
});

function formatDateTime() {
    const dateInput = document.getElementById('exam_date');
    const timeInput = document.getElementById('exam_time');

    const date = new Date(dateInput.value);
    const formattedDate = date.toISOString().slice(0, 10);
    dateInput.value = formattedDate;

    let time = timeInput.value;
    if (time.length === 5) {
        time += ':00';
    }
    timeInput.value = time;

    return true;
}

document.getElementById('subject').addEventListener('change', function() {
    var sub_id = this.value;
    console.log("Selected subject id: " + sub_id);

    var xhr = new XMLHttpRequest();
    xhr.open("POST", "get_classes.php", true);
    xhr.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");

    xhr.onreadystatechange = function() {
        if (this.readyState === XMLHttpRequest.DONE && this.status === 200) {
            console.log("Received data: " + this.responseText);
            document.getElementById('classid').innerHTML = this.responseText;
        }
    };

    xhr.send("sub_id=" + encodeURIComponent(sub_id));
});
</script>

<style>
    .examform {
    padding: 20px;
}
</style>

<!--add quiz end-->

<!--add quiz step2 start-->
<?php
/*if(@$_GET['q']==4 && (@$_GET['step'])==2 ) {
echo '
<div class="row">
<span class="title1" style="margin-left:40%;font-size:30px;"><b>Enter Question Details</b></span><br /><br />
 <div class="col-md-3"></div><div class="col-md-6"><form class="form-horizontal title1" name="form" action="update.php?q=addqns&n='.@$_GET['n'].'&eid='.@$_GET['eid'].'&ch=4 "  method="POST">
<fieldset>
';

 for($i=1;$i<=@$_GET['n'];$i++)
 {
echo '<b>Question number&nbsp;'.$i.'&nbsp;:</><br /><!-- Text input-->
<div class="form-group">
  <label class="col-md-12 control-label" for="qns'.$i.' "></label>
  <div class="col-md-12">
  <textarea rows="3" cols="5" name="qns'.$i.'" class="form-control" required="required" placeholder="Write question number '.$i.' here..."></textarea>
  </div>
</div>
<!-- Text input-->
<div class="form-group">
  <label class="col-md-12 control-label" for="'.$i.'1"></label>
  <div class="col-md-12">
  <input id="'.$i.'1" name="'.$i.'1" placeholder="Enter option a" class="form-control input-md" type="text" required="required">

  </div>
</div>
<!-- Text input-->
<div class="form-group">
  <label class="col-md-12 control-label" for="'.$i.'2"></label>
  <div class="col-md-12">
  <input id="'.$i.'2" name="'.$i.'2" placeholder="Enter option b" class="form-control input-md" type="text" required="required">

  </div>
</div>
<!-- Text input-->
<div class="form-group">
  <label class="col-md-12 control-label" for="'.$i.'3"></label>
  <div class="col-md-12">
  <input id="'.$i.'3" name="'.$i.'3" placeholder="Enter option c" class="form-control input-md" type="text" required="required">

  </div>
</div>
<!-- Text input-->
<div class="form-group">
  <label class="col-md-12 control-label" for="'.$i.'4"></label>
  <div class="col-md-12">
  <input id="'.$i.'4" name="'.$i.'4" placeholder="Enter option d" class="form-control input-md" type="text" required="required">

  </div>
</div>
<br />
<b>Correct answer</b>:<br />
<select id="ans'.$i.'" name="ans'.$i.'" placeholder="Choose correct answer " class="form-control input-md" >
   <option value="a">Select answer for question '.$i.'</option>
  <option value="a">option a</option>
  <option value="b">option b</option>
  <option value="c">option c</option>
  <option value="d">option d</option> </select><br /><br />';
 }

echo '<div class="form-group">
  <label class="col-md-12 control-label" for=""></label>
  <div class="col-md-12">
    <input  type="submit" style="margin-left:45%" class="btn btn-primary" value="Submit Quiz" class="btn btn-primary"/>
  </div>
</div>

</fieldset>
</form></div>';



}*/

if (@$_GET['q'] == 4 && (@$_GET['step']) == 2) {
  include_once 'dbConnection.php'; // Ensure this file includes your database connection code
  $eid = @$_GET['eid'];
  $n = @$_GET['n'];

  echo '
  <div class="row">
  <span class="title1" style="margin-left:40%;font-size:30px;"><b>Enter Question Details</b></span><br /><br />
  <div class="col-md-3"></div>
  <div class="col-md-6">
  <form class="form-horizontal title1" name="form" action="update.php?q=addqns&n='.$n.'&eid='.$eid.'&ch=4" method="POST" enctype="multipart/form-data">
  <fieldset>
  ';

  for ($i = 1; $i <= $n; $i++) {
      echo '<b>Question number&nbsp;' . $i . '&nbsp;:</b><br />';
      
      // Image upload button and preview
      echo '
      <div class="form-group">
          <label class="col-md-12 control-label" for="qns' . $i . '"></label>
          <div class="col-md-12">
              <div id="image-preview-' . $i . '" style="display: none;">
                  <img id="preview-img-' . $i . '" src="#" alt="Image Preview" style="max-width: 100%; max-height: 200px;" />
              </div>
              <input type="file" name="image' . $i . '" id="image' . $i . '" accept="image/*" onchange="readURL(this, ' . $i . ');" />
          </div>
      </div>
      ';

      // Question text input
      echo '
      <div class="form-group">
          <label class="col-md-12 control-label" for="qns' . $i . '"></label>
          <div class="col-md-12">
              <textarea rows="3" cols="5" name="qns' . $i . '" class="form-control" required="required" placeholder="Write question number ' . $i . ' here..."></textarea>
          </div>
      </div>
      ';

      // Add a script to initialize CKEditor for the question textarea
      echo '
      <script>
          CKEDITOR.replace("qns' . $i . '");
      </script>
      ';

      // Options input
      for ($j = 1; $j <= 4; $j++) {
          $option_label = chr(96 + $j); // a, b, c, d
          echo '
          <div class="form-group">
              <label class="col-md-12 control-label" for="' . $i . $j . '"></label>
              <div class="col-md-12">
                  <input id="' . $i . $j . '" name="' . $i . $j . '" placeholder="Enter option ' . $option_label . '" class="form-control input-md" type="text" required="required">
              </div>
          </div>
          ';
      }

      // Correct answer selection
      echo '
      <br /><b>Correct answer</b>:<br />
      <select id="ans' . $i . '" name="ans' . $i . '" placeholder="Choose correct answer" class="form-control input-md" required>
          <option value="" disabled selected>Select answer for question ' . $i . '</option>
          <option value="a">Option a</option>
          <option value="b">Option b</option>
          <option value="c">Option c</option>
          <option value="d">Option d</option>
      </select><br /><br />
      ';
  }

  echo '
  <div class="form-group">
      <label class="col-md-12 control-label" for=""></label>
      <div class="col-md-12">
          <input type="submit" style="margin-left:45%" class="btn btn-primary" value="Submit Quiz" />
      </div>
  </div>
  </fieldset>
  </form>
  </div>
  </div>
  ';
}
?>

<script>
function readURL(input, id) {
    if (input.files && input.files[0]) {
        var reader = new FileReader();
        
        reader.onload = function (e) {
            document.getElementById('image-preview-' + id).style.display = 'block';
            document.getElementById('preview-img-' + id).src = e.target.result;
        };
        
        reader.readAsDataURL(input.files[0]);
    }
}
</script>
<!--add quiz step 2 end-->

<!--remove quiz-->
<?php 
if(@$_GET['q']==5) {

    // Fetch distinct subjects associated with the staffid
    $subject_query = "
    SELECT DISTINCT s.sub_id, s.subject 
    FROM subjects s
    INNER JOIN staff_class_subject scs ON s.sub_id = scs.sub_id
    WHERE scs.staffid = '$staffid'";
    $subject_result = mysqli_query($con, $subject_query) or die(mysqli_error($con));

    // Fetch classids associated with the staffid
    $class_query = "
    SELECT DISTINCT scs.classid 
    FROM staff_class_subject scs
    WHERE scs.staffid = '$staffid'";
    $class_result = mysqli_query($con, $class_query) or die(mysqli_error($con));

    // Store subjects in an array
    $subjects = [];
    while ($row = mysqli_fetch_assoc($subject_result)) {
        $subjects[$row['sub_id']] = $row['subject'];
    }

    // Store classes in an array
    $classes = [];
    while ($row = mysqli_fetch_assoc($class_result)) {
        $classes[$row['classid']] = $row['classid']; // Assuming classid is the class name
    }

    // Fetch exams associated with the subjects and classes of the staff
    $exams_query = "
    SELECT e.*, t.term 
    FROM exams e
    INNER JOIN terms t ON e.termid = t.termid
    WHERE e.sub_id IN (SELECT sub_id FROM staff_class_subject WHERE staffid = '$staffid')
    AND e.classid IN (SELECT classid FROM staff_class_subject WHERE staffid = '$staffid')
    ORDER BY e.date DESC";
    $result = mysqli_query($con, $exams_query) or die(mysqli_error($con));

    echo  '<div class="panel"><div class="table-responsive"><table class="table table-striped title1">
    <tr><td><b>S.N.</b></td><td><b>Subject</b></td><td><b>Class</b></td><td><b>Term</b></td><td><b>Session</b></td><td><b>Date</b></td><td></td></tr>';
    $c=1;
    while ($row = mysqli_fetch_array($result)) {
        $sub_id = $row['sub_id'];
        $class_id = $row['classid'];
        $total = $row['tnoq'];
        $sahi = $row['sahi'];
        $time = $row['timelimit'];
        $eid = $row['eid'];
        $term = $row['term'];
        $session = $row['session'];
        $date = date('Y-m-d H:i:s', strtotime($row['date'])); // Format exam date/time
        $subject = isset($subjects[$sub_id]) ? $subjects[$sub_id] : 'Unknown Subject';
        $class = isset($classes[$class_id]) ? $classes[$class_id] : 'Unknown Class';

        echo '<tr><td>'.$c++.'</td><td>'.$subject.'</td><td>'.$class.'</td><td>'.$term.'</td><td>'.$session.'</td><td>'.$date.'</td>
        <td><b><a href="update.php?q=rmquiz&eid='.$eid.'" class="pull-right btn sub1" style="margin:0px;background:red"><span class="glyphicon glyphicon-trash" aria-hidden="true"></span>&nbsp;<span class="title1"><b>Remove</b></span></a></b></td></tr>';
    }
    $c=0;
    echo '</table></div></div>';

}
?>

</div><!--container closed-->
</div></div>
</body>
</html>
