<?php
    //start the session
    session_start();
    require_once 'db.php'; //includes the database named db
    //retrieve user datails to dispaly________________________________________________________________
    $user_id = $_SESSION['user_id'];
    //select the username and email that belongs to that user_id
    //parameterized queries  to avoid sql injection
    $query = $conn->prepare("SELECT username, email FROM users WHERE user_id = ?");
    $query->bind_param("i", $user_id);
    $query->execute();
    $result = $query->get_result();
    $user = $result->fetch_assoc();
    // Update user details__________________________________________________________________________
    $update_message = '';
    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        //store input and trim any spaces to avoid errors
        $new_username = trim($_POST['username']);
        $new_email = trim($_POST['email']);
        //if the fields are not empty
        if (!empty($new_username) && !empty($new_email)) {
            //check if email already exists______________________________________________
            //parameterized queries  to avoid sql injection
            $query = "SELECT * FROM users WHERE email = ?";
            $stmt = $conn->prepare($query);
            $stmt->bind_param("s", $new_email);
            $stmt->execute();
            $result = $stmt->get_result();
            //if email exists
            if ($result->num_rows > 0) {
                // Email already exists (2 accounts with the same email cannot exist)
                echo "<p style='color: red;'>Error: This email is already registered. Please use another email.</p>";}
            else{
                 //query to update username and email that belongs to the user_id, again parameterized queries to avoid SQL injection
                 $update_query = $conn->prepare("UPDATE users SET username = ?, email = ? WHERE user_id = ?");
                 $update_query->bind_param("ssi", $new_username, $new_email, $user_id);
                 //if the execution of the query is successful
                 if ($update_query->execute()) {
                     //print this message
                     $update_message = "Details updated successfully!";
                     // update username and email with the new ones
                     $user['username'] = $new_username;
                     $user['email'] = $new_email;
                 } }
        } else {
            //if the update failed to execute
            $update_message = "Error updating details. Please try again.";
        }      }    
?>
<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <!-- Link to external CSS file -->
        <link rel="stylesheet" href="styles.css">
        <!-- Link to bootstrap -->
        <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
        <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous"></script>
        <title>Account Page</title>
    </head>
    <body>
        <!-- Navigation bar_________________________________________________________________________________________________________ -->
        <!--nav class from bootsrap -->
        <nav class="nav nav-pills flex-column flex-sm-row">
            <!-- class  to style the logo -->
            <div class="logo">
                <!-- using icon from bootstrap-->
               <svg xmlns="http://www.w3.org/2000/svg" width="29" height="29" fill="currentColor" class="bi bi-airplane-engines" viewBox="0 0 16 16">
               <path d="M8 0c-.787 0-1.292.592-1.572 1.151A4.35 4.35 0 0 0 6 3v3.691l-2 1V7.5a.5.5 0 0 0-.5-.5h-1a.5.5 0 0 0-.5.5v1.191l-1.17.585A1.5 1.5 0 0 0 0 10.618V12a.5.5 0 0 0 .582.493l1.631-.272.313.937a.5.5 0 0 0 .948 0l.405-1.214 2.21-.369.375 2.253-1.318 1.318A.5.5 0 0 0 5.5 16h5a.5.5 0 0 0 .354-.854l-1.318-1.318.375-2.253 2.21.369.405 1.214a.5.5 0 0 0 .948 0l.313-.937 1.63.272A.5.5 0 0 0 16 12v-1.382a1.5 1.5 0 0 0-.83-1.342L14 8.691V7.5a.5.5 0 0 0-.5-.5h-1a.5.5 0 0 0-.5.5v.191l-2-1V3c0-.568-.14-1.271-.428-1.849C9.292.591 8.787 0 8 0M7 3c0-.432.11-.979.322-1.401C7.542 1.159 7.787 1 8 1s.458.158.678.599C8.889 2.02 9 2.569 9 3v4a.5.5 0 0 0 .276.447l5.448 2.724a.5.5 0 0 1 .276.447v.792l-5.418-.903a.5.5 0 0 0-.575.41l-.5 3a.5.5 0 0 0 .14.437l.646.646H6.707l.647-.646a.5.5 0 0 0 .14-.436l-.5-3a.5.5 0 0 0-.576-.411L1 11.41v-.792a.5.5 0 0 1 .276-.447l5.448-2.724A.5.5 0 0 0 7 7z"/>
               </svg>
               TravelMate
            </div>
              <!-- the links on the nav bar to navigate between the pages -->
            <a class="flex-sm-fill text-sm-center nav-link" href="index.php">Home</a>
            <a class="flex-sm-fill text-sm-center nav-link" href="packages.php">Packages</a>
            <a class="flex-sm-fill text-sm-center nav-link" href="feedback.php">Feedback</a>
            <a class="flex-sm-fill text-sm-center nav-link active" aria-current="page" href="account.php">
                <!--using profile icon from bootstrap-->
                <svg xmlns="http://www.w3.org/2000/svg" width="32" height="32" fill="currentColor" class="bi bi-person-circle" viewBox="0 0 16 16">
                <path d="M11 6a3 3 0 1 1-6 0 3 3 0 0 1 6 0"/>
                <path fill-rule="evenodd" d="M0 8a8 8 0 1 1 16 0A8 8 0 0 1 0 8m8-7a7 7 0 0 0-5.468 11.37C3.242 11.226 4.805 10 8 10s4.757 1.225 5.468 2.37A7 7 0 0 0 8 1"/>
                </svg>Account
            </a>
        </nav>
        <!-- container for the account details____________________________________________________________________________ -->
        <div class="container">
            <!--if the user updates their infoirmation execute the code above and print the appropriate message -->
            <?php if ($update_message): ?>
                <p style="color:#28a745;"><?php echo $update_message; ?></p>
            <?php endif; ?>
            <!-- user details that could be updated  -->
            <form method="POST">
                <!-- labeled input feilds to be updated if needed___________________________________________________________________________________________________ -->
                <!--htmlspecialchars() converts special characters into their safe HTML equivalents-->
                <label for="username">Username:</label>
                <input type="text" id="username" name="username" value="<?php echo htmlspecialchars($user['username']); ?>" required>
                <label for="email">Email:</label>
                <input type="email" id="email" name="email" value="<?php echo htmlspecialchars($user['email']); ?>" required>
                <!-- the submit button -->
                <button type="submit">Update Details</button>
                <!-- links to logout or delete the account -->
                <a href="logout.php">Logout</a>
                <a href="delete_account.php">Delete Account</a>
            </form>
        </div>
    </body>
</html>
