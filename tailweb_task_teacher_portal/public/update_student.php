<?php
session_start();
require '../includes/db_connect.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $id = $_POST['id'] ?? null;
    $name = $_POST['name'] ?? null;
    $subject = $_POST['subject'] ?? null;
    $marks = $_POST['marks'] ?? null;

    $response = [];

    // Logging request data
    error_log("ID: $id, Name: $name, Subject: $subject, Marks: $marks");

    if ($id && $name && $subject && $marks) {
        // Prepare and bind
        $query = "UPDATE students SET name = ?, subject = ?, marks = ? WHERE id = ?";
        if ($stmt = $conn->prepare($query)) {
            $stmt->bind_param("ssii", $name, $subject, $marks, $id);

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
