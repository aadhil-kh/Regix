<?php
include_once 'functions.php';
sec_session_start();
if(login_check()==false)
{
   header("Location: login.php");
}
require("dbconnect.php");
try
{
$pdo = new PDO($dsn, $user, $pass, $opt);
$stmt=$pdo->prepare("select name_of_institute,location,regix_id from regix where user_name=?");
$stmt->execute([$_SESSION['user']]);
$row=$stmt->fetch();
$inst_name=$row['name_of_institute'];
$inst_location=$row['location'];
$regix_id=$row['regix_id'];
$stmt=$pdo->prepare("select distinct(department) from ".$regix_id."_department_list");
$stmt->execute();
$dep=[];
$i=0;
while($row=$stmt->fetchColumn())
{
    $dep[$i]=$row;
    $i++;
}
$stud_tot=0;
$staff_tot=0;
foreach($dep as $depo)
{
    $stmt=$pdo->prepare("select count(register_number) from ".$regix_id."_student_list where department=?");
    $stmt->execute([$depo]);
    $stud_count[$depo]=$stmt->fetchColumn();
    $stud_tot+=$stud_count[$depo];
    $stmt=$pdo->prepare("select count(username) from ".$regix_id."_staff_list where department=?");
    $stmt->execute([$depo]);
    $staff_count[$depo]=$stmt->fetchColumn();
    $staff_tot+=$staff_count[$depo];
}
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
                        echo $_SESSION['user'];
                    ?></span></li>
                  <li class="dropdown avatar-dropdown">
                   <img src="asset/img/avatar.jpg" class="img-circle avatar" alt="user name" data-toggle="dropdown" aria-haspopup="true" aria-expanded="true"/>
                   <ul class="dropdown-menu user-dropdown">
                     <li><a href="edit_profile.php"><span class="fa fa-user"></span> MY profile</a></li>
                     <li><a href="edit_calendar.php"><span class="fa fa-calendar"></span> My Calendar</a></li>
                     <li><a href="logout.php"><span class="fa fa-power-off "> logout</span></a></li>
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
                      <h1 class="animated slideInLeft"><?php date_default_timezone_set('Asia/Kolkata');echo date('h:i');?></h1>
                      <p class="animated slideInRight"><?php echo date('D,M d Y');?></p>
                    </li>
                    <li class="active ripple"><a href="admin-panel.php"><span class="fa fa-home"></span>Dashboard</a></li>
                    <li class="ripple"><a href="staff.php"><span class="fa fa-male"></span>Staff List</a></li>
										<li class="ripple"><a href="class_tutor.php"><span class="fa fa-user">Class Tutors</span></a></li>
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
                <div class="panel animated slideInDown">
                  <div class="panel-body">
                      <div class="col-md-6 col-sm-12">
                        <h3><?php echo $inst_name; ?></h3>
                        <p><span class="fa  fa-map-marker"></span> <?php echo $inst_location; ?></p>
                    </div>
                  </div>                    
                </div>
                <div class="col-md-12" style="padding:20px;">
                    <div class="col-md-12 padding-0">
                        <div class="col-md-8 padding-0">
                            <div class="col-md-12 padding-0 animated slideInLeft">
                                <div class="col-md-6">
                                    <div class="panel box-v1">
                                      <div class="panel-heading bg-white border-none">
                                        <div class="col-md-6 col-sm-6 col-xs-6 text-left padding-0">
                                          <h4 class="text-left">Number of Students</h4>
                                        </div>
                                        <div class="col-md-6 col-sm-6 col-xs-6 text-right">
                                           <h4>
                                           <span class="icon-user icons icon text-right"></span>
                                           </h4>
                                        </div>
                                      </div>
                                      <div class="panel-body text-center">
                                        <h1><?php echo $stud_tot; ?></h1>
                                        <hr/>
                                          <a href="#students" class="btn btn-primary btn-round" role="button">
                                              <span class="fa fa-info"></span>
                                              Details >></a>
                                      </div>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="panel box-v1">
                                      <div class="panel-heading bg-white border-none">
                                        <div class="col-md-6 col-sm-6 col-xs-6 text-left padding-0">
                                          <h4 class="text-left">Number of Staffs</h4>
                                        </div>
                                        <div class="col-md-6 col-sm-6 col-xs-6 text-right">
                                           <h4>
                                           <span class="icon-user icons icon text-right"></span>
                                           </h4>
                                        </div>
                                      </div>
                                      <div class="panel-body text-center">
                                        <h1><?php echo $staff_tot; ?></h1>
                                        <hr/>
                                        <a href="#staffs" class="btn btn-primary btn-round" role="button">
                                            <span class="fa fa-info"></span> Details >></a>
                                      </div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-12 animated slideInLeft">
                                <div class="panel box-v4">
                                    <div class="panel-heading bg-white border-none">
                                     <div class="col-md-12 padding-0">
                                        <div class="col-md-9 padding-0">
                                          <h4><span class="fa fa-calendar"></span>  College Events</h4>    
                                    </div>
                                         <div class="col-md-3">
                                              <a href="edit_calendar.php" class="btn btn-primary btn-round" role="button">
                                                  <span class="fa fa-calendar"></span>  Events >>
                                             </a>
                                         </div>
                                        </div>
                                    </div>
                                    <br><br>
                                    <div class="panel-body padding-0">
                                        <div class="calendar">
                              <?php    
                                    $json_array=[];
                                    $stmt=$pdo->prepare("select dat,name,description from ".$regix_id."_events");
                                    $stmt->execute();
                                    $i=0;
                                    while($row=$stmt->fetch())
                                    {
                                        $json_array[$i]["title"]=$row['name'];
                                        $json_array[$i]["start"]=$row['dat'];  
                                        $json_array[$i]["constraint"]='fixed'.$i;
                                        $i++;
                                        $json_array[$i]["id"]='fixed';
                                        $json_array[$i]["start"]=$json_array[$i-1]["start"];
                                        $json_array[$i]["end"]=$json_array[$i-1]["start"];
                                        $json_array[$i]["rendering"]='background';
                                        $i++;
                                    }
                                ?>
                                        </div>
                                    </div>
                                </div> 
                            </div>
                        </div>
                        <div class="col-md-4 animated slideInRight">
                            <div class="col-md-12 padding-0">
                              <div class="panel box-v2">
                                  <div class="panel-heading padding-0">
                                    <img src="asset/img/bg2.jpg" class="box-v2-cover img-responsive"/>
                                    <div class="box-v2-detail">
                                      <img src="asset/img/avatar.jpg" class="img-responsive"/>
                                      <h4><?php echo $_SESSION["regix_id"]; ?> </h4>
                                    </div>
                                  </div>
                                  <div class="panel-body">
                                    <div class="col-md-12 padding-0 text-center">
                                            <a href="edit_profile.php" class="btn btn-primary btn-round btn-round" role="button">
                                                <span class="fa fa-user"></span> Profile >>
                                            </a>
                                    </div>
                                  </div>
                              </div>
                            </div>
                        </div>
                    </div>
                </div>
                
                  <div class="col-md-12 card-wrap padding-0">
                    <div class="col-md-6" id="students">
                        <div class="panel">
                          <div class="panel-heading bg-white border-none" style="padding:20px;">
                            <div class="col-md-6 col-sm-6 col-sm-12 text-left">
                              <h4>Students</h4>
                            </div>
                            </div>
                          <div class="panel-body">
                              <div id="canvas-holder1">
                                <canvas class="stud-chart" height="250px"></canvas>
                              </div>
                          </div>
                        </div>
                    </div>
                      <div class="col-md-6" id="staffs">
                        <div class="panel">
                          <div class="panel-heading bg-white border-none" style="padding:20px;">
                            <div class="col-md-6 col-sm-6 col-sm-12 text-left">
                              <h4>Staffs</h4>
                            </div>
                            </div>
                          <div class="panel-body">
                              <div id="canvas-holder1">
                                <canvas class="staff-chart" height="250px"></canvas>
                              </div>
                          </div>
                        </div>
                    </div>
                </div>
          </div>
          <!-- end: content -->
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
    <script>
    (function(jQuery){
			 $("html").niceScroll();
        var array_json=<?php echo json_encode($json_array)?>;
                
        // start: Chart =============

        Chart.defaults.global.pointHitDetectionRadius = 1;
        Chart.defaults.global.customTooltips = function(tooltip) {

            var tooltipEl = $('#chartjs-tooltip');

            if (!tooltip) {
                tooltipEl.css({
                    opacity: 0
                });
                return;
            }

            tooltipEl.removeClass('above below');
            tooltipEl.addClass(tooltip.yAlign);

            var innerHtml = '';
            if (undefined !== tooltip.labels && tooltip.labels.length) {
                for (var i = tooltip.labels.length - 1; i >= 0; i--) {
                    innerHtml += [
                        '<div class="chartjs-tooltip-section">',
                        '   <span class="chartjs-tooltip-key" style="background-color:' + tooltip.legendColors[i].fill + '"></span>',
                        '   <span class="chartjs-tooltip-value">' + tooltip.labels[i] + '</span>',
                        '</div>'
                    ].join('');
                }
                tooltipEl.html(innerHtml);
            }

            tooltipEl.css({
                opacity: 1,
                left: tooltip.chart.canvas.offsetLeft + tooltip.x + 'px',
                top: tooltip.chart.canvas.offsetTop + tooltip.y + 'px',
                fontFamily: tooltip.fontFamily,
                fontSize: tooltip.fontSize,
                fontStyle: tooltip.fontStyle
            });
        };
        var randomScalingFactor = function() {
            return Math.round(Math.random() * 100);
        };        
        var staffChartData = {
                labels: [ <?php
                    if(count($dep)>0)
                        echo json_encode($dep[0]."(".$staff_count[$dep[0]].")"); 
                    for($i=1;$i<count($dep);$i++)
                    {
                        echo ",";
                        echo json_encode($dep[$i]."(".$staff_count[$dep[$i]].")");
                    } 
                         ?>],
                datasets: [
                    {
                        label: "My First dataset",
                        fillColor: "rgba(0, 0, 0, 0.4)",
                        strokeColor: "rgba(220,220,220,0.8)",
                        highlightFill: "rgba(61, 75, 68, 0.2)",
                        highlightStroke: "rgba(53, 78, 66, 0.2)",
                        data: [<?php 
                        if(count($dep)>0)
                            echo $staff_count[$dep[0]];
                        for($i=1;$i<count($dep);$i++)
                        {
                            echo ",";
                            echo $staff_count[$dep[$i]];
                        }
                        ?>
                        ]
                    }
                ]
            };
        
        var studChartData = {
                labels: [ <?php 
                    if(count($dep>0))
                        echo json_encode($dep[0]."(".$stud_count[$dep[0]].")");
                    for($i=1;$i<count($dep);$i++) 
                        echo ",".json_encode($dep[$i]."(".$stud_count[$dep[$i]].")"); 
                         ?>],
                datasets: [
                    {
                        label: "My First dataset",
                        fillColor: "rgba(0, 0, 0, 0.4)",
                        strokeColor: "rgba(220,220,220,0.8)",
                        highlightFill: "rgba(61, 75, 68, 0.2)",
                        highlightStroke: "rgba(53, 78, 66, 0.2)",
                        data: [<?php 
                        if(count($dep)>0)
                            echo $stud_count[$dep[0]];
                        for($i=1;$i<count($dep);$i++)
                        {
                            echo ",";
                            echo $stud_count[$dep[$i]];
                        }
                        ?>
                        ]
                    }
                ]
            };
         window.onload = function(){
                var ctx2 = $(".staff-chart")[0].getContext("2d");
                window.myLine = new Chart(ctx2).Bar(staffChartData, {
                     responsive: true,
                     showTooltips: true,
                });

                var ctx3 = $(".stud-chart")[0].getContext("2d");
                window.myLine = new Chart(ctx3).Bar(studChartData, {
                     responsive: true,
                     showTooltips: true
                });
            };
        
        //  end:  Chart =============

        // start: Calendar =========
        $('.dashboard .calendar').fullCalendar({
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
    })(jQuery);
        // end : Calendar==========      
    </script>
</body>
</html>
