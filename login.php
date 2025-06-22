<?php
session_start(); // Start the session

// Database connection details (replace with your actual credentials)
$servername = "localhost";
$username = "root";
$password = "";  // Update if you have a password set for the root user
$database = "batmandb";

// Create connection
$conn = mysqli_connect($servername, $username, $password, $database);

// Check connection
if (!$conn) {
  die("Connection failed: " . mysqli_connect_error());
}

// Initialize error message
$errorMessage = '';

// Check if form is submitted
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
  $username = $_POST['username'];
  $password = $_POST['password'];

  // Use prepared statements to prevent SQL injection
  $stmt = $conn->prepare("SELECT * FROM users WHERE username = ? AND password = ?");
  $stmt->bind_param("ss", $username, $password);
  $stmt->execute();
  $result = $stmt->get_result();

  // Check if a matching user is found
  if ($result->num_rows > 0) {
    $user = $result->fetch_assoc();
    $_SESSION['loggedin'] = true; // Set session variable
    $_SESSION['username'] = $user['username'];
    $_SESSION['password'] = $user['password'];
    if ($user['username'] === 'admin3' && $user['password'] === 'justviewing') {
      header("Location: view.php"); // Redirect to view.php on successful login for admin3
    } elseif ($user['username'] === 'admin' && $user['password'] === 'imtheking') {
      header("Location: edit.php"); // Redirect to edit.php on successful login for admin
    } elseif ($user['username'] === 'admin2' && $user['password'] === 'imanoob') {
      header("Location: edit2.php"); // Redirect to edit2.php on successful login for new admin
    } else {
      header("Location: merchandise.php"); // Redirect to merchandise.php on successful login for non-admin users
    }
    exit();
  } else {
    $errorMessage = 'Invalid username or password.';
  }

  $stmt->close();
}

$conn->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="icon" href="assets/img/batman-32.ico"> <!-- Link to the favicon image -->
    <title>Store | Login</title>
    <style>
        body {
            background-image: url("logbg.jpg");
            background-position: center;
            background-size: cover;
            background-repeat: no-repeat;
            font-family: "Copperplate Gothic", serif;
            text-align: center;
            color: yellow;
            min-height: 100vh;
        }

        .login-form {
            display: flex;
            flex-direction: column;
            margin: 0 auto;
            width: 400px;
            padding: 80px;
            border: 1px solid #ddd;
            border-radius: 5px;
            background-color: rgba(0, 0, 0, 0.7);
            color: white;
        }

        .login-form label {
            margin-bottom: 5px;
            display: block;
        }

        .login-form input[type="text"],
        .login-form input[type="password"] {
            width: 100%;
            padding: 10px;
            border: 1px solid #ccc;
            border-radius: 3px;
            margin-bottom: 10px;
        }

        .login-form button {
            background-color: red;
            color: black;
            border: none;
            padding: 10px 15px;
            border-radius: 3px;
            cursor: pointer;
            font-family: "Copperplate Gothic", serif;
        }

        .login-form button:hover {
            background-color: darkred;
        }

        .error-message {
            color: red;
            margin-bottom: 10px;
        }
    </style>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            <?php if (!empty($_SESSION['errorMessage'])): ?>
                alert('<?php echo $_SESSION['errorMessage']; ?>');
                <?php unset($_SESSION['errorMessage']); ?>
            <?php endif; ?>
        });

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
    <h1 style="color: #ffffb3"><strong>Store | Login</strong></h1>
    <div class="login-form">
        <h2>Welcome, Crimefighter!</h2>
        <p>Please enter your login credentials to access the store.</p>
        <?php if (!empty($errorMessage)): ?>
            <p class="error-message"><?php echo $errorMessage; ?></p>
        <?php endif; ?>
        <form action="<?php echo htmlspecialchars($_SERVER['PHP_SELF']); ?>" method="post">
            <label for="username">Username:</label>
            <input type="text" id="username" name="username" placeholder="Enter your username" required>
            <label for="password">Password:</label>
            <input type="password" id="password" name="password" placeholder="Enter your password" required>
            <button type="submit"><strong>Enter the Store</strong></button>
        </form>
    </div>
</body>
</html>
