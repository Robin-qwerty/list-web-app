<?php
    $listId = $_GET['list_id'];

    // Fetch the list name based on the provided list_id
    $stmtList = $conn->prepare("SELECT `name` FROM `lists` WHERE `id` = :listId");
    $stmtList->bindParam(':listId', $listId);
    $stmtList->execute();
    $listDetails = $stmtList->fetch(PDO::FETCH_ASSOC);
?>

<div class="container">
        <?php
        if (isset($_GET['list_id'])) {
            $listName = htmlspecialchars($listDetails['name']);
            $currentURL = "http://$_SERVER[HTTP_HOST]$_SERVER[REQUEST_URI]";
            $listURL = htmlentities($currentURL);
            
            echo '<a href="index.php" class="back-button"><svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24"><g fill="none" fill-rule="evenodd"><path d="M24 0v24H0V0zM12.593 23.258l-.011.002l-.071.035l-.02.004l-.014-.004l-.071-.035c-.01-.004-.019-.001-.024.005l-.004.01l-.017.428l.005.02l.01.013l.104.074l.015.004l.012-.004l.104-.074l.012-.016l.004-.017l-.017-.427c-.002-.01-.009-.017-.017-.018m.265-.113l-.013.002l-.185.093l-.01.01l-.003.011l.018.43l.005.012l.008.007l.201.093c.012.004.023 0 .029-.008l.004-.014l-.034-.614c-.003-.012-.01-.02-.02-.022m-.715.002a.023.023 0 0 0-.027.006l-.006.014l-.034.614c0 .012.007.02.017.024l.015-.002l.201-.093l.01-.008l.004-.011l.017-.43l-.003-.012l-.01-.01z"/><path fill="currentColor" d="M7.94 13.06a1.5 1.5 0 0 1 0-2.12l5.656-5.658a1.5 1.5 0 1 1 2.121 2.122L11.122 12l4.596 4.596a1.5 1.5 0 1 1-2.12 2.122z"/></g></svg></a> <br> <br>';
            echo "<h1>{$listName} <button class='share-button' onclick='copyToClipboard(\"{$listURL}\")'><svg width='24' height='24' viewBox='0 0 24 24'><path fill='currentColor' d='M18 22q-1.25 0-2.125-.875T15 19q0-.175.025-.363t.075-.337l-7.05-4.1q-.425.375-.95.588T6 15q-1.25 0-2.125-.875T3 12q0-1.25.875-2.125T6 9q.575 0 1.1.213t.95.587l7.05-4.1q-.05-.15-.075-.337T15 5q0-1.25.875-2.125T18 2q1.25 0 2.125.875T21 5q0 1.25-.875 2.125T18 8q-.575 0-1.1-.212t-.95-.588L8.9 11.3q.05.15.075.338T9 12q0 .175-.025.363T8.9 12.7l7.05 4.1q.425-.375.95-.587T18 16q1.25 0 2.125.875T21 19q0 1.25-.875 2.125T18 22m0-16q.425 0 .713-.287T19 5q0-.425-.288-.712T18 4q-.425 0-.712.288T17 5q0 .425.288.713T18 6M6 13q.425 0 .713-.288T7 12q0-.425-.288-.712T6 11q-.425 0-.712.288T5 12q0 .425.288.713T6 13m12 7q.425 0 .713-.288T19 19q0-.425-.288-.712T18 18q-.425 0-.712.288T17 19q0 .425.288.713T18 20m0-1'/></svg></h1>";
        }
    ?>

    <form class="addtaskform" action="php/addtask.php?list_id=<?php echo $listId; ?>" method="POST">
        <input type="text" name="task" id="taskInput" placeholder="Enter item" required autofocus>
        <button type="submit">Add</button>
    </form>
    <ul class="task-list">
