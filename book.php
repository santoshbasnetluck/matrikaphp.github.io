<?php 
    session_start();
    if(isset($_SESSION['user'])){
         $user_id = $_SESSION['user'][0];
        if(!isset($_POST['proceed']) && !isset($_GET['id']) && !isset($_POST['remove']) && !isset($_POST['confirm'])){
            header('Location: search.php');
        }
    }else{
        header("Location: login.php");
    }
?>
<!DOCTYPE html>
<html>
    <head>
        <meta charset="utf-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <title>Select Book</title>
        <meta name="description" content="">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <link rel="stylesheet" href="CSS/style.css">

        <link rel="preconnect" href="https://fonts.googleapis.com">
        <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
        <link href="https://fonts.googleapis.com/css2?family=Open+Sans:ital,wght@0,300;0,400;0,500;0,700;1,300;1,400;1,500;1,700&family=Playfair+Display:ital,wght@0,400;0,500;0,700;1,400;1,500;1,700&display=swap" rel="stylesheet">

        <script src="" async defer></script>
    </head>

    <body class="flex-wrapper">
        <header>
             <?php include("nav.php"); ?>
        </header>

        <main class="main-container">
            <div class="left-book background-image"></div>

            <div class="mobile-container right-book">
                <?php
                    if(isset($_POST['proceed']) || isset($_GET['id'])){
                        include("db_connect.php");
                        if(isset($_POST['proceed'])){
                            $shelf_id = mysqli_real_escape_string($db_connection, $_POST['shelf']);
                        }else{
                            $shelf_id = mysqli_real_escape_string($db_connection, $_GET['id']);
                        }
                        
                        $query = "SELECT shelf.shelf_name, book.title, book.isbn FROM shelf JOIN shelf_book ON shelf.shelf_id = shelf_book.shelf_id JOIN book ON shelf_book.ISBN=book.ISBN WHERE shelf.shelf_id = '$shelf_id' AND user_id='$user_id';";
                        $result = mysqli_query($db_connection, $query);

                        if(mysqli_num_rows($result) == 0){
                            echo "<h2>Shelf currently doesn't have any books.</h2>";
                        }else{
                            $get_shelf = "SELECT shelf_name FROM shelf WHERE shelf_id = $shelf_id";
                            $shelf_result = mysqli_query($db_connection, $get_shelf);
                                while($row = mysqli_fetch_array($shelf_result)){
                                    $shelf_name = $row['shelf_name'];
                                }
                            ?>
                            <h1 class="header--big text--unbold text--italize text--capitalize"><?php echo $shelf_name; ?></h1>
                            <form action="display.php" method="post" z class="form-container bookcase-form-container">
                             <table>
                                <tr>
                                        <th>ISBN</th>
                                        <th>Title</th>
                                </tr>
                                <?php                         
                                while($row = mysqli_fetch_array($result)){ ?>
                                    <tr>
                                        <td><a href="display.php?id=<?php echo $row['isbn'];?>"><?php echo $row['isbn']; ?></a></td>
                                        <td class="text--capitalize"><?php echo $row['title']; ?></td>
                                    </tr>
                                <?php } ?>
                            </table>
                 
                                </form>
                        <?php }
                    }
                ?>

                <?php
                    if(isset($_POST['remove'])){
                        include("db_connect.php");
                        $shelf_id = $_POST['shelf'];
                        $get_shelf = "SELECT shelf_name FROM shelf WHERE shelf_id ='$shelf_id'";
                        $result = mysqli_query($db_connection, $get_shelf);
                        while($row = mysqli_fetch_array($result)){
                            $shelf_name = $row['shelf_name'];
                        }
                    ?>
                    <h1>Are you sure you want to delete "<span class="text--capitalize"><?php echo $shelf_name; ?>" </span>?</h1>
                    <?php
                        $get_bookcase_name = "SELECT bookcase_name FROM shelf JOIN bookcase ON bookcase.bookcase_id = shelf.bookcase_id WHERE shelf_id = '$shelf_id' ";
                        $get_bookcase_name_result = mysqli_query($db_connection, $get_bookcase_name);
                        while($row = mysqli_fetch_array($get_bookcase_name_result)){
                            $bc_name = $row['bookcase_name'];
                        }

                        $get_books = "SELECT book.isbn, title FROM shelf_book JOIN shelf ON shelf.shelf_id = shelf_book.shelf_id JOIN book ON shelf_book.isbn = book.isbn WHERE shelf_book.shelf_id = '$shelf_id' AND book.user_id = '$user_id'";
                        $result = mysqli_query($db_connection, $get_books);
                        if(mysqli_num_rows($result) == 0){
                            echo "<h2 class='error-message'>Shelf will be removed from bookcase <span class='text--capitalize'>" . $bc_name . "</span> !</h2>";
                        }else{
                            echo "<h2 class='error-message'>Shelf will be removed from bookcase <span class='text--capitalize'>" . $bc_name . "</span>!</h2>";
                    ?>
                    <table> 
                        <tr>
                                <th>ISBN</th>
                                <th>Title</th>
                        </tr>
                        <?php                         
                        while($row = mysqli_fetch_array($result)){ ?>
                            <tr>
                                <td><?php echo $row['isbn']; ?></td>
                                <td class="text--capitalize"><?php echo $row['title']; ?></td>
                            </tr>
                        <?php } } ?>
                    </table>
                    <form action="book.php" method="post" class="form-container bookcase-add-container">
                        <div class="double-button-container">
                            <input type="hidden" name="shelf" value="<?php echo $shelf_id; ?>">                    
                            <input type="submit" class="submit submit--remove" value="Delete" name="confirm">
                            <input type="submit" class="submit submit--dark" value="Cancel" name="proceed">
                        </div>
                    </form>
                <?php }  
                    if(isset($_POST['confirm'])){
                        include("db_connect.php");
                        $shelf_id = $_POST['shelf'];
                        $get_shelf = "SELECT shelf_name FROM shelf WHERE shelf_id ='$shelf_id'";
                        $result = mysqli_query($db_connection, $get_shelf);
                        while($row = mysqli_fetch_array($result)){
                            $shelf_name = $row['shelf_name'];
                        }
                        $query = "SELECT isbn FROM shelf_book JOIN shelf ON shelf.shelf_id = shelf_book.shelf_id WHERE shelf_book.shelf_id = '$shelf_id'";
                        $result = mysqli_query($db_connection, $query);
                        if(mysqli_num_rows($result) > 0){
                             while($row = mysqli_fetch_array($result)){
                                 $isbn = $row['isbn'];
                                $add_to_heap = "INSERT INTO heap (user_id, isbn) VALUES ('$user_id','$isbn')";
                                $add_to_heap_result = mysqli_query($db_connection, $add_to_heap);
                             }
                        }
                   
                        $remove_shelf = "DELETE FROM shelf WHERE shelf_id = '$shelf_id'";
                        $remove_shelf_result = mysqli_query($db_connection, $remove_shelf);
 
                        echo "<h1 class='success-message'> Shelf " . $shelf_name . " was successfully deleted!</h1>";
                    }
                ?>
             
            </div>
        </main>
     
    </body>
</html>