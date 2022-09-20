<?php 
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
            <?php include("nav.php"); ?>
        </header>

        <main class="mobile-container search-container">
            <h1 class="header--big text--unbold text--italize">Search</h1>
            <?php 
                session_start();
                if(isset($_SESSION['user'])){

                    $user_id = $_SESSION['user'][0]; 
                }else{
                    header("Location: login.php");
                }
            ?>
            <form method="post" class="form-container search-form-container">
                <select name="search" class="search-form__dropdown">
                    <option value="isbn">ISBN</option>
                    <option value="title">Book Title</option>
                    <option value="author">Author</option>
                </select>
                <span><?php echo isset($keyword_error) ? $keyword_error : ""?></span>
                <input type="text" class="form__input search-form__textfield" placeholder="Insert text here" name="keyword">
                <input type="submit" class="submit submit--dark" value="Submit" name="submit">
                <input type="hidden" name="user-id" value='<?php echo $user_id; ?>'>
            </form>
            <?php if(isset($result) && mysqli_num_rows($result) > 0){ ?>
                <?php
                    switch($choice){
                        case "isbn":
                            echo "<h1 class='header--big text--unbold text--italize'>ISBN Matches</h1>";
                            echo "<div class='mobile-container search-result'>";
                            while($row = mysqli_fetch_array($result)){
                                echo "<a href=display.php?id=" .$row['isbn'] .">".$row['isbn']."</a>";
                            }                            
                            echo "</div>";
                            break;
                        case "title":
                            echo "<h1 class='header--big text--unbold text--italize'>Book Title Matches</h1>";
                            echo "<div class='mobile-container search-result'>";
                            while($row = mysqli_fetch_array($result)){
                                echo "<a href=display.php?id=" .$row['isbn'] .">".$row['title']."</a>";
                            }
                            echo "</div>";
                            break;
                        case "author":
                            echo "<h1 class='header--big text--unbold text--italize'>Author Matches</h1>";
                            echo "<div class='mobile-container search-result'>";
                            while($row = mysqli_fetch_array($result)){
                                echo "<a href=display.php?id=" .$row['isbn'] .">".$row['first_name']." " . $row['last_name'] ." - ". $row['title'] . "</a>";
                            }
                            echo "</div>";
                            break;
                        default:
                            break;
                    }    
                    
                ?>
                <?php }else{
                    if(isset($keyword)){
                        switch($choice){
                            case "isbn":
                                echo "<h2>No book with the ISBN: " . $keyword . "</h2>";
                                break;
                            case "title":
                                echo "<h2>No book with the title: " . $keyword . "</h2>";
                                break;
                            case "author":
                                echo "<h2>No book with the author:  " . $keyword . "</h2>";
                                break;
                            default:
                                break;                            
                        }
                    }
                }
            
            ?>
                <?php
                    $get_heap = "SELECT DISTINCT(heap.user_id), book.isbn, book.title, author.first_name, author.last_name, book.publish_date, genre.name from heap JOIN book on heap.user_id = book.user_id AND heap.isbn=book.isbn JOIN genre on book.genre_id=genre.genre_id JOIN author on author.author_id=book.author_id WHERE heap.user_id='$user_id';";
                    $get_heap_result = mysqli_query($db_connection, $get_heap);
                    if(mysqli_num_rows($get_heap_result) == 0){
                        echo "No Books in heap.";
                    }else{?>
                    <h1>Books in Heap</h1>
                    <table>
                        <tr>
                            <th>ISBN</th>
                            <th>Title</th>
                            <th>Author</th>
                        </tr>
                    <?php
                        while($row = mysqli_fetch_array($get_heap_result)){?>
                            <tr>
                                
                                    <td><a href="display.php?id=<?php echo $row['isbn']?>"><?php echo $row['isbn'] ;?></a></td>
                                    <td><?php echo $row['title'] ;?></td>
                                    <td><?php echo $row['first_name'] . " " . $row['last_name'];?></td>
                                
                            </tr>
                        <?php } ?>
                        </table>
                   <?php } ?>
                

    
        </main>

    </body>
</html>