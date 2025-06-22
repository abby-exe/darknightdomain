<?php
// Connection to the database
$connection = mysqli_connect("localhost", "root", "", "batmandb");

// Check if the connection is successful
if (!$connection) {
    die("Connection failed: " . mysqli_connect_error());
}

// Section A: Display selection form or add new product form
if (!isset($_POST['select_record']) && !isset($_POST['update_product']) && !isset($_POST['delete_product']) && !isset($_POST['add_product'])) {
?>

<!DOCTYPE html>
<html>
<head>
    <title>Edit Page</title>
    <link rel="icon" href="assets/img/batman-32.ico"> <!-- Link to the favicon image -->
    <style>
        body {
            background-image: url("editbg2.jpg"); /* Set your background image */
            background-position: center;
            background-size: cover;
            background-repeat: no-repeat;
            min-height: 100vh;
            font-family: "Copperplate Gothic", serif; /* Set Copperplate Gothic font */
            text-align: center; /* Center align text */
            color: yellow; /* Set font color to yellow */
            padding-top: 50px; /* Add padding to the top */
        }
        
        form {
            display: inline-block; /* Display form elements inline */
            margin: auto; /* Center align the form */
            text-align: left; /* Align form elements to the left */
        }
        
        h1 {
            margin-bottom: 30px; /* Add margin to the bottom of heading */
        }
        
        input[type="text"] {
            width: 200px; /* Set width for text inputs */
            padding: 10px; /* Add padding to inputs */
            margin-bottom: 15px; /* Add margin to the bottom of inputs */
        }
        
        input[type="submit"], input[type="reset"] {
            background-color: red; /* Set background color for buttons */
            font-family: 'Copperplate Gothic';
            color: black; /* Set font color for buttons */
            border: none; /* Remove border */
            padding: 10px 20px; /* Add padding to buttons */
            margin-right: 10px; /* Add margin to the right of buttons */
            cursor: pointer; /* Add cursor pointer */
        }
        
        input[type="submit"]:hover, input[type="reset"]:hover {
            background-color: #cc0000; /* Darken background color on hover */
            
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
      text-decoration: none;
      font-family: "Copperplate Gothic", serif;
    }

    .logout-button:hover {
      background-color: darkred;
    }
    </style>
</head>
<body>
    <a href="logout.php" class="logout-button"><strong>Logout</strong></a>
    <h1><strong>BATMAN STORE DATABASE EDITOR</strong></h1>
    
    <h2>ADD NEW PRODUCT:</h2>
    <form method="post" action="edit.php">
        <label for="productid"><strong>Product ID:</strong></label><br>
        <input type="text" name="productid" required><br>
        <label for="productname"><strong>Product Name:</strong></label><br>
        <input type="text" name="productname" required><br>
        <label for="productprice"><strong>Product Price:</strong></label><br>
        <input type="text" name="productprice" required><br>
        <input type="submit" name="add_product" value="Add Product">
        <input type="reset" value="Reset">
    </form><br><br><br>
    
    <h2>SELECT PRODUCT ID TO EDIT OR DELETE:</h2>
    <form method="post" action="edit.php">
        <label for="productid"><strong>Product ID:</strong></label><br>
        <div style="text-align: center;">
    <select name="productid">
        <?php
        // Query to fetch existing records from the database
        $sql = "SELECT productid FROM products";
        $result = mysqli_query($connection, $sql);

        // Check if records are fetched successfully
        if (mysqli_num_rows($result) > 0) {
            while ($row = mysqli_fetch_assoc($result)) {
                echo "<option value='" . $row['productid'] . "'>" . $row['productid'] . "</option>";
            }
        } else {
            echo "<option value=''>No records found</option>";
        }
        ?>
    </select>
</div><br><br><br><br>
        <input type="submit" name="select_record" value="Select">
        <input type="reset" value="Reset">
    </form>
</body>
</html>
<?php
}

