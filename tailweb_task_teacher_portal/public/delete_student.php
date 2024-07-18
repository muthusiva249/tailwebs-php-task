<?php
session_start();
require '../includes/db_connect.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $id = $_POST['id'] ?? null;

    $response = [];

    // Logging request data
    error_log("ID to delete: $id");

    if ($id) {
        // Prepare and bind
        $query = "DELETE FROM students WHERE id = ?";
        if ($stmt = $conn->prepare($query)) {
            $stmt->bind_param("i", $id);

            // Execute the statement
            if ($stmt->execute()) {
                $response['success'] = true;
            } else {
                $response['success'] = false;
                $response['error'] = $stmt->error;
                error_log("SQL Error: " . $stmt->error);
            }
            $stmt->close();
        } else {
            $response['success'] = false;
            $response['error'] = $conn->error;
            error_log("Connection Error: " . $conn->error);
        }
    } else {
        $response['success'] = false;
        $response['error'] = "Missing required POST parameters";
    }

    $conn->close();

    // Send JSON response
    header('Content-Type: application/json');
    echo json_encode($response);
}
?>
