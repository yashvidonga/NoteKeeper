<?php
     require 'composer/vendor/autoload.php';
    use PHPMailer\PHPMailer\PHPMailer;
    use PHPMailer\PHPMailer\Exception;

    session_start();
    $servername = "localhost:3306"; 
    $username = "root"; 
    $password = "";
   
    $database = "notes_app";
   
    try 
      {
        $conn = new PDO("mysql:host=$servername;dbname=$database", $username, $password);
        $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        
      } 
      catch(PDOException $e)
       {
        echo "Connection failed: " . $e->getMessage();
      }

      function test_input($data) 
      {
        $data = trim($data);
        $data = stripslashes($data);
        $data = htmlspecialchars($data);
        return $data;
    }                       
     
    $errTask = "";
    $task = "";
    $errorCheck = TRUE;
    $user = $_SESSION['username'];
    $pdo_statement = $conn->prepare("SELECT * FROM tasks WHERE Username = :Username");
    $pdo_statement->bindParam(':Username', $user);
    $pdo_statement->execute();
    $result = $pdo_statement->fetchAll();

    if ($_SERVER["REQUEST_METHOD"] == "POST") 
    {

        $task = $_POST['task'];
        if (empty($task)) {
            $errTask = "*Task Required";
            $errorCheck = FALSE;
        } 
        else 
        {
            $task = test_input($task);
            $errorCheck = TRUE;
        }
       
        if ($errorCheck)
        {
            $message = "Task Created";
            if(isset($_POST['task'])) 
            {
                $task = $_POST['task'];
                $ddate = $_POST['deadline'];
                $date = date('Y-m-d H:i:s');
                $reminder=0;
                //$user = $_SESSION['username'];
                $stmt = $conn->prepare("INSERT INTO tasks VALUES (:Username, :Task, :PostDate, :Deadline,:Reminder)");
                $stmt->bindParam(':Username', $user);
                $stmt->bindParam(':Task', $task);
                $stmt->bindParam(':PostDate', $date);
                $stmt->bindParam(':Deadline', $ddate);
                $stmt->bindParam(':Reminder', $reminder);
                $stmt->execute();
                echo "<script>alert('$message');</script>";
                header("Refresh:0; url=tasks.php");
            }
        }
    }
    function delete($task) {
      $servername = "localhost:3306"; 
      $username = 'root';
      $password = '';
      $database = "notes_app";
   
      $user = $_SESSION['username'];
      $conn = new PDO("mysql:host=$servername;dbname=$database", $username, $password);
      $message = "Task Deleted"; 
      try{
          $pdo_statement = $conn->prepare("DELETE FROM Tasks WHERE Username = :Username AND Task = :Task");
          $pdo_statement->bindParam(':Username', $user);
          $pdo_statement->bindParam(':Task', $task);
          $pdo_statement->execute();    
          echo "<script>alert('$message');</script>";       
          header("Refresh:0, url=tasks.php");
      } 
      catch (PDOExpection $e)
      {
          echo "Delete unsuccessful";
      }
  }
  
  function sendmail($name,$task,$email)
  {
    $mail = new PHPMailer(true);
    $mail->SMTPDebug = 0;                                   
    $mail->isSMTP();                                            
    $mail->Host  = 'smtp.gmail.com;';                   
    $mail->SMTPAuth = true;                         
    $mail->Username = '';             
    $mail->Password = '';                       
    $mail->SMTPSecure = 'tls';                          
    $mail->Port  = 587;

    $mail->setFrom('', 'Notes App');      
    $mail->addAddress($email);
    
    $mail->isHTML(true);                                
    $mail->Subject = 'NotesApp Task Reminder';
    $mail->Body = '<html><body><h1 style="color:red;">Reminder '.$name.'!</h1><h3>This is your reminder that your task-'.$task.' is due tomorrow!</h3><h3>Regards,</h3><br><h3>NotesApp Team</h3></body></html>';
    $mail->send();
  }
?>

