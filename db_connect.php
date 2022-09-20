<?php
        $host_name = "localhost";
        $db_user = "root";
        $db_pass = "";
        $db_name = "book_db";

         $db_connection = mysqli_connect($host_name, $db_user, $db_pass, $db_name); 

         if(mysqli_connect_errno()) {
            die("Connection failed: " . mysqli_connect_error());
        }
?>