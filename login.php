<?php


session_start();

//Added for the Admin Feature in order to not output an error message on default
$_SESSION['error_message_admin'] = "";

// Function to validate the username against the users.txt file
function validateUsername($username)
{

    // sanitizing the username for security
    if( !preg_match('/^[\w_\-]+$/', $username) ){
        $_SESSION['error_message'] = 'Invalid username. Please try again.';
        header('Location: login.php');
        exit;
    }

    // Read the file contents and check if the username exists
    $username = trim($username);  // Trim the input
    $usersFile = '/home/ec2-user/users.txt';
    $usernames = file($usersFile, FILE_IGNORE_NEW_LINES);
    foreach ($usernames as $name) {
        if (!empty($name) && $name === $username) {  // Check if name is not empty
            return true;
        }
    }

    return false;
}

// phpself will check this stuff again to validate the username
if (isset($_SERVER['REQUEST_METHOD']) && $_SERVER['REQUEST_METHOD'] === 'POST') {
    // Retrieve the entered username from the form
    $username = $_POST['username']; // 

    // Validate the username
    if (validateUsername($username)) {
        // Username is valid, create a session and redirect to the dashboard
        $_SESSION['username'] = $username;
        header('Location: dashboard.php');
    
        exit;
    } else {
        // Username is not valid, store the error message in a session variable
        $_SESSION['error_message'] = 'Invalid username. Please try again.';
        header('Location: login.php');
        exit;
    }
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8"> 
    <title>Login</title>
    <link rel="stylesheet" href="serverStyle.css">
</head>

<body>
    <div class="container">
        <h2>Login</h2>

        <?php if (isset($_SESSION['error_message'])): ?>
            <div class="error-message">
                <?php echo $_SESSION['error_message']; ?>
            </div>
        <?php endif; ?>

     
        <!-- Phpself is a way to redirect a site back to itself, it is a good way to run the above username verification code when we gain a username input -->
       <!-- link:https://html.form.guide/php-form/php-form-action-self/  where php_self explanation was from for it's usage-->
        <form class="login-form" action="<?php echo $_SERVER['PHP_SELF']; ?>" method="POST">
            <label for="username">Username:</label>
            <input type="text" name="username" id="username" placeholder="Enter your username" required autocomplete="off"><br><br>
            <input type="submit" value="Login">
        </form>
      
        <?php unset($_SESSION['error_message']); ?>
    </div>
</body>

</html>