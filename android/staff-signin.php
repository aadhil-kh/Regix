<?php
require("../dbconnect.php");
$password=$_POST['password'];
$username=$_POST['username'];
$regix_id=$_POST['clg_id'];
try{
$pdo = new PDO($dsn, $user, $pass, $opt);
$table_name=$regix_id.'_staff_list';
$stm=$pdo->prepare("select password from ".$table_name." where username=?");
$stm->execute([$username]);
$y=$stm->fetch();
if($password==$y['password'])
{
    $json_array[0]["name"]=" ";
    $i=0;
    $table_name=$regix_id.'_subject_handling';
    $stmt=$pdo->prepare("select department,year,section,subject from ".$table_name." where username = ?");
    $stmt->execute([$username]);
    while($row=$stmt->fetch())
    {
        $json_array[$i]["name"]=$row['subject'];
        $json_array[$i]["department"]=$row['department'];
        $json_array[$i]["year"]=$row['year'];
        $json_array[$i]["section"]=$row['section'];
        $table_name=$regix_id.'_student_list';
        $stmt1=$pdo->prepare("select register_number,name from ".$table_name." where department=? and year=? and section=?");
        $stmt1->execute([$json_array[$i]["department"],$json_array[$i]["year"],$json_array[$i]["section"]]);
        $j=0;
        while($row1=$stmt1->fetch())
        {
            $json_array[$i]["students"][$j][0]=$row1["name"];
            $json_array[$i]["students"][$j][1]=$row1["register_number"];
            $j=$j+1;
        }
        $table_name=$regix_id."_".$row['department']."_".$row['year']."_".$row['section'];
        $array_of_day=["monday","tuesday","wednesday","thursday","friday"];
        $h=0;
        foreach($array_of_day as $array_list)
        {
            $query="select period from ".$table_name." where ".$array_list."=?";
            $stmt2=$pdo->prepare($query);
            $stmt2->execute([$json_array[$i]["name"]]);
            $json_array[$i]["schedule"][$h]=array();
            while($row3=$stmt2->fetch())
            {
                array_push($json_array[$i]["schedule"][$h],$row3['period']);
            }
            $h++;
        }
        $i=$i+1;
    }
    $table_name=$regix_id."_department_list";
    $stmt23=$pdo->prepare("select schedule_per_day from ".$table_name." where department =? and section=? and year=?");
    $stmt23->execute([$json_array[0]["department"],$json_array[0]["section"],$json_array[0]["year"]]);
    $row23=$stmt23->fetchColumn();
    echo json_encode(array("subject"=>$json_array,"number_of_periods"=>$row23));
}
else
{
    echo json_encode(array("error"=>"invalid credentials"));
}
    $pdo=null;
}
catch(PDOException $p)
{
    echo "error in pdo".$p->getMessage();
}
?>
	
