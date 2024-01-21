<?php
    error_reporting(E_ALL);
    ini_set('display_errors', 1);

    // Include the file containing the database connection
    require_once '../private/dbconnect.php';

    if ($_SERVER["REQUEST_METHOD"] === "POST") {
        // Check if the task ID and list ID are sent via POST
        if (isset($_POST['task_id']) && isset($_GET['list_id'])) {
            // Sanitize input to prevent SQL injection
            $task_id = filter_var($_POST['task_id'], FILTER_SANITIZE_NUMBER_INT);
            $list_id = filter_var($_GET['list_id'], FILTER_SANITIZE_NUMBER_INT);

            // Update the task's archive status to 0 (restored) for the specified list and task ID
            $sql = "UPDATE tasks SET archive = 0 WHERE `id` = :task_id AND `list-id` = :list_id";
            $stmt = $conn->prepare($sql);
            $stmt->bindParam(':task_id', $task_id);
            $stmt->bindParam(':list_id', $list_id);

            if ($stmt->execute()) {
                // Redirect back to the list page after restoring the task
                header("Location: ../index.php?page=list&list_id=" . $list_id);
                exit();
            } else {
                // Handle the case if the task restoration fails
                echo "Failed to restore the task. Please try again.";
                // You can add further error handling or redirect as needed
                exit();
            }
        } else {
            // Handle cases where task ID or list ID is not provided
            echo "Task ID or List ID is missing.";
            // You can add further error handling or redirect as needed
            exit();
        }
    } else {
        // Handle cases where the request method is not POST
        echo "Invalid request method.";
        // You can add further error handling or redirect as needed
        exit();
    }
?>
