<?php
// Database connection details
$host = 'localhost';
$db   = 'authentication_app';
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

// Add a student
if (isset($_POST['add'])) {
    $name = sanitize($_POST['name']);
    $age = sanitize($_POST['age']);
    $grade = sanitize($_POST['grade']);
    
    $sql = "INSERT INTO students (name, age, grade) VALUES ('$name', '$age', '$grade')";

    if ($conn->query($sql) === TRUE) {
        echo "Student added successfully.";
    } else {
        echo "Error: " . $sql . "<br>" . $conn->error;
    }
}

// Delete a student
if (isset($_GET['delete'])) {
    $id = $_GET['delete'];

    $sql = "DELETE FROM students WHERE id = $id";

    if ($conn->query($sql) === TRUE) {
        echo "Student deleted successfully.";
    } else {
        echo "Error: " . $sql . "<br>" . $conn->error;
    }
}

// Update a student
if (isset($_POST['update'])) {
    $id = $_POST['id'];
    $name = sanitize($_POST['name']);
    $age = sanitize($_POST['age']);
    $grade = sanitize($_POST['grade']);

    $sql = "UPDATE students SET name='$name', age='$age', grade='$grade' WHERE id='$id'";

    if ($conn->query($sql) === TRUE) {
        echo "Student updated successfully.";
    } else {
        echo "Error: " . $sql . "<br>" . $conn->error;
    }
}

// Retrieve all students
$sql = "SELECT * FROM students";
$result = $conn->query($sql);

?>

<!DOCTYPE html>
<html>
<head>
    <title>Student Management System</title>
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
    <h1>Student Management System</h1>

    <!-- Form to add/update a student -->
    <h2>Add/Update Student</h2>
    <form method="post" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>">
        <?php if (isset($_GET['edit'])) {
            $id = $_GET['edit'];
            $sql = "SELECT * FROM students WHERE id = $id";
            $editResult = $conn->query($sql);
            $editRow = $editResult->fetch_assoc();
        ?>
            <input type="hidden" name="id" value="<?php echo $editRow['id']; ?>">
        <?php } ?>
        <label>Name:</label>
        <input type="text" name="name" value="<?php if(isset($editRow)) echo $editRow['name']; ?>" required><br><br>
        
        <label>Age:</label>
        <input type="number" name="age" value="<?php if(isset($editRow)) echo $editRow['age']; ?>" required><br><br>
        
        <label>Grade:</label>
        <input type="text" name="grade" value="<?php if(isset($editRow)) echo $editRow['grade']; ?>" required><br><br>
        
        <?php if (isset($_GET['edit'])) { ?>
            <input type="submit" name="update" value="Update Student">
        <?php } else { ?>
            <input type="submit" name="add" value="Add Student">
        <?php } ?>
    </form>

    <!-- Display all students -->
    <h2>All Students</h2>
    <table>
        <tr>
            <th>ID</th>
            <th>Name</th>
            <th>Age</th>
            <th>Grade</th>
            <th>Action</th>
        </tr>
        <?php
        if ($result->num_rows > 0) {
            while ($row = $result->fetch_assoc()) {
                echo "<tr>";
                echo "<td>".$row['id']."</td>";
                echo "<td>".$row['name']."</td>";
                echo "<td>".$row['age']."</td>";
                echo "<td>".$row['grade']."</td>";
                echo "<td><a href='?edit=".$row['id']."'>Edit</a> | <a href='?delete=".$row['id']."' onclick='return confirm(\"Are you sure you want to delete this student?\")'>Delete</a></td>";
                echo "</tr>";
            }
        } else {
            echo "<tr><td colspan='5'>No students found.</td></tr>";
        }
        ?>
    </table>

    <?php
    // Close database connection
    $conn->close();
    ?>
</body>
</html>
