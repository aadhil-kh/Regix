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
    $pdo = new PDO($dsn, $user, $pass, $opt);
    $iam=$_POST['aiam'];
    $need=$_POST['aneed'];
    $myval=$_POST['amyval'];
    $regix_id=$_POST['regix_id'];
    if($iam=="dep")
    {
        $st=$pdo->prepare("select distinct year from ".$regix_id."_department_list where department=? order by year");
        $st->execute([$myval]);
        $str="";
        while($row=$st->fetch())
        {
		  $str=$str."<option value='".$row['year']."'>".$row['year']."</option>";
	    }
        echo $str;
    }
    else if($iam=="year")
    {
        $dep=$_POST['adep'];
        $str="";
        $st=$pdo->prepare("select distinct section from ".$regix_id."_department_list where year=? and department=? order by section");
        $st->execute([$myval,$dep]);
        while($row=$st->fetch())
        {
            $str=$str."<option value='".$row['section']."'>".$row['section']."</option>";
        }        
        echo $str;
    }
    else if($iam=="section"&&$need=="subject")
    {
        $str="";
        $dep=$_POST['adep'];
        $year=$_POST['ayear'];
        $st=$pdo->prepare("select distinct subject from ".$regix_id."_subject_list where year=? and department=? and section=? order by subject");
        $st->execute([$year,$dep,$myval]);
        while($row=$st->fetch())
        {
            $str=$str."<option value='".$row['subject']."'>".$row['subject']."</option>";
        }        
        echo $str;
    }
    else if($iam=="section"&&$need=="reg")
    {
        $dep=$_POST['adep'];
        $year=$_POST['ayear'];
        $st=$pdo->prepare("select distinct register_number from ".$regix_id."_student_list where year=? and department=? and section=? order by register_number");
        $st->execute([$year,$dep,$myval]);
        while($row=$st->fetch())
        {
            $str=$str."<option value='".$row['register_number']."'>".$row['register_number']."</option>";
        }        
        echo $str;
    }

}

catch(PDOException $e)
{
    echo "ero";
}
?>
