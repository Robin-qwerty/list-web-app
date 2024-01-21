<?php
    // Fetch existing lists
    $sql = "SELECT * FROM lists";
    $result = $conn->query($sql);
?>

<div class="container">
    <h1>Lists</h1>

    <!-- Form to add a new list -->
    <form action="php/addlist.php" method="POST">
        <input type="text" name="list_name" placeholder="Enter list name" required>
        <button type="submit">Add</button>
    </form>

    <!-- Display existing lists -->
    <?php if ($result->rowCount() > 0): ?>
        <ul class="list-list">
            <?php while ($row = $result->fetch(PDO::FETCH_ASSOC)): ?>
                <li>
                    <span class="list-name" contenteditable="false" onclick="goToList(<?php echo $row['id']; ?>)">
                        <?php echo htmlspecialchars($row['name']); ?>
                    </span>
                    <button onclick="editList(this, <?php echo $row['id']; ?>)">Edit</button>
                    <input type="hidden" name="list_id" value="<?php echo $row['id']; ?>">
                </li>
            <?php endwhile; ?>
        </ul>
    <?php else: ?>
        <p>No lists found</p>
    <?php endif; ?>
</div>

<script>
    let isEditing = false;

    function goToList(listId) {
        if (!isEditing) {
            window.location.href = `index.php?page=list&list_id=${listId}`;
        }
    }

    function editList(button, listId) {
        const listNameElement = button.previousElementSibling;

        if (button.innerText === 'Edit') {
            listNameElement.contentEditable = true;
            listNameElement.focus();
            button.innerText = 'Save';
            isEditing = true;
        } else {
            listNameElement.contentEditable = false;
            const newListName = listNameElement.innerText.trim();

            // Send the updated list name to the server using form submission
            const form = document.createElement('form');
            form.method = 'POST';
            form.action = 'php/updatelist.php';
            form.style.display = 'none';
            document.body.appendChild(form);

            const listIdInput = document.createElement('input');
            listIdInput.type = 'hidden';
            listIdInput.name = 'list_id';
            listIdInput.value = listId;
            form.appendChild(listIdInput);

            const newListNameInput = document.createElement('input');
            newListNameInput.type = 'hidden';
            newListNameInput.name = 'new_name';
            newListNameInput.value = newListName;
            form.appendChild(newListNameInput);

            form.submit();
            button.innerText = 'Edit';
            isEditing = false;
        }
    }
</script>
