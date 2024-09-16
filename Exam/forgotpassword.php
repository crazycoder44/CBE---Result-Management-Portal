<?php
session_start();
include_once 'dbConnection.php'; // Ensure this file contains your database connection details



$response = array('status' => 'error', 'message' => '');

// Check connection
if ($con->connect_error) {
    $response['message'] = "Connection failed: " . $con->connect_error;
    echo json_encode($response);
    exit();
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['email'], $_POST['sid'], $_POST['new_password'], $_POST['confirm_password'])) {
        $email = filter_var($_POST['email'], FILTER_SANITIZE_EMAIL);
        $sid = htmlspecialchars($_POST['sid'], ENT_QUOTES, 'UTF-8');
        $new_password = htmlspecialchars($_POST['new_password'], ENT_QUOTES, 'UTF-8');
        $confirm_password = htmlspecialchars($_POST['confirm_password'], ENT_QUOTES, 'UTF-8');

        // Validate passwords match
        if ($new_password !== $confirm_password) {
            $response['message'] = "Passwords do not match.";
        } else {
            // Hash the new password using md5
            $hashed_password = md5($new_password);

            // Validate the user
            $stmt = $con->prepare("SELECT * FROM students WHERE TRIM(email) = ? AND TRIM(sid) = ?");
            if ($stmt === false) {
                $response['message'] = "Prepare failed: (" . $con->errno . ") " . $con->error;
            } else {
                $stmt->bind_param('ss', $email, $sid);
                if ($stmt->execute() === false) {
                    $response['message'] = "Execute failed: (" . $stmt->errno . ") " . $stmt->error;
                } else {
                    $result = $stmt->get_result();
                }
            }

            if ($result->num_rows > 0) {
                // User exists, update the password
                $update_stmt = $con->prepare("UPDATE students SET password = ? WHERE TRIM(email) = ? AND TRIM(sid) = ?");
                if ($update_stmt) {
                    $update_stmt->bind_param('sss', $hashed_password, $email, $sid);
                    if ($update_stmt->execute()) {
                        $response['status'] = 'success';
                        $response['message'] = "Password has been updated successfully.";
                    } else {
                        $response['message'] = "Error updating password. Please try again.";
                    }
                    $update_stmt->close();
                } else {
                    $response['message'] = "Error preparing statement for password update. Please try again.";
                }
            } else {
                $response['message'] = "Invalid Email or Student ID!";
            }
            $stmt->close();
        }
    } else {
        $response['message'] = "All fields are required.";
    }
    $con->close();
    echo json_encode($response);
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Forgot Password</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f0f0f0;
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
            margin: 0;
        }
        .forgot-password-container {
            background-color: #fff;
            padding: 20px;
            border-radius: 10px;
            box-shadow: 0 0 15px rgba(0, 0, 0, 0.2);
            width: 350px;
        }
        .forgot-password-container h2 {
            text-align: center;
            margin-bottom: 20px;
            color: #333;
        }
        .forgot-password-container label {
            display: block;
            margin-bottom: 5px;
            color: #555;
        }
        .forgot-password-container input[type="email"],
        .forgot-password-container input[type="text"],
        .forgot-password-container input[type="password"] {
            width: calc(100% - 20px);
            padding: 10px;
            margin-bottom: 10px;
            border: 1px solid #ccc;
            border-radius: 5px;
        }
        .forgot-password-container button {
            width: 100%;
            padding: 10px;
            background-color: #007BFF;
            color: white;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            box-shadow: 0 5px 10px rgba(0, 123, 255, 0.3);
        }
        .forgot-password-container button:hover {
            background-color: #0056b3;
        }
        .message {
            text-align: center;
            margin-top: 10px;
            color: red;
        }
    </style>
</head>
<body>
    <div class="forgot-password-container">
        <h2>Forgot Password</h2>
        <form id="forgot-password-form">
            <label for="email">Email:</label>
            <input type="email" id="email" name="email" required>

            <label for="sid">Student ID:</label>
            <input type="text" id="sid" name="sid" required>

            <label for="new_password">New Password:</label>
            <input type="password" id="new_password" name="new_password" required>

            <label for="confirm_password">Confirm Password:</label>
            <input type="password" id="confirm_password" name="confirm_password" required>

            <button type="submit">Reset Password</button>
        </form>
        <div class="message" id="message"></div>
    </div>

    <script>
        document.getElementById('forgot-password-form').addEventListener('submit', function(e) {
            e.preventDefault();
            const form = e.target;
            const formData = new FormData(form);
            const messageDiv = document.getElementById('message');

            fetch('', {
                method: 'POST',
                body: formData,
            })
            .then(response => response.json())
            .then(data => {
                if (data.status === 'success') {
                    messageDiv.style.color = 'green';
                    messageDiv.textContent = data.message;
                    setTimeout(() => {
                        window.location.href = 'index.php';
                    }, 2000);
                } else {
                    messageDiv.style.color = 'red';
                    messageDiv.textContent = data.message;
                }
            })
            .catch(error => {
                messageDiv.style.color = 'red';
                messageDiv.textContent = 'An error occurred. Please try again.';
                console.error('Error:', error);
            });
        });
    </script>
</body>
</html>
