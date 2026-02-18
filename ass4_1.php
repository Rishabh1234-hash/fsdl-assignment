<?php
session_start();

$conn = new mysqli("localhost", "root", "__", "fullstackdev");
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

$message = "";
$action = isset($_GET['action']) ? $_GET['action'] : "";

/* ADMIN LOGIN */
if (isset($_POST['admin_login'])) {
    $username = $_POST['username'];
    $password = $_POST['password'];

    if ($username === "admin" && $password === "admin123") {
        $_SESSION['admin'] = true;
        $message = "Admin logged in successfully!";
    } else {
        $message = "Invalid admin credentials!";
    }
}

/* LOGOUT */
if (isset($_GET['logout'])) {
    session_destroy();
    header("Location: index.php");
    exit();
}

/* INSERT */
if (isset($_POST['insert'])) {

    $first = $_POST['first_name'];
    $last = $_POST['last_name'];
    $roll = $_POST['roll_no'];
    $pass = $_POST['password'];
    $confirm = $_POST['confirm_password'];
    $contact = $_POST['contact'];

    if ($pass != $confirm) {
        $message = "Passwords do not match!";
    } else {

        $sql = "INSERT INTO students(first_name,last_name,roll_no,password,contact)
                VALUES('$first','$last','$roll','$pass','$contact')";

        if ($conn->query($sql)) {
            $message = "Student inserted successfully!";
        } else {
            $message = "Error: " . $conn->error;
        }
    }
}

/* DELETE */
if (isset($_POST['delete'])) {
    $roll = $_POST['roll_no'];
    $sql = "DELETE FROM students WHERE roll_no='$roll'";
    $conn->query($sql);
    $message = "Student deleted successfully!";
}

/* UPDATE */
if (isset($_POST['update'])) {
    $roll = $_POST['roll_no'];
    $field = $_POST['field'];
    $value = $_POST['value'];

    $sql = "UPDATE students SET $field='$value' WHERE roll_no='$roll'";
    $conn->query($sql);
    $message = "Student record updated successfully!";
}

