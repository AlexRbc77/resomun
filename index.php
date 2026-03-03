<html>
<?php session_start(); include("database.php");?>
<head>
<link rel='stylesheet' href='style.css' type='text/css'>
<title>ResoMUN</title>
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
<?php echo $_SESSION['email'] != NULL ? "<p id='user_info'> Logged in as ".$_SESSION['firstname']." ".$_SESSION['lastname']."</p>": "Not logged in";?>
</header>
<body>

<center>
<h2 style="color: darkblue;font-size: 2em;"> Hello from ResoMUN </h2><br>
<i><p3>Official ResoMUN app coming soon</p3></i><br>
<div style='overflow:auto;width:50%;'>
    <div style='float:left;border: 2px solid blue; margin: 2%; width:45%;display:inline-block;padding-bottom:5px;'>
    <h3> Returning user? </h3>
        <?php button_link("login.php", "Login");?>
    </div>
    <div style='clear:left;border: 2px solid green; margin: 2%; width:45%;display:inline-block;padding-bottom:5px;'>
    <h3> New user ? </h3>
        <?php button_link("create_account.php", "Create account");?><br>
    </div>
</div>
<div style='border: 2px solid red; width:23%;padding-bottom:5px;'>
<h3> Already logged in? </h3>
<?php button_link("myresomun.php", "My ResoMUN");?>
</div>
<h2 style='color: darkgreen;font-size: 2em; font-weight:bold;'> What is ResoMUN? </h2>
<p2 style='font-size: 1.2em; width:25%;'> Have you ever been bothered by the fact that you needed several websites / software when you edited and shared your resolutions? Google drive and emails or facebook groups? Not just that
    but there's always that one person that doesn't have / want one for paranoia reasons. With ResoMUN, you can get all of those in 1 place in your web browser. </p2>
    <h3 style='font-size: 1.3em; font-weight:bold;'> With ResoMUN you can: </h3>
    <ul style="list-style:none;">
        <li> Create Resolutions </li>
        <li> Edit Resolutions </li>
        <li> Share Resolutions </li>
        <li> Sign Resolutions </li>
        <li> Print Resolutions as PDF files (with signatures, it looks really cool) </li>
</ul>

<p1 style='font-size: 2em; width:25%;'> So what are you waiting for? Create an account and get started!</p1>
<br>
<a href='ResoMUN_FINAL.apk' download> Download the Android app unofficially </a>
<br>
</center>

<a href='mailto:alexandrerobic312@gmail.com?subject=Forgot Password&body=The user by this current email has forgotten his/her password for ResoMUN. Please help. Sincerely Mr or Mrs LostMyPassword'> I lost my password </a>
</body>

<center><a href='documentation.pdf'><h3>How to use ResoMUN</h3></a></center>

<h4>Things to work on</h4>
<ul>
    <li>Publish app on android and ios</li>
    <li>Auto delete resolutions with empty titles</li>
    <li>Admin page</li>
</ul>

<footer>
<center>
<?php echo(date("D M dS Y"));?>
</center>
</footer>

</html>