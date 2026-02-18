<?php
$conn = new mysqli("localhost","root","__","fullstackdev");
if($conn->connect_error){ die("Connection failed"); }

$message="";
$action=isset($_GET['action'])?$_GET['action']:"";

/* INSERT */
if(isset($_POST['insert'])){
    $name=$_POST['emp_name'];
    $empid=$_POST['emp_id'];
    $dept=$_POST['department'];
    $phone=$_POST['phone'];
    $date=$_POST['joining_date'];

    if(!preg_match("/^[0-9]{10}$/",$phone)){
        $message="Phone must be 10 digits!";
    } else {
        $conn->query("INSERT INTO employees(emp_name,emp_id,department,phone,joining_date)
                      VALUES('$name','$empid','$dept','$phone','$date')");
        $message="Employee inserted successfully!";
    }
}

/* DELETE */
if(isset($_POST['delete'])){
    $empid=$_POST['emp_id'];
    $conn->query("DELETE FROM employees WHERE emp_id='$empid'");
    $message="Employee deleted successfully!";
}

/* UPDATE */
if(isset($_POST['update'])){
    $empid=$_POST['emp_id'];
    $field=$_POST['field'];
    $value=$_POST['value'];

    $conn->query("UPDATE employees SET $field='$value' WHERE emp_id='$empid'");
    $message="Employee updated successfully!";
}

$result=$conn->query("SELECT * FROM employees");
?>

<!DOCTYPE html>
<html>
<head>
<title>Employee Management</title>
<style>
body{font-family:Segoe UI;background:linear-gradient(120deg,#a1ffce,#faffd1);padding:20px;}
.container{width:520px;margin:auto;background:white;padding:25px;border-radius:20px;
box-shadow:0 10px 30px rgba(0,0,0,0.2);}
h2{text-align:center;}
input,select{width:100%;padding:8px;margin:6px 0;border-radius:8px;border:1px solid #ccc;}
button{padding:8px 15px;margin:5px;border-radius:20px;border:none;background:#00c9ff;color:white;cursor:pointer;}
button:hover{transform:scale(1.1);}
table{width:95%;margin:20px auto;border-collapse:collapse;background:white;
box-shadow:0 10px 25px rgba(0,0,0,0.2);}
th{background:#00c9ff;color:white;padding:10px;}
td{padding:8px;text-align:center;border-bottom:1px solid #eee;}
</style>
</head>
<body>

<div class="container">
<h2>Employee Management</h2>
<p><?php echo $message;?></p>

<a href="?action=insert"><button>Insert</button></a>
<a href="?action=update"><button>Update</button></a>
<a href="?action=delete"><button>Delete</button></a>

<?php if($action=="insert"){ ?>
<form method="POST">
<input name="emp_name" placeholder="Employee Name" required>
<input name="emp_id" placeholder="Employee ID" required>
<input name="department" placeholder="Department Name" required>
<input name="phone" placeholder="Phone Number" required>
<input type="date" name="joining_date" required>
<button name="insert">Submit</button>
</form>
<?php } ?>

<?php if($action=="update"){ ?>
<form method="POST">
<input name="emp_id" placeholder="Enter Employee ID" required>
<select name="field">
<option value="emp_name">Employee Name</option>
<option value="department">Department</option>
<option value="phone">Phone</option>
<option value="joining_date">Joining Date</option>
</select>
<input name="value" placeholder="New Value" required>
<button name="update">Update</button>
</form>
<?php } ?>

<?php if($action=="delete"){ ?>
<form method="POST">
<input name="emp_id" placeholder="Enter Employee ID to Delete" required>
<button name="delete">Delete</button>
</form>
<?php } ?>
</div>

<h2 align="center">Employee Records</h2>
<table>
<tr>
<th>ID</th><th>Name</th><th>Employee ID</th>
<th>Department</th><th>Phone</th><th>Joining Date</th>
</tr>
<?php while($row=$result->fetch_assoc()){ ?>
<tr>
<td><?= $row['id']?></td>
<td><?= $row['emp_name']?></td>
<td><?= $row['emp_id']?></td>
<td><?= $row['department']?></td>
<td><?= $row['phone']?></td>
<td><?= $row['joining_date']?></td>
</tr>
<?php } ?>
</table>
</body>
</html>

