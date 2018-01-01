<?php
include_once 'functions.php';
sec_session_start();
if(login_check()==false)
{
   header("Location: login.php");
}
require('dbconnect.php');
try{
$pdo = new PDO($dsn, $user, $pass, $opt);
$stmt=$pdo->prepare("select regix_id from regix where user_name=?");
$stmt->execute([$_SESSION['user']]);
$row=$stmt->fetch();
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
    <title>Events</title>
    <link rel="stylesheet" type="text/css" href="asset/css/bootstrap.min.css">
      <link rel="stylesheet" type="text/css" href="asset/css/plugins/font-awesome.min.css"/>
      <link rel="stylesheet" type="text/css" href="asset/css/plugins/simple-line-icons.css"/>
      <link rel="stylesheet" type="text/css" href="asset/css/plugins/animate.min.css"/>
      <link rel="stylesheet" type="text/css" href="asset/css/plugins/fullcalendar.min.css"/>
      <link rel="stylesheet" type="text/css" href="asset/css/plugins/datatables.bootstrap.min.css"/>
	<link href="asset/css/style.css" rel="stylesheet">
	<link rel="shortcut icon" href="asset/img/logomi.png">
    <style>
        
ul.dropdown-lr {
  width: 300px;
}
    </style>
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
                    <li class="ripple"><a href="department.php"><span class="fa fa-book"></span>Departments</a></li>
                    <li class="ripple"><a href="class_report.php"><span class="fa fa-area-chart"></span>Class Report</a></li>
                    <li class="ripple"><a href="student_report.php"><span class="fa fa-bar-chart"></span>Student Report</a></li>
                    <li class="ripple"><a href="generate_report.php"><span class="fa fa-line-chart"></span>Generate Report</a></li>
                    <li class="active ripple"><a href="edit_calendar.php"><span class="fa fa-calendar"></span>Events</a></li>
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
                      <div class="col-md-12">
                        <h3 class="animated fadeInLeft"><span class="fa fa-calendar"></span>Events</h3>
                    </div>
                  </div>
                </div>
                <div class="col-md-12">
                <div class="dropdown">
                        <button class="dropdown-toggle btn btn-primary" data-toggle="dropdown">Add Events <span class="caret"></span></button>
                        <ul class="dropdown-menu dropdown-lr animated flipInX" role="menu">
                            <div class="col-lg-12">
                                <div class="text-center"><h3><b>Add Events</b></h3></div>
									<div class="form-group">
										<input type="text" name="eventname" id="eventname" tabindex="1" class="form-control" placeholder="Event Name" value="">
									</div>
                                    <div class="form-group">
                                        <textarea rows="3" style="width:100%;" id="eventdescription" placeholder="Event Description" required></textarea>
                                </div>
									<div class="form-group">
										<input type="date" name="eventdate" id="eventdate" tabindex="2" class="form-control" placeholder="date">
									</div>
									<div class="form-group">
										<div class="row">
											<div class="col-xs-6 col-xs-offset-3">
                                                <button id="addevent" tabindex="4" class="form-control btn btn-info">Add Event</button>
											</div>
										</div>
									</div>
				            </div>
                        </ul>
                    </div>
          </div>
                <br><br><br>
                    <div class="col-md-12">
                        <div class="panel">
                            <div class="panel-heading">
                                <h3>List of events</h3>
                            </div>
                            <div class='panel-body'>
                                <?php    
                                    $json_array=[];
                                    $stmt=$pdo->prepare("select dat,name,description from ".$regix_id."_events");
                                    $stmt->execute();
                                    if($stmt->rowCount()==0)
                                    {
                                        echo "<h5>No events added</h5>";
                                    }
                                    else
                                    {
                                        echo "<div class='responsive-table'>
                                        <table id='datatabl' class='table table-striped table-bordered table-hover' width='100%' cellspacing='0'>
                                            <thead><tr><th>Date</th><th>Event Name</th><th>Description</th></tr></thead><tbody>";
                                        $i=0;
                                        while($row=$stmt->fetch())
                                        {
                                            $json_array[$i]["title"]=$row['name'];
                                            $json_array[$i]["start"]=$row['dat'];  
                                            echo "<tr><td>".$row['dat']."</td><td>".$row['name']."</td><td>".$row['description']."</td></tr>"; 
                                            $i++;
                                        }        
                                        echo "</tbody></table></div>";
                                    }
                                ?>
                            </div>
                        </div>
                    </div>
                <div class="col-md-12">
                    <div class="col-md-2">
                    
                    </div>
                    <div class="col-md-8">
                <div class="panel">
                    <div class="panel-heading"><h3>Events Calendar</h3></div>
                    <div class="panel-body">
                        <div id="calendar">
                        </div>
                    </div>
                </div>
                    </div>
                    <div class="col-md-2">
                    
                    </div>
                </div>
                    
            </div>
          </div>
          <!-- end: content -->
     <script src="asset/js/jquery.min.js"></script>
    <script src="asset/js/jquery.min.js"></script>
    <script src="asset/js/jquery.ui.min.js"></script>
    <script src="asset/js/bootstrap.min.js"></script>
    <script src="asset/js/plugins/moment.min.js"></script>
    <script src="asset/js/plugins/fullcalendar.min.js"></script>
    <script src="asset/js/plugins/jquery.nicescroll.js"></script>
    <script src="asset/js/plugins/jquery.vmap.min.js"></script>
    <script src="asset/js/plugins/maps/jquery.vmap.world.js"></script>
    <script src="asset/js/plugins/jquery.vmap.sampledata.js"></script>
    <script src="asset/js/plugins/chart.min.js"></script>
    <script src="asset/js/main.js"></script>
    <script src="asset/js/plugins/jquery.datatables.min.js"></script>
    <script src="asset/js/plugins/datatables.bootstrap.min.js"></script>
     <script>
         var array_json=<?php echo json_encode($json_array)?>;
         $(document).ready(function(){
             $('#datatabl').DataTable();
             $('#addevent').click(function(){
                 var regix=<?php echo json_encode($regix_id)?>;
                 $.ajax({
				        type:'POST',
                        url:'add_event.php',
                        data:
				        {
                            regix_id:regix,
                            event:$('#eventname').val(),
                            event_description:$('#eventdescription').val() ,
                            event_date:$('#eventdate').val()
                        },
				        success:function(response)
                        {
                            window.location="edit_calendar.php";
                        } 
                    });
             });
        $('#calendar').fullCalendar({
            header: {
                left: 'prev,next today',
                center: 'title',
                right: 'month,agendaWeek,agendaDay'
            },
            defaultDate: <?php  
             $today = date("Y-m-d");
             echo json_encode($today);
             ?>,
            businessHours: true, // display business hours
            editable: true,
            events: array_json
        }); 
         });
     </script>
</body>
</html>
