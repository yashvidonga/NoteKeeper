<?php
    session_start();
    if ($_SERVER["REQUEST_METHOD"] == "POST") {
        if ($_COOKIE['style'] == "dark"){
            setcookie("style", "light", time() + (86400 * 30), "/"); 
        }else{
            setcookie("style", "dark", time() + (86400 * 30), "/"); 
        }
    }else{
        setcookie("style", "light", time() + (86400 * 30), "/"); 
    }
?>
<!DOCTYPE html>
<html lang="en">

    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <meta http-equiv="X-UA-Compatible" content="ie=edge">
        <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-EVSTQN3/azprG1Anm3QDgpJLIm9Nao0Yz1ztcQTwFspd3yD65VohhpuuCOmLASjC" crossorigin="anonymous">
        <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-MrcW6ZMFYlzcLA8Nl+NtUVF0sA7MsXsP1UyJoMp4YLEuNSfAP+JcXn/tWtIaxVXM" crossorigin="anonymous"></script>
        <script src="https://kit.fontawesome.com/a076d05399.js"></script>
        <link rel="stylesheet" href="./homestyle.css">
        <title>Notes Keeper</title>
    </head>

    <body>
    <nav class="navbar navbar-expand-lg navbar-light fixed-top">
        <div class="container">
            <a class="brand" href="./home.php"> <img src="./4.png" style="width: 185px;" alt="logo"></a>
            <div class="collapse navbar-collapse" id="navbarSupportedContent">
                <ul class="navbar-nav ms-auto">
                    <li class="nav-item active">
                        <a class="nav-link" href="./home.php">Home</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="./login.php">Log In</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="./register.php">Sign Up</a>
                    </li>
                    
                    <li class="nav-item">
                        <a class="nav-link" href="./contact.php">Contact</a>
                    </li>     
                    <li class="nav-item">
                        <a class="nav-link"></a>
                    </li> 
                    <?php
                        if(isset($_SESSION["username"])) {
                    ?>
                        <li class="nav-item">
                            <a class="nav-link" href="./notes.php">My Notes</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="./publicNotes.php">Public Notes</a>
                        </li>
                        <li class="nav-item">
                            <a class="logout" href="./logout.php"><span id="logout"><i class="fas fa-sign-out-alt" style="color: aliceblue; font-size:xx-large; padding: 5px;"></i></span></a>
                        </li>
                    <?php
                        }else echo "";
                    ?>     
                    <form action='./home.php' method="POST" class="nav-item" id="form">
                        <div class="theme-check" >
                            <label id="switch" class="switch">
                                <input type="checkbox" onchange="toggleTheme()" id="slider">
                                <span class="slider round"></span>
                            </label>
                        </div>
                    </form>
                    <script>
                            function toggleTheme(){
                                let form = document.getElementById("form");
                                form.submit();
                                if (localStorage.getItem('theme') === 'theme-dark') {
                                    setTheme('theme-light');
                                } else {
                                    setTheme('theme-dark');
                                }
                            }
                            function setTheme(themeName) {
                                localStorage.setItem('theme', themeName);
                                document.documentElement.className = themeName;
                            }

                            // Immediately invoked function to set the theme on initial load
                            (function () {
                                if (localStorage.getItem('theme') === 'theme-dark') {
                                    setTheme('theme-dark');
                                    document.getElementById('slider').checked = false;
                                } else {
                                    setTheme('theme-light');
                                document.getElementById('slider').checked = true;
                                }
                            })();
                    </script>
                </ul>
            </div>
        </div>
    </nav>
        <div id="carouselExampleControls" class="carousel slide" data-bs-ride="carousel">
            <div class="carousel-inner">
                <div class="carousel-item active">
                    <img src="1.png" class="d-block w-100" alt="notepadImage">
                </div>
                <div class="carousel-item">
                    <img src="2.png" class="d-block w-100" alt="notepadImage">
                </div>
                <div class="carousel-item">
                    <img src="3.png" class="d-block w-100" alt="notepadImage">
                </div>
            </div>
            <button class="carousel-control-prev" type="button" data-bs-target="#carouselExampleControls" data-bs-slide="prev">
                <span class="carousel-control-prev-icon" aria-hidden="true"></span>
                <span class="visually-hidden">Previous</span>
            </button>
            <button class="carousel-control-next" type="button" data-bs-target="#carouselExampleControls" data-bs-slide="next">
                <span class="carousel-control-next-icon" aria-hidden="true"></span>
                <span class="visually-hidden">Next</span>
            </button>
        </div>

    </body>
</html>