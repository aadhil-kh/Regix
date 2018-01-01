<?php
include_once 'functions.php';
sec_session_start();
if(staff_login_check()==false)
{
	header("Location: staff_login.php");
}
require("dbconnect.php");
try
{
$pdo = new PDO($dsn, $user, $pass, $opt);
$stmt=$pdo->prepare("select name_of_institute,location from regix where regix_id=?");
$stmt->execute([$_SESSION['regix_id']]);
$row=$stmt->fetch();
$inst_name=$row['name_of_institute'];
$inst_location=$row['location'];
}
catch(PDOException $p)
{
    echo "error ";
}
?>
<!DOCTYPE html>

<html lang="en">
<head>
	<meta charset="utf-8">
	<meta name="description" content="Admin panel">
	<meta name="author" content="Digital_Brains">
	<meta name="keyword" content="none">
	<meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Admin panel</title>
    <link rel="stylesheet" type="text/css" href="asset/css/bootstrap.min.css">
      <link rel="stylesheet" type="text/css" href="asset/css/plugins/font-awesome.min.css"/>
      <link rel="stylesheet" type="text/css" href="asset/css/plugins/simple-line-icons.css"/>
      <link rel="stylesheet" type="text/css" href="asset/css/plugins/animate.min.css"/>
      <link rel="stylesheet" type="text/css" href="asset/css/plugins/fullcalendar.min.css"/>
	<link href="asset/css/style.css" rel="stylesheet">
	<link rel="shortcut icon" href="asset/img/logomi.png">
  </head>
 <body id="mimin" class="dashboard">
      <!-- start: Header -->
        <nav class="navbar navbar-default header navbar-fixed-top">
          <div class="col-md-12 nav-wrapper">
            <div class="navbar-header" style="width:100%;">
                <img src="asset/img/logo.png">
                <a href="index.html" class="navbar-brand"> 
                 <b>Regix</b>
                </a>
              <ul class="nav navbar-nav navbar-right user-nav">
                <li class="user-name"><span>
                    <?php 
                        echo $_SESSION['regix_staff_id'];
                    ?></span></li>
                  <li class="dropdown avatar-dropdown">
                   <img src="asset/img/avatar.jpg" class="img-circle avatar" alt="user name" data-toggle="dropdown" aria-haspopup="true" aria-expanded="true"/>
                   <ul class="dropdown-menu user-dropdown">
                     <li><a href="edit_profile.php"><span class="fa fa-user"></span> MY profile</a></li>
                     <li><a href="logout.php"><span class="fa fa-power-off "> logout</span></a></li>
                  </ul>
                </li>
              </ul>
            </div>
          </div>
        </nav>
      <div class="container-fluid mimin-wrapper">
  				<div id="left-menu">
              <div class="sub-left-menu scroll">
                <ul class="nav nav-list">
                    <li class="time">
                      <h1 class="animated slideInLeft"><?php date_default_timezone_set('Asia/Kolkata');echo date('h:i');?></h1>
                      <p class="animated slideInRight"><?php echo date('D,M d Y');?></p>
                    </li>
                    <li class="active ripple"><a href="staff_panel.php"><span class="fa fa-home"></span>Dashboard</a></li>
                    <li class="ripple"><a href="staff_test.php"><span class="fa fa-male"></span>Tests</a></li>
                    </ul>
                </div>
            </div>
            <div id="content">
                
            </div>
    <script src="asset/js/jquery.min.js"></script>
    <script src="asset/js/jquery.ui.min.js"></script>
    <script src="asset/js/bootstrap.min.js"></script>
    <script src="asset/js/plugins/moment.min.js"></script>
    <script src="asset/js/plugins/fullcalendar.min.js"></script>
    <script src="asset/js/plugins/jquery.nicescroll.js"></script>
    <script src="asset/js/plugins/jquery.vmap.min.js"></script>
    <script src="asset/js/main.js"></script>
    <script>
			$(document).ready(function(){
				 $("html").niceScroll();
			});
		</script>
</body>
</html>
