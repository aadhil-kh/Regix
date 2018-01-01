<?php
require('../dbconnect.php')
try
{
    $pdo = new PDO($dsn, $user, $pass, $opt);
}
catch(PDOException $e)
{
    echo "ero";
}
 $regix_id=$_POST['regix_id'];
 $dep=$_POST['department'];
 $year=$_POST['year'];
 $over=$_POST['overall'];
 $section=$_POST['section'];
 if($over=="0")
 { 
    $frm=$_POST['from'];
    $to=$_POST['to'];  
 }
 else
 {
     $frm="2000-01-01";
     $to="2100-01-01";
 }
 try
 {
     $table=$regix_id."_subject_list";
     $stm=$pdo->prepare("select subject from ".$table." where department=? and year=? and section=?");
     $stm->execute([$dep,$year,$section]);
     $x=0;
     while($row=$stm->fetch())
     {
         $sub[$x]=$row['subject'];
         $x++;
     }  
     $table=$regix_id."_student_list";
     $stm=$pdo->prepare("select register_number,name from ".$table." where department=? and year=? and section=?");
     $stm->execute([$dep,$year,$section]);
     $i=0;
     $json_stud=[];
     while($row=$stm->fetch())
     {
         $json_stud[$i]["name"]=$row["name"];
         $json_stud[$i]["roll"]=$row["register_number"];
         $std=$regix_id."_".$dep."_".$year."_".$section."_".$row["register_number"]."_2";
         for($j=0;$j<count($sub);$j++)
         {
             $subj=str_replace(" ","_",$sub[$j]);
             $stm1=$pdo->prepare("select ".$subj." from ".$std." where dat between ? and ?");
             $stm1->execute([$frm,$to]);
             $abs=0;
             $pre=0;
             $od=0;
             $tot=0;
             while($row1=$stm1->fetchColumn())
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
             if($tot==0)
             {
                 $pre_per=$abs_per=$od_per=0;
             }
             else
             {
                $pre_per=($pre/$tot)*100;
                $abs_per=($abs/$tot)*100;
                $od_per=($od/$tot)*100;    
             }
             $json_stud[$i][$sub[$j]]=[$pre,$abs,$od,$pre_per,$abs_per,$od_per,$tot];
         }
         $i++;
     }
    echo json_encode(array("subject"=>$sub,"student"=>$json_stud));    
 }
catch(PDOException $e)
{
    echo "error".$e;
}
?>
