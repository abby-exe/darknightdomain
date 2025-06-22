<?php
session_start();

if (!isset($_SESSION['loggedin']) || $_SESSION['loggedin'] !== true || $_SESSION['username'] !== 'admin3' || $_SESSION['password'] !== 'justviewing') {
    header("Location: login.php");
    exit();
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <a href="logout.php" class="logout-button"><strong>Logout</strong></a>
  <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.1/css/bootstrap.min.css">
  <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.4.1/jquery.min.js"></script>
  <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.1/js/bootstrap.min.js"></script>
  <link rel="icon" href="assets/img/batman-32.ico"> <!-- Link to the favicon image -->
  <style>
    body {
      background-color: #222;
      color: #fff;
      font-family: "Copperplate Gothic", serif;
    }
    .table {
      background-color: #333;
      color: #fff;
    }
    .table th, .table td {
      border-color: #555;
    }
    .table thead th {
      background-color: #444;
    }
    .table tbody tr:nth-child(even) {
      background-color: #555;
    }
    .table tbody tr:nth-child(odd) {
      background-color: #666;
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
  <title>View Users</title>
</head>
<body>
  <?php
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

  // Fetch number of users
  $countQuery = "SELECT COUNT(*) as userCount FROM users";
  $countResult = mysqli_query($conn, $countQuery);
  $userCount = mysqli_fetch_assoc($countResult)['userCount'];

  // Fetch user data
  $q = "SELECT * FROM users";
  $rs = mysqli_query($conn, $q);
  ?>

  <div class="container">
    <h1>User Records</h1>
    <p>Total number of users: <?php echo $userCount; ?></p>

    <table class="table table-dark" border="2">
      <thead>
        <tr>
          <th scope="col">#</th>
          <th scope="col">ID</th>
          <th scope="col">Username</th>
          <th scope="col">Password</th>
        </tr>
      </thead>
      <tbody>
        <?php
        $x = 1; // counter
        while ($row = mysqli_fetch_array($rs)) {
          ?>
          <tr>
            <th scope="row"><?php echo $x; ?></th>
            <td><?php echo $row['id']; ?></td>
            <td><?php echo $row['username']; ?></td>
            <td><?php echo $row['password']; ?></td>
          </tr>
          <?php
          $x++;
        }
        mysqli_close($conn);
        ?>
      </tbody>
    </table>
  </div>
</body>
</html>
