<?php
    session_start();
    include 'db.php';  // include Database names db
    // Check if user is logged in
    if (!isset($_SESSION['user_id'])) {
        //if not redirect them to login form
        header("Location: login.php");
        exit;}
    // Check if package_id is passed in the URL
    if (!isset($_GET['package_id'])) {
        //die() to terminate script execution immediately
        die("Package ID is missing.");}
    //get package_id and store it in $package_id\
    $package_id = $_GET['package_id'];
    // Fetch all package details_______________________________________________________
    //parameterized queries  to avoid sql injection
    $query = "SELECT * FROM tour_packages WHERE package_id = ?";
    $stmt = $conn->prepare($query);
    $stmt->bind_param("i", $package_id);
    $stmt->execute();
    $result = $stmt->get_result();
    //if there are no packages
    if ($result->num_rows == 0) {
        //die() to terminate script execution immediately
        die("Package not found.");}
    $package = $result->fetch_assoc();
    // Handle review submission________________________________________________________________________
    //ensure The server request method is POST
    if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['review'])) {
            //get $user_id from the user_id the session is established with
            $user_id = $_SESSION['user_id'];
            //get the review from user and store it in $reveiw
            $review = $_POST['review'];
            // Check if the user has booked the package_________________________________________________________
            //select from bookings the package that has been by this user
            //parameterized queries  to avoid sql injection
            $booking_check_query = "SELECT * FROM bookings WHERE user_id = ? AND package_id = ?";
            $stmt = $conn->prepare($booking_check_query);
            $stmt->bind_param("ii", $user_id, $package_id);
            $stmt->execute();
            $booking_result = $stmt->get_result();
            //if statement turns out with a result
            if ($booking_result->num_rows > 0) {
            //means User has booked the package, allow them to review            
            //insert into table reveiws the user_id, package_id, the reveiw
            //parameterized queries  to avoid sql injection
            $insert_review_query = "INSERT INTO reviews (user_id, package_id, review) VALUES (?, ?, ?)";
            $stmt = $conn->prepare($insert_review_query);
            $stmt->bind_param("iis", $user_id, $package_id, $review);
            $stmt->execute();
            // Refresh the page
            header("Location: trip_details.php?package_id=" . $package_id); 
        //else the user didnt book the package
        }else{
            echo "<p style='color: red; font-size: 2rem;'>Error: you must book package first.</p>";}}
    // Fetch existing reviews_______________________________________________________________________________________________________________________________
    //parameterized queries  to avoid sql injection
    //fetch reveiw from reveiws table and the username from users table that belong to the package_id
    $reviews_query = "SELECT reviews.review, users.username FROM reviews INNER JOIN users ON reviews.user_id = users.user_id WHERE package_id = ?";
    $stmt = $conn->prepare($reviews_query);
    $stmt->bind_param("i", $package_id);
    $stmt->execute();
    $reviews_result = $stmt->get_result();
?>
<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>Trip Details</title>
        <!-- Link to external CSS file -->
        <link rel="stylesheet" href="styles.css">
        <!-- Link to bootstrap -->
        <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" crossorigin="anonymous">
        <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" crossorigin="anonymous"></script>
    </head>
    <body>
        <!-- Navbar Section____________________________________________________________________________________________________________________ -->
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
                <a class="flex-sm-fill text-sm-center nav-link" href="account.php">
                    <!--using profile icon from bootstrap-->
                    <svg xmlns="http://www.w3.org/2000/svg" width="32" height="32" fill="currentColor" class="bi bi-person-circle" viewBox="0 0 16 16">
                    <path d="M11 6a3 3 0 1 1-6 0 3 3 0 0 1 6 0"/>
                    <path fill-rule="evenodd" d="M0 8a8 8 0 1 1 16 0A8 8 0 0 1 0 8m8-7a7 7 0 0 0-5.468 11.37C3.242 11.226 4.805 10 8 10s4.757 1.225 5.468 2.37A7 7 0 0 0 8 1"/>
                    </svg>Account
                </a>
        </nav>
        <div class="container">
            <!-- Trip Details -->
            <!--class from bootstrap to adjust horizontal alignment-->
            <div class="row">
                <!--class is from Bootstrap to handle grid columns-->
                <div class="col-md-8">
                    <!--htmlspecialchars() converts special characters into their safe HTML equivalents-->
                    <!--display the package name from the database-->
                    <h2><?php echo htmlspecialchars($package['package_name']); ?></h2>
                    <!--display image of the package from the database-->
                    <img src="<?php echo $package['image_url']; ?>" class="img-fluid" alt="<?php echo htmlspecialchars($package['package_name']); ?>">
                    <!--nl2br() to convert newline characters (\n) in a string into HTML <br>-->
                    <!--display the description of the package from the database under the image-->
                    <p><?php echo nl2br(htmlspecialchars($package['description'])); ?></p>
                    <!--list to display the price and dates using class from bootstrap_____________________________________________________________________________________________________________________-->
                    <ul class="list-group">
                        <li class="list-group-item">Price: $<?php echo number_format($package['price'], 2); ?></li>
                        <li class="list-group-item">Start Date: <?php echo $package['start_date']; ?></li>
                        <li class="list-group-item">End Date: <?php echo $package['end_date']; ?></li>
                    </ul>
                    <!-- link to Booking Form using bootsrap class -->
                    <a href="book.php?package_id=<?php echo $package_id; ?>" class="btn btn-primary mt-3">Book</a>
                </div>
                <!--reveiws section-->
                <!--class is from Bootstrap to handle grid columns-->
                <div class="col-md-4">
                    <h3>Reviews</h3>
                    <!--checks if there are reveiws-->
                    <?php if ($reviews_result->num_rows > 0) {
                            //if there are reveiws continue in the loop until there isnt anymore
                            while ($review = $reviews_result->fetch_assoc()) {
                                //classes from bootstrap to handle styling and grid columns
                                //displays the username and reveiew under it
                                echo '<div class="card mb-3">
                                        <div class="card-body">
                                            <strong>' . htmlspecialchars($review['username']) . ':</strong>
                                            <p>' . nl2br(htmlspecialchars($review['review'])) . '</p>
                                        </div>
                                    </div>';}
                        //if there arent any reveiws
                        } else {
                            echo "<p>No reviews yet. Be the first to write one!</p>";}
                      ?>
                        <!-- Review Form_____________________________________________________________________________________________________ -->
                        <form method="POST">
                            <div class="mb-3">
                                <label for="review" class="form-label">Write a Review</label>
                                <!--input area for the review-->
                                <textarea class="form-control" id="review" name="review" rows="4"></textarea>
                            </div>
                            <!--submit button for the review-->
                            <button type="submit" class="btn btn-success">Submit Review</button>
                        </form>
                </div>
            </div>
        </div>

    </body>
</html>
