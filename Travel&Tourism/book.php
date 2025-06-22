<?php 
    //start a session
    session_start();
    //include database called db
    include 'db.php';
    //check if user is logged in
    if (!isset($_SESSION['user_id'])) {
        //if not redirect to login page
        header("Location: login.php");
        exit;}    
    // Retrieve the package details___________________________________________________________________________
    // Check if package ID is set
    //using GET method to access data from database
    if (isset($_GET['package_id'])) {
        //get package_id from database and store it in $package_id
        $package_id = $_GET['package_id'];
        //get the user_id of the session and store it in $user_id
        $user_id = $_SESSION['user_id'];
        //select tour package with the same id as this one
        //parameterized queries  to avoid sql injection
        $query = "SELECT * FROM tour_packages WHERE package_id = ?";
        $stmt = $conn->prepare($query);
        $stmt->bind_param("i", $package_id);
        $stmt->execute();
        $result = $stmt->get_result();
        $package = $result->fetch_assoc();
        //if there are no results in $package
        if (!$package) {
            //die() to terminate script execution immediately
            //print "Package not found."
            die("Package not found.");}
    //if the package ID is not set
    } else {
        //die() to terminate script execution immediately
        die("Package ID is not set.");
    }
    //booking details submission________________________________________________________________________________
    //request methiod POST to submit data to server
    if ($_SERVER["REQUEST_METHOD"] == "POST") {
        //store number of people and special requests
        $num_people = $_POST['num_people'];
        $special_requests = $_POST['special_requests'] ?? '';
        // Calculate total price
        $total_price = $package['price'] * $num_people;
        // Insert booking details into the database
        //parameterized queries  to avoid sql injection
        $query = "INSERT INTO bookings (user_id, package_id, num_people, special_requests, total_price) VALUES (?, ?, ?, ?, ?)";
        $stmt = $conn->prepare($query);
        $stmt->bind_param("iiisd", $user_id, $package_id, $num_people, $special_requests, $total_price);
        //if statements execute successfully
        if ($stmt->execute()) {
            // Success message
            echo "<h3>Booking successful, keep up with you email for payment and further notice!</h3>";
            echo "<p>Total Price: $" . number_format($total_price, 2) . "</p>";
        //else print error
        } else {
            echo "<h3>Error: " . $stmt->error . "</h3>";
        }

        $stmt->close();
    }
?>

<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>Book Your Trip</title>
        <!-- Link to external CSS file -->
        <link rel="stylesheet" href="styles.css">
        <script>
            // Function to dynamically update total price based on number of guests___________________________________________________________
            function updateTotalPrice() {
                const pricePerPerson = <?php echo $package['price']; ?>; //gets the price per person
                const numPeople = document.getElementById('num_people').value;
                const totalPrice = pricePerPerson * numPeople; // Calculate total price
                //dynamically update the total price
                //modifying the text inside id="total_price" to access that text in real time
                document.getElementById('total_price').innerText = totalPrice.toFixed(2); // Display total price
            }
        </script>
    </head>
    <body>
        <header>
            <h1>TravelMate Booking</h1>
        </header>
        <!--container to hold the booking form-->
        <div class="container">
                    <!--htmlspecialchars() converts special characters into their safe HTML equivalents-->
                    <!--display package name-->
                    <h2>Book Your Trip: <?php echo htmlspecialchars($package['package_name']); ?></h2>
                    <p>Price per person: $<?php echo number_format($package['price'], 2); ?></p>
                    <!--booking form for a specific package-->
                    <form action="book.php?package_id=<?php echo $package_id; ?>" method="POST">

                        <!--oninput event is triggered the moment the value of 'num_people' changes, allowing real-time and dynamic feedback to 'updateTotalPrice'-->
                        <label for="num_people">Number of Guests:</label>
                        <!--input area where u chose the number of guests-->
                        <input type="number" id="num_people" name="num_people" min="1" value="1" required oninput="updateTotalPrice()">
                        
                        <!--textarea to submit any speciat requests-->
                        <label for="special_requests">Special Requests:</label>
                        <textarea id="special_requests" name="special_requests" placeholder="Any special requests?"></textarea>
                        <!--display totalprice, to be updated in real-time with the result of totalprice-->
                        <h3 style="color: black;">Total Price: $<span id="total_price"><?php echo number_format($package['price'], 2); ?></span></h3>
                        <!-- submit button to confirm booking -->
                        <button type="submit">Confirm Booking</button>
                    </form>
        </div>
    </body>
</html>
