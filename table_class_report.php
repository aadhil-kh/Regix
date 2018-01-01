<?php
include_once 'functions.php';
sec_session_start();
if(login_check()==false)
{
   header("Location: login.php");
}
$regix_id=$_POST['regix_id'];
$dep=$_POST['dep'];
$year=$_POST['yer'];
$over=$_POST['all'];
$section=$_POST['sec'];
$sub=$_POST['sub'];
if($over==0)
{ 
    $frm=$_POST['fday'];
    $to=$_POST['tday'];
}
else
{
    $frm="2000-01-01";
    $to="2100-01-01";
}
require('dbconnect.php');
try                                                                
 { 
	 $dbh = new PDO($dsn, $user, $pass, $opt);
     $stm2=$dbh->prepare("select register_number,name from ".$regix_id."_student_list where department=? and year=? and section=?");
     $stm2->execute([$dep,$year,$section]);
     $s=$dbh->prepare("select count(*) from ".$regix_id."_student_list where department=? and year=? and section=?");
     $s->execute([$dep,$year,$section]);
     $cs=$s->fetchColumn();
     $i=0;
     while($row=$stm2->fetch())
     {
        $st_name[$i]=$row['name'];
        $st_reg[$i]=$row['register_number'];                   
         $std=$regix_id."_".$dep."_".$year."_".$section."_".$row["register_number"]."_2";
             $subj=str_replace(" ","_",$sub);
             $stm3=$dbh->prepare("select ".$subj." from ".$std." where dat between ? and ?");
             $stm3->execute([$frm,$to]);
             $abs=0;
             $pre=0;
             $od=0;
             $tot=0;
             while($row1=$stm3->fetchColumn())
             {
                 if(!is_null($row1))
                 {
                 $att=explode(",",$row1);
                 $pre=$pre+$att[0];
                 $abs=$abs+$att[1];
                 $od=$od+$att[2];
                 }
             }
             $tot=$pre+$abs+$od;
             if($tot>0)
                $percent=round((($pre+$od)/$tot)*100);
             else   
                $percent=0;
         $aten[$i]=[$pre,$abs,$od,$percent];
         $i++;
     }
 }
catch(PDOException $e)
{
    echo "error".$e;
}
echo "<div class='panel'>                    
        <div id='tb_head' class='panel-heading'>
        </div>
        <div class='panel-body'>     
        <div class='responsive-table'>
            <table id='datatabl' class='table table-striped table-bordered table-hover' width='100%' cellspacing='0'>
    <thead>
    <tr>
    <th>Name</th>
    <th>Register_Number</th>";
echo "<th>Present</th>
      <th>Absent</th>
      <th>OD</th>
      <th>percentage</th>
      <th>chart</th>";  
echo "</tr>
      </thead>
      <tbody>";
for($i=0;$i<$cs;$i++) 
{  
    echo "<tr><td>".$st_name[$i]."</td><td>".$st_reg[$i]."</td>"; 
    for($k=0;$k<count($aten[$i]);$k++)                    
    {
        echo "<td>".$aten[$i][$k]."</td>";  
    }
    echo "<td style='cursor:pointer' onclick='chartgen(".$aten[$i][0].','.$aten[$i][1].','.$aten[$i][2].")'><span class='fa fa-bar-chart'></span></td>";
    echo "</tr>";
}
echo "</tbody></table></div></div></div>";
?>
