<?php session_start();
error_reporting(0);
include('includes/config.php');
if(strlen($_SESSION['alogin'])=="")
{   header("Location: index.php"); 
}else{
  
?>
<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="utf-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
    	<meta name="viewport" content="width=device-width, initial-scale=1">
        <title>Admin View Classes</title>
        <link rel="stylesheet" href="css/bootstrap.min.css" media="screen" >
        <link rel="stylesheet" href="css/font-awesome.min.css" media="screen" >
        <link rel="stylesheet" href="css/animate-css/animate.min.css" media="screen" >
        <link rel="stylesheet" href="css/lobipanel/lobipanel.min.css" media="screen" >
        <link rel="stylesheet" href="css/prism/prism.css" media="screen" > <!-- USED FOR DEMO HELP - YOU CAN REMOVE IT -->
        <link rel="stylesheet" type="text/css" href="js/DataTables/datatables.min.css"/>
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
            /* Modal Container */
            .modal {  
                display: none;
                position: fixed;
                left: 0;
                top: 0;
                width: 100%;
                height: 100%;
                background-color: rgba(0, 0, 0, 0.5);
                justify-content: center;
                align-items: center;
                z-index: 1000;
            }

            /* Modal Content */
            .modal-content {
                background-color: #fff;
                padding: 30px;
                border-radius: 10px;
                width: 300px;
                position: relative;
                display: flex;
                flex-direction: column;
                align-items: center;
                box-shadow: 0 4px 8px rgba(0, 0, 0, 0.2);
                animation: fadeIn 0.3s ease-out;
            }

            /* Fade In Animation */
            @keyframes fadeIn {
                from { opacity: 0; transform: scale(0.95); }
                to { opacity: 1; transform: scale(1); }
            }

            /* Close Button */
            .close {
                position: absolute;
                top: 15px;
                right: 15px;
                cursor: pointer;
                color: #333;
                font-size: 20px;
            }

            /* Radio Button Container */
            .radio-container {
                display: flex;
                align-items: center;
                margin: 10px 0;
                width: 100%;
            }

            /* Stylish Radio Button */
            .radio-container input[type="radio"] {
                margin-right: 10px;
                width: 20px;
                height: 20px;
                accent-color: #007bff; /* Primary color for the radio button */
            }

            /* OK Button */
            .modal-content button {
                margin-top: 20px;
                padding: 10px 20px;
                background-color: #007bff;
                border: none;
                color: white;
                border-radius: 5px;
                cursor: pointer;
                transition: background-color 0.3s ease;
            }

            .modal-content button:hover {
                background-color: #0056b3;
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
                                    <h2 class="title">View Classes</h2>
                                
                                </div>
                                
                                <!-- /.col-md-6 text-right -->
                            </div>
                            <!-- /.row -->
                            <div class="row breadcrumb-div">
                                <div class="col-md-6">
                                    <ul class="breadcrumb">
            							<li><a href="dashboard.php"><i class="fa fa-home"></i> Home</a></li>
                                        <li> <a href="manage-classes.php">Classes</a></li>
            							<li class="active">View Classes</li>
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
                                                <div class="panel-title">
                                                    <h5>View Classes Info</h5>
                                                </div>
                                            </div>
                                            <?php if($msg){ ?>
<div class="alert alert-success left-icon-alert" role="alert">
    <strong>Well done!</strong><?php echo htmlentities($msg); ?>
</div>
<?php } else if($error){ ?>
<div class="alert alert-danger left-icon-alert" role="alert">
    <strong>Oh snap!</strong> <?php echo htmlentities($error); ?>
</div>
<?php } ?>

<div class="panel-body p-20">
    <table id="example" class="display table table-striped table-bordered" cellspacing="0" width="100%">
        <thead>
            <tr>
                <th><input type="checkbox" id="select-all"></th>
                <th>#</th>
                <th>Student ID</th>
                <th>First Name</th>
                <th>Last Name</th>
                <th>Mobile</th>
                <th>Email</th>
                <th>Action</th>
            </tr>
        </thead>
        <tbody>
        <?php 
        $cid = htmlspecialchars(trim($_GET['classid']));
        // Fetch students based on classid
        $sql = "SELECT sid, fname, midname, lname, mobile, email 
                FROM students 
                WHERE classid = :cid";
        $query = $dbh->prepare($sql);
        $query->bindParam(':cid', $cid, PDO::PARAM_STR);
        $query->execute();
        $results = $query->fetchAll(PDO::FETCH_OBJ);
        $cnt = 1;

        if ($query->rowCount() > 0) {
            foreach ($results as $result) {
                // Check if the student is already in the blacklist
                $sqlCheckBlacklist = "SELECT sid FROM blacklist WHERE sid = :sid";
                $queryCheckBlacklist = $dbh->prepare($sqlCheckBlacklist);
                $queryCheckBlacklist->bindParam(':sid', $result->sid, PDO::PARAM_STR);
                $queryCheckBlacklist->execute();
                $isBlacklisted = $queryCheckBlacklist->rowCount() > 0; 
                ?>
                <tr>
                    <td>
                        <input type="checkbox" class="select-row" data-sid="<?php echo htmlentities($result->sid); ?>" data-email="<?php echo htmlentities($result->email); ?>">
                    </td>
                    <td><?php echo htmlentities($cnt); ?></td>
                    <td><?php echo htmlentities($result->sid); ?></td>
                    <td><?php echo htmlentities($result->fname); ?></td>
                    <td><?php echo htmlentities($result->lname); ?></td>
                    <td><?php echo htmlentities($result->mobile); ?></td>
                    <td><?php echo htmlentities($result->email); ?></td>
                    <td>
                        <a href="edit-student.php?stid=<?php echo htmlentities($result->sid); ?>" class="btn btn-info btn-xs" style="background-color: #36454F;"> Profile </a>
                        <button class="btn btn-blacklist btn-xs" data-sid="<?php echo htmlentities($result->sid); ?>" style="background-color: #3D0C02; color: white;">
                            <?php echo $isBlacklisted ? 'Unblacklist' : 'Blacklist'; ?>
                        </button>                    
                    </td>
                </tr>
                <?php $cnt++; 
            }
        } ?>
        </tbody>
    </table>
     <!-- Promote Button -->
     <button id="promote-button" class="btn btn-success" disabled>Promote</button>

    <!-- Modal -->
    <div id="promote-modal" class="modal">
        <div class="modal-content">
            <span class="close">&times;</span>
            <h3>Select Class for Promotion</h3>
            <form id="promote-form">
                <?php
                // Fetch all classes for the radio buttons in the modal
                $sql = "SELECT classid FROM class";
                $query = $dbh->prepare($sql);
                $query->execute();
                $classResults = $query->fetchAll(PDO::FETCH_OBJ);

                if ($query->rowCount() > 0) {
                    foreach ($classResults as $classResult) { ?>
                        <div class="radio-container">
                            <input type="radio" id="class-<?php echo htmlentities($classResult->classid); ?>" name="classid" value="<?php echo strtoupper(htmlentities($classResult->classid)); ?>">
                            <label for="class-<?php echo htmlentities($classResult->classid); ?>"><?php echo strtoupper(htmlentities($classResult->classid)); ?></label>
                        </div>
                    <?php }
                } ?>
                <button type="submit" class="btn btn-primary">OK</button>
            </form>
        </div>
    </div>


    
    </div>
</div>





<?php $cnt=$cnt+1;} ?>
                                                       
                                                    
                                                    </tbody>
                                                </table>

                                         
                                                <!-- /.col-md-12 -->
                                            </div>
                                        </div>
                                    </div>
                                    <!-- /.col-md-6 -->

                                                               
                                                </div>
                                                <!-- /.col-md-12 -->
                                            </div>
                                        </div>
                                        <!-- /.panel -->
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

                $('#example2').DataTable( {
                    "scrollY":        "300px",
                    "scrollCollapse": true,
                    "paging":         false
                } );

                $('#example3').DataTable();
            });
        </script>
        <!-- JavaScript -->
        <script>
        // Enable or disable the Promote button based on checkbox selection
        const promoteButton = document.getElementById('promote-button');
        const checkboxes = document.querySelectorAll('.select-row');
        const selectAllCheckbox = document.getElementById('select-all');

        checkboxes.forEach(checkbox => {
            checkbox.addEventListener('change', () => {
                promoteButton.disabled = !document.querySelectorAll('.select-row:checked').length;
            });
        });

        selectAllCheckbox.addEventListener('change', () => {
            checkboxes.forEach(checkbox => {
                checkbox.checked = selectAllCheckbox.checked;
            });
            promoteButton.disabled = !document.querySelectorAll('.select-row:checked').length;
        });

        // Modal handling
        const modal = document.getElementById('promote-modal');
        const closeModal = document.querySelector('.modal .close');

        promoteButton.onclick = function() {
            modal.style.display = "flex";
        };

        closeModal.onclick = function() {
            modal.style.display = "none";
        };

        window.onclick = function(event) {
            if (event.target == modal) {
                modal.style.display = "none";
            }
        };

        // Handle form submission
        document.getElementById('promote-form').onsubmit = function(event) {
            event.preventDefault();  // Prevent the default form submission

            const selectedClassId = document.querySelector('input[name="classid"]:checked');
            const selectedStudents = Array.from(document.querySelectorAll('.select-row:checked')).map(checkbox => ({
                sid: checkbox.getAttribute('data-sid'),
                email: checkbox.getAttribute('data-email')
            }));

            if (selectedClassId && selectedStudents.length > 0) {
                fetch('promote_students.php', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json'
                    },
                    body: JSON.stringify({ 
                        classid: selectedClassId.value, 
                        students: selectedStudents 
                    })
                })
                .then(response => response.text()) // Change to .text() to inspect the response
                .then(data => {
                    try {
                        const jsonData = JSON.parse(data); // Manually parse the JSON
                        if (jsonData.success) {
                            alert('Students promoted successfully!');
                            modal.style.display = "none";
                            window.location.reload();
                        } else {
                            alert('Failed to promote students: ' + jsonData.message);
                        }
                    } catch (e) {
                        console.error('Error:', e);
                        alert('Invalid JSON response. Please check the console for details.');
                        console.log('Raw response:', data); // Log the raw response for debugging
                    }
                })
                .catch(error => {
                    console.error('Error:', error);
                    alert('An error occurred during promotion.');
                });
            } else {
                alert('Please select a class and at least one student.');
            }
        };

        </script>
    </body>
</html>
<?php  ?>

