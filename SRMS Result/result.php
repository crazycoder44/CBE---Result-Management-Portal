<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Student Result Portal</title>
    <!-- Bootstrap CSS -->
    <link href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">
    <style>
    body {
        background-image: url('Dominican.jpg'); /* Replace with the actual path to your image */;
        background-size: cover;
        background-repeat: no-repeat;
        color: #333;
        font-family: 'Arial', sans-serif;
    }
    .card {
        border-radius: 15px;
        box-shadow: 0 8px 16px rgba(0, 0, 0, 0.2);
        background: #ffffff;
        border: 1px solid #ddd;
        padding: 20px;
    }
    .card-header {
        border-top-left-radius: 15px;
        border-top-right-radius: 15px;
        background: #D4AF37; /* Gold */
        color: #fff;
        text-align: center;
        font-size: 1.5rem;
        padding: 15px;
    }
    .btn-primary {
        background: #D4AF37; /* Gold */
        border: none;
        box-shadow: 0 4px 8px rgba(0, 0, 0, 0.2);
        transition: all 0.3s ease;
    }
    .btn-primary:hover {
        background: #b89c31;
        box-shadow: 0 6px 12px rgba(0, 0, 0, 0.3);
    }
    .btn-secondary {
        background: #6c757d;
        border: none;
        box-shadow: 0 4px 8px rgba(0, 0, 0, 0.2);
        transition: all 0.3s ease;
    }
    .btn-secondary:hover {
        background: #5a6268;
        box-shadow: 0 6px 12px rgba(0, 0, 0, 0.3);
    }
    .form-control {
        border-radius: 5px;
        box-shadow: inset 0 1px 2px rgba(0, 0, 0, 0.1);
        transition: box-shadow 0.3s ease;
    }
    .form-control:focus {
        box-shadow: inset 0 1px 5px rgba(0, 0, 0, 0.2);
        border-color: #D4AF37; /* Gold */
    }
    .message {
        margin-top: 10px;
        color: red;
        font-weight: bold;
    }
    /* Responsive adjustments */
    @media (max-width: 767.98px) {
        .card-header {
            font-size: 1.25rem;
            padding: 10px;
        }
        .card {
            padding: 15px;
        }
    }
</style>

</head>
<body>
<div class="container mt-5">
    <div class="row justify-content-center">
        <div class="col-md-8 col-sm-12">
            <div class="card">
                <div class="card-header">
                    <h4 class="mb-0">Result Portal</h4>
                </div>
                <div class="card-body">
                    <form id="resultForm" action="" method="post">
                        <div class="form-group">
                            <label for="email">Email</label>
                            <input type="email" id="email" name="email" class="form-control" required>
                        </div>
                        <div class="form-group">
                            <label for="sid">Student No</label>
                            <input type="text" id="sid" name="sid" class="form-control" required>
                        </div>
                        <div class="form-group">
                            <label for="session">Select Session</label>
                            <select id="session" name="session" class="form-control" required>
                                <option value="">Select Session</option>
                                <?php
                                // Database connection
                                include_once 'dbConnection.php';

                                // Fetch distinct sessions from the results table
                                $session_query = mysqli_query($con, "SELECT DISTINCT session FROM results ORDER BY session");
                                while ($row = mysqli_fetch_array($session_query)) {
                                    $session = $row['session'];
                                    echo '<option value="'.$session.'">'.$session.'</option>';
                                }
                                ?>
                            </select>
                        </div>
                        <div class="form-group">
                            <label for="term">Select Term</label>
                            <select id="term" name="term" class="form-control" required>
                                <option value="">Select Term</option>
                                <?php
                                // Fetch terms from the terms table
                                $term_query = mysqli_query($con, "SELECT termid, term FROM terms ORDER BY term");
                                while ($row = mysqli_fetch_array($term_query)) {
                                    $termid = $row['termid'];
                                    $term = $row['term'];
                                    echo '<option value="'.$termid.'">'.$term.'</option>';
                                }
                                ?>
                            </select>
                        </div>
                        <button type="button" class="btn btn-primary btn-block" onclick="submitForm('prep_class.php')">Download for Class</button>
                        <button type="button" class="btn btn-secondary btn-block mt-2" onclick="submitForm('prep_arm.php')">Download ARM</button>
                        <div class="message"></div> <!-- Message div -->
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
<script src="https://code.jquery.com/jquery-3.2.1.slim.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.11.0/umd/popper.min.js"></script>
<script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/js/bootstrap.min.js"></script>
<script>
    function submitForm(actionUrl) {
        var sid = document.getElementById('sid').value;
        
        // Perform AJAX call to check if sid is in the blacklist
        var xhr = new XMLHttpRequest();
        xhr.open('POST', 'check_blacklist.php', true);
        xhr.setRequestHeader('Content-type', 'application/x-www-form-urlencoded');
        xhr.onreadystatechange = function () {
            if (xhr.readyState == 4 && xhr.status == 200) {
                if (xhr.responseText === 'blacklisted') {
                    document.querySelector('.message').textContent = 'Please pay your school fees to check results';
                } else {
                    var form = document.getElementById('resultForm');
                    form.action = actionUrl;
                    form.submit();
                }
            }
        };
        xhr.send('sid=' + encodeURIComponent(sid));
    }
</script>
</body>
</html>
