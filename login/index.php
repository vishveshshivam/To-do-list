<?php
session_start();
require("../db.php");
if(isset($_SESSION['userId']))
{
    header("Location:../user/");
}
else if(isset($_POST['login']))
{
    $email=mysqli_real_escape_string($conn,$_POST['email']);
    $password=md5(mysqli_real_escape_string($conn,$_POST['password']));
    $result=mysqli_query($conn,"SELECT * FROM `user` WHERE email='$email' AND pass='$password'");
    if(mysqli_num_rows($result)>0)
    {
        $row=mysqli_fetch_assoc($result);
        $_SESSION['userId']=$row['id'];
        $_SESSION['email']=$row['email'];
        header("Location:../user/");

    }
    else
    {
        header("Location:./index.php?err");
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
        <div class="header">Sign In</div>
        <div class="inputs">
            <?php if(isset($_REQUEST['err']))
            {
                ?>
                <div class="error"><p>Invalid login details.</p></div>
                <?php
            }
            else if(isset($_REQUEST['accountsucc']))
            {
                ?>
                <div class="success"><p>Acoount created successfully.<br>Enter credentials to login</p></div>
                <?php
            }
            ?>
            <input placeholder="Email" class="input" type="email" name="email" required>
            <input placeholder="Password" class="input" type="password" name="password" required>
            <button class="sigin-btn" type="submit" name="login" >Submit</button>
            <p class="signup-link">Don't have an account? <a href="./signup.php">Sign up</a></p>
        </div>
    </form>
</body>

</html>