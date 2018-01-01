<?php
try{
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
 $reg=$_POST['register'];
 if($over=="0")
 { 
 $frm=$_POST['from'];
 $to=$_POST['to'];  
 }
 try
 {
     $table=$regix_id."_department_list";
     $stm=$pdo->prepare("select schedule_per_day from ".$table." where department=? and year=? and section=?");
     $stm->execute([$dep,$year,$section]);
     $num=$stm->fetchColumn();
     $std=$regix_id."_".$dep."_".$year."_".$section."_".$reg."_1";
     $period_list="period1";
     for($c=2;$c<=$num;$c++)
     {
         $period_list=$period_list.","."period".$c;
     }
    $pre=0;
    $abs=0;
    $tot=0;
    $od=0;  
    $stm=" ";
    if($over=="1")
    {
         $stm=$pdo->prepare("select ".$period_list." from ".$std);        
         $stm->execute();
     }
     else
     {
         $stm=$pdo->prepare("select ".$period_list." from ".$std." where dat between ? and ?");
         $stm->execute([$frm,$to]);
     }
     while($row=$stm->fetch())
     {
        for($x=1;$x<8;$x++)
        {
                if($row["period".$x]==0)   
                {
                    $abs++;$tot++;
                }
                else if($row["period".$x]==1)
                {
                    $pre++;
                    $tot++;
                }   
                 else if($row["period".$x]==2)
                 {
                     $od++;
                     $tot++;
                 }
        }
    }
    $pre_per=($pre/$tot)*100;
    $abs_per=($abs/$tot)*100;
    $od_per=($od/$tot)*100;
     $tmp=[round($pre_per),round($abs_per),round($od_per),$pre,$abs,$od,$tot];
    echo json_encode($tmp);
    
 }
catch(PDOException $e)
{
    echo "error";
}
?>
