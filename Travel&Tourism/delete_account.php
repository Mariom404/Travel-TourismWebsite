<?php
    //strat session
    session_start();
    //include database named db
    include 'db.php';
    // If the user cancels, redirect back to the account page
    if (isset($_POST['cancel_delete'])) {
        header("Location: account.php");
        exit();
    }
    //if the user confirms the deletion___________________________________________________
    if (isset($_POST['confirm_delete'])) {
        //checks if request method of $_SERVER is POST
        if ($_SERVER["REQUEST_METHOD"] == "POST") {
            //get the $user_id is from the for the one the session is established with
            $user_id = $_SESSION['user_id'];
            // Delete user's feedback and bookings
            $conn->query("DELETE FROM feedback WHERE user_id = $user_id");
            $conn->query("DELETE FROM bookings WHERE user_id = $user_id");
            // Delete the user account whose user_id matches $user_id
            $query = "DELETE FROM users WHERE user_id = ?";
            $stmt = $conn->prepare($query);
            $stmt->bind_param("i", $user_id);
            //if executed correctly
            if ($stmt->execute()) {
                session_destroy(); // Log the user out
                header("Location: index.php");//redirect to home page
                exit;
            } else {
                echo "Error: " . $stmt->error;//else error
            }}
        $stmt->close();}
?>
<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>Delete Account</title>
        <!-- Link to external CSS file -->
        <link rel="stylesheet" href="styles.css">
    </head>
    <body>
        <header>
            <h1>Delete Account</h1>
        </header>
            <!-- container for the text and buttons__________________________________________________ -->
            <div class="container">
            <!-- method=post to recieve the data to the server -->
            <form method="POST" action="delete_account.php">
                <p style="color: black;">Are you sure you want to delete your account? This action cannot be undone.</p>
                <!--choice to logout or cancel-->
                <button type="submit" name="confirm_delete" class="btn">Yes, Delete</button>
                <button type="submit" name="cancel_delete" class="btn btn-cancel">Cancel</button>
            </form>
        </div>
    </body>
</html>
