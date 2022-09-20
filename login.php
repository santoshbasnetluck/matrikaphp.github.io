<?php 
 
    
	if(isset($_SESSION['attempt_again'])){
		$now = time();
		if($now >= $_SESSION['attempt_again']){
			unset($_SESSION['attempt']);
			unset($_SESSION['attempt_again']);
		}
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
        <header class="login-header"></header>

        <main class="main-container">
            <div class="left-login-container background-image"></div>
            <div class="mobile-container right-login-container">
                <h1 class="right-login__header header--big text--unbold">Login</h1>
                <?php echo isset($_GET['success']) ? "<p class='success-message'> Registration successful!</p>": ""?>
                <?php echo isset($_SESSION['error']) ? "<span class='error-message'>" . $_SESSION['error'] . "</span>": "" ?>
                <form method="POST" class="form-container login-form-container--gap">
                    <?php echo isset($login_error) ? "<span class='error-message'>" . $login_error . "</span>": ""?>
                     <?php echo isset($username_error) ? "<span class='error-message'>" . $username_error . "</span>": ""?>
                    <input type="text" class="form__input login-form__input" name="username" placeholder="Username" value="<?php echo isset($_POST['username']) ? $_POST['username'] : '' ?>">
                    
                     <?php echo isset($password_error) ? "<span class='error-message'>" . $password_error . "</span>": ""?>
                    <input type="password" class="form__input login-form__input" name="password" placeholder="Password" required>
                    <input type="submit" class="submit submit--light submit--small" value="Sign In" name="submit" required>
                </form>
                <p class="right-login__para">Don't have an account? <a href="register.php" class="right-login__link text--bold">Sign up now</a></p>
            </div>
        </main>

     </body>
</html>