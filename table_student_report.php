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
$reg=$_POST['reg'];
$tbl = $regix_id.'_'.$dep.'_'.$year.'_'.$section.'_'.$reg;
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
	 
$pdo = new PDO($dsn, $user, $pass, $opt); 
        $stm=$dbh->prepare("select name from ".$regix_id."_student_list where register_number=?");
        $stm->execute([$reg]);
        $student_name=$stm->fetchColumn();
        $st=$dbh->prepare("select schedule_per_day from ".$regix_id."_department_list where department=? and year=? and section=?");
        $st->execute([$dep,$year,$section]);
     $schedule=$st->fetchColumn();
     echo "<div class='responsive-table'><div class='panel'><div class='panel-heading'><h3>".$student_name."</h3></div>
     <div class='panel-body'>
     <table id='datatabl' class='table table-striped table-bordered table-hover' width='100%' cellspacing='0'>
                        <thead>
                            <tr>
                                <th>DATE</th>
                                <th>DAY</th>";
     for($i=1;$i<=$schedule;$i++)
     {
         echo "<th>period ".$i."</th>";
     }
     echo "</tr></thead><tbody>";
        $pre=0;
        $abs=0;
        $tot=0;
        $od=0;
        $st=$dbh->prepare("select * from ".$tbl."_1 where dat between ? and ?");
        $st->execute([$frm,$to]);
        while ($row=$st->fetch())
        {
            echo "<tr><td>".$row['dat']."</td><td >".$row['day']."</td><td >";
            for($i=1;$i<=$schedule;$i++)
            {
                if($row['period'.$i]=="0")
                {
                    $tot++;$abs++;
                    echo "A";
                }
                else if($row['period'.$i]=="1")
                {
                    $tot++;$pre++;
                    echo "P";
                }
                else if($row['period'.$i]=="2")
                {
                    $tot++;$od++;
                    echo "OD";
                }
                echo "</td>";
                if($i<$schedule)
                    echo "<td >";               
            }                                         
            echo "</tr>";
        }
        echo "</tbody></table></div></div></div></div>";
        if($tot>0)
        {
                $pp=round(($pre/$tot)*100);
                $ap=round(($abs/$tot)*100);
                $op=round(($od/$tot)*100);
                $olap=($pre+$od);
                $oap=round(($olap/$tot)*100);           
        } 
        else
        {
            $pp=$ap=$op=$olap=$oap=0;
        }
        echo "<div id='div1' class='col-md-12 padding-0'>
                <div class='col-md-6' style='padding-left:0px'>
                <div class='panel'>
                <div class='panel-heading'>
                <h3>Attendance Percentage = ".$oap."%</h3>
                </div>
                <table  style='width:100%; !important'>
                         <tr>
                             <td>TOTAL NUMBER OF CLASSES</td>
                             <td>".$tot."</td>
                         </tr>
                         <tr>
                             <td>NUMBER OF CLASSES PRESENT</td>
                             <td>".$pre."</td>
                         </tr>
                         <tr>
                             <td>PRESENT PERCENTAGE</td>
                             <td id='pre'>".$pp."</td>
                         </tr>
                         <tr>
                             <td>NUMBER OF CLASSES ABSENT</td>
                             <td>".$abs."</td>
                         </tr>
                         <tr>
                             <td>ABSENT PERCENTAGE</td>
                             <td id='abs'>".$ap."</td>
                         </tr>
                         <tr>
                             <td>NUMBER OF OD</td>
                             <td>".$od."</td>
                         </tr>
                          <tr>
                             <td>OD PERCENTAGE</td>
                             <td id='odp'>".$op."</td>
                         </tr>
                      </table>
                      </div></div>
                      <div class='col-md-6' style='padding-right:0px'>
                      <div class='panel'>
                      <div class='panel-heading'><h3>Chart</h3></div>
                            <div class='panel-body'><canvas width=260px height=260px class='pie-chart'></canvas>
                      </div>
                      </div>
                      </div>
                      <br><br><br>";
}
catch(PDOException $e)
{
    echo "error".$e;
}

?>
