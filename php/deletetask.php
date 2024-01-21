<?php
    error_reporting(E_ALL);
    ini_set('display_errors', 1);

    // Include the file containing the database connection
    require_once '../private/dbconnect.php';

    if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['task_id']) && isset($_POST['archive_value'])) {
        $listId = $_GET['list_id'];
        $task_id = $_POST['task_id'];
        $archive_value = $_POST['archive_value'];

        // Update the 'archive' column based on the clicked button
        $sql = "UPDATE tasks SET archive = :archive_value WHERE id = :task_id";
        $stmt = $conn->prepare($sql);
        $stmt->bindParam(':archive_value', $archive_value);
        $stmt->bindParam(':task_id', $task_id);

        if ($stmt->execute()) {
            header("Location: ../index.php?page=list&list_id=" . $listId);
            exit();
        } else {
            echo "Error: " . $stmt->errorInfo()[2];
        }
    }
?>
