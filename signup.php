<?php
include 'conn.php';
if(isset($_POST['submit']))
{
$username=$_POST['username'];
$pass=$_POST['pass'];
$pass2=$_POST['pass2'];
if($pass!=$pass2)
{
	echo "<script>alert('The passwords do not match');</script>";
}
else
{
	$sql="INSERT INTO users (user_name,pwd) VALUES ('".$username."','".md5($pass)."')";
	$conn->query($sql);
	header("location: login.php?msg=1");
}

$conn->close();
}
?>
<!DOCTYPE html>
<html lang='en'>
<head>
  <title>Sentiment/signup</title>
  <meta charset='utf-8'>
  <meta name='viewport' content='width=device-width, initial-scale=1'>
  <link rel='stylesheet' href='http://maxcdn.bootstrapcdn.com/bootstrap/3.3.6/css/bootstrap.min.css'>
  <script src='https://ajax.googleapis.com/ajax/libs/jquery/1.12.0/jquery.min.js'></script>
  <script src='http://maxcdn.bootstrapcdn.com/bootstrap/3.3.6/js/bootstrap.min.js'></script>
</head>
<body>
<nav class="navbar navbar-inverse">
  <div class="container-fluid">
    <div class="navbar-header">
      <a class="navbar-brand"><span style='color:lightblue'>Sentiment Analysis</span></a>
    </div>
    <ul class="nav navbar-nav">
      <li><a href="login.php">Login</a></li>
      <li class="active"><a>Signup</a></li>
    </ul>
  </div>
</nav>
<div class='container'>
<div class='row'>
	<div class='col-sm-3'>
	</div>
	<div class='col-sm-6'>
		<form role='form' method='POST' action='signup.php'>
		  <div class='form-group'>
			<label for='username'>Username: </label>
			<input type='text' class='form-control' id='username' name='username'>
		  </div>
		  <div class='form-group'>
			<label for='pass'>Password: </label>
			<input type='password' class='form-control' id='pass' name='pass'>
		  </div>
		  <div class='form-group'>
			<label for='pass2'>Re-Type Password: </label>
			<input type='password' class='form-control' id='pass2' name='pass2'>
		  </div>
		  <div class='form-group'>
		    <input style='background:black;color:white;' type='submit' class='form-control' value='Submit' name='submit'>
		  </div>
		</form>
	</div>
	<div class='col-sm-3'>
	</div>
</div>
</div>
</body>
</html>