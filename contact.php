<?php
    require 'vendor/autoload.php';
    use PHPMailer\PHPMailer\PHPMailer;
    use PHPMailer\PHPMailer\Exception;
    
    session_start();
    $database_username = "root";
    $database_password = "";
    try {
        $conn = new PDO("mysql:host=127.0.0.1:8111; dbname=notes_app", $database_username, $database_password);
        $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    } catch(PDOException $e){
        echo "Connection failed: " . $e->getMessage();
    }
    function test_input($data) {
        $data = trim($data);
        $data = stripslashes($data);
        $data = htmlspecialchars($data);
        return $data;
    }
    $email = $name = $message = "";
    $errName = $errEmail = $errMessage = "";
    $errorCheck = TRUE;
    if ($_SERVER["REQUEST_METHOD"] == "POST") {
        $email = $_POST['email'];
        $name = $_POST['name'];
        $message = $_POST['message'];
        if (empty($name)) {
            $errName = "*Name Required";
            $errorCheck = FALSE;
        } else {
            $name = test_input($name);
            if (!preg_match("/^[a-zA-Z-' ]*$/",$name)) {
                $errName = "*Only letters and white space allowed";
                $errorCheck = FALSE;
            }
            else{
                $errorCheck = TRUE;
            }
        }
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
        if (empty($message)) {
            $errMessage = "*Message Required";
            $errorCheck = FALSE;
        }else {
            $message = test_input($message);
            if ($errorCheck){
                $errorCheck = TRUE;
            }
        }
        if ($errorCheck){
            $stmt = $conn->prepare("INSERT INTO Contact VALUES (:Username, :Email, :Messages)");
            $stmt->bindParam(":Username", $name);
            $stmt->bindParam(":Email", $email);
            $stmt->bindParam(":Messages", $message);
            $stmt->execute();
            $mail = new PHPMailer(true);

            try {
                $mail->SMTPDebug = 2;                                   
                $mail->isSMTP();                                            
                $mail->Host  = 'smtp.gmail.com;';                   
                $mail->SMTPAuth = true;                         
                $mail->Username = 'notesapp.php@gmail.com';             
                $mail->Password = '';                       
                $mail->SMTPSecure = 'tls';                          
                $mail->Port  = 587;

                $mail->setFrom('notesapp.php@gmail.com', 'Notes App');      
                $mail->addAddress($email);
                
                $mail->isHTML(true);                                
                $mail->Subject = 'NotesApp Contact';
                $mail->Body = '<html><body><h1>Thank for contacting us '.$name.'</h1><h3>We will keep this in mind in the future: '.$message.'</h3><h3>Regards,</h3><br><h3>NotesApp Team</h3></body></html>';
                $mail->send();
                $prompt_message = "Thank you for contacting us";
                echo "<script>alert('$prompt_message');
                    window.location.href='/MiniProject/NoteKeeper/home.php';
                </script>";
            } catch (Exception $e) {
                $prompt_message = "Message could not be sent. Mailer Error: {$mail->ErrorInfo}";
                echo "<script>alert('$prompt_message');
                    window.location.href='/MiniProject/NoteKeeper/home.php';
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
    <script src="https://smtpjs.com/v3/smtp.js"></script>
    <script src="https://kit.fontawesome.com/a076d05399.js"></script>
    <title>Contact</title>
    <link rel="stylesheet" type="text/css" href="<?php echo (($_COOKIE['style'] == "dark")?'contactstyle_dark':'contactstyle') ?>.css" />
</head>

<nav class="navbar navbar-expand-lg navbar-light fixed-top">
    <div class="container">
        <a class="brand" href="./home.php"> <img src="./4.png" style="width: 185px;" alt="logo"></a>
        <div class="collapse navbar-collapse" id="navbarSupportedContent">
            <ul class="navbar-nav ms-auto">
                <li class="nav-item">
                    <a class="nav-link" href="./home.php">Home</a>
                </li>
                <?php
                    if(!isset($_SESSION["username"])) {
                ?>
                    <li class="nav-item">
                        <a class="nav-link" href="./login.php">Log In</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="./register.php">Sign Up</a>
                    </li>
                <?php
                    }else echo "";
                ?>

                <li class="nav-item  active">
                    <a class="nav-link" href="./contact.php">Contact</a>
                </li>
                <?php
                    if(isset($_SESSION["username"])) {
                ?>
                    <li class="nav-item">
                    <a class="nav-link" href="./note.php">My Notes</a>
                    </li>
                    <li class="nav-item">
                    <a class="logout" href="./logout.php"><span id="logout"><i class="fas fa-sign-out-alt" style="color: aliceblue; font-size:xx-large; padding: 5px;"></i></span></a>
                    </li>
                <?php
                    }else echo "";
                ?>
            </ul>
        </div>
    </div>
</nav>

<body>

    <div class="contact-page">
        <div class="form">
            <span class="active"> Contact Us </span>
            <p>Please fill in your details to connect with us</p>

            <form class="contact-form" action='contact.php' method="POST">
                <input type="text" placeholder="Name" name="name" value="<?php echo $name;?>"/>
                <p class="error"><?php echo $errName;?></p>
                <input type="text" placeholder="Email" name="email" value="<?php echo $email;?>"/>
                <p class="error"><?php echo $errEmail;?></p>
                <textarea type="usermessage" rows=5 placeholder="Message" name="message" value ="<?php echo $message;?>" ></textarea>
                <p class="error"><?php echo $errMessage;?></p>
                <button>Send</button>
            </form>
        </div>
    </div>
</body>

</html>