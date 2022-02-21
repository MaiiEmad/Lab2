<?php
if (isset($_REQUEST["edit"]) && $_COOKIE["login"] == "login") {
    try {
        $connect = new pdo("mysql:dbname=student;host=localhost", "maii", "");
        $userData = $connect->prepare("select * from student where id = ?");
        $userData->execute([
            $_REQUEST["id"]
        ]);

        $result = $userData->fetch(PDO::FETCH_ASSOC);
        echo '<html lang="en">
<head>
    <title>Edit</title>
  <link rel="stylesheet" href="styles.css">
</head>
<body>
<div class="login-page">
    <div class="form">
        <form action="studentControl.php" method="post" class="login-form"
                 enctype="multipart/form-data">
            <input type="hidden" name="id" value="' . $result['id'] . '"/>
            <input type="text" name="userName" value="' . $result['userName'] . '"/>
            <input type="email" name="userEmail" value="' . $result['email'] . '"/>
            <input type="password" name="userPassword" value="' . $result['pass'] . '"/>
            <input type="file" name="profilePicture" />
            <input type="submit" class="button" name="update" value="Update"/>
        </form>
    </div>
</div>
</body>
</html>';
    } catch (PDOException $e) {
        die($e->getMessage());
    }
    $connect = null;
}
