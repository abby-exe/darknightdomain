<?php
session_start();
if (!isset($_SESSION['loggedin']) || $_SESSION['loggedin'] !== true) {
    header("Location: login.php");
    exit();
}

// Database connection details
$servername = "localhost";
$username = "root";
$password = "";  // Update if you have a password set for the root user
$database = "batmandb";

// Create connection
$conn = new mysqli($servername, $username, $password, $database);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Fetch all products
$result = $conn->query("SELECT * FROM products");

$conn->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="icon" href="assets/img/batman-32.ico">
    <title>Store</title>
    <style>
        body {
            background-image: url("merchbg.jpg"); /* Replace "merchbg.jpg" with your actual image path */
            background-position: center;
            background-size: cover;
            background-repeat: no-repeat;
            filter: brightness(90%);
            font-family: "Copperplate Gothic", serif;
            text-align: center;
            color: yellow;
            min-height: 100vh;
        }
        
        .items {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
            gap: 20px;
            padding: 20px;
        }
        
        .item {
            display: flex;
            flex-direction: column;
            justify-content: center;
            align-items: center;
            border: 1px solid #ddd;
            padding: 10px;
            border-radius: 5px;
            text-align: center;
            background-color: rgba(0, 0, 0, 0.7);
        }
        
        .item img {
            width: 100%;
            height: auto;
            margin-bottom: 10px;
        }

        .logout-button {
            position: absolute;
            top: 20px;
            right: 20px;
            background-color: red;
            color: black;
            border: none;
            padding: 10px 15px;
            border-radius: 3px;
            cursor: pointer;
            font-family: "Copperplate Gothic", serif;
        }

        .logout-button:hover {
            background-color: darkred;
        }
    </style>
<script>
    // Disable right-click
    document.addEventListener('contextmenu', event => event.preventDefault());

    // Disable specific key combinations
    document.onkeydown = function(e) {
        if (e.keyCode == 123) {
            return false; // F12 key
        }
        if (e.ctrlKey && e.shiftKey && e.keyCode == 'I'.charCodeAt(0)) {
            return false; // Ctrl+Shift+I
        }
        if (e.ctrlKey && e.shiftKey && e.keyCode == 'C'.charCodeAt(0)) {
            return false; // Ctrl+Shift+C
        }
        if (e.ctrlKey && e.shiftKey && e.keyCode == 'J'.charCodeAt(0)) {
            return false; // Ctrl+Shift+J
        }
        if (e.ctrlKey && e.keyCode == 'U'.charCodeAt(0)) {
            return false; // Ctrl+U
        }
    }
  </script>
</head>
<body>
    <a href="logout.php" class="logout-button"><strong>Logout</strong></a>
    <h1 style="color: gold;"><strong>THE DARK KNIGHT STORE</strong></h1>
    <p style="color: gold;"><em>The Official Wayne Enterprises Merchandise Source</em></p>
    <div class="items">
        <?php while ($row = $result->fetch_assoc()): ?>
            <div class="item">
                <img src="<?php echo $row['productimage']; ?>" alt="<?php echo $row['productname']; ?>">
                <h3><?php echo $row['productname']; ?></h3>
                <p><em><?php echo $row['productdesc']; ?></em></p>
                <span>Price: MYR <?php echo $row['productprice']; ?></span>
            </div>
        <?php endwhile; ?>
    </div>
</body>
</html>
