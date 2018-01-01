<?php
include_once 'functions.php';
sec_session_start();
if(login_check()==false)
{
   header("Location: login.php");
}
$data=$_POST['mydat'];
require('dbconnect.php');
try                
{
	$dbh=new PDO($dsn,$user,$pass,$opt);
	$tbl=$regix_id."_staff_list";
	$stmt=$dbh->prepare("select name,department,username,password from ".$tbl);
	$stmt->execute();
	$i=0;
	$data_from_table[0]=[];
	$data_from_table[1]=[];
	$data_from_table[2]=[];
	$data_from_table[3]=[];
	while($row=$stmt->fetch())
	{
		$data_from_table[0][$i]=$row['name'];
		$data_from_table[1][$i]=$row['department'];
		$data_from_table[2][$i]=$row['username'];
		$data_from_table[3][$i]=$row['password'];
		$i++;
	}
	$stmt=$dbh->prepare("truncate table ".$tbl);
	$stmt->execute();
	$name_arr=$data_from_table[0];
	for($j=0;$j<count($data);$j++)
	{
		$staff_info=$data[$j];
		$name=$staff_info[0];
		$department=$staff_info[1];
		if(array_search($name,$name_arr)!==FALSE)
		{
			 $key=array_search($name,$name_arr);
			 if($data_from_table[1][$key]==$department)
			 {
				 	$stmt=$dbh->prepare("insert into ".$tbl."(username, password,name,department) values(?,?,?,?)");
					$stmt->execute([$data_from_table[2][$key],$data_from_table[3][$key],$data_from_table[0][$key],$data_from_table[1][$key]]);		
			 }
		}
		else
		{
			$user_name=str_replace(" ","",$name);
			$user_name=str_replace(".","",$user_name);
   		$check=$user_name."%";
   		$stmt=$dbh->prepare("select username from ".$regix_id."_staff_list where username like ?");
  	  $stmt->execute([$check]);
      $i=$stmt->rowCount()+1;
      $user_name=$user_name.$i;
      $user_name=strtolower($user_name);
			$password_staff=substr(md5(microtime(true)),0,10);
      $stmt= $dbh->prepare("insert into ".$regix_id."_staff_list(username,password,name,department) values(?,?,?,?)");
      $stmt->execute([$user_name,$password_staff,$name,$department]);
		}
	}
}
catch(PDOException $e)
{
    echo "error".$e;
}

?>
