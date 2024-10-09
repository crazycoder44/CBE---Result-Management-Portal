<?php
session_start();
error_reporting(0);


$staffid = $_SESSION['staffid']; // Retrieve staff ID from session

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
    <title>Staff Portal | Add Exam Questions</title>
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
        <?php include('includes/topbar.php');?> 
        <!-- ========== WRAPPER FOR BOTH SIDEBARS & MAIN CONTENT ========== -->
        <div class="content-wrapper">
            <div class="content-container">
                <?php include('includes/leftbar.php');?>  
                <div class="main-page">
                    <div class="container-fluid">
                        <div class="row page-title-div">
                            <div class="col-md-6">
                                <h2 class="title">Add Exam Questions</h2>
                            </div>
                        </div>
                        <!-- /.row -->
                        <div class="row breadcrumb-div">
                            <div class="col-md-6">
                                <ul class="breadcrumb">
                                    <li><a href="dashboard.php"><i class="fa fa-home"></i> Home</a></li>
                                    <li> Exams </li>
                                    <li class="active">Add Exam Questions</li>
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
                                            <!-- <div class="panel-title">
                                                <h5>Add Exam Questions</h5>
                                            </div> -->
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
                                            <div class="row">
                                                <form class="form-horizontal title1" name="form" action="update.php?q=addqns&n=<?php echo htmlentities($n); ?>&eid=<?php echo htmlentities($eid); ?>&ch=4" method="POST" enctype="multipart/form-data">
                                                
                                                    <?php
                                                    // Get values from URL
                                                    $eid = @$_GET['eid'];
                                                    $n = @$_GET['n'];

                                                    // Loop to generate form fields for each question
                                                    for ($i = 1; $i <= $n; $i++) {
                                                    ?>
                                                    
                                                    <b>Question number&nbsp;<?php echo $i; ?>&nbsp;:</b><br />
                                                    
                                                    <!-- Image upload button and preview -->
                                                    <div class="form-group">
                                                        <label class="col-md-12 control-label" for="qns<?php echo $i; ?>"></label>
                                                        <div class="col-md-12">
                                                            <div id="image-preview-<?php echo $i; ?>" style="display: none;">
                                                                <img id="preview-img-<?php echo $i; ?>" src="#" alt="Image Preview" style="max-width: 100%; max-height: 200px;" />
                                                            </div>
                                                            <input type="file" name="image<?php echo $i; ?>" id="image<?php echo $i; ?>" accept="image/*" onchange="readURL(this, <?php echo $i; ?>);" />
                                                        </div>
                                                    </div>

                                                    <!-- Question text input -->
                                                    <div class="form-group">
                                                        <label class="col-md-12 control-label" for="qns<?php echo $i; ?>"></label>
                                                        <div class="col-md-12">
                                                            <textarea rows="3" cols="5" name="qns<?php echo $i; ?>" class="form-control" required="required" placeholder="Write question number <?php echo $i; ?> here..."></textarea>
                                                        </div>
                                                    </div>

                                                    <!-- CKEditor for question input -->
                                                    <script>
                                                        CKEDITOR.replace("qns<?php echo $i; ?>");
                                                    </script>

                                                    <!-- Options input fields -->
                                                    <?php for ($j = 1; $j <= 4; $j++) {
                                                        $option_label = chr(96 + $j); // a, b, c, d
                                                    ?>
                                                    <div class="form-group">
                                                        <label class="col-md-12 control-label" for="<?php echo $i . $j; ?>"></label>
                                                        <div class="col-md-12">
                                                            <input id="<?php echo $i . $j; ?>" name="<?php echo $i . $j; ?>" placeholder="Enter option <?php echo $option_label; ?>" class="form-control input-md" type="text" required="required">
                                                        </div>
                                                    </div>
                                                    <?php } ?>

                                                    <!-- Correct answer selection -->
                                                    <br /><b>Correct answer</b>:<br />
                                                    <select id="ans<?php echo $i; ?>" name="ans<?php echo $i; ?>" placeholder="Choose correct answer" class="form-control input-md" required>
                                                        <option value="" disabled selected>Select answer for question <?php echo $i; ?></option>
                                                        <option value="a">Option a</option>
                                                        <option value="b">Option b</option>
                                                        <option value="c">Option c</option>
                                                        <option value="d">Option d</option>
                                                    </select><br /><br />
                                                    
                                                    <?php } ?>

                                                    <div class="form-group">
                                                        <label class="col-md-12 control-label" for=""></label>
                                                        <div class="col-md-12">
                                                            <input type="submit" style="margin-left:45%" class="btn btn-primary" value="Submit Quiz" />
                                                        </div>
                                                    </div>
                                                </form>
                                            </div>
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
</body>
</html>

<?php } ?>