<!DOCTYPE html>
<html>
    <head>
        
  
          <meta charset="utf-8">
          <meta name="viewport" content="width=device-width, initial-scale=1">
          <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-EVSTQN3/azprG1Anm3QDgpJLIm9Nao0Yz1ztcQTwFspd3yD65VohhpuuCOmLASjC" crossorigin="anonymous">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-MrcW6ZMFYlzcLA8Nl+NtUVF0sA7MsXsP1UyJoMp4YLEuNSfAP+JcXn/tWtIaxVXM" crossorigin="anonymous"></script>
    <script src="https://smtpjs.com/v3/smtp.js"></script>
    <script src="https://kit.fontawesome.com/a076d05399.js"></script>
         <link rel="stylesheet" type="text/css" href="<?php echo (($_COOKIE['style'] == "dark")?'task_style_dark':'task_style') ?>.css" />
        <title>Tasks</title>
    </head>
    <nav class="navbar navbar-expand-lg navbar-light fixed-top" style="padding: 0.5% 0% 0.5% 7.5%;">
        <div class="container">
            <a class="brand" href="./home.php"> <img src="./4.png" style="width: 185px;" alt="logo"></a>
            <div class="collapse navbar-collapse" id="navbarSupportedContent">
                <ul class="navbar-nav ms-auto">
                    <li class="nav-item">
                        <a class="nav-link" href="home.php">Home</a>
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
                    <li class="nav-item">
                        <a class="nav-link" href="contact.php">Contact</a>
                    </li>
                    <?php
                        if(isset($_SESSION["username"])) {
                    ?>
                        <li class="nav-item  active">
                            <a class="nav-link" href="./notes.php">My Notes</a>
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
   
        <div class="container" style="margin-top: 4%;">
            
                <!-- <div class="col" style="padding: 0px 0px 0px 0px; ">
                    <div class="row-half" style="padding: 0px 0px 0px 0px; "></div> -->
                <!-- <div class="body">   -->
       
              <div class="wrapper fadeInDown" id="wrapper">
                <div id="formContent">
                    <div class="fadeIn first">
                        <h2>Add Your Task With Deadline Here</h2><br>    
                        <div class = "main">
                        <form class="task-form" action='tasks.php' method="POST">
                            <input type = "text" name='task' id = "task"><br><br>                            
                            <label for="deadline" style='color:red;'>Deadline:</label>
                            <input type="date" id="deadline" name="deadline"><br><br>    
                            <input type="submit" class="fadeIn fourth" value="Add"/>
                            </form>
                            <hr>
                            <div class = "tasksbar">
                            <ul class="taskmenu">
                            <?php   
                     
                          
                               if ($result) 
                               {
                                   foreach($result as $row)
                                   {
                                    if (isset($_GET['name']))
                                     {
                                      delete($_GET['name']);
                                    }

                                    $date=date_create($row['Deadline']);
                                    $deadline=date_format($date,"d-m-Y");
                                    $currdate=date("d-m-Y");
                                    $comparedate=date('d-m-Y', strtotime($currdate. ' + 1 days'));
                                    if(($comparedate==$deadline) && ($row['Reminder']!=1))
                                    {
                                       sendmail($row['Username'],$row['Task'], $_SESSION['email']);
                                       $sql=$conn->prepare("UPDATE Tasks SET Reminder='1' WHERE Task = :Task");
                                       $sql->bindParam(':Task',$row['Task']);
                                       $sql->execute();

                                    }
                                       echo "<li>";                                                            
                                       echo '<span class = "delete">
                                       <a href="?name='. $row['Task'] .'">X</a>
                                       </span>'."<input type = 'checkbox'"."<label class='task'>".$row['Task']."</label> &nbsp;".
                                      "<label style='Color:red;float:right;'>".$deadline."</label>";
                                       echo"</li>";                        
                                   }
                                 } 
                                 else 
                                 {
                                  echo 'No tasks due.';
                                 }
                                 ?>
                              </ul>
                            </div>
                          </div>
                    </div>
                </div>
            </div>
          </div>
         </div>
    </body>
  </html>
