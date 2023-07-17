<?php
// Database connection details
$host = 'localhost';
$db   = 'company_data';
$user = 'root';
$pass = 'password';

// Establish database connection
$conn = new mysqli($host, $user, $pass, $db);

// Check for connection errors
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Function to sanitize user input
function sanitize($data) {
    $data = trim($data);
    $data = stripslashes($data);
    $data = htmlspecialchars($data);
    return $data;
}

// Add a company
if (isset($_POST['add'])) {
    $name = sanitize($_POST['name']);
    $email = sanitize($_POST['email']);
    $address = sanitize($_POST['address']);
    $category = sanitize($_POST['category']);
    
    $sql = "INSERT INTO company (name, email, address, category) VALUES ('$name', '$email', '$address', '$category')";

    if ($conn->query($sql) === TRUE) {
        echo "Company added successfully.";
    } else {
        echo "Error: " . $sql . "<br>" . $conn->error;
    }
}

// Delete a company
if (isset($_GET['delete'])) {
    $id = $_GET['delete'];

    $sql = "DELETE FROM company WHERE id = $id";

    if ($conn->query($sql) === TRUE) {
        echo "Company deleted successfully.";
    } else {
        echo "Error: " . $sql . "<br>" . $conn->error;
    }
}

// Retrieve all companies
$sql = "SELECT * FROM company";
$result = $conn->query($sql);

?>

<!DOCTYPE html>
<html>
<head>
    <title>Company Management System</title>
    <style>
        body {
            font-family: Arial, sans-serif;
        }

        h1, h2 {
            text-align: center;
        }

        form {
            max-width: 400px;
            margin: 0 auto;
        }

        label {
            display: inline-block;
            width: 80px;
            font-weight: bold;
            margin-bottom: 10px;
        }

        input[type="text"],
        input[type="email"],
        textarea {
            width: 100%;
            padding: 5px;
            margin-bottom: 10px;
            border-radius: 5px;
            border: 1px solid #ccc;
        }

        input[type="submit"] {
            display: block;
            width: 100%;
            padding: 10px;
            background-color: #4CAF50;
            color: #fff;
            border: none;
            border-radius: 5px;
            cursor: pointer;
        }

        table {
            width: 100%;
            border-collapse: collapse;
        }

        th, td {
            padding: 8px;
            border: 1px solid #ddd;
            text-align: left;
        }

        th {
            background-color: #4CAF50;
            color: #fff;
        }

        @media (max-width: 600px) {
            form {
                max-width: 100%;
                width: 90%;
            }
        }
    </style>
</head>
<body>
    <h1>Company Management System</h1>

    <!-- Form to add a company -->
    <h2>Add Company</h2>
    <form method="post" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>">
        <label>Name:</label>
        <input type="text" name="name" required><br><br>
        
        <label>Email:</label>
        <input type="email" name="email" required><br><br>
        
        <label>Address:</label>
        <textarea name="address" required></textarea><br><br>
        
        <label>Category:</label>
        <input type="text" name="category" required><br><br>
        
        <input type="submit" name="add" value="Add Company">
    </form>

    <!-- Display all companies -->
    <h2>All Companies</h2>
    <table>
        <tr>
            <th>ID</th>
            <th>Name</th>
            <th>Email</th>
            <th>Address</th>
            <th>Category</th>
            <th>Action</th>
        </tr>
        <?php
        if ($result->num_rows > 0) {
            while ($row = $result->fetch_assoc()) {
                echo "<tr>";
                echo "<td>".$row['id']."</td>";
                echo "<td>".$row['name']."</td>";
                echo "<td>".$row['email']."</td>";
                echo "<td>".$row['address']."</td>";
                echo "<td>".$row['category']."</td>";
                echo "<td><a href='?delete=".$row['id']."' onclick='return confirm(\"Are you sure you want to delete this company?\")'>Delete</a></td>";
                echo "</tr>";
            }
        } else {
            echo "<tr><td colspan='6'>No companies found.</td></tr>";
        }
        ?>
    </table>

    <?php
    // Close database connection
    $conn->close();
    ?>
</body>
</html>
