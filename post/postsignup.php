<?php
$url = "http://digitalbrains.esy.es/signup.php";
$data = array("regix_id"=>"amscg","username"=>"aadhilkh2","location"=>"chennai","name_of_institute"=>"amscseaa","password"=>"aasdd","email"=>"my");
// use key 'http' even if you send the request to https://...
$options = array(
    'http' => array(
        'header'  => "Content-type: application/x-www-form-urlencoded\r\n",
        'method'  => 'POST',
        'content' => http_build_query($data)
    )
);
$context  = stream_context_create($options);
$result = file_get_contents($url, false, $context);
if ($result === FALSE) { echo "error in check"; }

echo($result);
?>