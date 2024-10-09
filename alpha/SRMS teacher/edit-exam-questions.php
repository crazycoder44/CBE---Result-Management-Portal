<?php
session_start();
error_reporting(0);

$staffid = $_SESSION['staffid']; // Retrieve staff ID from session
include('includes/config.php');
if(strlen($_SESSION['alogin']) == "") {   
    header("Location: index.php"); 
} else {
    // Get values from URL
    $eid = @$_GET['eid'];
    $n = @$_GET['n'];
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Staff Portal | Edit Exam Questions</title>
    <link rel="stylesheet" href="css/bootstrap.min.css" media="screen">
    <link rel="stylesheet" href="css/font-awesome.min.css" media="screen">
    <link rel="stylesheet" href="css/animate-css/animate.min.css" media="screen">
    <link rel="stylesheet" href="css/lobipanel/lobipanel.min.css" media="screen">
    <link rel="stylesheet" href="css/prism/prism.css" media="screen">
    <link rel="stylesheet" type="text/css" href="js/DataTables/datatables.min.css"/>
    <link rel="stylesheet" href="css/main.css" media="screen">
    <script src="js/modernizr/modernizr.min.js"></script>
    <script src="js/jquery.js" type="text/javascript"></script>
    <script src="https://cdn.ckeditor.com/4.16.2/standard/ckeditor.js"></script>
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
        <?php include('includes/topbar.php'); ?> 
        <!-- ========== WRAPPER FOR BOTH SIDEBARS & MAIN CONTENT ========== -->
        <div class="content-wrapper">
            <div class="content-container">
                <?php include('includes/leftbar.php'); ?>  
                <div class="main-page">
                    <div class="container-fluid">
                        <div class="row page-title-div">
                            <div class="col-md-6">
                                <h2 class="title">Edit Exam Questions</h2>
                            </div>
                        </div>
                        <!-- /.row -->
                        <div class="row breadcrumb-div">
                            <div class="col-md-6">
                                <ul class="breadcrumb">
                                    <li><a href="dashboard.php"><i class="fa fa-home"></i> Home</a></li>
                                    <li> Exams </li>
                                    <li><a href="manage-exams.php"> Manage Exams </a> </li>
                                    <li class="active">Edit Exam Questions</li>
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
                                        <div class="panel-heading"></div>

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
                                            <form class="form-horizontal title1" name="form" action="update-exam-questions.php?q=addqns&n=<?php echo $n; ?>&eid=<?php echo $eid; ?>&ch=4" method="POST" enctype="multipart/form-data">

                                                <?php
                                                // Prepare to fetch data for each question
                                                for ($i = 1; $i <= $n; $i++) {
                                                    // Query to get question details
                                                    $query_question = $dbh->prepare("SELECT qns, image, qid FROM questions WHERE eid = :eid AND sn = :sn");
                                                    $query_question->bindParam(':eid', $eid, PDO::PARAM_STR);
                                                    $query_question->bindParam(':sn', $i, PDO::PARAM_INT);
                                                    $query_question->execute();
                                                    $question = $query_question->fetch(PDO::FETCH_ASSOC);

                                                    if ($question) {
                                                        $qns_value = htmlspecialchars($question['qns']);
                                                        $image_blob = $question['image'];
                                                        $qid = $question['qid'];

                                                        // Prepare image data for display if it exists
                                                        $image_src = '';
                                                        if ($image_blob) {
                                                            $image_src = 'data:image/jpeg;base64,' . base64_encode($image_blob);
                                                        }

                                                        // Query to get options for the question
                                                        $query_options = $dbh->prepare("SELECT optionid, option FROM options WHERE qid = :qid ORDER BY optionid");
                                                        $query_options->bindParam(':qid', $qid, PDO::PARAM_STR);
                                                        $query_options->execute();
                                                        $options = $query_options->fetchAll(PDO::FETCH_ASSOC);

                                                        // Query to get the correct answer
                                                        $query_answer = $dbh->prepare("SELECT ansid FROM answer WHERE qid = :qid");
                                                        $query_answer->bindParam(':qid', $qid, PDO::PARAM_STR);
                                                        $query_answer->execute();
                                                        $answer = $query_answer->fetch(PDO::FETCH_ASSOC);
                                                        $correct_answer = $answer ? $answer['ansid'] : '';

                                                        ?>
                                                        <div class="row">
                                                            <b>Question number <?php echo $i; ?>:</b><br />
                                                            
                                                            <!-- Image upload button and preview -->
                                                            <div class="form-group">
                                                                <label class="col-md-12 control-label"></label>
                                                                <div class="col-md-12">
                                                                    <div id="image-preview-<?php echo $i; ?>" style="display: <?php echo $image_src ? 'block' : 'none'; ?>;">
                                                                        <img id="preview-img-<?php echo $i; ?>" src="<?php echo htmlspecialchars($image_src); ?>" alt="Image Preview" style="max-width: 100%; max-height: 200px;" />
                                                                    </div>
                                                                    <input type="file" name="image<?php echo $i; ?>" id="image<?php echo $i; ?>" accept="image/*" onchange="readURL(this, <?php echo $i; ?>);" />
                                                                </div>
                                                            </div>

                                                            <!-- Question text input -->
                                                            <div class="form-group">
                                                                <label class="col-md-12 control-label"></label>
                                                                <div class="col-md-12">
                                                                    <textarea rows="3" cols="5" name="qns<?php echo $i; ?>" class="form-control" required="required" placeholder="Write question number <?php echo $i; ?> here..."><?php echo $qns_value; ?></textarea>
                                                                </div>
                                                            </div>

                                                            <script>
                                                                CKEDITOR.replace("qns<?php echo $i; ?>");
                                                            </script>

                                                            <!-- Options input fields -->
                                                            <?php foreach ($options as $index => $option) {
                                                                $option_label = chr(97 + $index); // a, b, c, d
                                                                $option_value = htmlspecialchars($option['option']);
                                                            ?>
                                                                <div class="form-group">
                                                                    <label class="col-md-12 control-label" for="<?php echo $i . $option_label; ?>"></label>
                                                                    <div class="col-md-12">
                                                                        <input id="<?php echo $i . $option_label; ?>" name="<?php echo $i . $option_label; ?>" placeholder="Enter option <?php echo $option_label; ?>" class="form-control input-md" type="text" value="<?php echo $option_value; ?>" required="required">
                                                                    </div>
                                                                </div>
                                                            <?php } ?>

                                                            <!-- Correct answer selection -->
                                                            <br /><b>Correct answer</b>:<br />
                                                            <select id="ans<?php echo $i; ?>" name="ans<?php echo $i; ?>" placeholder="Choose correct answer" class="form-control input-md" required>
                                                                <option value="" disabled <?php echo !$correct_answer ? 'selected' : ''; ?>>Choose correct answer</option>
                                                                <option value="a" <?php echo $correct_answer == 'a' ? 'selected' : ''; ?>>Option a</option>
                                                                <option value="b" <?php echo $correct_answer == 'b' ? 'selected' : ''; ?>>Option b</option>
                                                                <option value="c" <?php echo $correct_answer == 'c' ? 'selected' : ''; ?>>Option c</option>
                                                                <option value="d" <?php echo $correct_answer == 'd' ? 'selected' : ''; ?>>Option d</option>
                                                            </select>
                                                        </div>
                                                        <?php
                                                    }
                                                }
                                                ?>

                                                <!-- Submit Button -->
                                                <div class="form-group">
                                                    <div class="col-md-12">
                                                        <button type="submit" class="btn btn-primary">Update Questions</button>
                                                    </div>
                                                </div>
                                            </form>
                                        </div>
                                    </div>
                                </div>
                                <!-- /.col-md-12 -->
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

    <!-- Script to preview uploaded images -->
    <script type="text/javascript">
        function readURL(input, questionNumber) {
            if (input.files && input.files[0]) {
                var reader = new FileReader();

                reader.onload = function (e) {
                    var previewDiv = document.getElementById('image-preview-' + questionNumber);
                    var previewImg = document.getElementById('preview-img-' + questionNumber);

                    previewImg.src = e.target.result;
                    previewDiv.style.display = 'block';
                };

                reader.readAsDataURL(input.files[0]);
            }
        }
    </script>
</body>
</html>
