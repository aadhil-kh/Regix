<?php
include_once 'functions.php';
sec_session_start();
if(login_check()==false)
{
   header("Location: login.php");
}
require('dbconnect.php')
$dep = $_GET['dep'];
try{
$pdo = new PDO($dsn, $user, $pass, $opt);
$stmt=$pdo->prepare("select name_of_institute,location,regix_id from regix where user_name=?");
$stmt->execute([$_SESSION['user']]);
$row=$stmt->fetch();
$inst_name=$row['name_of_institute'];
$inst_location=$row['location'];
$regix_id=$row['regix_id'];
}
catch(PDOException $p)
{
    echo "error ".$p;
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
    <title>Departments</title>
    <link rel="stylesheet" type="text/css" href="asset/css/bootstrap.min.css">
      <link rel="stylesheet" type="text/css" href="asset/css/plugins/font-awesome.min.css"/>
      <link rel="stylesheet" type="text/css" href="asset/css/plugins/simple-line-icons.css"/>
      <link rel="stylesheet" type="text/css" href="asset/css/plugins/animate.min.css"/>
      <link rel="stylesheet" type="text/css" href="asset/css/plugins/fullcalendar.min.css"/>
	
			<link rel="stylesheet" type="text/css" href="asset/css/plugins/hover.min.css"/>
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
                        echo $_SESSION['user'];
                    ?></span></li>
                  <li class="dropdown avatar-dropdown">
                   <img src="asset/img/avatar.jpg" class="img-circle avatar" alt="user name" data-toggle="dropdown" aria-haspopup="true" aria-expanded="true"/>
                   <ul class="dropdown-menu user-dropdown">
                     <li><a href="edit_profile.php"><span class="fa fa-user"></span>MY profile</a></li>
                     <li><a href="edit_calendar.php"><span class="fa fa-calendar"></span>My Calendar</a></li>
                     <li><a href="logout.php"><span class="fa fa-power-off ">logout</span></a></li>
                  </ul>
                </li>
              </ul>
            </div>
          </div>
        </nav>
      <!-- end: Header -->
      <div class="container-fluid mimin-wrapper">
          <!-- start:Left Menu -->
            <div id="left-menu">
              <div class="sub-left-menu scroll">
                <ul class="nav nav-list">
                    <li class="time">
                      <h1 class="animated fadeInLeft"><?php date_default_timezone_set('Asia/Kolkata');echo date('h:i');?></h1>
                      <p class="animated fadeInRight"><?php echo date('D,M d Y');?></p>
                    </li>
                    <li class="ripple"><a href="admin-panel.php"><span class="fa fa-home"></span>Dashboard</a></li>
                    <li class="ripple"><a href="staff.php"><span class="fa fa-male"></span>Staff List</a></li>
                    <li class="active ripple"><a href="department.php"><span class="fa fa-book"></span>Departments</a></li>
                    <li class="ripple"><a href="class_report.php"><span class="fa fa-area-chart"></span>Class Report</a></li>
                    <li class="ripple"><a href="student_report.php"><span class="fa fa-bar-chart"></span>Student Report</a></li>
                    <li class="ripple"><a href="generate_report.php"><span class="fa fa-line-chart"></span>Generate Report</a></li>
                    <li class="ripple"><a href="edit_calendar.php"><span class="fa fa-calendar"></span>Events</a></li>
                    <li class="ripple"><a href="edit_profile.php"><span class="fa fa-user"></span>Profile</a></li>
                    
                    <li class="ripple"><a href="credits.php"><span class="fa fa-users"></span>Credits</a></li>                
                  </ul>
                </div>
            </div>
          <!-- end: Left Menu -->
          <!-- start: content -->
            <div id="content">
                <div class="panel">
                  <div class="panel-body">
                      <div class="col-md-6 col-sm-12">
                          <h3 class="animated fadeInLeft"><span class="fa fa-book"></span>Departments</h3>
                    </div>
                  </div>                    
                </div>
                <div class="col-md-12">
                    <div class="panel">
                        <div class="panel-heading"><h3><?php echo $dep;?></h3></div>
                        <div class="panel-body">
                             <?php
                            try 
                            {
                                    $stmt= $pdo->prepare("select count(distinct year) from ".$regix_id."_department_list where department=?");
                                    $stmt->execute([$dep]);
                                    $cnt=$stmt->fetchColumn();
                                    $stmt= $pdo->prepare("select distinct year from ".$regix_id."_department_list where department=?");
                                    $stmt->execute([$dep]);
                                    for($i=0;$i<$cnt;$i++)
                                    { 
                                        if($i==0)
                                            echo "<div class='col-md-12'>";
                                        else if($i%4==0)
                                            echo "</div><div class='col-md-12'>";
                                        $row=$stmt->fetch();
                                        echo "<a href='class.php?dep=".$dep."&year=".$row['year']."'><div class='outer col-md-3'>
																				<div class='animated fadeInLeft col-md-12 divelement hvr-sweep-to-right'>  
                                              <span><b>Year:".$row['year']."</b></span></div></div></a>";
                                    }
                                    echo "</div>";
                            }
                            catch(PDOException $e)
                            {
                                echo $e;
                            }
                            finally
                            {
                                $pdo=null;
                            }
                        ?>
                        </div>
                    </div>
                </div>
                   
                   
     </div>
     </div>
     <script src="asset/js/jquery.min.js"></script>
    <script src="asset/js/jquery.ui.min.js"></script>
    <script src="asset/js/bootstrap.min.js"></script>
    <script src="asset/js/plugins/moment.min.js"></script>
    <script src="asset/js/plugins/jquery.nicescroll.js"></script>
    <script src="asset/js/main.js"></script>
	 <script>
	 		$(document).ready(function(){
				 $("html").niceScroll();
			});
	 </script>
    </body>
</html>
