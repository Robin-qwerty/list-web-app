<?php
    error_reporting(E_ALL);
    ini_set('display_errors', 1);

    require_once '../private/dbconnect.php';

    // Check if the form is submitted and insert the task into the database
    if ($_SERVER["REQUEST_METHOD"] == "POST") {
        $task = $_POST["task"];
        $listId = $_GET['list_id'];

        // Use prepared statements to prevent SQL injection
        $stmt = $conn->prepare("INSERT INTO tasks (`list-id`, task_name) VALUES (:listId, :task)");
        $stmt->bindParam(':listId', $listId);
        $stmt->bindParam(':task', $task);

        if ($stmt->execute()) {
            header("Location: ../index.php?page=list&list_id=" . $listId); // Redirect back to the specific list
            exit();
        } else {
            echo "Error: " . $stmt->errorInfo()[2];
        }
    }
?>
