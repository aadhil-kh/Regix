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
     <link rel="stylesheet" type="text/css" href="asset/css/plugins/datatables.bootstrap.min.css"/>
      <link rel="stylesheet" type="text/css" href="asset/css/plugins/fullcalendar.min.css"/>
      <link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/buttons/1.2.4/css/buttons.dataTables.min.css"/>
    
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
                    <li class="active ripple"><a href="staff.php"><span class="fa fa-male"></span>Staff List</a></li>
                    <li class="ripple"><a href="department.php"><span class="fa fa-book"></span>Departments</a></li>
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
                          <h3 class="animated fadeInLeft"><span class="fa fa-male"></span>staff</h3>
                    </div>
                  </div>                    
                </div>
                <div class="col-md-12">
                <div class="dropdown col-md-2">
                        <button class="dropdown-toggle btn btn-primary" id="area" data-toggle="dropdown">Add Staff<span class="caret"></span></button>
                        <ul class="dropdown-menu dropdown-lr animated flipInX" role="menu">
                            <div class="col-lg-12">
                                <div class="text-center"><h3><b>Add Staff</b></h3></div>
									<div class="form-group">
										<input type="text" name="staffname" id="staffname" tabindex="1" class="form-control" placeholder="Staff name" value="">
									</div>
                                    <div class="form-group">
                                        Duration:
                                        <select name="departmentname" id="dep" class="form-control">
                                            <?php
                                            try 
                                            {
                                                $stmt= $pdo->prepare("select distinct department from ".$regix_id."_department_list");
                                                $stmt->execute();
                                                while($row=$stmt->fetchColumn())
                                                {
                                                    echo "<option>".$row."</option>";
                                                }
                                            }
                                            catch(PDOException $e)
                                            {
                                                echo $e;
                                            }
                                            ?>
                                        </select>
                                    </div> 
                                    <div class="form-group">
										<div class="row">
											<div class="col-xs-6 col-xs-offset-3">
                                                <button id="addstaff" tabindex="4" class="form-control btn btn-info">Add</button>
											</div>
										</div>
									</div>
				            </div>
                        </ul>
                    </div>
                    <div class="col-md-2">
                        <a href="edit_staff.php">
                            <button class="btn btn-primary" id="edit_staff">Edit/Import</button>
                        </a>
                    </div>
                </div>
                <br><br><br>
                <div class="col-md-12">
                    <div class="panel">
                        <div class="panel-heading"><h3>Staff List</h3></div>
                        <div class="panel-body">
                            <div class="responsive-table">
                                <table id="datatabl" class="table table-striped table-bordered table-hover">
                                <thead>
                                    <tr>
                                        <th>Staff Name</th>
                                        <th>Department</th>
                                        <th>Regix_staff_id</th>
                                        <th>Password</th>
                                    </tr>    
                                </thead>
                                    <tbody>
                            <?php
                                $stm=$pdo->prepare("select * from ".$regix_id."_staff_list");
                                $stm->execute();
                                while($row=$stm->fetch())
                                {
                                    echo "<tr>";
                                    echo "<td>".$row['name']."</td>";
                                    echo "<td>".$row['department']."</td>";
                                    echo "<td>".$row['username']."</td>";
                                    echo "<td>".$row['password']."</td>";
                                    echo "</tr>";
                                }
                            ?>  
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
     </div>
     </div>
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
     <script src="asset/js/plugins/jquery.datatables.min.js"></script>
    <script src="asset/js/plugins/datatables.bootstrap.min.js"></script>
    <script src="asset/js/plugins/jquery.nicescroll.js"></script>
      <script src="https://cdn.datatables.net/buttons/1.2.4/js/dataTables.buttons.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jszip/2.5.0/jszip.min.js"></script>
    <script src="https://cdn.rawgit.com/bpampuch/pdfmake/0.1.18/build/pdfmake.min.js"></script>
    <script src="https://cdn.rawgit.com/bpampuch/pdfmake/0.1.18/build/vfs_fonts.js"></script>
    <script src="https://cdn.datatables.net/buttons/1.2.4/js/buttons.html5.min.js"></script>
     <script>
          $(document).ready(function(){
						 $("html").niceScroll();
               $('#datatabl').DataTable({
                                    "dom": 'Bfrtip',
                                    "buttons":[
                                       "copyHtml5",
                                        {
                                           extend: 'pdfHtml5',
                                           text: 'Download as PDF'
                                        },
                                        {
                                           extend: 'excelHtml5',
                                           text: 'Download as XLS'
                                        },
                                        {
                                           extend: 'csvHtml5',
                                           text: 'Download as CSV'
                                        }
                                    ]
                                });
              $('.dropdown-menu').on('click', function(event) {
                    event.stopPropagation();
              });
              $('body').on('click', function(event) {
                  var target = $(event.target);
                  if (target.parents('.bootstrap-select').length) {
                  event.stopPropagation();
                  $('.bootstrap-select.open').removeClass('open');
             }
             }); 
             $('#addstaff').click(function(){
                 var regix=<?php echo json_encode($regix_id)?>;
                 $.ajax({
				        type:'POST',
                        url:'add_staff.php',
                        data:
				        {
                            regix_id:regix,
                            department:$('#dep').val(),
                            staff:$('#staffname').val()
                        },
				        success:function(response)
                        {
                            window.location="staff.php";
                        } 
                    });
             });
          });
     </script>
    </body>
</html>
