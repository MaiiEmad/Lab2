<?php
//too collect all error in one array
$ERRORS["status"] = "success";

//connect to DB
try {
    $connect = new pdo("mysql:dbname=student;host=localhost", "maii", "");
} catch (PDOException $e) {
    die($e->getMessage());
}

//delete form DB
if (isset($_REQUEST["delete"])) {
    $userData = $connect->prepare("delete from student where id = ?");
    $userData->execute([
        $_REQUEST["id"]
    ]);
    header("Location:home.php?backToHome");
}

//update
if (isset($_REQUEST["update"])) {
    if ($_FILES['profilePicture']["size"] > 0) {
        $profilePicture = $_FILES['profilePicture'];
        savePicture($_FILES['profilePicture']);


        $userData = $connect->prepare("UPDATE student  SET userName = ? ,email = ? , pass = ?,roomNumber = ?,image=? WHERE id = ?");
        $userData->execute([
            $_REQUEST["userName"],
            $_REQUEST["userEmail"],
            $_REQUEST["userPassword"],
            $_REQUEST["roomNumber"],
            $profilePicture['name'],
            $_REQUEST["id"]
        ]);
    } else {
        $userData = $connect->prepare("UPDATE student  SET userName = ? ,email = ? , pass = ?,roomNumber = ? WHERE id = ?");
        $userData->execute([
            $_REQUEST["userName"],
            $_REQUEST["userEmail"],
            $_REQUEST["userPassword"],
            $_REQUEST["roomNumber"],
            $_REQUEST["id"]
        ]);
    }
    header("Location:home.php?backToHome");

}
//show from DB
if (isset($_REQUEST["show"])) {
    $userData = $connect->prepare("select * from student where id = ?");
    $userData->execute([
        $_REQUEST["id"]
    ]);
    $result = $userData->fetch(PDO::FETCH_ASSOC);
    json_encode($result);
    header("Location:show.php?show&data=" . json_encode($result));
}

//create new user in database
if (isset($_REQUEST["signup"])) {
    $userName = $_REQUEST['userName'];
    $userEmail = $_REQUEST['userEmail'];
    $userPassword = $_REQUEST['userPassword'];
    $conformPassword = $_REQUEST['conformPassword'];
    $roomNumber = $_REQUEST['roomNumber'];
    $profilePicture = $_FILES['profilePicture'];

    //save data to DB;
    if ($profilePicture["size"] > 0) {
        savePicture($_FILES['profilePicture']);
    } else {
        $profilePicture['name'] = "null";
    }
    $stm = $connect->prepare("insert into student (userName,email,pass,roomNumber,image) values (?,?,?,?,?)");
    $stm->execute([
        $userName,
        $userEmail,
        $userPassword,
        $roomNumber,
        $profilePicture['name']
    ]);
    header("Location:login.php");
}

//for login
if (isset($_REQUEST["login"])) {
//    $emailPattern = "/^([a-z0-9\+_\-]+)(\.[a-z0-9\+_\-]+)*@([a-z0-9\-]+\.)+[a-z]{2,6}$/ix";
    $userEmail = strtolower($_REQUEST['userEmailLogin']);
    $userPassword = $_REQUEST['userPasswordLogin'];

    $userData = $connect->prepare("select * from student where LOWER(email)=? and pass =?");
    $userData->execute([
        $userEmail,
        $userPassword,
    ]);
    $result = $userData->fetch(PDO::FETCH_ASSOC);

    //checking email
    if (empty(trim($userEmail))) {
        $ERRORS["status"] = "failure";
        $ERRORS["emailError"] = "Please Check You've Entered Your Email";
    } else {
        if ($userEmail != $result["email"]) {
            $ERRORS["status"] = "failure";
            $ERRORS["emailError"] = "This email does not exist";
        }
        //checking password
        if (empty(trim($userPassword))) {
            $ERRORS["status"] = "failure";
            $ERRORS["passError"] = "Please Check You've Entered Your Password";
        } else {
            if ($userPassword != $result["pass"] && strlen($userPassword) <= 8) {
                $ERRORS["status"] = "failure";
                $ERRORS["passError"] = "Invalid Password";
            }
        }
    }
    if ($ERRORS["status"] == "success") {
        setcookie("login", "login");
        header("Location:home.php?backToHome");
    } else {
        setcookie("emailError", $ERRORS["emailError"], time() + 3600);
        setcookie("passError", $ERRORS["passError"], time() + 3600);
        header("Location:login.php");
    }
}

