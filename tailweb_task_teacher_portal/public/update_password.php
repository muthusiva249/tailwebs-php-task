<?php
session_start();
require '../includes/db_connect.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = $_POST['username'];
    $password = $_POST['password'];

    // Hash the new password for security
    $hashed_password = password_hash($password, PASSWORD_DEFAULT);

    $response = [];

    // Check if the user exists
    $query = "SELECT * FROM users WHERE username = ?";
    if ($stmt = $conn->prepare($query)) {
        $stmt->bind_param("s", $username);
        $stmt->execute();
        $result = $stmt->get_result();
        if ($result->num_rows > 0) {
            // User exists, update the password
            $update_query = "UPDATE users SET password = ? WHERE username = ?";
            if ($update_stmt = $conn->prepare($update_query)) {
                $update_stmt->bind_param("ss", $hashed_password, $username);
                if ($update_stmt->execute()) {
                    $response['success'] = true;
                } else {
                    $response['success'] = false;
                    $response['error'] = $update_stmt->error;
                }
                $update_stmt->close();
            } else {
                $response['success'] = false;
                $response['error'] = $conn->error;
            }
        } else {
            // User not found
            $response['success'] = false;
            $response['message'] = "User not found. Please register first.";
        }
        $stmt->close();
    } else {
        $response['success'] = false;
        $response['error'] = $conn->error;
    }

    $conn->close();

    // Send JSON response
    header('Content-Type: application/json');
    echo json_encode($response);
}
?>

