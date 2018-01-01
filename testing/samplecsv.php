<?php
require('dbconnect.php');
try{
$pdo = new PDO($dsn, $user, $pass, $opt);
$stmt=$pdo->prepare("insert into sample values(?,?,?)");
//$rc=$pdo->exec("insert into sample values('qere','ertfg','qerere')");
//    echo "sql query has been executed";
//    $f=fopen("sampletxt.txt","r") or die("unable to read the file");
  //  $stm=fread($f,filesize("sampletxt.txt"));
    //echo $stm;
    //echo "\n";
    $file=fopen("samplecsv.csv","r");
    while(!feof($file))
    {
        $arr=fgetcsv($file);
        if($arr==false)
            break;
        $stmt->execute($arr);      
    }
    fclose($file);
    echo "table might have been update please look into it";
    $pdo=null;
}
catch(PDOException $p)
{
    echo "error".$p->getMessage();
    $pdo=null;
}
?>
	
