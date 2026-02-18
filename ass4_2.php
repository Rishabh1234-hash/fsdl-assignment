<?php
$conn = new mysqli("localhost","root","__","fullstackdev");
if($conn->connect_error){ die("Connection failed"); }

$message="";
$action=isset($_GET['action'])?$_GET['action']:"";

/* INSERT */
if(isset($_POST['insert'])){
    $name=$_POST['book_name'];
    $isbn=$_POST['isbn'];
    $title=$_POST['book_title'];
    $author=$_POST['author_name'];
    $publisher=$_POST['publisher_name'];

    if(!preg_match("/^[0-9\-]+$/",$isbn)){
        $message="Invalid ISBN format!";
    } else{
        $sql="INSERT INTO books(book_name,isbn,book_title,author_name,publisher_name)
              VALUES('$name','$isbn','$title','$author','$publisher')";
        $conn->query($sql);
        $message="Book inserted successfully!";
    }
}

/* DELETE */
if(isset($_POST['delete'])){
    $isbn=$_POST['isbn'];
    $conn->query("DELETE FROM books WHERE isbn='$isbn'");
    $message="Book deleted successfully!";
}

/* UPDATE */
if(isset($_POST['update'])){
    $isbn=$_POST['isbn'];
    $field=$_POST['field'];
    $value=$_POST['value'];

    $conn->query("UPDATE books SET $field='$value' WHERE isbn='$isbn'");
    $message="Book updated successfully!";
}

$result=$conn->query("SELECT * FROM books");
?>

<!DOCTYPE html>
<html>
<head>
<title>Library Management</title>
<style>
body{font-family:Segoe UI;background:linear-gradient(120deg,#f6d365,#fda085);padding:20px;}
.container{width:520px;margin:auto;background:white;padding:25px;border-radius:20px;
box-shadow:0 10px 30px rgba(0,0,0,0.2);}
h2{text-align:center;}
input,select{width:100%;padding:8px;margin:6px 0;border-radius:8px;border:1px solid #ccc;}
button{padding:8px 15px;margin:5px;border-radius:20px;border:none;background:#ff6a00;color:white;cursor:pointer;}
button:hover{transform:scale(1.1);}
table{width:95%;margin:20px auto;border-collapse:collapse;background:white;
box-shadow:0 10px 25px rgba(0,0,0,0.2);}
th{background:#ff6a00;color:white;padding:10px;}
td{padding:8px;text-align:center;border-bottom:1px solid #eee;}
</style>
</head>
<body>

<div class="container">
<h2>Library Management</h2>
<p><?php echo $message;?></p>

<a href="?action=insert"><button>Insert</button></a>
<a href="?action=update"><button>Update</button></a>
<a href="?action=delete"><button>Delete</button></a>

<?php if($action=="insert"){ ?>
<form method="POST">
<input name="book_name" placeholder="Book Name" required>
<input name="isbn" placeholder="ISBN No" required>
<input name="book_title" placeholder="Book Title" required>
<input name="author_name" placeholder="Author Name" required>
<input name="publisher_name" placeholder="Publisher Name" required>
<button name="insert">Submit</button>
</form>
<?php } ?>

<?php if($action=="update"){ ?>
<form method="POST">
<input name="isbn" placeholder="Enter ISBN" required>
<select name="field">
<option value="book_name">Book Name</option>
<option value="book_title">Book Title</option>
<option value="author_name">Author Name</option>
<option value="publisher_name">Publisher Name</option>
</select>
<input name="value" placeholder="New Value" required>
<button name="update">Update</button>
</form>
<?php } ?>

<?php if($action=="delete"){ ?>
<form method="POST">
<input name="isbn" placeholder="Enter ISBN to Delete" required>
<button name="delete">Delete</button>
</form>
<?php } ?>
</div>

<h2 align="center">Book Records</h2>
<table>
<tr>
<th>ID</th><th>Book Name</th><th>ISBN</th>
<th>Title</th><th>Author</th><th>Publisher</th>
</tr>
<?php while($row=$result->fetch_assoc()){ ?>
<tr>
<td><?= $row['id']?></td>
<td><?= $row['book_name']?></td>
<td><?= $row['isbn']?></td>
<td><?= $row['book_title']?></td>
<td><?= $row['author_name']?></td>
<td><?= $row['publisher_name']?></td>
</tr>
<?php } ?>
</table>
</body>
</html>

