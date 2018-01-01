<?php
require("../dbconnect.php");
$regix_id=$_POST['regix_id'];
try{
    $opt = [
PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
 PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
PDO::ATTR_EMULATE_PREPARES => false
];
$pdo = new PDO($dsn, $user, $pass, $opt);
$stm=$pdo->prepare("select regix_id from regix where regix_id=?");
$stm->execute([$regix_id]);
if($y=$stm->fetchColumn())
{
    echo json_encode("success");
}
else
{
    echo json_encode("college not found");
}
    $pdo=null;
}
catch(PDOException $p)
{
    echo "error";
    $pdo=null;
}
?>
	
