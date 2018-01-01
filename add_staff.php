<?php
include_once 'functions.php';
sec_session_start();
if(login_check()==false)
{
   header("Location: login.php");
}
require("dbconnect.php");
try
{
    $dep=$_POST['department'];
    $staff=$_POST['staff'];
    $regix_id=$_SESSION['regix_id'];
    $dbh=new PDO($dsn,$user,$pass,$opt);
    $user_name=str_replace(" ","",$staff);
    $user_name=str_replace(".","",$user_name);
    $check=$user_name."%";
    $stmt=$dbh->prepare("select username from ".$regix_id."_staff_list where username like ?");
    $stmt->execute([$check]);
    $i=$stmt->rowCount()+1;
    $user_name=$user_name.$i;
    $user_name=strtolower($user_name);
		$password_staff=substr(md5(microtime(true)),0,10);
    $stmt= $dbh->prepare("insert into ".$regix_id."_staff_list(username,password,name,department) values(?,?,?,?)");
    $stmt->execute([$user_name,$password_staff,$staff,$dep]);
}
catch(PDOException $e)
{
    echo "error";
}
?>