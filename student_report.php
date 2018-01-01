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
    <title>Student Report</title>
    <link rel="stylesheet" type="text/css" href="asset/css/bootstrap.min.css">
      <link rel="stylesheet" type="text/css" href="asset/css/plugins/font-awesome.min.css"/>
      <link rel="stylesheet" type="text/css" href="asset/css/plugins/simple-line-icons.css"/>
      <link rel="stylesheet" type="text/css" href="asset/css/plugins/animate.min.css"/>
      <link rel="stylesheet" type="text/css" href="asset/css/plugins/datatables.bootstrap.min.css"/>
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
                    <li class="active ripple"><a href="student_report.php"><span class="fa fa-bar-chart"></span>Student Report</a></li> 
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
                        <h3 class="animated slideInDown"><span class="fa fa-bar-chart"></span>Student report</h3>
                    </div>
                  </div>                    
                </div>
                <div class="col-md-12">
                    <div class="panel  animated slideInLeft">
                        <div class="panel-body">
                            <div class="col-md-12 padding-0">
                    <div class="col-md-4 padding-0" >
                        <lable><b>Department:</b></lable>
                        <select name="dep" id="dep_select" onchange="fetch_ajax('dep','year',this.value);">
                            <option value="empty" selected></option>
                            <?php
                              try 
                              {
                                  $stmt=$pdo->prepare("select distinct(department) from ".$regix_id."_department_list ");
                                  $stmt->execute();
                                  $i=0;
                                  while($row=$stmt->fetchColumn())
                                  { 
                                      $yx[$i]=$row;
                                      $i++;   
                                  }
                              }
                              catch(PDOException $e)
                              {
                                  echo "error somewhere here ";
                              }
                              for($i=0;$i<count($yx);$i++)
                              {
                                  ?>
                                        <option value=<?php echo $yx[$i];?>><?php echo $yx[$i]; ?></option>
                                  <?php
                              }
                            ?>
                        </select>
                    </div>
                    <div class="col-md-2 padding-0">
                          <lable><b>Year:</b></lable>
                          <select id="year_select" name="yer" onchange="fetch_ajax('year','section',this.value);">
                              <option value="empty" selected></option>
                          </select>
                    </div>
                    <div class="col-md-2 padding-0">
                          <lable><b>Section:</b></lable>
                          <select id="section_select" name="sec" onchange="fetch_ajax('section','reg',this.value);">
                            <option value="empty" selected></option>
                          </select>
                    </div> 
                   <div class="col-md-4 padding-0">
                          <lable><b>Register number:</b></lable>
                          <select id="reg_select" name="sub">
                            <option value="empty" selected></option>
                          </select>
                    </div>
                        </div><br><br><br>
                <div class="col-md-12 padding-0">
                         <div class="col-md-3 padding-0">
                             <b>Overall Report:</b><input type="checkbox" id="ch" value="all" name="all">
                         </div>
                         <div id="date">
                            <div class="col-md-3 padding-0">
                                <b>From:</b><input type="date" id="day1" name="fday">
                            </div>
                            <div class="col-md-3 padding-0">
                                <b>To:</b><input type="date" id="day2" name="tday">
                            </div>
                         </div>
                        <div class="col-md-3 padding-0">
                            <button class="btn btn-primary" id="mybtn">Apply</button>
                        </div>
                    </div>
                    <br>
                    <br>
                    </div>
                    
                    </div>
                <div id="table_fr" class='col-md-12 padding-0'>
                
                </div>
                    
                      </div>
                      <br><br><br>
                </div>   
          </div>
          <!-- end: content -->
    <script src="asset/js/jquery.min.js"></script>
     <script>
         $(document).ready(function(){
					  $("html").niceScroll();
            $('#mybtn').click(function(){
                var regix=<?php echo json_encode($regix_id)?>;
                if($('#year_select').val()=="empty")
                    alert("select year");
                
                else if($('#dep_select').val()=="empty")
                    alert("select dep");
                
                else if($('#section_select').val()=="empty")
                    alert("select section");
                
                else if($('#reg_select').val()=="empty")
                    alert("select register number");
                else if(!$('#ch').is(":checked"))
                {
                    if(!$('#day1').val()||!$('#day2').val())
                    {
                        alert("select from and to date");
                    }
                    else
                    {
                        $.ajax({
                            type:'POST',
                            url:'table_student_report.php',
                            data:
                            {
                                regix_id:regix,
                                dep:$('#dep_select').val(),
                                yer:$('#year_select').val(),
                                all:0,
                                sec:$('#section_select').val(),
                                reg:$('#reg_select').val(),
                                fday:$('#day1').val(),
                                tday:$('#day2').val()
                            },
                            success:function(response)
                            {
                                $('#table_fr').html(response);
                                $('#datatabl').DataTable({'scrollX':true});
                              var pre=parseInt($('#pre').html());
                                var abs=parseInt($('#abs').html());
                                var odp=parseInt($('#odp').html());
                                var chartdata = [
                                {
                                    value: pre,
                                    color:"#0cbf65",
                                    highlight: "#15BA67",
                                    label: "Present"
                                },
                                {
                                    value: abs,
                                    color: "rgb(255, 0, 0)",
                                    highlight: "#f21954",
                                    label: "Absent"
                                },
                                {
                                    value: odp,
                                    color: "#004eff",
                                    highlight: "#1a88f7",
                                    label: "OD"
                                }
                                ];
                                var ctx2 = $(".pie-chart")[0].getContext("2d");
                                    window.myPie = new Chart(ctx2).Pie(chartdata, {
                                    showTooltips: true
                                    });
                                } 
                        });
                    }
                }
                else
                {
                    $.ajax({
				        type:'POST',
                        url:'table_student_report.php',
                        data:
				        {
                            regix_id:regix,
                                dep:$('#dep_select').val(),
                                yer:$('#year_select').val(),
                                all:1,
                                sec:$('#section_select').val(),
                                reg:$('#reg_select').val(),
                        },
				        success:function(response)
                        {
                            $('#table_fr').html(response);
                             $('#datatabl').DataTable({'scrollX':true});
                              var pre=parseInt($('#pre').html());
                                var abs=parseInt($('#abs').html());
                                var odp=parseInt($('#odp').html());
                                var chartdata = [
                                {
                                    value: pre,
                                    color:"#0cbf65",
                                    highlight: "#15BA67",
                                    label: "Present"
                                },
                                {
                                    value: abs,
                                    color: "rgb(255, 0, 0)",
                                    highlight: "#f21954",
                                    label: "Absent"
                                },
                                {
                                    value: odp,
                                    color: "#004eff",
                                    highlight: "#1a88f7",
                                    label: "OD"
                                }
                                ];
                                var ctx2 = $(".pie-chart")[0].getContext("2d");
                                    window.myPie = new Chart(ctx2).Pie(chartdata, {
                                    showTooltips: true
                                    });
                        } 
                    });
                }
                
            });
            $('#ch').click(function(){
                $('#date').toggle();    
            }); 
         });
         function fetch_ajax(iam,need,myval)
         {
             var regix=<?php echo json_encode($regix_id)?>;
             if(iam=="dep")
             {
		     $.ajax({
				type:'POST',
				url:'ajax_class_report.php',
				data:
				{
                    regix_id:regix,
                    aiam:iam,
					aneed:need,
                    amyval:myval
                },
				success:function(response)
				{
                    $('#year_select').html("<option value='empty'></option>"+response);
                } 
             });
             }
             else if(iam=="year")
             {
                 var dep=$('#dep_select').val(); 
		         $.ajax({
     				type:'POST',
                    url:'ajax_class_report.php',
				    data:
                     {
                        regix_id:regix,
                        aiam:iam,
                        adep:dep,
					    aneed:need,
                        amyval:myval
                    },
				    success:function(response)
                    {
                          $('#section_select').html("<option value='empty'></option>"+response);
                    } 
             });
             }
             else if(iam=="section")
             {
                 var dep=$('#dep_select').val();
                 var year=$('#year_select').val();
		         $.ajax({
     				type:'POST',
                    url:'ajax_class_report.php',
				    data:
                     {
                        regix_id:regix,
                        aiam:iam,
                        adep:dep,
                        ayear:year,
					    aneed:need,
                        amyval:myval
                    },
				    success:function(response)
                    {
                         $('#reg_select').html("<option value='empty'></option>"+response);
                    } 
             });
             }
		 }
     </script>
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
</body>
</html>
