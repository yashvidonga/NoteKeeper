

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
        <link rel="stylesheet" href="notestyle.css">
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
                        <li class="nav-item">
                            <a class="nav-link" href="./login.php">Log In</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="./register.php">Sign Up</a>
                        </li>
                    <li class="nav-item">
                        <a class="nav-link" href="contact.php">Contact</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="publicNotes.php">Public Notes</a>
                    </li>
                        <li class="nav-item  active">
                            <a class="nav-link" href="./note.php">My Notes</a>
                        </li>
                        <li class="nav-item">
                            <a class="logout" href="./logout.php"><span id="logout"><i class="fas fa-sign-out-alt" style="color: aliceblue; font-size:xx-large; padding: 5px;"></i></span></a>
                        </li>
                </ul>
            </div>
        </div>
    </nav>
    <body>
        <div class="container" style="margin-top: 4%;">
            <div class="row">
                <div class="col" style="padding: 0px 0px 0px 0px;">
                    <div class="row-half" style="padding: 0px 0px 0px 0px; "></div>
                <div class="body">
                <!--  -->
                </div>
            </div>
            <div class="col-6" style="margin-left: 20%;">
                <form class="note-form" action="note.php" method="POST">
                    <?php 
                    if($insert == true) {
                        $message = "Note created successfully.";
                        echo 
                        "<script>alert('$message');
                        </script>";
                    }     
                    ?>
                    <input type ="text" placeholder="Add a title..."  name="title" style="width: 85%; height: 5%;"/>
                    <textarea placeholder="Content" name="content" style="width: 85%; height: 60vh;"></textarea>
                    <span style="padding: 1%;"><input type="checkbox" name="check" value="Yes">Make the Note Public</span>
                    <br>
                    <button>Create Note</button>
                </form>
            </div>
        </div>
    </body>
</html>