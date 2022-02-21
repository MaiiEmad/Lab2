<?php setcookie("login","logout"); ?>
<!DOCTYPE html>
<html lang="en">
<head>
    <title>Login</title>
    <link rel="stylesheet" href="styles.css">
</head>
<body>
<div class="login-page">
    <div class="form">
        <form action="studentControl.php" class="login-form" method="post">
            <?php
            if (isset($_COOKIE["emailError"])){
                echo "<span>{$_COOKIE["emailError"]}</span>";
                setcookie("emailError","",time()-3600);
            }
            ?>
            <input type="email" required value=" <?php echo isset($_POST['userEmailLogin']) ? $_POST['userEmailLogin'] : ''; ?>" name="userEmailLogin" placeholder="user Email"/>
            <?php
            if (isset($_COOKIE["passError"])){
                echo "<span>{$_COOKIE["passError"]}</span>";
                setcookie("passError","",time()-3600);
            }
            ?>
            <input type="password" required name="userPasswordLogin" placeholder="password"/>
            <input type="submit" name="login" class="button" value="Login"/>
            <p class="message">Not registered? <a href="singUp.php">Create an account</a></p>
        </form>
    </div>
</div>
</body>
</html>
