<?php
session_start();
require("../db.php");
if(isset($_POST['signup']))
{
    $email=mysqli_real_escape_string($conn,$_POST['email']);
    $password=md5(mysqli_real_escape_string($conn,$_POST['password']));
    $name=mysqli_real_escape_string($conn,$_POST['name']);

    $result=mysqli_query($conn,"SELECT * FROM `user` WHERE email='$email'");
    if(mysqli_num_rows($result)==0)
    {
        if(mysqli_query($conn,"INSERT INTO `user`(`name`, `email`, `pass`) VALUES ('$name','$email','$password')"))
        {
            header("Location:./index.php?accountsucc");
        }
        else
        {
            header("Location:./signup.php?err");
        }

    }
    else
    {
        header("Location:./signup.php?exists");
    }
}
?>
<!DOCTYPE html>
<html>
<head>
    <meta charset='utf-8'>
    <meta http-equiv='X-UA-Compatible' content='IE=edge'>
    <title>Login</title>
    <meta name='viewport' content='width=device-width, initial-scale=1'>
    <link rel='stylesheet' type='text/css' href='style.css'>
</head>

<body>
    <form class="form" action="<?php echo $_SERVER['PHP_SELF']; ?>" method="post">
        <div class="header">Sign Up</div>
        <div class="inputs">
            <?php if(isset($_REQUEST['err']))
            {
                ?>
                <div class="error"><p>error occured while creating account.</p></div>
                <?php
            }
            else if(isset($_REQUEST['exists']))
            {
                ?>
                <div class="error"><p>Acoount Exists,Please login to continue.</p></div>
                <?php
            }
            ?>
            <input placeholder="Name" class="input" type="text" name="name" required>
            <input placeholder="Email" class="input" type="email" name="email" required>
            <input placeholder="Password" class="input" type="password" name="password" pattern="(?=.*\d)(?=.*[a-z])(?=.*[A-Z]).{8,}"
            title="Must contain at least one  number and one uppercase and lowercase letter, and at least 8 or more characters"
            required>
            <button class="sigin-btn" type="submit" name="signup" >Submit</button>
            <p class="signup-link">Already have an account? <a href="./index.php">Log in</a></p>
        </div>
    </form>
</body>

</html>