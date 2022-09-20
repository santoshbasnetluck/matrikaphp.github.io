<?php
    include("db_connect");
    session_start();
    session_unset();
    session_destroy();
    if ($db_connection) {
        mysqli_close($db_connection);
    }
    header("Location: login.php");
?>