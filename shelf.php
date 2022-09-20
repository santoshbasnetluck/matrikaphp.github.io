<?php 
    session_start();
    if(isset($_SESSION['user'])){
        //echo $_SESSION['user'][0]; 
        $user_id = $_SESSION['user'][0];
    }else{
    }

    if(isset($_POST['proceed']) || isset($_GET['id'])){
        //establish db connection
        include("db_connect.php");
        if(isset($_POST['proceed'])){
            $bookcase = mysqli_real_escape_string($db_connection,$_POST['bookcase']);
        }else{
            $bookcase = mysqli_real_escape_string($db_connection,$_GET['id']);
        }
        $query = "SELECT bookcase_id, bookcase_name FROM bookcase WHERE bookcase_id = '$bookcase';";
        $result = mysqli_query($db_connection, $query);
        while($row = mysqli_fetch_array($result)){
            $bookcase_name = $row['bookcase_name'];
            $_SESSION['bookcase'] = $row;
        }
    }
    
    if(isset($_SESSION['bookcase'][0])){
        $bookcase = $_SESSION['bookcase']['bookcase_id'];
        $bookcase_name = $_SESSION['bookcase']['bookcase_name'];
    }

    if(!isset($_POST['bookcase']) && !isset($_SESSION['bookcase'])){
        header("Location: index.php");
    }
?>
<!DOCTYPE html>
<html>
    <head>
        <meta charset="utf-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <title>Select Shelf</title>
        <meta name="description" content="">
        <meta name="viewport" content="width=device-width, initial-scale=1">

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
            <div class="mobile-container left-shelf">
                <?php
                if(isset($_POST['back_to_book'])){
                    header("Location: bookcase.php");
                }
                if(isset($_POST['confirm_delete_bc'])){
                    $bc_id = $_POST['bookcase-id'];
                    $bc_name = $_POST['bookcase-name'];
                    $get_books = "SELECT book.isbn, shelf_name, book.title FROM shelf JOIN shelf_book ON shelf.shelf_id = shelf_book.shelf_id JOIN book ON book.ISBN = shelf_book.isbn WHERE bookcase_id = '$bc_id' AND book.user_id = '$user_id'";
                    $get_books_result = mysqli_query($db_connection, $get_books);
                    if(mysqli_num_rows($get_books_result) > 0){
                        while($row = mysqli_fetch_array($get_books_result)){
                            $isbn = $row['isbn'];
                            $add_to_heap = "INSERT INTO heap (user_id, isbn) VALUES ('$user_id','$isbn')";
                            $add_to_heap_result = mysqli_query($db_connection, $add_to_heap);
                        }
                    }                   
                    $remove_bc = "DELETE FROM bookcase WHERE bookcase_id = '$bc_id'";
                    $remove_bc_result = mysqli_query($db_connection, $remove_bc);
                    echo "<h1 class='success-message'> Bookcase " . $bc_name . " was successfully deleted!</h1>";
                }else if(isset($_POST['remove'])){
                    include("db_connect.php");
                    $bc_id = $_POST['bookcase'];
                    $get_bc_name = "SELECT bookcase_name FROM bookcase WHERE bookcase_id = '$bc_id'";
                    $bc_result = mysqli_query($db_connection, $get_bc_name);
                    while($row = mysqli_fetch_array($bc_result)){
                        $bc_name = $row['bookcase_name'];
                    }
                    $get_books = "SELECT shelf_name, book.title FROM shelf JOIN shelf_book ON shelf.shelf_id = shelf_book.shelf_id JOIN book ON book.ISBN = shelf_book.isbn WHERE bookcase_id = '$bc_id' AND book.user_id = '$user_id'";
                    $get_books_result = mysqli_query($db_connection, $get_books);?>
                        <form action="shelf.php" method="post" class="form-container bookcase-add-container">
                            <h1 class="error-message"> Are you sure you want to delete the bookcase <span class='text--capitalize'><?php echo $bc_name; ?></span> ?</h1>
                            <?php
                                if(mysqli_num_rows($get_books_result) > 0){
                                    echo "<h2 class='error-message'>Removing a bookcase will remove all shelves and move these books to the heap.</h2>";
                                    while($row = mysqli_fetch_array($get_books_result)){
                                        echo "<p>" . $row['title'] . "</p>";
                                    }
                                   
                                }else{
                                    echo "<p>Bookcase is empty.</p>";
                                }
                            ?>
                            <div class="double-button-container">
                                <input type="hidden" name="bookcase-id" value="<?php echo $bc_id; ?>">
                                <input type="hidden" name="bookcase-name" value="<?php echo $bc_name; ?>">                         
                                <input type="submit" class="submit submit--remove" value="Delete" name="confirm_delete_bc">
                                <input type="submit" class="submit submit--dark" value="Cancel" name="back_to_book">
                            </div>
                        </form>
                <?php }else{ ?>
                <h1 class="header--big text--unbold text--italize text--capitalize"><?php echo $bookcase_name?></h1>
                <?php echo isset($success) ? "<span class='success-message'>" . $success . "</span>" : ""; ?>
                <?php 
                    $user_id = $_SESSION['user'][0];
                    $query = "SELECT * FROM shelf JOIN bookcase ON shelf.bookcase_id = bookcase.bookcase_id WHERE bookcase.user_id = '$user_id' AND bookcase.bookcase_id = '$bookcase'";
                    $result = mysqli_query($db_connection, $query);
                    if(mysqli_num_rows($result) == 0){ ?>
                        <h2>This bookcase does not have any shelves yet.</h2>
                    <?php }else{ ?>
                        <form action="book.php" method="post" class="form-container bookcase-form-container">
                            <select class="text--capitalize" name="shelf" class="select">
                                <?php while($row = mysqli_fetch_array($result)){?>
                                    <option value="<?php echo $row[0]; ?>"><?php echo $row[1]; ?></option>
                                <?php } ?>
                            </select>
                            <div class="double-button-container">
                                <input type="submit" class="submit submit--dark" value="Proceed" name="proceed">
                                <input type="submit" class="submit submit--remove" value="Remove" name="remove">
                            </div>
                        </form>
                    <?php } ?>
                <form method="post" class="form-container bookcase-add-container">
                    <label for="shelf_name" class="form__label bookcase-form__label">Add Shelf:</label>
                    <?php echo isset($shelf_error) ? "<span class='error-message'>" . $shelf_error . "</span>" : "" ?>
                    <input type="text" class="form__input bookcase-form__input" name="shelf_name" placeholder="Enter text here">
                    <input type="hidden" name="bookcase_id" value="<?php echo $bookcase; ?>">
                    <input type="submit" class="submit submit--dark submit--small" value="Add" name="add">
                </form>
                <?php } ?>
            </div>
            <div class="right-shelf background-image"></div>
        </main>
        
      
    </body>
</html>