<?php
    session_start();
    $servername = "localhost"; 
    $username = "root"; 
    $password = "";
   
    $database = "notes_app";
   
    try {
        $conn = new PDO("mysql:host=$servername;dbname=$database", $username, $password);
        $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        echo "Connected successfully";
      } catch(PDOException $e) {
        echo "Connection failed: " . $e->getMessage();
      }

      function test_input($data) {
        $data = trim($data);
        $data = stripslashes($data);
        $data = htmlspecialchars($data);
        return $data;
    }

    $errName = $errPassword = $errCPassword = $errEmail = $errMobile = "";
    $username = $password = $cpassword = $mobile = $email = "";
    $errorCheck = TRUE;
    $showAlert = false; 
    $showError = false; 
    $exists=false;
    $exists1=false;

    if ($_SERVER["REQUEST_METHOD"] == "POST") {
        $email = $_POST['email'];
        $username = $_POST['username'];
        $password = $_POST['password'];
        $cpassword = $_POST['cpassword'];
        $mobile = $_POST['mobile'];
        echo $cpassword;

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

    if (empty($cpassword))
    {
        $errCPassword = "*Password Required";
        $errorCheck = FALSE;
    }
    else
    {
        if($password==$cpassword)
        {
            $errorCheck = TRUE;
        }
        else
        {
            $errCPassword = "*Passwords don't match";
            $errorCheck = FALSE;
        }

    }
    
    if (empty($mobile)){
        $errMobile = "*Phone Number Required";
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
                    $message = "Registered Successfully";
                    if(isset($_POST['agree'])) {
                        $check = 0;
                        $ustmt = $conn->prepare("SELECT * FROM users where username='$username'");
                        $ustmt->execute();
                        $num=$ustmt->rowCount();
                        $estmt = $conn->prepare("SELECT * FROM users where email='$email'");
                        $estmt->execute();
                        $num1=$estmt->rowCount();
                        $mstmt = $conn->prepare("SELECT * FROM users where PhoneNo='$mobile'");
                        $mstmt->execute();
                        $num2=$mstmt->rowCount();
                        if($num==0 && $num1==0 && $num2==0)
                        {
                                $stmt = $conn->prepare("INSERT INTO users VALUES (:Email, :Username, :PhoneNo, :Password)");
                                $stmt->bindParam(':Email', $email);
                                $stmt->bindParam(':Username', $username);
                                $stmt->bindParam(':PhoneNo', $mobile);
                                $stmt->bindParam(':Password', $password);
                                $stmt->execute();
                                echo "<script>alert('$message');</script>";
                        }
                        if($num>0){
                            $errName='Username already exists!';
                        }
                        if($num1>0){
                            $errEmail='Email already exists!';
                        }
                        if($num2>0){
                            $errMobile='Phone number already exists!';
                        }
                    }
            else
                {
                    $message = "You have not agreed to the terms and conditions!";
                        echo "<script>alert('$message');
                                window.location.href='/MiniProject/NotekKeeper-master/register.php';
                            </script>";
                }
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
        <link rel="stylesheet" href="./registerstyle.css">
        //<link rel="stylesheet" type="text/css" href="<?php echo (($_COOKIE['style'] == "dark")?'registerstyle_dark':'registerstyle') ?>.css" />
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
                    <input id="cpassword" name='cpassword' type="password" placeholder="Confirm Password"/>
                    <p class="error"><?php echo $errCPassword;?></p>
                    <input id="mobile" name='mobile' type="text" placeholder="Mobile No" value = "<?php echo $mobile;?>"/>
                    <p class="error"><?php echo $errMobile;?></p>
                    <br> Agree to Terms of Service:
                    <input type="checkbox" name="agree">
                    <br><br>
                    <input type="hidden" name="form_submitted" value="1" />
                    <button type="Submit">Register</button>
                    <p class="message">Already registered? <a href="./login.php">LogIn</a></p>
                </form>
            </div>
        </div>
    </body>
</html>



