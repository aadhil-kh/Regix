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
    $year=$_POST['year'];
    $section=$_POST['section'];
    $gender=$_POST['gender'];
    $dob=$_POST['dob'];
    $name=$_POST['name'];
    $register_number=$_POST['register_number'];
    $regix_id=$_SESSION['regix_id'];
    $dbh=new PDO($dsn,$user,$pass,$opt);
    $stmt= $dbh->prepare("insert into ".$regix_id."_student_list(department,year,section,register_number,name,dob,gender) values(?,?,?,?,?,?,?)");
    $stmt->execute([$dep,$year,$section,$register_number,$name,$dob,$gender]);
}
catch(PDOException $e)
{
    echo "error";
}

