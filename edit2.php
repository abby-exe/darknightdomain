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

// Initialize variables
$message = '';

// Handle form submissions
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['add_product'])) {
        // Add product
        $productname = $_POST['productname'];
        $productprice = $_POST['productprice'];
        $productimage = $_POST['productimage'];
        $productdesc = $_POST['productdesc'];
        
        $stmt = $conn->prepare("INSERT INTO products (productname, productprice, productimage, productdesc) VALUES (?, ?, ?, ?)");
        $stmt->bind_param("siss", $productname, $productprice, $productimage, $productdesc);
        if ($stmt->execute()) {
            $message = "Product added successfully.";
        } else {
            $message = "Error adding product: " . $conn->error;
        }
        $stmt->close();
    } elseif (isset($_POST['update_product'])) {
        // Update product
        $productid = $_POST['productid'];
        $productname = $_POST['productname'];
        $productprice = $_POST['productprice'];
        $productimage = $_POST['productimage'];
        $productdesc = $_POST['productdesc'];
        
        $stmt = $conn->prepare("UPDATE products SET productname = ?, productprice = ?, productimage = ?, productdesc = ? WHERE productid = ?");
        $stmt->bind_param("sissi", $productname, $productprice, $productimage, $productdesc, $productid);
        if ($stmt->execute()) {
            $message = "Product updated successfully.";
        } else {
            $message = "Error updating product: " . $conn->error;
        }
        $stmt->close();
    } elseif (isset($_POST['delete_product'])) {
        // Delete product
        $productid = $_POST['productid'];
        
        $stmt = $conn->prepare("DELETE FROM products WHERE productid = ?");
        $stmt->bind_param("i", $productid);
        if ($stmt->execute()) {
            $message = "Product deleted successfully.";
        } else {
            $message = "Error deleting product: " . $conn->error;
        }
        $stmt->close();
    }
}

// Fetch all products for display
$result = $conn->query("SELECT * FROM products");

$conn->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="icon" href="assets/img/batman-32.ico">
    <title>Edit Products</title>
    <style>
        body {
            background-image: url("editbg3.jpg"); /* Replace with your actual image path */
            background-position: center;
            background-size: cover;
            background-repeat: no-repeat;
            font-family: "Copperplate Gothic", serif;
            text-align: center;
            color: yellow;
            min-height: 100vh;
            margin: 0;
            display: flex;
            justify-content: center;
            align-items: center;
            flex-direction: column;
        }
        .container {
            width: 80%;
            background: rgba(0, 0, 0, 0.7);
            padding: 20px;
            border-radius: 10px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.5);
        }
        .form-container {
            background: rgba(0, 0, 0, 0.8);
            padding: 20px;
            margin: 20px 0;
            border-radius: 10px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.5);
        }
        input[type="text"], input[type="number"], textarea {
            width: 100%;
            padding: 10px;
            margin: 10px 0;
            border: 1px solid #ddd;
            border-radius: 5px;
            font-family: "Copperplate Gothic", serif;
            color: black;
        }
        button {
            background-color: red;
            color: black;
            border: none;
            padding: 10px 20px;
            cursor: pointer;
            border-radius: 5px;
            font-family: "Copperplate Gothic", serif;
        }
        button:hover {
            background-color: darkred;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin: 20px 0;
        }
        table, th, td {
            border: 1px solid #ddd;
            color: yellow;
        }
        th, td {
            padding: 10px;
            text-align: center;
        }
        .message {
            color: green;
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
</head>
<body>
    <a href="logout.php" class="logout-button"><strong>Logout</strong></a>
    <div class="container">
        <h1>Edit Products</h1>
        <?php if (!empty($message)): ?>
            <p class="message"><?php echo $message; ?></p>
        <?php endif; ?>

        <div class="form-container">
            <h2>Add Product</h2>
            <form action="edit2.php" method="post">
                <input type="text" name="productname" placeholder="Product Name" required>
                <input type="number" name="productprice" placeholder="Product Price" required>
                <input type="text" name="productimage" placeholder="Product Image URL" required>
                <textarea name="productdesc" placeholder="Product Description" required></textarea>
                <button type="submit" name="add_product"><strong>Add Product</strong></button>
            </form>
        </div>

        <div class="form-container">
            <h2>Update or Delete Products</h2>
            <table>
                <tr>
                    <th>ID</th>
                    <th>Name</th>
                    <th>Price</th>
                    <th>Image</th>
                    <th>Description</th>
                    <th>Actions</th>
                </tr>
                <?php while ($row = $result->fetch_assoc()): ?>
                    <tr>
                        <form action="edit2.php" method="post">
                            <td><?php echo $row['productid']; ?></td>
                            <td><input type="text" name="productname" value="<?php echo $row['productname']; ?>" required></td>
                            <td><input type="number" name="productprice" value="<?php echo $row['productprice']; ?>" required></td>
                            <td><input type="text" name="productimage" value="<?php echo $row['productimage']; ?>" required></td>
                            <td><textarea name="productdesc" required><?php echo $row['productdesc']; ?></textarea></td>
                            <td>
                                <input type="hidden" name="productid" value="<?php echo $row['productid']; ?>">
                                <button type="submit" name="update_product"><strong>Update</strong></button>
                                <button type="submit" name="delete_product" style="background-color: red;"><strong>Delete</strong></button>
                            </td>
                        </form>
                    </tr>
                <?php endwhile; ?>
            </table>
        </div>
    </div>
</body>
</html>