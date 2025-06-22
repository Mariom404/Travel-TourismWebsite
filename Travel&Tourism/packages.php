<?php  
    //start the session
    session_start();
    //include the database named db
    include 'db.php';
 
?>

<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <!-- Link to bootstrap -->
        <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
        <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous"></script>
        <link rel="stylesheet" href="styles.css"> <!-- Link to your CSS file -->
        <title>Tour Packages</title>

    </head>
    <body>
            <!-- Navbar Section_____________________________________________________________________________________ -->
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
                <a class="flex-sm-fill text-sm-center nav-link active" aria-current="page" href="packages.php">Packages</a>
                <a class="flex-sm-fill text-sm-center nav-link" href="feedback.php">Feedback</a>
                <!-- if the user is logged in -->
                <?php if (isset($_SESSION['user_id'])): ?>
                    <!-- display the link to the user's personal account using icon from bootstrap-->
                    <a class="flex-sm-fill text-sm-center nav-link" href="account.php">
                       <!--using profile icon from bootstrap-->
                       <svg xmlns="http://www.w3.org/2000/svg" width="32" height="32" fill="currentColor" class="bi bi-person-circle" viewBox="0 0 16 16">
                       <path d="M11 6a3 3 0 1 1-6 0 3 3 0 0 1 6 0"/>
                       <path fill-rule="evenodd" d="M0 8a8 8 0 1 1 16 0A8 8 0 0 1 0 8m8-7a7 7 0 0 0-5.468 11.37C3.242 11.226 4.805 10 8 10s4.757 1.225 5.468 2.37A7 7 0 0 0 8 1"/>
                       </svg>Account
                    </a>
                <!-- if the user is not logged in-->
                <?php else: ?>
                    <!-- display the link to login or register in the nav bar-->
                    <a class="flex-sm-fill text-sm-center nav-link" href="login.php">Login</a>
                    <a class="flex-sm-fill text-sm-center nav-link" href="register.php">Register</a>
                <?php endif; ?>
            </nav>

            <!-- Packages Section -->
            <!-- container for the packages -->
            <div class="container">
                <!--class from bootstrap to adjust horizontal alignment-->
                <div class="row">
                <?php
                    // Fetching all the packages from the database____________________________________________________
                    $query = "SELECT * FROM tour_packages";
                    //store the results in $result
                    $result = $conn->query($query);
                    
                    //if the output results are more than 0
                    if ($result->num_rows > 0) {
                        //as long as there are rows in $row
                        while ($row = $result->fetch_assoc()) {
                ?>
                        <!-- HTML Card for Each Package -->
                        <!--class is from Bootstrap to handle grid columns-->
                        <div class="col-sm-6 mb-3 mb-sm-0">
                        <!-- class to display image from database -->
                            <div class="card" style="width: 18rem;">
                                <img src="<?php echo $row['image_url']; ?>" class="card-img-top" alt="<?php echo $row['package_name']; ?>">
                                <!-- display package name from database and ckeck now putton -->
                                <div class="card-body">
                                    <!--htmlspecialchars() converts special characters into their safe HTML equivalents-->
                                    <h5 class="card-title"><?php echo nl2br(htmlspecialchars($row['package_name'])); ?></h5>
                                    <a href="trip_details.php?package_id=<?php echo $row['package_id']; ?>" class="btn btn-primary">Check now</a>
                                </div>
                            </div>
                        </div>

                <?php
                    //else print this
                        }
                    } else {
                        echo "<p class='no-packages'>No tour packages found.</p>";
                    }
                ?>
                </div>
            </div>

    </body>
</html>
