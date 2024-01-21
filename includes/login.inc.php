<?php

?>

<div class="background">
    <div class="center-container" action="php/joingroup.php">
        <form class="list-group-password">
            <div class="input-label">Enter List Group Password:</div>
            <input type="text" name="grouppassword" placeholder="List Group Password">
            <br>
            <button type="button" onclick="goToListGroup()">Go to List Group</button>
        </form>

        <hr>
        <br>

        <form class="new-list-group" action="php/creategroup.php">
            <div class="input-label">Create a New List Group:</div>
            <input type="text" name="groupname" placeholder="Make a New List Group">
            <br>
            <button type="button" onclick="makeNewListGroup()">Make New List Group</button>
        </form>
    </div>
</div>