<?php
    // Check if 'list_id' is provided in the URL
    if (isset($_GET['list_id'])) {
        // Retrieve tasks for the specified list from the database
        $sql = "SELECT * FROM tasks WHERE `list-id` = :listId ORDER BY archive ASC"; // Order tasks by 'archive' column
        $stmt = $conn->prepare($sql);
        $stmt->bindParam(':listId', $listId);
        $stmt->execute();

        $nonArchivedTasks = false; // Initialize a variable to track non-archived tasks

        if ($stmt->rowCount() > 0) {
            while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
                if ($row['archive'] == 2) {
                    continue; // Skip tasks with archive = 2
                }
                $nonArchivedTasks = true; // Set to true if at least one non-archived task is found

                echo "<li class='" . ($row['archive'] == 1 ? 'archived' : '') . "'>";
                echo "<span class='task-name" . ($row['archive'] == 1 ? ' archived-task' : '') . "'>" . htmlspecialchars($row['task_name']) . "</span>";
                
                if ($row['archive'] == 1) {
                    // Show a Restore button for archived tasks
                    echo "<form action='php/restoretask.php?list_id=".$row['list-id']."' method='POST'>";
                    echo "<input type='hidden' name='task_id' value='" . $row['id'] . "'>";
                    echo "<button type='submit' class='restore-btn' name='archive_value' value='0'>
                            <svg xmlns='http://www.w3.org/2000/svg' width='24' height='24' viewBox='0 0 24 24'><path fill='currentColor' d='M12 3a9 9 0 0 0-9 9H0l4 4l4-4H5a7 7 0 0 1 7-7a7 7 0 0 1 7 7a7 7 0 0 1-7 7c-1.5 0-2.91-.5-4.06-1.3L6.5 19.14A9.115 9.115 0 0 0 12 21a9 9 0 0 0 9-9a9 9 0 0 0-9-9m2 9a2 2 0 0 0-2-2a2 2 0 0 0-2 2a2 2 0 0 0 2 2a2 2 0 0 0 2-2'/></svg>    
                        </button>";
                    echo "</form>";
                }

                // Show the Delete button for both archived and non-archived tasks
                echo "<form action='php/deletetask.php?list_id=".$row['list-id']."' method='POST'>";
                echo "<input type='hidden' name='task_id' value='" . $row['id'] . "'>";
                echo "<button type='submit' class='delete-btn' name='archive_value' value='" . ($row['archive'] == 0 ? '1' : '2') . "'>" . ($row['archive'] == 0 ? '<svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 256 256"><path fill="currentColor" d="M208.49 191.51a12 12 0 0 1-17 17L128 145l-63.51 63.49a12 12 0 0 1-17-17L111 128L47.51 64.49a12 12 0 0 1 17-17L128 111l63.51-63.52a12 12 0 0 1 17 17L145 128Z"/></svg>' : "<svg width='24' height='24' viewBox='0 0 24 24'><path fill='none' stroke='currentColor' stroke-linecap='round' stroke-linejoin='round' stroke-width='2' d='M4 7h16m-10 4v6m4-6v6M5 7l1 12a2 2 0 0 0 2 2h8a2 2 0 0 0 2-2l1-12M9 7V4a1 1 0 0 1 1-1h4a1 1 0 0 1 1 1v3'/></svg>") . "</button>";
                echo "</form></li>";
            }
        }

        if (!$nonArchivedTasks) {
            echo "<li>No items found</li>";
        }
    } else {
        echo "<li>No list selected</li>";
    }
?>
</ul>
</div>

<script>
    // Function to set focus on the text input after a delay when the page loads
    window.onload = function() {
        const inputField = document.getElementById("taskInput");

        // Check if the page is loaded on a mobile device
        const isMobileDevice = /Android|webOS|iPhone|iPad|iPod|BlackBerry|IEMobile|Opera Mini/i.test(navigator.userAgent);

        // If it's a mobile device, focus on the input after a small delay to ensure the keyboard pops up
        if (isMobileDevice) {
            setTimeout(function() {
                inputField.focus();
            }, 500); // You can adjust the delay time (in milliseconds) if needed
        }
    };
</script>

<script>
    function copyToClipboard(text) {
        var tempInput = document.createElement('input');
        tempInput.value = text;
        document.body.appendChild(tempInput);
        tempInput.select();
        document.execCommand('copy');
        document.body.removeChild(tempInput);

        alert('Link copied to clipboard!');
    }
</script>