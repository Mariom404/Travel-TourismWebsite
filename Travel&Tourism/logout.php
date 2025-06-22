<?php
    session_start();

    // Check if the user has confirmed the logout action
    if (isset($_POST['confirm_logout'])) {
        // Destroy the session to log the user out___________________________
        session_unset();
        session_destroy();
        // Redirect to the home page 
        header("Location: index.php");
        exit();
    }

    // If the user cancels, redirect back to the account page
    if (isset($_POST['cancel_logout'])) {
        header("Location: account.php");
        exit();
    }
?>
<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>Confirm Logout</title>
        <!-- Link to external CSS file -->
        <link rel="stylesheet" href="styles.css">
    </head>
    <body>
        <header>
            <h1>Logout</h1>
        </header>
        <!-- container for the text and buttons__________________________________________________ -->
        <div class="container">
            <h1 style="color: black;">Are you sure you want to log out?</h1>
            <!-- method=post to recieve the data to the server -->
            <form method="post" action="logout.php">
                <!--choice to logout or cancel-->
                <button type="submit" name="confirm_logout" class="btn">Yes, Log Out</button>
                <button type="submit" name="cancel_logout" class="btn btn-cancel">Cancel</button>
            </form>
        </div>
    </body>
</html>
