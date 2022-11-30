<?php
    session_start();
    $database_username = 'root';
    $database_password = '';
    $pdo_conn = new PDO( 'mysql:host=127.0.0.1:8111;dbname=notes_app', $database_username, $database_password );
     
    function test_input($data) {
        $data = trim($data);
        $data = stripslashes($data);
        $data = htmlspecialchars($data);
        return $data;
    }

    function delete($title) {
        $database_username = 'root';
        $database_password = '';
        $user = $_SESSION['username'];
        $pdo_conn = new PDO( 'mysql:host=127.0.0.1:8111;dbname=notes_app', $database_username, $database_password );
        try{
            $pdo_statement = $pdo_conn->prepare("DELETE FROM notes WHERE Username = :Username AND Title = :Title");
            $pdo_statement->bindParam(':Username', $user);
            $pdo_statement->bindParam(':Title', $title);
            $pdo_statement->execute();            
            header("Refresh:0; url=notes.php");
        } catch (PDOExpection $e){
            echo "Delete unsuccessful";
        }
    }

    function download($title) {
        $database_username = 'root';
        $database_password = '';
        $user = $_SESSION['username'];
        $pdo_conn = new PDO( 'mysql:host=127.0.0.1:8111;dbname=notes_app', $database_username, $database_password );
        $pdo_statement = $pdo_conn->prepare("SELECT * FROM notes WHERE Username = :Username AND Title = :Title");
        $pdo_statement->bindParam(':Username', $user);
        $pdo_statement->bindParam(':Title', $title);
        $pdo_statement->execute();
        $row = $pdo_statement->fetch();         
        if(!empty($row)) {
            $title = $row['Title'];
            $content = $row['Content'];
            $date = $row['Date'];
            $fileNameTitle = $title.".txt";
            $myfile = fopen($fileNameTitle, "w") or die("Unable to open file!");
            $txt = "Title:$title\nNote:$content\nDate:$date\n";
            fwrite($myfile, $txt);
            fclose($myfile);
            $filePath = './'.$fileNameTitle;
            if(!empty($fileNameTitle) && file_exists($filePath)){
                header('Content-Description: File Transfer');
                header('Content-Type: application/octet-stream');
                header('Content-Disposition: attachment; filename="./'.$fileNameTitle."\"");
                header('Content-Transfer-Encoding: binary');
                header('Expires: 0');
                header('Cache-Control: must-revalidate, post-check=0, pre-check=0');
                header('Pragma: public');
                ob_clean();
                flush();
                readfile($fileNameTitle);
               exit();
            }
            else{
               echo 'The file does not exist.';
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
        <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.0/dist/css/bootstrap.min.css" rel="stylesheet">
        <link rel="preconnect" href="https://fonts.googleapis.com">
        <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
        <link href="https://fonts.googleapis.com/css2?family=Source+Sans+Pro:wght@300;400;600&display=swap" rel="stylesheet">
        <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.9.2/dist/umd/popper.min.js"></script>
        <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.1/dist/js/bootstrap.min.js" integrity="sha384-Atwg2Pkwv9vp0ygtn1JAojH0nYbwNJLPhwyoVbhoPwBhjQPR5VtM2+xf0Uwh9KtT" crossorigin="anonymous"></script>
        <script src="https://kit.fontawesome.com/a076d05399.js"></script>
        <link rel="stylesheet" type="text/css" href="<?php echo (($_COOKIE['style'] == "dark")?'notestyle_dark':'notestyle') ?>.css" />
        <title>My Notes</title>
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
                        <li class="nav-item">
                            <a class="nav-link" href="./notes.php">My Notes</a>
                        </li>
                        <li class="nav-item active">
                            <a class="nav-link" href="./publicNotes.php">Public Notes</a>
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
                <h2>Public notes</h2>
                <div class="body">
                    <?php
                        $user = $_SESSION["username"];
                        $check = 1;
                        $pdo_statement = $pdo_conn->prepare("SELECT * FROM notes WHERE Is_public = :public");
                        $pdo_statement->bindParam(':public', $check);
                        $pdo_statement->execute();
                        $result = $pdo_statement->fetchAll();
                        if ($result) {
                            foreach($result as $row){
                                echo "<div class='note'>";
                                if (isset($_GET['name2'])) {
                                    download($_GET['name2']);
                                }
                                if (isset($_GET['name'])) {
                                    delete($_GET['name']);
                                }
                                echo "<h2>" . $row['Title'] . "</h2>";
                                echo "<p>By ". $row['Username'] ."</p>";
                                echo "<p>" . $row['Content'] . "</p>";
                                echo "<span class='date'>" . $row['Date'] . "</span>";
                                echo "<div id='icon'>";
                                echo '&nbsp <span id="icon1"><a id = "trash" href="?name='. $row['Title'] .'"><i class="fas fa-trash"></i></a></span>&nbsp &nbsp';
                                echo '&nbsp &nbsp <span id="icon1"><a id = "trash" href="?name2='. $row['Title'] .'"><i class="fas fa-download"></i></a></span>&nbsp &nbsp';
                                echo "</div>";
                                echo "</div>";
                            }
                          } else {
                            echo "0 results";
                          }
                    ?>
                </div>
            
        </div>
    </body>
</html>