<?php
session_start();
if (!isset($_SESSION['user_id'])) {
    header('Location: login.php');
    exit();
}

require '../includes/db_connect.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name = $_POST['name'];
    $subject = $_POST['subject'];
    $marks = $_POST['marks'];

    $stmt = $conn->prepare("SELECT id, marks FROM students WHERE name = ? AND subject = ?");
    $stmt->bind_param("ss", $name, $subject);
    $stmt->execute();
    $stmt->store_result();
    // echo '<pre>';print_r($stmt);die;

    if ($stmt->num_rows > 0) {
        $stmt->bind_result($id, $existing_marks);
        $stmt->fetch();
        $new_marks = $existing_marks + $marks;
        $update_stmt = $conn->prepare("UPDATE students SET marks = ? WHERE id = ?");
        $update_stmt->bind_param("ii", $new_marks, $id);
        $update_stmt->execute();
        echo 'Updated '.$name.' record.';
    } else {
        $insert_stmt = $conn->prepare("INSERT INTO students (name, subject, marks) VALUES (?, ?, ?)");
        // echo "<pre>";print_r($insert_stmt);die;
        $insert_stmt->bind_param("ssi", $name, $subject, $marks);
        $insert_stmt->execute();
        echo 'New student record inserted.';
    }

    // header('Location: home.php');
    // exit();
}
$conn->close();
?>
