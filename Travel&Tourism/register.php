<?php
    //includes the database named 'db'
    include 'db.php';

    //checks if request method of $_SERVER is POST
    if ($_SERVER["REQUEST_METHOD"] == "POST") {
            //recieve user input and store it
            $username = trim($_POST['username']);
            $email = trim($_POST['email']);
            $password = trim($_POST['password']);
            $confirm_password = trim($_POST['confirm_password']);
            //check if email is already registered________________________________________________________________________________
            //parameterized queries to prevent SQL injection
            $query = "SELECT * FROM users WHERE email = ?";
            $stmt = $conn->prepare($query);
            $stmt->bind_param("s", $email);
            $stmt->execute();
            $result = $stmt->get_result();

            //if there are any rows that in result that mean the email exists
            if ($result->num_rows > 0) {
                // Email already exists (2 accounts with the same email cannot exist)
                echo "<p style='color: red;'>Error: This email is already registered. Please use another email.</p>";
            } else {
                //else check if password and confirm password are the same______________________________________________________
                if ($password !== $confirm_password) {
                    echo "<p style='color: red;'>Passwords do not match!</p>";
                    exit;
            }
            //salt the password then has it
            $salt = bin2hex(random_bytes(16));
            $hashed_password = password_hash($salt . $password, PASSWORD_BCRYPT); // Hash the password

            //insert the data into the datbase_________________________________________
            $query = "INSERT INTO users (username, email, password, salt) VALUES (?, ?, ?, ?)";
            $stmt = $conn->prepare($query);
            $stmt->bind_param("ssss", $username, $email, $hashed_password, $salt);

            if ($stmt->execute()) {
                echo "Registration successful!";
                //import user to home page
                header("Location: index.php");
                exit;
            } else {
                echo "Error: " . $stmt->error;

            }

            $stmt->close();
            $conn->close();
            }
    }
?>
<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>Register</title>
        <!-- Link to bootstrap -->
        <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
        <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous"></script>
        <!-- Link to external CSS file -->
        <link rel="stylesheet" href="styles.css">
    </head>
    <body>
        <!-- Navigation bar_________________________________________________________________ -->
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
           <a class="flex-sm-fill text-sm-center nav-link active" aria-current="page" href="register.php">register</a>
        </nav>

        <!-- Registration form___________________________________________________________________________ -->
        <!-- container for the form -->
        <div class="container">
            <!-- method=post to access the input feild in the form -->
            <form method="POST" action="register.php">
                <h2>Register</h2>
                <!-- the input feilds -->
                <input type="text" name="username" placeholder="UserName" required>
                <input type="email" name="email" placeholder="Email" required>
                <input type="password" name="password" placeholder="Password" required>
                <input type="password" name="confirm_password" placeholder="Confirm Password" required>
                <!-- the submit button -->
                <button type="submit">Register</button>
                <!-- link to the login form incase the user has an account -->
                <p style="color: black;">Already have an account? <a href="login.php">Login here</a></p>
            </form>
        </div>
    </body>
</html>