if (isset($_REQUEST["signup"])) {
    $emailPattern = "/^([a-z0-9\+_\-]+)(\.[a-z0-9\+_\-]+)*@([a-z0-9\-]+\.)+[a-z]{2,6}$/ix";
    //for username
    if (empty(trim($_REQUEST["userName"]))) {
        $ERRORS["status"] = "failure";
        $ERRORS["userNameError"] = "Please Check You've Entered your name";
    }
    if (strlen($_REQUEST["userName"]) <= 3) {
        $ERRORS["status"] = "failure";
        $ERRORS["userNameError"] = "Please Check You've name is larger than 3 character ";
    }
    //for email
    if (empty(trim($_REQUEST["userEmail"]))) {
        $ERRORS["status"] = "failure";
        $ERRORS["emailError"] = "Please Check You've Entered your email";
    } else {
        if (!filter_var(trim($_REQUEST["userEmail"]), FILTER_VALIDATE_EMAIL) //first one user filter
//        || preg_match($emailPattern,$userEmail)
        ) {//second one used pattern
            $ERRORS["status"] = "failure";
            $ERRORS["emailError"] = "Invalid email format";
        }
    }
    //for Password
    if (empty(trim($_REQUEST["userPassword"]))) {
        $ERRORS["status"] = "failure";
        $ERRORS["passError"] = "Please Check You've Entered your Password";
    } else {
        if (strlen(trim($_REQUEST["userPassword"])) <= 8) {
            $ERRORS["status"] = "failure";
            $ERRORS["passError"] = "Your Password Must Contain At Least 8 Characters";
        } elseif (!preg_match("#[0-9]+#", trim($_REQUEST["userPassword"]))) {
            $ERRORS["status"] = "failure";
            $ERRORS["passError"] = "Your Password Must Contain At Least 1 Number";
        } elseif (!preg_match("#[A-Z]+#", trim($_REQUEST["userPassword"]))) {
            $ERRORS["status"] = "failure";
            $ERRORS["passError"] = "Your Password Must Contain At Least 1 Capital Letter";
        } elseif (!preg_match("#[a-z]+#", trim($_REQUEST["userPassword"]))) {
            $ERRORS["status"] = "failure";
            $ERRORS["passError"] = "Your Password Must Contain At Least 1 Lowercase Letter";
        }
    }
    //for confirmPassword
    if (trim($_REQUEST["userPassword"]) != trim($_REQUEST["conformPassword"])) {
        $ERRORS["status"] = "failure";
        $ERRORS["conformPassError"] = "Please Check You've Entered confirmed Password like password";
    }
    if ($ERRORS["status"] == "success") {
        header("Location:login.php");
    } else {
        setcookie("userNameError", $ERRORS["userNameError"], time() + 3600);
        setcookie("emailError", $ERRORS["emailError"], time() + 3600);
        setcookie("passError", $ERRORS["passError"], time() + 3600);
        setcookie("conformPassError", $ERRORS["conformPassError"], time() + 3600);
        header("Location:singUp.php");
    }
}

function savePicture($FILES)
{
    #to save picture
    $errors = array();
    //to get file Data from $_FILES
    $file_name = $FILES['name'];
    $file_size = $FILES['size'];
    $file_tmp = $FILES['tmp_name'];
    $file_type = $FILES['type'];
    // get file extension
    $ext = explode('.', $FILES['name']);
    $file_ext = strtolower(end($ext));
    $extensions = array("jpeg", "jpg", "png");
    if (in_array($file_ext, $extensions) === false) {
        $errors[] = "extension not allowed, please choose a JPEG or PNG file.";
    }
    if ($file_size > 2097152) {
        $errors[] = 'File size must be excately 2 MB';
    }
    if (empty($errors) == true) {
        try {
            move_uploaded_file($file_tmp, "profilePicture/" . $file_name);
        } catch (Exception $e) {
            echo $e;
        }
    } else {
        print_r($errors);
    }
}


$connect = null;
