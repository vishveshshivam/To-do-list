<?php
session_start();
error_reporting(1);
require("../db.php");

if (isset($_POST['name']) && $_POST['name'] == "addTask") 
{
    $task = $_POST['task'];

    $userId = $_SESSION['userId'];
    if (mysqli_query($conn, "INSERT INTO `task`(`user_id`, `task_name`,`status`) VALUES ('$userId','$task','todo')")) {
        if ($data = mysqli_query($conn, "SELECT * FROM `task` WHERE `user_id`='$userId'")) 
        {
            $appendData = '<div class="success">Task added successfully.</div><table><tr>
            <th>Done</th>
            <th>Task</th>
            <th>Date Added</th>
            <th>Delete Task</th>
            </tr>';
            while ($row = mysqli_fetch_assoc($data)) 
            {
                $status = $row['status'] == 'todo' ? "" : "checked";
                $strike = $row['status'] == 'todo' ? "" : "class='strike'";
                $appendData .= '
                <tr>
                    <td>
                    <input type="checkbox" onclick="status(this.id)" id="' . $row['id'] . '" ' . $status . ' >
                    </td>
                <td '.$strike.'>
                ' . $row['task_name'] . '
                </td>
                <td>' . $row['date_added'] . '
                </td>
                <td>
                <a class="btn" href="todos.php?delete&id='.$row['id'].'" >Delete</a>
                </td>
                </tr>';
            }
        } else 
        {
            $appendData .= '<tr><td colspan="4" class="center">No Tasks found</td></tr>';
        }

        $appendData .= '</table>';
        echo $appendData;
    } else 
    {
        $appendData = '<div class="error">An error occured! Please retry.</div><table><tr>
        <th>Done</th>
        <th>Task</th>
        <th>Date Added</th>
        <th>Delete Task</th>
    </tr><tr><td colspan="4">No Tasks found</td></tr></table>';
    }
} else if (isset($_POST['name']) && $_POST['name'] == "change-status") 
{
    
$userId = $_SESSION['userId'];
    $tid = $_POST['tid'];
    if ($data = mysqli_query($conn, "SELECT * FROM `task` WHERE `id`='$tid'")) {
        $row = mysqli_fetch_assoc($data);
        $status = $row['status'] == 'todo' ? "" : "checked";
        if ($row['status'] == 'todo') 
        {
            mysqli_query($conn, "UPDATE `task` SET `status`='done' WHERE `id`='$tid'");
        } else 
        {
            mysqli_query($conn, "UPDATE `task` SET `status`='todo' WHERE `id`='$tid'");
        }
        $data = mysqli_query($conn, "SELECT * FROM `task` WHERE `user_id`='$userId'");
        $appendData = '<div class="success">Status of task updated successfully.</div><table><tr>
            <th>Done</th>
            <th>Task</th>
            <th>Date Added</th>
            <th>Delete Task</th>
        </tr>';
        while ($row = mysqli_fetch_assoc($data)) 
        {
            $status = $row['status'] == 'todo' ? "" : "checked";
            $strike = $row['status'] == 'todo' ? "" : "class='strike'";
            $appendData .= '
                <tr>
                    <td>
                    <input type="checkbox" onclick="status(this.id)" id="' . $row['id'] . '" ' . $status . ' >
                    </td>
                <td '.$strike.'>
                ' . $row['task_name'] . '
                </td>
                <td>' . $row['date_added'] . '
                </td>
                <td>
                <a class="btn" href="todos.php?delete&id='.$row['id'].'" >Delete</a>
                </td>
                </tr>';
        }

        $appendData .= '</table>';
        echo $appendData;
    }
} else if (isset($_POST['name']) && $_POST['name'] == "search") 
{
    $string = $_POST['string'];
    
$userId = $_SESSION['userId'];
    if ($data = mysqli_query($conn, "SELECT * FROM `task` WHERE `user_id`='$userId' AND `task_name` LIKE '%$string%'")) {
        $appendData = '<div class="center"><p>Matching Results ( <b>'.$string.'</b> )</p></div><table><tr>
        <th>Done</th>
        <th>Task</th>
        <th>Date Added</th>
        <th>Delete Task</th>
    </tr>';
        if (mysqli_num_rows($data) > 0) 
        {
            while ($row = mysqli_fetch_assoc($data)) {
                $status = $row['status'] == 'todo' ? "" : "checked";
                $strike = $row['status'] == 'todo' ? "" : "class='strike'";
                $appendData .= '
                <tr>
                    <td>
                    <input type="checkbox" onclick="status(this.id)" id="' . $row['id'] . '" ' . $status . '>
                    </td>
                <td '.$strike.'>
                ' . $row['task_name'] . '
                </td>
                <td>' . $row['date_added'] . '
                </td>
                <td>
                <a class="btn" href="todos.php?delete&id='.$row['id'].'" >Delete</a>
                </td>
                </tr>';
            }
        } else 
        {
            $appendData .= '<tr><td colspan="4" class="center">No Tasks found</td></tr>';
        }
        $appendData .= '</table>';
        echo $appendData;
    }
}
else if(isset($_GET['delete']) && isset($_GET['id']))
{
    $id=mysqli_real_escape_string($conn,$_GET['id']);
    if(mysqli_query($conn,"DELETE FROM `task` WHERE `id`=$id"))
    {
        header("Location:./index.php?deleted");
    }
    else
    {
        header("Location:./index.php?errdeleted");
    }
}
else
{
    echo "<b>nothing sent with the request</b>";
}