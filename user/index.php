<?php
session_start();
require("../db.php");
if (!isset($_SESSION['userId'])) {
    header("Location:../login");
}
$userId = $_SESSION['userId'];
?>
<!DOCTYPE html>
<html>

<head>
    <meta charset='utf-8'>
    <meta http-equiv='X-UA-Compatible' content='IE=edge'>
    <title>Dashboard</title>
    <meta name='viewport' content='width=device-width, initial-scale=1'>
    <link rel='stylesheet' type='text/css' href='main.css'>
    <script>
        function formToggle(ID) {
            var element = document.getElementById(ID);
            if (element.style.display === "none") {
                element.style.display = "block";
            } else {
                element.style.display = "none";
            }
        }
    </script>

    <script src="../assets/js/jquery.js"></script>
    <script type="text/javascript">
        $(document).ready(function () {
            // Get the form element
            var form = $('#add-task-form');

            // Bind an event handler to the submit event
            form.on('submit', function (event) {
                // Prevent the default action from happening
                event.preventDefault();
                var task = $("#task").val();
                if (task == "") {
                    alert("Please enter a task to continue")
                }
                else {
                    $.ajax({
                        url: 'todos.php',
                        type: 'POST',
                        data: { task: task, name: "addTask" },
                        success: function (response) {
                            $("#checklist").html(response);
                        }
                    });
                }
            });
        });


    </script>
    <script type="text/javascript">
        function status(id) {
            $(document).ready(function () {
                $.ajax({
                    url: 'todos.php',
                    type: 'POST',
                    data: { tid: id, name: "change-status" },
                    success: function (response) {
                        $("#checklist").html(response);
                    }
                });
            });
        }  
    </script>
    <script type="text/javascript">
        function searchResult() {
            let searchTerm = $("#search").val();
            $(document).ready(function () {
                $.ajax({
                    url: 'todos.php',
                    type: 'POST',
                    data: { string: searchTerm, name: "search" },
                    success: function (response) {
                        $("#checklist").html(response);
                    }
                });
            });
        }  
    </script>
</head>

<body>
    <div class="cards center">
        <div class="container-form">
            <div class="add-new-item">

                <button class="icon-btn add-btn" onclick="formToggle('add-task-div');" title="Add new task">
                    <div class="add-icon"></div>
                </button>
            </div>
            <a class="Btn" href="./logout.php" title="logout">

                <div class="sign"><svg viewBox="0 0 512 512">
                        <path
                            d="M377.9 105.9L500.7 228.7c7.2 7.2 11.3 17.1 11.3 27.3s-4.1 20.1-11.3 27.3L377.9 406.1c-6.4 6.4-15 9.9-24 9.9c-18.7 0-33.9-15.2-33.9-33.9l0-62.1-128 0c-17.7 0-32-14.3-32-32l0-64c0-17.7 14.3-32 32-32l128 0 0-62.1c0-18.7 15.2-33.9 33.9-33.9c9 0 17.6 3.6 24 9.9zM160 96L96 96c-17.7 0-32 14.3-32 32l0 256c0 17.7 14.3 32 32 32l64 0c17.7 0 32 14.3 32 32s-14.3 32-32 32l-64 0c-53 0-96-43-96-96L0 128C0 75 43 32 96 32l64 0c17.7 0 32 14.3 32 32s-14.3 32-32 32z">
                        </path>
                    </svg></div>

                <div class="text">Logout</div>
            </a>



        </div>
        <br>
        <!-- new list item forms -->
        <div id="add-task-div" style="display: none;">
            <form id="add-task-form">
                <label>Enter Task</label><br>
                <textarea id="task" name="task" placeholder="Enter task" required></textarea>
                <br>
                <button id="bottone5">
                    <span>
                        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" width="24" height="24">
                            <path fill="none" d="M0 0h24v24H0z"></path>
                            <path fill="currentColor" d="M11 11V5h2v6h6v2h-6v6h-2v-6H5v-2z"></path>
                        </svg> Create
                    </span>
                </button>
            </form>
        </div>
        <br>
        <h4>To Do list</h4>
        <form class="form">
            <label for="search">
                <input required="" autocomplete="off" placeholder="search your Task list" id="search" type="text"
                    oninput="searchResult()">
                <div class="icon">
                    <svg stroke-width="2" stroke="currentColor" viewBox="0 0 24 24" fill="none"
                        xmlns="http://www.w3.org/2000/svg" class="swap-on">
                        <path d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" stroke-linejoin="round"
                            stroke-linecap="round"></path>
                    </svg>
                    <svg stroke-width="2" stroke="currentColor" viewBox="0 0 24 24" fill="none"
                        xmlns="http://www.w3.org/2000/svg" class="swap-off">
                        <path d="M10 19l-7-7m0 0l7-7m-7 7h18" stroke-linejoin="round" stroke-linecap="round"></path>
                    </svg>
                </div>
                <button type="reset" class="close-btn">
                    <svg viewBox="0 0 20 20" class="h-5 w-5" xmlns="http://www.w3.org/2000/svg">
                        <path clip-rule="evenodd"
                            d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z"
                            fill-rule="evenodd"></path>
                    </svg>
                </button>
            </label>
        </form>

        <div id="checklist">
            <?php
            if (isset($_GET['deleted'])) {
                echo "<div class='success'>Task deleted successfully.</div>";
            } else if (isset($_GET['errdeleted'])) {
                echo "<div class='error'>Error While deleting task.</div>";
            }
            ?>
            <?php
            if ($data = mysqli_query($conn, "SELECT * FROM `task` WHERE `user_id`='$userId'")) {
                $appendData = '<table><tr>
            <th>Done</th>
            <th>Task</th>
            <th>Date Added</th>
            <th>Delete Task</th>
        </tr>';
                if (mysqli_num_rows($data) > 0) {
                    while ($row = mysqli_fetch_assoc($data)) {
                        $status = $row['status'] == 'todo' ? "" : "checked";
                        $strike = $row['status'] == 'todo' ? "" : "class='strike'";
                        $appendData .= '
                    <tr>
                        <td>
                        <input type="checkbox" onclick="status(this.id)" id="' . $row['id'] . '" ' . $status . '>
                        </td>
                    <td ' . $strike . '>
                    ' . $row['task_name'] . '
                    </td>
                    <td>' . $row['date_added'] . '
                    </td>
                    <td>
                    <a class="btn" href="todos.php?delete&id=' . $row['id'] . '" >Delete</a>
                </td>
                    </tr>';
                    }
                } else {
                    $appendData .= '<tr><td colspan="4" class="center">No Tasks found</td></tr>';
                }
            }
            $appendData .= '</table>';
            echo $appendData;
            ?>
        </div>
    </div>
</body>

</html>