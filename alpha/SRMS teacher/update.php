<?php
include_once 'dbConnection.php';
session_start();
$email=$_SESSION['email'];
//delete feedback
if(isset($_SESSION['key'])){
if(@$_GET['fdid'] && $_SESSION['key']=='emedit09050118734') {
$id=@$_GET['fdid'];
$result = mysqli_query($con,"DELETE FROM feedback WHERE id='$id' ") or die('Error');
header("location:dash.php?q=3");
}
}

//delete user
if(isset($_SESSION['key'])){
if(@$_GET['demail'] && $_SESSION['key']=='emedit09050118734') {
$demail=@$_GET['demail'];
$r1 = mysqli_query($con,"DELETE FROM rank WHERE email='$demail' ") or die('Error');
$r2 = mysqli_query($con,"DELETE FROM history WHERE email='$demail' ") or die('Error');
$result = mysqli_query($con,"DELETE FROM user WHERE email='$demail' ") or die('Error');
header("location:dash.php?q=1");
}
}
//remove quiz
if(isset($_SESSION['key'])){
if(@$_GET['q']== 'rmquiz' && $_SESSION['key']=='emedit09050118734') {
$eid=@$_GET['eid'];
$result = mysqli_query($con,"SELECT * FROM questions WHERE eid='$eid' ") or die('Error');
while($row = mysqli_fetch_array($result)) {
	$qid = $row['qid'];
$r1 = mysqli_query($con,"DELETE FROM options WHERE qid='$qid'") or die('Error');
$r2 = mysqli_query($con,"DELETE FROM answer WHERE qid='$qid' ") or die('Error');
}
$r3 = mysqli_query($con,"DELETE FROM questions WHERE eid='$eid' ") or die('Error');
$r4 = mysqli_query($con,"DELETE FROM exams WHERE eid='$eid' ") or die('Error');
$r4 = mysqli_query($con,"DELETE FROM history WHERE eid='$eid' ") or die('Error');

header("location:dash.php?q=5");
}
}

// Add quiz
if (isset($_SESSION['key']) && @$_GET['q'] == 'addquiz' && $_SESSION['key'] == 'emedit09050118734') {
  $sub_id = $_POST['sub_id'];
  $classid = $_POST['classid'];
  $termid = $_POST['termid'];
  $session = $_POST['session'];
  $sahi = $_POST['sahi'];
  $waam = isset($_POST['waam']) ? $_POST['waam'] : null; // Optional field
  $timelimit = $_POST['timelimit'];
  $tnoq = $_POST['tnoq'];
  $exam_date = $_POST['exam_date'];
  $exam_time = $_POST['exam_time'];
  $instruction = isset($_POST['instruction']) ? $_POST['instruction'] : null; // Optional field
  $id = uniqid(); // Generate unique examid


  // Set PHP timezone to system timezone
  date_default_timezone_set('Africa/Lagos');

  // Convert exam_date to Y-m-d format and exam_time to H:i:s format
  $formatted_date = date('Y-m-d', strtotime($exam_date));
  $formatted_time = date('H:i:s', strtotime($exam_time));

  // Combine date and time into a single datetime string
  $datetime = "$formatted_date $formatted_time";

  // Store the datetime in the desired format
  $datetime = date('Y-m-d H:i:s', strtotime($datetime));

  // Prepare the SQL query
  $query = "
      INSERT INTO exams (eid, sub_id, classid, sahi, waam, timelimit, tnoq, date, instruction, termid, session) 
      VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";

  // Prepare the statement
  $stmt = $con->prepare($query);
  if ($stmt === false) {
      die('Prepare failed: ' . htmlspecialchars($con->error));
  }

  // Bind the parameters
  $stmt->bind_param("sssiiisiiss", $id, $sub_id, $classid, $sahi, $waam, $timelimit, $tnoq, $datetime, $instruction, $termid, $session);

  // Execute the statement
  if ($stmt->execute()) {
      echo "Exam successfully added.";
  } else {
      echo "Error: " . htmlspecialchars($stmt->error);
  }

  // Close the statement
  $stmt->close();

  // Redirect to dashboard with additional parameters
  header("location:dash.php?q=4&step=2&eid=$id&n=$tnoq");
}

//add question

