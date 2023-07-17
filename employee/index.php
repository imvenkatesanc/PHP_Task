<?php
// Database connection details
$host = 'localhost';
$db   = 'employees';
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

// Add an employee
if (isset($_POST['add'])) {
    $name = isset($_POST['name']) ? sanitize($_POST['name']) : '';
    $email = isset($_POST['email']) ? sanitize($_POST['email']) : '';
    $salary = isset($_POST['salary']) ? sanitize($_POST['salary']) : '';

    if (empty($salary)) {
        echo "Error: Salary field cannot be empty.";
    } else {
        // Validate and format salary
        if (!is_numeric($salary)) {
            echo "Error: Invalid salary value.";
        } else {
            $salary = str_replace(',', '.', $salary); // Replace commas with dots

            $sql = "INSERT INTO employees (name, email, salary) VALUES ('$name', '$email', '$salary')";

            if ($conn->query($sql) === TRUE) {
                echo "Employee added successfully.";
            } else {
                echo "Error: " . $sql . "<br>" . $conn->error;
            }
        }
    }
}

// Delete an employee
if (isset($_GET['delete'])) {
    $id = $_GET['delete'];

    $sql = "DELETE FROM employees WHERE id = $id";

    if ($conn->query($sql) === TRUE) {
        echo "Employee deleted successfully.";
    } else {
        echo "Error: " . $sql . "<br>" . $conn->error;
    }
}

// Retrieve all employees
$sql = "SELECT * FROM employees";
$result = $conn->query($sql);

?>

<!DOCTYPE html>
<html>
<head>
    <title>Employee Management System</title>
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
        input[type="number"] {
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
    <h1>Employee Management System</h1>

    <!-- Form to add an employee -->
    <h2>Add Employee</h2>
    <form method="post" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>">
        <label>Name:</label>
        <input type="text" name="name" required><br><br>
        
        <label>Email:</label>
        <input type="email" name="email" required><br><br>
        
        <label>Salary:</label>
        <input type="number" name="salary" required><br><br>
        
        <input type="submit" name="add" value="Add Employee">
    </form>

    <!-- Display all employees -->
    <h2>All Employees</h2>
    <table>
        <tr>
            <th>ID</th>
            <th>Name</th>
            <th>Email</th>
            <th>Salary</th>
            <th>Action</th>
        </tr>
        <?php
        if ($result->num_rows > 0) {
            while ($row = $result->fetch_assoc()) {
                echo "<tr>";
                echo "<td>".$row['id']."</td>";
                echo "<td>".$row['name']."</td>";
                echo "<td>".$row['email']."</td>";
                echo "<td>".$row['salary']."</td>";
                echo "<td><a href='?delete=".$row['id']."' onclick='return confirm(\"Are you sure you want to delete this employee?\")'>Delete</a></td>";
                echo "</tr>";
            }
        } else {
            echo "<tr><td colspan='5'>No employees found.</td></tr>";
        }
        ?>
    </table>

    <?php
    // Close database connection
    $conn->close();
    ?>
</body>
</html>
