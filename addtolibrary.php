<?php
    session_start();
    if(isset($_SESSION['user'])){
         $user_id = $_SESSION['user'][0];
     }else{
        header("Location: login.php");
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
        <header><?php include 'nav.php' ?></header>

        <main class="main-container">
            <div class="mobile-container addtolibrary-container">
                <h1 class="header--big text--italize text--unbold">Add Book to Heap</h1>
                <form method="post" class="form-container">
                    <div class="form-container--rows">
                         <div class="mobile-container">
                            <label for="isbn" class="form__label bookcase-form__label">ISBN</label>
                            <?php echo isset($isbn_error) ? "<span class='error-message'>" . $isbn_error . "</span>": ""?>
                            <input type="number" class="form__input bookcase-form__input" name="isbn" placeholder="Enter text here" value="<?php echo isset($isbn) ? $isbn : ""?>">
                        </div>

                         <div class="mobile-container">
                            <label for="list-price" class="form__label bookcase-form__label">List Price</label>
                            <?php echo isset($lp_error) ? "<span class='error-message'>" . $lp_error . "</span>": ""?>
                            <input type="number" step="any" class="form__input bookcase-form__input" name="list-price" placeholder="Enter text here" value="<?php echo isset($list_price) ? $list_price : ""?>">
                        </div>
                    </div>

                     <div class="mobile-container">
                        <label for="book-title" class="form__label bookcase-form__label">Title</label>
                        <?php echo isset($title_error) ? "<span class='error-message'>" . $title_error . "</span>": ""?>
                        <input type="text" class="form__input bookcase-form__input" name="book-title" placeholder="Enter text here" value="<?php echo isset($title) ? $title : ""?>">
                    </div>

                    <div class="form-container--rows">
                         <div class="mobile-container">
                            <label for="author-firstname" class="form__label bookcase-form__label">Author's First Name</label>
                            <?php echo isset($fn_error) ? "<span class='error-message'>" . $fn_error . "</span>": ""?>
                            <input type="text" class="form__input bookcase-form__input" name="author-firstname" placeholder="Enter text here" value="<?php echo isset($first_name) ? $first_name : ""?>">
                        </div>

                         <div class="mobile-container">
                            <label for="author-lastname" class="form__label bookcase-form__label">Author's Last Name</label>
                            <?php echo isset($ln_error) ? "<span class='error-message'>" . $ln_error . "</span>": ""?>
                            <input name="author-lastname" class="form__input bookcase-form__input" name="author-lastname" placeholder="Enter text here" value="<?php echo isset($last_name) ? $last_name : ""?>">
                        </div>
                    </div>

                    <div class="form-container--rows">
                         <div class="mobile-container">
                            <label for="publish-date" class="form__label bookcase-form__label">Publish Date</label>
                            <?php echo isset($pd_error) ? "<span class='error-message'>" . $pd_error . "</span>": ""?>
                            <input type="date" class="form__input bookcase-form__input" name="publish-date" value="<?php echo isset($publish_date) ? $publish_date : ""?>" max="<?php echo date('Y-m-d'); ?>">
                        </div>

                         <div class="mobile-container">
                            <label for="genre" class="form__label bookcase-form__label">Genre</label>
                             <?php echo isset($genre_error) ? "<span class='error-message'>" . $genre_error . "</span>": ""?>                          
                            <select class="form__input bookcase-form__input" name="genre">
                                <option disabled selected value>Select genre</option>
                                <?php
                                    include("db_connect.php");
                                    $query = "SELECT genre_id, name FROM genre";
                                    $result = mysqli_query($db_connection, $query);
                                    while($row = mysqli_fetch_array($result)){?>
                                        <option value="<?php echo $row['genre_id'];?>" <?php echo isset($_POST['genre']) && $_POST['genre'] == $row['genre_id'] ? 'selected' : '';?>><?php echo $row['name'];?></option>
                                <?php }?>
                            </select>
                        </div>
                    </div>                  
                    <input type="submit" class="submit submit--dark submit--small" value="Submit" name="submit">
                </form>
            </div>
        </main>

    </body>
</html>