/* FETCH RECORDS FOR ADMIN */
if (isset($_SESSION['admin'])) {
    $result = $conn->query("SELECT * FROM students");
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Student Management System</title>
<style>
    body {
        font-family: 'Segoe UI', sans-serif;
        margin: 0;
        padding: 20px;
        background: linear-gradient(120deg, #89f7fe, #66a6ff);
        min-height: 100vh;
    }

    .container {
        width: 520px;
        background: rgba(255, 255, 255, 0.95);
        padding: 30px;
        margin: auto;
        border-radius: 20px;
        box-shadow: 0 15px 40px rgba(0, 0, 0, 0.2);
        transition: 0.3s ease-in-out;
    }

    .container:hover {
        transform: translateY(-5px);
    }

    h2 {
        text-align: center;
        margin-bottom: 20px;
        color: #333;
        letter-spacing: 1px;
    }

    input, select {
        width: 100%;
        padding: 10px;
        margin: 8px 0;
        border-radius: 10px;
        border: 1px solid #ccc;
        font-size: 14px;
        transition: 0.3s;
    }

    input:focus, select:focus {
        border-color: #66a6ff;
        box-shadow: 0 0 8px rgba(102,166,255,0.5);
        outline: none;
        transform: scale(1.02);
    }

    button {
        padding: 10px 18px;
        margin: 6px 3px;
        border-radius: 25px;
        border: none;
        font-weight: bold;
        cursor: pointer;
        color: white;
        background: linear-gradient(45deg, #ff6a00, #ee0979);
        transition: 0.3s ease-in-out;
    }

    button:hover {
        transform: scale(1.1);
        box-shadow: 0 5px 15px rgba(0,0,0,0.2);
    }

    table {
        width: 95%;
        margin: 30px auto;
        border-collapse: collapse;
        background: white;
        border-radius: 15px;
        overflow: hidden;
        box-shadow: 0 10px 30px rgba(0,0,0,0.2);
    }

    th {
        background: linear-gradient(45deg, #36d1dc, #5b86e5);
        color: white;
        padding: 12px;
        text-transform: uppercase;
        font-size: 14px;
    }

    td {
        padding: 10px;
        border-bottom: 1px solid #eee;
        text-align: center;
    }

    tr:hover {
        background: #f1f9ff;
        transition: 0.3s;
    }

    .message {
        text-align: center;
        font-weight: bold;
        color: #ee0979;
        margin-bottom: 10px;
    }

    .center {
        text-align: center;
    }

    a {
        text-decoration: none;
    }
</style>

</head>
<body>

<div class="container">

<h2 class="center">Student Management</h2>
<div class="message"><?php echo $message; ?></div>

<?php if ($action == "") { ?>
    <div class="center">
        <a href="?action=insert"><button>Insert</button></a>
        <a href="?action=update"><button>Update</button></a>
        <a href="?action=delete"><button>Delete</button></a>
        <a href="?action=admin"><button>Admin Login</button></a>
    </div>
<?php } ?>

<!-- INSERT -->
<?php if ($action == "insert") { ?>
<form method="POST">
    <input type="text" name="first_name" placeholder="First Name" required>
    <input type="text" name="last_name" placeholder="Last Name" required>
    <input type="text" name="roll_no" placeholder="Roll No" required>
    <input type="password" name="password" placeholder="Password" required>
    <input type="password" name="confirm_password" placeholder="Confirm Password" required>
    <input type="text" name="contact" placeholder="Contact Number" required>
    <button type="submit" name="insert">Submit</button>
</form>
<?php } ?>

<!-- UPDATE -->
<?php if ($action == "update") { ?>
<form method="POST">
    <input type="text" name="roll_no" placeholder="Enter Roll No" required>
    <select name="field" required>
        <option value="">Select Field</option>
        <option value="first_name">First Name</option>
        <option value="last_name">Last Name</option>
        <option value="contact">Contact</option>
        <option value="password">Password</option>
    </select>
    <input type="text" name="value" placeholder="New Value" required>
    <button type="submit" name="update">Update</button>
</form>
<?php } ?>

<!-- DELETE -->
<?php if ($action == "delete") { ?>
<form method="POST">
    <input type="text" name="roll_no" placeholder="Enter Roll No" required>
    <button type="submit" name="delete">Delete</button>
</form>
<?php } ?>

<!-- ADMIN LOGIN -->
<?php if ($action == "admin" && !isset($_SESSION['admin'])) { ?>
<form method="POST">
    <input type="text" name="username" placeholder="Admin Username" required>
    <input type="password" name="password" placeholder="Admin Password" required>
    <button type="submit" name="admin_login">Login</button>
</form>
<?php } ?>

<br>
<div class="center">
    <a href="index.php"><button>Back to Menu</button></a>
    <?php if (isset($_SESSION['admin'])) { ?>
        <a href="?logout=true"><button>Logout</button></a>
    <?php } ?>
</div>

</div>

<!-- ADMIN TABLE -->
<?php if (isset($_SESSION['admin'])) { ?>

<h2 class="center">All Student Records (Admin Only)</h2>

<table>
<tr>
    <th>ID</th>
    <th>First Name</th>
    <th>Last Name</th>
    <th>Roll No</th>
    <th>Password</th>
    <th>Contact</th>
</tr>

<?php while($row = $result->fetch_assoc()) { ?>
<tr>
    <td><?php echo $row['id']; ?></td>
    <td><?php echo $row['first_name']; ?></td>
    <td><?php echo $row['last_name']; ?></td>
    <td><?php echo $row['roll_no']; ?></td>
    <td><?php echo $row['password']; ?></td>
    <td><?php echo $row['contact']; ?></td>
</tr>
<?php } ?>

</table>

<?php } ?>

</body>
</html>