// Section B: Display edit form for selected product
if (isset($_POST['select_record'])) {
    $selected_productid = $_POST['productid'];

    // Query to fetch the product details based on the selected code
    $sql = "SELECT * FROM products WHERE productid = '$selected_productid'";
    $result = mysqli_query($connection, $sql);

    // Check if the product details are fetched successfully
    if (mysqli_num_rows($result) == 1) {
        $products = mysqli_fetch_assoc($result);
        ?>
        <!DOCTYPE html>
        <html>
        <head>
            <title>Edit Product Details</title>
            <link rel="icon" href="assets/img/batman-32.ico"> <!-- Link to the favicon image -->
        </head>
        <body style="background-image: url('editbg2.jpg'); background-position: center; background-size: cover; background-repeat: no-repeat; min-height: 100vh; font-family: 'Copperplate Gothic', serif; text-align: center; color: yellow; padding-top: 50px;">
            <h2>EDIT PRODUCT DETAILS:</h2><br><br>
            <form method="post" action="edit.php">
                <label for="productid"><strong>Product ID:</strong></label>
                <input type="text" name="productid" value="<?php echo isset($products['productid']) ? $products['productid'] : ''; ?>" readonly><br><br><br><br>
                <label for="productname"><strong>Product Name:</strong></label>
                <input type="text" name="productname" value="<?php echo isset($products['productname']) ? $products['productname'] : ''; ?>"><br><br><br><br>
                <label for="productprice"><strong>Product Price:</strong></label>
                <input type="text" name="productprice" value="<?php echo isset($products['productprice']) ? $products['productprice'] : ''; ?>"><br><br><br><br>
                <input type="submit" name="update_product" value="Update" style="background-color: red; font-family: 'Copperplate Gothic'; color: black; border: none; padding: 10px 20px; margin-right: 10px; cursor: pointer;"><br><br>
                <input type="submit" name="delete_product" value="Delete" style="background-color: red; font-family: 'Copperplate Gothic'; color: black; border: none; padding: 10px 20px; margin-right: 10px; cursor: pointer;">
            </form>
        </body>
        </html>
        <?php
    } else {
        echo "Product not found";
    }
}

// Section C: Handle update product
if (isset($_POST['update_product'])) {
    $productid = $_POST['productid'];
    $productname = $_POST['productname'];
    $productprice = $_POST['productprice'];

    // Update the product record in the database
    $sql = "UPDATE products SET productname='$productname', productprice='$productprice' WHERE productid='$productid'";
    if (mysqli_query($connection, $sql)) {
        echo "Product updated successfully!";
    } else {
        echo "Error updating record: " . mysqli_error($connection);
    }
}

// Section D: Handle delete product
if (isset($_POST['delete_product'])) {
    $productid = $_POST['productid'];

    // Delete the product record from the database
    $sql = "DELETE FROM products WHERE productid='$productid'";
    if (mysqli_query($connection, $sql)) {
        echo "<p style='color: green; font-weight: bold; margin-top: 20px; background-color: lightgreen;'>Product deleted successfully!</p>";
    } else {
        echo "<p style='color: red; font-weight: bold; margin-top: 20px; background-color: lightcoral;'>Error deleting product: " . mysqli_error($connection). "</p>";
    }
}

// Section E: Handle add new product
if (isset($_POST['add_product'])) {
    $productid = $_POST['productid'];
    $productname = $_POST['productname'];
    $productprice = $_POST['productprice'];

    // Insert the new product record into the database
    $sql = "INSERT INTO products (productid, productname, productprice) VALUES ('$productid', '$productname', '$productprice')";
    if (mysqli_query($connection, $sql)) {
        echo "<p style='color: green; font-weight: bold; margin-top: 20px; background-color: lightgreen;'>Product added successfully!</p>";
    } else {
        echo "<p style='color: red; font-weight: bold; margin-top: 20px; background-color: lightcoral;'>Error adding product: " . mysqli_error($connection). "</p>";
    }
}

// Close the database connection
mysqli_close($connection);
?>
