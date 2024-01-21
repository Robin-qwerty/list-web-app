<?php
    error_reporting(E_ALL);
    ini_set('display_errors', 1);

    require_once '../private/dbconnect.php';

    if ($_SERVER["REQUEST_METHOD"] == "POST") {
        if (isset($_POST['list_name']) && !empty($_POST['list_name'])) {
            $listName = $_POST['list_name'];

            // Prepare and execute the query to insert a new list into the 'lists' table
            $sql = "INSERT INTO lists (name) VALUES (:listName)";
            $stmt = $conn->prepare($sql);
            $stmt->bindParam(':listName', $listName);

            if ($stmt->execute()) {
                // If the list is added successfully, redirect back to the home page
                header("Location: ../index.php");
                exit();
            } else {
                echo "Error adding list: " . $stmt->errorInfo()[2];
            }
        } else {
            echo "List name is required"; // Display an error if the list name is empty
        }
    }
?>
