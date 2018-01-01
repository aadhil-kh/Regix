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
$subject_arr=[];
try                                                                
 {
	 $dbh = new PDO($dsn, $user, $pass, $opt); 
     $stm2=$dbh->prepare("select register_number,name from ".$regix_id."_student_list where department=? and year=? and section=?");
     $stm2->execute([$dep,$year,$section]);
     $i=0;
     while($row=$stm2->fetch())
     {
        $j=0;
        $aten[$i][$j]=$row['register_number'];
        $j++;
        $aten[$i][$j]=$row['name'];
        $j++;
        $std=$regix_id."_".$dep."_".$year."_".$section."_".$row["register_number"]."_2";
        $stm4=$dbh->prepare("select subject from ".$regix_id."_subject_list where department=? and year=? and section=?");
        $stm4->execute([$dep,$year,$section]);
        $k=0;
        while($row4=$stm4->fetchColumn())
        {
            $subject_arr[$k]=$row4;
            $k++;
            $subj=str_replace(" ","_",$row4);
            $stm3=$dbh->prepare("select ".$subj." from ".$std." where dat between ? and ?");
            $stm3->execute([$frm,$to]);
            $abs=0;$pre=0;$od=0;$tot=0;
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
            $aten[$i][$j]=$pre;
            $j++;
        }
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
    foreach($subject_arr as $sub_list)
    {
        echo "<th>".$sub_list."</th>";
    }
echo "</tr></thead><tbody>";
foreach($aten as $row) 
{  
    echo "<tr>";
    foreach($row as $col)
    {
        echo "<td>".$col."</td>";
    }
    echo "</tr>";
}
echo "</tbody></table></div></div></div>";
/*
require('fpdf.php');
class PDF extends FPDF
{
    function BasicTable($header, $aten)
    {
	// Head
	   foreach($header as $col)
		  $this->Cell(40,7,$col,1);
	   $this->Ln();
	// body
        foreach($aten as $row)
	   {
		  foreach($row as $col)
              $this->Cell(40,6,$col,1);
           $this->Ln();
	   }   
    }
}
$pdf = new PDF('L','mm',A3);
$header[0]="Register number";
$header[1]="Name";
$l=2;
foreach($subject_arr as $sub_list)
    $header[$l++]=$sub_list;
$pdf->SetFont('Arial','',14);
$pdf->AddPage();
$pdf->BasicTable($header,$aten);
$pdf->Output();*/
?>
