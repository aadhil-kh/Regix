<?php
include_once 'functions.php';
sec_session_start();
if(login_check()==false)
{
   header("Location: login.php");
}
$data=$_POST['mydat'];
require("dbconnect.php");
$department=$_POST['department'];
$year=$_POST['year'];
$section=$_POST['section'];
$regix_id=$_SESSION['regix_id'];
try                
{
	$dbh=new PDO($dsn,$user,$pass,$opt);
	$tbl=$regix_id."_student_list";
	$stmt=$dbh->prepare("delete from ".$tbl." where department=? and year=? and section=?");
	$stmt->execute([$department,$year,$section]);
	for($j=0;$j<count($data);$j++)
	{
		$register_number=$data[$j][0];
		$name=$data[$j][1];
		$dob=$data[$j][5];
		$gender=$data[$j][6];
		$stmt=$dbh->prepare("insert into ".$tbl."(register_number,name,department,year,section,dob,gender) values(?,?,?,?,?,?,?)");
		$stmt->execute([$register_number,$name,$department,$year,$section,$dob,$gender]);
	}
}
catch(PDOException $e)
{
    echo "error";
}
finally
{
	$dbh=null;
}
?>