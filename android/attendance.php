<?php
require("../dbconnect.php");
try{
    $pdo = new PDO($dsn, $user, $pass, $opt);
    for($i=0;$i<count($_POST);$i++)
    {
        $report=json_decode($_POST["report".$i],true);
        $regix_id=$report['regix_id'];
        $department=$report['department'];
        $username=$report['username'];
        $year=$report['year'];
        $date=$report['date'];
        $day=$report['day'];
        $subject_name=$report['subject_name'];
        $subject_name=explode(" ",$subject_name);
        $sub=$subject_name[0];
        for($d=1;$d<count($subject_name);$d++)
        {
            $sub=$sub."_".$subject_name[$d];
        }
        $period=$report['period'];
        $section=$report['section'];
        $attendance=$report['attendance'];
        $num=count($attendance);
        $already=false;
        $table_name=$regix_id."_".$department."_".$year."_".$section."_".$attendance[0]["roll_no"]."_1";
        $stm=$pdo->prepare("select * from ".$table_name." where dat=?");
        $stm->execute([$date]);
        if($stm->fetch())
            $already=true;
        for($j=0;$j<$num;$j++)
        {
            $table_name=$regix_id."_".$department."_".$year."_".$section."_".$attendance[$j]["roll_no"];
            if($already)
            {
                $stm=$pdo->prepare("update ".$table_name."_1 set period".$period."=".$attendance[$j]["value"]);
                $stm->execute();
                $stm=$pdo->prepare("select ".$sub." from ".$table_name."_2 where dat=?");
                $stm->execute([$date]);
                $atten=$stm->fetchColumn();
                if(!is_null($atten))
                {
                    $aten=explode(',',$atten);
                    if($attendance[$j]["value"]=="0")
                    {
                        $aten[1]++;
                    }
                    else if($attendance[$j]["value"]=="1")
                    {
                        $aten[0]++;
                    }
                    else
                    {
                        $aten[2]++;
                    }
                    $att=$aten[0].','.$aten[1].','.$aten[2];
                    $stm=$pdo->prepare("update ".$table_name."_2 set ".$sub."=? where dat=? and day=?");
                    $stm->execute([$att,$date,$day]);   
                }
                else
                {
                    if($attendance[$j]["value"]=="0")
                    {
                        $att="0,1,0";
                    }
                    else if($attendance[$j]["value"]=="1")
                    {
                        $att="1,0,0";
                    }
                    else
                    {
                        $att="0,0,1";
                    }
                    $stm=$pdo->prepare("update ".$table_name."_2 set ".$sub."=? where dat=? and day=?");
                    $stm->execute([$att,$date,$day]);    
                }
            }
            else
            {
                $stm=$pdo->prepare("insert into ".$table_name."_1(dat,day,period".$period.") values(?,?,?)");
                $stm->execute([$date,$day,$attendance[$j]["value"]]);
                $stm=$pdo->prepare("insert into ".$table_name."_2(dat,day,".$sub.") values(?,?,?)");
                if($attendance[$j]["value"]=="0")
                {
                    $att="0,1,0";
                }
                else if($attendance[$j]["value"]=="1")
                {
                    $att="1,0,0";
                }
                else
                {
                    $att="0,0,1";
                }
                $stm->execute([$date,$day,$att]);
            }
        }
    }
}
catch(PDOException $p)
{
    echo "error in pdo".$p->getMessage();
}
?>