if (isset($_SESSION['key'])) {
    if ($_GET['q'] == 'addqns' && $_SESSION['key'] == 'emedit09050118734') {
        $n = @$_GET['n'];
        $eid = @$_GET['eid'];
        $ch = @$_GET['ch'];

        for ($i = 1; $i <= $n; $i++) {
            $qid = uniqid();
            $qns = $_POST['qns' . $i];

            // Handle image upload
            $imageData = NULL;
            if (isset($_FILES['image' . $i]) && $_FILES['image' . $i]['error'] == 0) {
                $imageData = file_get_contents($_FILES['image' . $i]['tmp_name']);
            }

            // Insert into questions table
            $q3 = mysqli_prepare($con, "INSERT INTO questions (eid, qid, qns, choice, sn, image) VALUES (?, ?, ?, ?, ?, ?)");
            mysqli_stmt_bind_param($q3, 'ssssss', $eid, $qid, $qns, $ch, $i, $imageData);
            $result = mysqli_stmt_execute($q3);

            if (!$result) {
                die('Error inserting question: ' . mysqli_stmt_error($q3));
            }

            mysqli_stmt_close($q3);

            // Insert options and answer
            $options = [];
            $optionIds = [];
            for ($j = 1; $j <= 4; $j++) {
                $options[] = $_POST[$i . $j];
                $optionIds[] = uniqid();
            }

            for ($j = 0; $j < 4; $j++) {
                $q4 = mysqli_prepare($con, "INSERT INTO options (qid, option, optionid) VALUES (?, ?, ?)");
                mysqli_stmt_bind_param($q4, 'sss', $qid, $options[$j], $optionIds[$j]);
                $result = mysqli_stmt_execute($q4);

                if (!$result) {
                    die('Error inserting option: ' . mysqli_stmt_error($q4));
                }

                mysqli_stmt_close($q4);
            }

            // Determine the correct answer
            $ans = $_POST['ans' . $i];
            $ansIndex = ord($ans) - ord('a'); // 'a' => 0, 'b' => 1, 'c' => 2, 'd' => 3
            $ansid = $optionIds[$ansIndex];

            // Insert the correct answer
            $q5 = mysqli_prepare($con, "INSERT INTO answer (qid, ansid) VALUES (?, ?)");
            mysqli_stmt_bind_param($q5, 'ss', $qid, $ansid);
            $result = mysqli_stmt_execute($q5);

            if (!$result) {
                die('Error inserting answer: ' . mysqli_stmt_error($q5));
            }

            mysqli_stmt_close($q5);
        }

        header("Location: dash.php?q=0");
    }
}


/*if (@$_GET['q'] == 'quiz' && @$_GET['step'] == 2) {
  $eid = @$_GET['eid'];
  $sn = @$_GET['n'];
  $total = @$_GET['t'];

  // Store the selected answer in the session
  if (isset($_POST['ans'])) {
      $_SESSION['quiz'][$eid][$sn] = $_POST['ans'];
  }

  // Determine which button was clicked
  if (isset($_POST['action'])) {
      if ($_POST['action'] == 'next') {
          $sn++;
      } elseif ($_POST['action'] == 'previous') {
          $sn--;
      }

      // Redirect to the appropriate question
      header('Location: account.php?q=quiz&step=2&eid=' . $eid . '&n=' . $sn . '&t=' . $total);
      exit();
  }

  // Handle final submission
  if (isset($_GET['submit']) && $_GET['submit'] == 1) {
      foreach ($_SESSION['quiz'][$eid] as $sn => $answer) {
          $qid = $_GET['qid']; // Ensure you have the correct qid for each question
          $query = "INSERT INTO user_answers (eid, qid, sn, answer) VALUES ('$eid', '$qid', '$sn', '$answer')";
          mysqli_query($con, $query);
      }

      // Clear the session for this quiz
      unset($_SESSION['quiz'][$eid]);

      // Redirect to a completion page
      header('Location: completion.php');
      exit();
  }
}*/


//restart quiz
if(@$_GET['q']== 'quizre' && @$_GET['step']== 25 ) {
$eid=@$_GET['eid'];
$n=@$_GET['n'];
$t=@$_GET['t'];
$q=mysqli_query($con,"SELECT score FROM history WHERE eid='$eid' AND email='$email'" )or die('Error156');
while($row=mysqli_fetch_array($q) )
{
$s=$row['score'];
}
$q=mysqli_query($con,"DELETE FROM `history` WHERE eid='$eid' AND email='$email' " )or die('Error184');
$q=mysqli_query($con,"SELECT * FROM rank WHERE email='$email'" )or die('Error161');
while($row=mysqli_fetch_array($q) )
{
$sun=$row['score'];
}
$sun=$sun-$s;
$q=mysqli_query($con,"UPDATE `rank` SET `score`=$sun ,time=NOW() WHERE email= '$email'")or die('Error174');
header("location:account.php?q=quiz&step=2&eid=$eid&n=1&t=$t");
}
?>







