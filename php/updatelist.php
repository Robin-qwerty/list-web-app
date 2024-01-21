<?php
    error_reporting(E_ALL);
    ini_set('display_errors', 1);

    require_once '../private/dbconnect.php';

    if ($_SERVER["REQUEST_METHOD"] === "POST") {
        // Get the list ID and new name from the POST parameters
        $listId = $_POST['list_id'];
        $newName = $_POST['new_name'];
    
        // Prepare and execute the SQL update query
        $sql = "UPDATE lists SET `name` = :newName WHERE `id` = :listId";
        $stmt = $conn->prepare($sql);
        $stmt->bindParam(':newName', $newName);
        $stmt->bindParam(':listId', $listId);
    
        if ($stmt->execute()) {
            // Redirect back to the referring page after successful update
            header('Location: ' . $_SERVER['HTTP_REFERER']);
            exit();
        } else {
            // Redirect back to the referring page with an error message if the update fails
            header('Location: ' . $_SERVER['HTTP_REFERER'] . '?error=update_failed');
            exit();
        }
    } else {
        // If the request method is not POST, redirect back to the referring page with an error message
        header('Location: ' . $_SERVER['HTTP_REFERER'] . '?error=method_not_allowed');
        exit();
    }
?>
