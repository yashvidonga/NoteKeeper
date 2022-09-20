<?php
    function test_input($data) {
        $data = trim($data);
        $data = stripslashes($data);
        $data = htmlspecialchars($data);
        return $data;
    }
    $errName = $errPassword = "";
    $username = $password = "";
    $errorCheck = TRUE;
    if ($_SERVER["REQUEST_METHOD"] == "POST") {
        $username = $_POST['username'];
        $password = $_POST['password'];
        if (empty($username)) {
            $errName = "*Username Required";
            $errorCheck = FALSE;
        } else {
            $username = test_input($username);
            if (!preg_match("/^[a-zA-Z-' ]*$/",$username)) {
                $errName = "*Only letters and white space allowed";
                $errorCheck = FALSE;
            }
            else{
                $errorCheck = TRUE;
            }
        }
        if (empty($password)){
            $errPassword = "*Password Required";
            $errorCheck = FALSE;
        } else {
            $password = test_input($password);
            if(strlen($password) < 8) {
                $errPassword = "*Password must have 8 characters";
                $errorCheck = FALSE;
            }
            else if (!preg_match("/[0-9]/", $password)) {
                $errPassword = "*Password should contain a Number";
                $errorCheck = FALSE;
            }
            if ($errorCheck){
                $errorCheck = TRUE;
            }
        }
        if ($errorCheck){
            $message = "Login Successful!";
            echo "<script>alert('$message');
                    window.location.href='/MiniProject/NoteKeeper/notes.php';
                  </script>";
        }
    }

?>
<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="UTF-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-EVSTQN3/azprG1Anm3QDgpJLIm9Nao0Yz1ztcQTwFspd3yD65VohhpuuCOmLASjC" crossorigin="anonymous">
        <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-MrcW6ZMFYlzcLA8Nl+NtUVF0sA7MsXsP1UyJoMp4YLEuNSfAP+JcXn/tWtIaxVXM" crossorigin="anonymous"></script>
        <title>Login</title>
        <link rel="stylesheet" href="./loginstyle.css">
        <script src="https://kit.fontawesome.com/a076d05399.js"></script>
    </head>

    <nav class="navbar navbar-expand-lg navbar-light fixed-top">
        <div class="container">
            <a class="brand" href="./home.php"> <img src="./4.png" style="width: 185px;" alt="logo"></a>
            <div class="collapse navbar-collapse" id="navbarSupportedContent">
                <ul class="navbar-nav ms-auto">
                    <li class="nav-item">
                        <a class="nav-link" href="./home.php">Home</a>
                    </li>
                    <li class="nav-item active">
                        <a class="nav-link" href="./login.php">Log In</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="./register.php">Sign Up</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="./contact.php">Contact</a>
                    </li>
                </ul>
            </div>
        </div>
    </nav>

    <body>
        <div class="login-page">
            <div class="form">
                <span class="active"> Log In </span>
                <p>Please Log into your account</p>
                <form class="login-form" action='./login.php' method="POST">
                    <input type="text" name="username" placeholder="Username" id="username" value = "<?php echo $username;?>"/>
                    <p class="error"><?php echo $errName;?></p>
                    <input type="password" name="password" placeholder="Password" id="password"/>
                    <p class="error"><?php echo $errPassword;?></p>
                    <button>login</button>
                    <p class="message">Not registered? <a href="./register.php">Sign In</a></p>
                </form>
            </div>
        </div>
    </body>

</html>