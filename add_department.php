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
    $dur=$_POST['year'];
    $regix_id=$_SESSION['regix_id'];
    $dbh=new PDO($dsn,$user,$pass,$opt);
    for($i=1;$i<=$dur;$i++)
    {
        $stmt= $dbh->prepare("insert into ".$regix_id."_department_list(department,year,section) values(?,?,?)");
        $stmt->execute([$dep,$i,'1']);
    }
}
catch(PDOException $e)
{
    echo "error";
}

