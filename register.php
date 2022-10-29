<?php
    session_start();
    function test_input($data) {
        $data = trim($data);
        $data = stripslashes($data);
        $data = htmlspecialchars($data);
        return $data;
    }

    $errName = $errPassword = $errEmail = $errMobile = "";
    $username = $password = $mobile = $email = "";
    $errorCheck = TRUE;

    if ($_SERVER["REQUEST_METHOD"] == "POST") {
        $email = $_POST['email'];
        $username = $_POST['username'];
        $password = $_POST['password'];
        $mobile = $_POST['mobile'];
    

    if (empty($email)) {
        $errEmail = "*Email Required";
        $errorCheck = FALSE;
    }else {
        $email = test_input($email);
        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            $errEmail = "*Invalid Email";
            $errorCheck = FALSE;
        }
        if ($errorCheck){
            $errorCheck = TRUE;
        }
    }

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

    if (empty($mobile)){
        $errMobile = "*Mobile Number Required";
        $errorCheck = FALSE;
    }
    else {
        $mobile = test_input($mobile);

        if(!preg_match('/^[0-9]{10}+$/', $mobile))
            {
                $errMobile = "*Invalid Phone Number";
                $errorCheck = FALSE;         
            } 
            else 
            {
                $errorCheck = TRUE;   
            }
    }
    if ($errorCheck)
        {
                
            if (isset($_POST['agree'])): 
                    {
                        $message = "Registration Successful!";            
                        $cookie_name = "user";
                        $cookie_value = $username;
                        setcookie($cookie_name, $cookie_value, time() + (86400 * 30), "/"); 
                        echo "<script>alert('$message');
                                window.location.href='/MiniProject/NoteKeeper/notes.php';
                            </script>";
                    }
            else:
                {
                    $message = "You have not agreed to the terms and conditions!";
                        echo "<script>alert('$message');
                                window.location.href='/MiniProject/NotekKeeper/register.php';
                            </script>";
                }

            endif;
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
        <title>Sign Up</title>
        <!-- <link rel="stylesheet" href="./registerstyle.css"> -->
        <link rel="stylesheet" type="text/css" href="<?php echo (($_COOKIE['style'] == "dark")?'registerstyle_dark':'registerstyle') ?>.css" />
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
                    <li class="nav-item">
                        <a class="nav-link" href="./login.php">Log In</a>
                    </li>
                    <li class="nav-item  active">
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


        <div class="login-page" style="width:500px;">
            <div class="form">
                <span class="active"> Sign Up </span>
                <p>Please register your account</p>
                <form class="login-form" action='register.php' method="POST">
                    <input name='email' id="email" type="text" placeholder="Email ID" value = "<?php echo $email;?>"/>
                    <p class="error"><?php echo $errEmail;?></p>
                    <input id="username" name='username' type="text" placeholder="Set Username" value = "<?php echo $username;?>" />
                    <p class="error"><?php echo $errName;?></p>
                    <input id="password" name='password' type="password" placeholder="Set Password" />
                    <p class="error"><?php echo $errPassword;?></p>
                    <input id="mobile" name='mobile' type="text" placeholder="Mobile No" value = "<?php echo $mobile;?>"/>
                    <p class="error"><?php echo $errMobile;?></p>
                    <br> Agree to Terms of Service:
                    <input type="checkbox" name="agree">
                    <br>
                    <input type="hidden" name="form_submitted" value="1" />
                    <button type="Submit">Register</button>
                    <p class="message">Already registered? <a href="./login.php">LogIn</a></p>
                </form>
            </div>
        </div>
    </body>
</html>
