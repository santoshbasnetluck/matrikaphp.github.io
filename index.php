<?php 
    session_start();
    if(isset($_SESSION['user'])){
        // echo $_SESSION['user'][0]; 
    }else{
     }
?>

<!DOCTYPE html>
<html>
    <head>
        <meta charset="utf-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <title></title>
        <meta name="description" content="">
        <meta name="viewport" content="width=device-width, initial-scale=1">
 
        <link rel="preconnect" href="https://fonts.googleapis.com">
        <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
        <link href="https://fonts.googleapis.com/css2?family=Open+Sans:ital,wght@0,300;0,400;0,500;0,700;1,300;1,400;1,500;1,700&family=Playfair+Display:ital,wght@0,400;0,500;0,700;1,400;1,500;1,700&display=swap" rel="stylesheet">
        
        <script src="" async defer></script>
    </head>
    <body class="flex-wrapper">
        <header>
            <?php include("nav.php");?>
        </header>
        
        <main class="index-container">
             <div class="index-images">
                 <img src="Photos/Index01.jpg" class="index-photo01">
                 <img src="Photos/Index02.jpg" class="index-photo02">
                 <img src="Photos/Index03.jpg" class="index-photo03">
                 <div class="index-border01"></div>
                 <div class="index-border02"></div>
                 <div class="index-blue"></div>
                 <div class="index-purple"></div>
             </div>
             <div class="index-content-container">
                 <h1 class="index-content__header header--big text--capitalize">Welcome, <?php echo $_SESSION['user']['first_name']; ?></h1>
                 <h1 class="text--capitalize">Your bookcases:</h1>
                 <?php
                    include("db_connect.php");
                    $user_id = $_SESSION['user'][0];
                    $query = "SELECT bookcase_id, bookcase_name FROM bookcase JOIN user ON bookcase.user_id = user.user_id WHERE bookcase.user_id='$user_id'";
                    $result = mysqli_query($db_connection, $query);
                    echo "<div class='mobile-container search-result'>";
                    if(mysqli_num_rows($result) == 0){ ?>
                        <h3>You don't have any bookcases.</h3>
                    <?php
                        }else{
                            while($row = mysqli_fetch_array($result)){?>
                                <a class="text--capitalize" href="shelf.php?id=<?php echo $row['bookcase_id'];?>"><?php echo $row['bookcase_name']; ?></a>
                    <?php        }
                        }
                    echo "</div>"
                    ?>
                 <div>
                    <input type="submit" 
                        class="submit submit--dark" 
                        value="Add New Bookcases" 
                        name="addNewBookcases"
                        onclick="location.href = '/dashboard/bookcase.php'">
                   
              </div>
        </main>

       
    </body>
</html>