<?php
session_start();
unset($_SESSION["username"]);
unset($_SESSION["password"]);
$message = "User logged out successfully!";
echo  
        "<script>alert('$message');
        window.location.href='/MiniProject/NoteKeeper/home.php';
        </script>";
?>