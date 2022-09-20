    <?php 
        session_start();
        if(isset($_SESSION['user'])){
            if(isset($_GET['id'])){
                if(isset($_GET['id'])){
                    $isbn = $_GET['id'];
                }
             }else{
                echo "here";
             }
        }else{
         }

        if(isset($_POST['display-bookcase'])){
            $bookcase_id = $_POST['display-bookcase'];
            if($_POST['display-bookcase'] == "heap"){
                $shelf = FALSE;
            }else{
                $shelf = TRUE;
            }
        }else{
            $shelf = FALSE;
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
            <link rel="stylesheet" href="CSS/style.css">

            <link rel="preconnect" href="https://fonts.googleapis.com">
            <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
            <link href="https://fonts.googleapis.com/css2?family=Open+Sans:ital,wght@0,300;0,400;0,500;0,700;1,300;1,400;1,500;1,700&family=Playfair+Display:ital,wght@0,400;0,500;0,700;1,400;1,500;1,700&display=swap" rel="stylesheet">
        </head>

        <body class="flex-wrapper">
            <header>
                <?php include("nav.php"); ?>
            </header>
            <main class="main-container">
                <?php 
                if(isset($_GET['id'])){
                    include("db_connect.php");
                     $isbn = mysqli_real_escape_string($db_connection,$_GET["id"]);
             
                    $query = "SELECT isbn, title, first_name, last_name, FORMAT(list_price,2) AS list_price, genre.name, publish_date FROM book JOIN author ON book.author_id = author.author_id JOIN genre ON book.genre_id = genre.genre_id WHERE isbn = $isbn";
                    $result = mysqli_query($db_connection, $query);
                    if(mysqli_num_rows($result) == 0){
                     }else{
                        while($row = mysqli_fetch_array($result)){
                            $ISBN = $row['isbn'];
                            $title = $row['title'];
                            $author = $row['first_name'] . " " . $row['last_name'];
                            $genre = $row['name'];
                            $list_price = $row['list_price'];
                            $publish_date = $row['publish_date'];
                        }
    
                        $user_id = $_SESSION['user'][0];
                        $check_heap = "SELECT * FROM heap WHERE isbn='$isbn' AND user_id='$user_id';";
                        $check_heap_result = mysqli_query($db_connection, $check_heap);
    
    
                        $check_shelf = "SELECT * FROM shelf_book JOIN shelf ON shelf.shelf_id = shelf_book.shelf_id JOIN bookcase on bookcase.bookcase_id = shelf.bookcase_id WHERE isbn='$isbn' AND user_id='$user_id'";
                        $check_shelf_result = mysqli_query($db_connection, $check_shelf);
                        if(mysqli_num_rows($check_heap_result) == 1){
                            $location = "Heap";
                        }
                        if(mysqli_num_rows($check_shelf_result) == 1){
                            while($row = mysqli_fetch_array($check_shelf_result)){
                                $location = "Bookcase: ". $row['bookcase_name'] . "<br> Shelf: " . $row['shelf_name'];
                                $shelf_id = $row['shelf_id'];
                            }
                        }
                    }

                }           
                ?>
                <div class="mobile-container left-display">
                <?php     
                        if(isset($_POST['confirm'])){
                            $remove_from_book = "DELETE FROM book WHERE user_id='$user_id' AND isbn='$isbn'";
                            $remove_from_book_result = mysqli_query($db_connection, $remove_from_book);
                            echo "<h1 class='success-message'> Book  <span class='text--capitalize'>" . $_POST['title'] . "</span> was successfully deleted!</h1>";
                        }else{
                    ?>
                    <h1 class="left-display__header header--big text--unbold text--italize text--capitalize"><?php echo $title ?></h1>
                    <ul>
                        <li class="left-display__output"><span class="left-display__output--title">Author</span> <?php echo $author; ?></li>
                        <li class="left-display__output"><span class="left-display__output--title">Genre:</span> <?php echo $genre;?></li>
                        <li class="left-display__output" id="isbn"><span class="left-display__output--title">ISBN:</span> <?php echo $isbn;?></li>
                        <li class="left-display__output"><span class="left-display__output--title">List Price:</span> <?php echo $list_price;?></li>
                        <li class="left-display__output"><span class="left-display__output--title">Publish Date:</span> <?php echo $publish_date;?></li>
                        <li class="left-display__output"><span class="left-display__output--title">Location:</span> <?php echo $location;?></li>
                    </ul>
                    <form action="display.php?id=<?php echo $isbn; ?>" method="post" class="form-container display-form">
                        <?php 
                            $get_bookcases = "SELECT bookcase_id, bookcase_name FROM bookcase WHERE user_id='$user_id'";
                            $get_bookcases_result = mysqli_query($db_connection, $get_bookcases);
                            if(mysqli_num_rows($get_bookcases_result) == 0){
                                echo "<h2>No bookcase yet.</h2>";
                            }else{ ?>
                                <?php 
                                if(isset($_POST['remove'])){?>
                                <h2 class="error-message">Are you sure you want to delete this book?</h2>
                                <form action="display.php?id=<?php echo $isbn; ?>" method="post" class="form-container bookcase-add-container">
                                    <div class="double-button-container">
                                        <input type="hidden" name="title" value="<?php echo $_POST['title'];?>">
                                        <input type="hidden" name="book-id" value="<?php echo $_POST['book-id'];?>">                   
                                        <input type="submit" class="submit submit--remove" value="Delete" name="confirm">
                                        <input type="submit" class="submit submit--dark" value="Cancel" name="proceed">
                                    </div>
                                </form>
                                <?php
                                }else{
                                ?>
                                <?php echo isset($bc_error) ? "<span class='error-message'>" . $bc_error . "</span>" : "";?>
                                <select onchange="this.form.submit()" id="display-bookcase" class="text--capitalize" name="display-bookcase" class="select" >
                                    <option disabled selected value>Select Bookcase</option>
                                <?php
                                while($row = mysqli_fetch_array($get_bookcases_result)){ ?>
                                    <option value="<?php echo $row['bookcase_id'];?>" <?php echo isset($_POST['display-bookcase']) && $_POST['display-bookcase'] == $row['bookcase_id'] ? 'selected' : '';?>> 
                                    <?php echo $row['bookcase_name']; ?> </option>
                                <?php } ?>
                                <option value="heap" <?php echo isset($_POST['display-bookcase']) && $_POST['display-bookcase'] == 'heap' ? 'selected' : ''; ?>> Heap </option>
                                </select>
                                <?php
                                if($shelf){
                                    $get_shelf = "SELECT shelf_id, shelf_name FROM shelf JOIN bookcase ON bookcase.bookcase_id = shelf.bookcase_id WHERE bookcase.user_id='$user_id' AND bookcase.bookcase_id='$bookcase_id'";
                                    $get_shelf_result = mysqli_query($db_connection, $get_shelf);
                                        if(mysqli_num_rows($get_shelf_result) == 0){
                                            echo "<h2> No shelf</h2>";
                                        }else{
                                ?>
                                    <?php echo isset($shelf_error) ? "<span class='error-message'>" . $shelf_error . "</span>" : "";?>
                                    <select name="display-shelf" class="select">
                                        <option disabled selected value>Select Shelf</option>
                                        <?php while($row = mysqli_fetch_array($get_shelf_result)){?>
                                        <option value="<?php echo $row['shelf_id'];?>" value="<?php echo $row['bookcase_id'];?>"><?php echo $row['shelf_name']; ?>
                                        </option>
                                <?php }
                                    }
                                }
                        ?>
                        </select>
                        <input type="hidden" name="title" value="<?php echo $title;?>">
                        <input type="hidden" name="book-id" value="<?php echo $isbn;?>">
                        <input type="hidden" name="shelf-id" value="<?php echo $shelf_id;?>">
                        <input type="hidden" name="book-location" value="<?php echo $shelf_id;?>">
                        <div class="double-button-container">
                            <input type="submit" class="submit submit--dark " value="Move" name="move">
                            <input type="submit" class="submit submit--remove" value="delete" name="remove">
                        </div>
                        <?php } } }?>
                    </form>

                </div>

                <div class="right-display">
                    <div class="display-blue01"></div>
                    <img src="Photos/Book.jpg" class="display-photo">
                    <div class="display-border--purple"></div>
                    <div class="display-border--blue"></div>
                    <div class="display-purple"></div>
                    <div class="display-blue02"></div>
                </div>
            </main>
            
         
        </body>
    </html>