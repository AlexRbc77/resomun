<html>
<?php session_start(); include("database.php");?>
<head>
<link rel='stylesheet' href='style.css' type='text/css'>
<title>Logged off</title>
</head>
<header id='header'>
<center>
<h1> Resolution Edition Software Of MUN </h1>
</center>
<img src='resomun_logo.png' id='logo' style='position:absolute;right:10px;overflow:auto;top:10px;'>
<script type='text/javascript'>
var logo = document.getElementById('logo');
var header = document.getElementById('header');
logo.setAttribute('height', header.scrollHeight);
</script>
<?php echo $_SESSION['email'] != NULL ? "<p id='user_info'> Logged in as ".$_SESSION['firstname']." ".$_SESSION['lastname']."</p>" : "Not logged in";?>
</header>
<body>


<?php session_destroy(); echo "<p style='color:green;'> User logged off </p>";
gotolink("index.php");
?>



</body>


<footer>
<center>
<?php echo(date("D M dS Y"));?>
</center>
</footer>

</html>