<?php
require("dbconnect.php");
$password=$_POST['password'];
$regix_id=$_POST['regix_id'];
$username=$_POST['username'];
$location=$_POST['location'];
$email=$_POST['email'];
$name_of_institute=$_POST['name_of_institute'];
try
{	
$pdo = new PDO($dsn, $user, $pass, $opt);
$stm=$pdo->prepare("select user_name from regix where user_name=?");
$stm->execute([$username]);
$y=$stm->fetch();
if($y['user_name'])
{
    echo "username taken";
}
else
{
    $stm=$pdo->prepare("select regix_id from regix where regix_id=?");
    $stm->execute([$regix_id]);
    $y=$stm->fetch();
    if($y['regix_id'])
    {
        echo "id already taken";
    }
    else
    {
        $password_hashed=password_hash($password,PASSWORD_BCRYPT);
        $sql ="insert into regix(regix_id,name_of_institute,location,user_name,password,email) values('$regix_id','$name_of_institute','$location','$username','$password_hashed','$email')";
        $pdo->query($sql);    
        echo "signup successful".$regix_id."name:".$username;
    }
}
    $pdo=null;
}
catch(PDOException $p)
{
    echo "error in pdo";
}
?>
	