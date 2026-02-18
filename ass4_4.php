<?php
$conn = new mysqli("localhost","root","__","fullstackdev");
if($conn->connect_error){ die("Connection failed"); }

$message="";
$action=isset($_GET['action'])?$_GET['action']:"";

/* INSERT */
if(isset($_POST['insert'])){
    $name=$_POST['passenger_name'];
    $from=$_POST['source'];
    $to=$_POST['destination'];
    $DOB=$_POST['DOB'];
    $dep=$_POST['departure_date'];
    $arr=$_POST['arrival_date'];
    $phone=$_POST['phone'];
    $email=$_POST['email'];

    if(!preg_match("/^[0-9]{10}$/",$phone)){
        $message="Phone must be 10 digits!";
    }
    elseif(!filter_var($email,FILTER_VALIDATE_EMAIL)){
        $message="Invalid Email!";
    }
    else{
        $conn->query("INSERT INTO flights(passenger_name,source,destination,DOB,
        departure_date,arrival_date,phone,email)
        VALUES('$name','$from','$to','$DOB','$dep','$arr','$phone','$email')");
        $message="Booking inserted successfully!";
    }
}

/* DELETE */
if(isset($_POST['delete'])){
    $phone=$_POST['phone'];
    $conn->query("DELETE FROM flights WHERE phone='$phone'");
    $message="Booking deleted successfully!";
}

/* UPDATE */
if(isset($_POST['update'])){
    $phone=$_POST['phone'];
    $field=$_POST['field'];
    $value=$_POST['value'];

    $conn->query("UPDATE flights SET $field='$value' WHERE phone='$phone'");
    $message="Booking updated successfully!";
}

$result=$conn->query("SELECT * FROM flights");
?>

<!DOCTYPE html>
<html>
<head>
<title>Flight Booking System</title>
<style>
body{font-family:Segoe UI;background:linear-gradient(120deg,#fddb92,#d1fdff);padding:20px;}
.container{width:520px;margin:auto;background:white;padding:25px;border-radius:20px;
box-shadow:0 10px 30px rgba(0,0,0,0.2);}
h2{text-align:center;}
input,select{width:100%;padding:8px;margin:6px 0;border-radius:8px;border:1px solid #ccc;}
button{padding:8px 15px;margin:5px;border-radius:20px;border:none;background:#ff9966;color:white;cursor:pointer;}
button:hover{transform:scale(1.1);}
table{width:95%;margin:20px auto;border-collapse:collapse;background:white;
box-shadow:0 10px 25px rgba(0,0,0,0.2);}
th{background:#ff9966;color:white;padding:10px;}
td{padding:8px;text-align:center;border-bottom:1px solid #eee;}
</style>
</head>
<body>

<div class="container">
<h2>Flight Booking System</h2>
<p><?php echo $message;?></p>

<a href="?action=insert"><button>Insert</button></a>
<a href="?action=update"><button>Update</button></a>
<a href="?action=delete"><button>Delete</button></a>

<?php if($action=="insert"){ ?>
<form method="POST">
<input name="passenger_name" placeholder="Passenger Name" required>
<input name="source" placeholder="From" required>
<input name="destination" placeholder="To" required>
<input type="date" name="DOB" required>
<input type="date" name="departure_date" required>
<input type="date" name="arrival_date" required>
<input name="phone" placeholder="Phone Number" required>
<input name="email" placeholder="Email ID" required>
<button name="insert">Submit</button>
</form>
<?php } ?>

<?php if($action=="update"){ ?>
<form method="POST">
<input name="phone" placeholder="Enter Phone Number" required>
<select name="field">
<option value="passenger_name">Passenger Name</option>
<option value="source">From</option>
<option value="destination">To</option>
<option value="DOB">DOB Date</option>
<option value="departure_date">Departure Date</option>
<option value="arrival_date">Arrival Date</option>
<option value="email">Email</option>
</select>
<input name="value" placeholder="New Value" required>
<button name="update">Update</button>
</form>
<?php } ?>

<?php if($action=="delete"){ ?>
<form method="POST">
<input name="phone" placeholder="Enter Phone to Delete" required>
<button name="delete">Delete</button>
</form>
<?php } ?>
</div>

<h2 align="center">Flight Booking Records</h2>
<table>
<tr>
<th>ID</th><th>Name</th><th>From</th><th>To</th>
<th>DOB</th><th>Departure</th><th>Arrival</th>
<th>Phone</th><th>Email</th>
</tr>
<?php while($row=$result->fetch_assoc()){ ?>
<tr>
<td><?= $row['id']?></td>
<td><?= $row['passenger_name']?></td>
<td><?= $row['source']?></td>
<td><?= $row['destination']?></td>
<td><?= $row['DOB']?></td>
<td><?= $row['departure_date']?></td>
<td><?= $row['arrival_date']?></td>
<td><?= $row['phone']?></td>
<td><?= $row['email']?></td>
</tr>
<?php } ?>
</table>
</body>
</html>

