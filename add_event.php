<?php
include_once 'functions.php';
sec_session_start();
if(login_check()==false)
{
   header("Location: login.php");
}
require("dbconnect.php");
try{
    $pdo = new PDO($dsn, $user, $pass, $opt);
    $evtname=$_POST['event'];
    $evtdes=$_POST['event_description'];
    $evtdate=$_POST['event_date'];
    $regix_id=$_POST['regix_id'];
    $tbl=$regix_id."_events";
    $st=$pdo->prepare("insert into ".$tbl."(name,description,dat) values(?,?,?)");
    $st->execute([$evtname,$evtdes,$evtdate]);
    echo "success";
}

catch(PDOException $e)
{
    echo "ero";
}
?>
