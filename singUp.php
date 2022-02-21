<!DOCTYPE html>
<html lang="en">
<head>
    <title>Create an account</title>
    <link rel="stylesheet" href="styles.css">
</head>
<body>
<div class="login-page">
    <div class="form">
        <form action="studentControl.php" class="login-form" method="post"
              enctype="multipart/form-data">
            <?php
            if (isset($_COOKIE["userNameError"])) {
                echo "<span>{$_COOKIE["userNameError"]}</span>";
                setcookie("userNameError", "", time() - 3600);
            }
            ?>
            <input type="text" required name="userName" placeholder="Name"
                   value="<?php echo isset($_POST['userName']) ? $_POST['userName'] : ''; ?>"/>
            <?php
            if (isset($_COOKIE["emailError"])) {
                echo "<span>{$_COOKIE["emailError"]}</span>";
                setcookie("emailError", "", time() - 3600);
            }
            ?>
            <input type="email" required value=" <?php echo isset($_POST['userEmail']) ? $_POST['userEmail'] : ''; ?>"
                   name="userEmail" placeholder="Email"/>

            <?php
            if (isset($_COOKIE["passError"])) {
                echo "<span>{$_COOKIE["passError"]}</span>";
                setcookie("passError", "", time() - 3600);
            }
            ?> <input type="password" required name="userPassword" placeholder="Password"/>
            <?php
            if (isset($_COOKIE["conformPassError"])) {
                echo "<span>{$_COOKIE["conformPassError"]}</span>";
                setcookie("conformPassError", "", time() - 3600);
            }
            ?><input type="password" required name="conformPassword" placeholder="confirm Password"/>
            <input type="file" name="profilePicture"/>
            <input type="submit" class="button" name="signup" value="Sing Up"/>
            <p class="message">Already registered? <a href="login.php">Login</a></p>
        </form>
    </div>
</div>
</body>
</html>