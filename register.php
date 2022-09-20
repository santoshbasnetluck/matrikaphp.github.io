<?php 
    session_start();
    if(isset($_SESSION['user'])){
        header("Location: index.php");
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
    <body class="background-color flex-wrapper">
        <header class="register-header"></header>

        <main class="main-container">
            <div class="mobile-container left-register-container">
                <h1 class="left-register__header header--big text--unbold">Register</h1>
                <form method="post" class="form-container">
                    <label for="firstname" class="form__label register-form__label">First Name</label>
                    <?php echo isset($fn_error) ? "<span class='error-message'>" . $fn_error . "</span>": ""?>
                     <input type="text" class="form__input register-form__input" name="firstname" placeholder="Enter text here" value="<?php echo isset($_POST['firstname']) ? $_POST['firstname'] : '' ?>" required>

                    <label for="lastname" class="form__label register-form__label">Last Name</label>
                    <?php echo isset($ln_error) ? "<span class='error-message'>" . $ln_error . "</span>": ""?>
                     <input type="text" class="form__input register-form__input" name="lastname" placeholder="Enter text here" value="<?php echo isset($_POST['lastname']) ? $_POST['lastname'] : '' ?>" required>

                     <label for="username" class="form__label register-form__label">Username</label>
                    <?php echo isset($username_error) ? "<span class='error-message'>" . $username_error . "</span>": ""?>
                     <input type="text" class="form__input register-form__input" name="username" placeholder="Enter text here" value="<?php echo isset($_POST['username']) ? $_POST['username'] : '' ?>" required>

                     <label for="password" class="form__label register-form__label">Password</label>
                    <?php echo isset($password_error) ? "<span class='error-message'>" . $password_error . "</span>": ""?>
                     <input type="password" class="form__input register-form__input" name="password" placeholder="Enter text here" value="<?php echo isset($_POST['password']) ? $_POST['password'] : '' ?>" required>
                    

                     <label for="email" class="form__label register-form__label">Email</label>
                    <?php echo isset($email_error) ? "<span class='error-message'>" . $email_error . "</span>": ""?>
                     <input type="text" class="form__input register-form__input" name="email" placeholder="Enter text here" value="<?php echo isset($_POST['email']) ? $_POST['email'] : '' ?>" required>
                    
                    <input type="submit" class="submit submit--light submit--small register-form__submit" value="Submit" name="submit">
                </form>
            </div>
                    
            <div class="right-register-container background-image"></div>
        </main>

    
    </body>
</html>