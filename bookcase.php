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
        <title>Select Bookcase</title>
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
            <div class="left-bookcase background-image"></div>

            <div class="mobile-container right-bookcase">
                <h1 class="header--big text--unbold text--italize">Bookcases</h1>
                <?php 
                    include("db_connect.php");
                     $user_id = $_SESSION['user'][0];
                    $query = "SELECT * FROM bookcase where user_id = $user_id";

                    $result = mysqli_query($db_connection, $query);

                    if(mysqli_num_rows($result) == 0){ ?>
                        <h2> You don't have any bookcases. </h2>
                    <?php }else{ ?>
                        <?php echo isset($success) ? "<span class='success-message'>".$success."</span>" : "" ?>
                        <form action="shelf.php" method="post" class="form-container bookcase-form-container">
                            <select name="bookcase" class="select">
                                <?php while($row = mysqli_fetch_array($result)){ ?>             
                                <option class="text--capitalize" value='<?php echo $row['bookcase_id']; ?>'> <?php echo $row['bookcase_name']; ?> </option>
                            <?php } ?>
                            </select>
                            <div class="double-button-container">
                                <input type="submit" class="submit submit--dark" value="Proceed" name="proceed">
                                <input type="submit" class="submit submit--remove" value="Remove" name="remove">
                            </div>
                        </form>
                    <?php } ?>

                <form method="post" class="form-container bookcase-add-container">
                    <label for="bookcase_name" class="form__label bookcase-form__label">Add Bookcase:</label>
                    <?php echo isset($name_error) ? "<span class='error-message'>" . $name_error . "</span>": ""?>
                    <input type="text" class="form__input bookcase-form__input" name="bookcase_name" placeholder="Enter text here" value='<?php echo isset($name) ? $name : "" ?>'>
                    <input type="submit" class="submit submit--dark submit--small" value="Add" name="add">
                </form>
            </div>
        </main>
        
      
    </body>
</html>