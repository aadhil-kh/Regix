<?php
function sec_session_start() {
    $session_name = 'regix_session';
    session_name($session_name);
    $secure = false;
    $httponly = true;
    if (ini_set('session.use_only_cookies', 1) === FALSE) {
        echo "turn ON cookies to use this website";
        exit();
    }
    $cookieParams = session_get_cookie_params();
    session_set_cookie_params($cookieParams["lifetime"],
        $cookieParams["path"], 
        $cookieParams["domain"], 
        $secure,
        $httponly);
    session_start();             
    session_regenerate_id(true); 
}
function staff_login_check()
{
  if(isset($_SESSION['regix_id'],$_SESSION['regix_staff_id'],$_SESSION['login_string']))
  {
    $regix_staff_id=$_SESSION['regix_staff_id'];
    $login_string=$_SESSION['login_string'];
    $regix_id = $_SESSION['regix_id'];
    $user_browser = $_SERVER['HTTP_USER_AGENT']; 
    require("dbconnect.php");
    try
    {
      $pdo=new PDO($dsn,$user,$pass,$opt);
      $tbl=$regix_id."_staff_list";
      $stmt=$pdo->prepare("select password from ".$tbl." where username=?");
      $stmt->execute([$regix_staff_id]);
      $poas=$stmt->fetchColumn();
      
     }
    catch(PDOException $e)
    {
      echo "errror";
    }
    finally{
      $pdo=null;
    }
    $login_check = hash('sha512',$poas.$user_browser);
        if(hash_equalss($login_check,$login_string))
        {
            return true;
        } 
        else 
        {
            return false;        
        }
    }
    else
    {
        return false;
    }
  }
function login_check() 
{
    if (isset($_SESSION['user'], $_SESSION['regix_id'],$_SESSION['login_string'])) 
    {
        $user_name_of_user = $_SESSION['user'];
        $login_string=$_SESSION['login_string'];
        $regix_id = $_SESSION['regix_id'];
        $user_browser = $_SERVER['HTTP_USER_AGENT']; 
        require("dbconnect.php");
        try
        {
            $pdo = new PDO($dsn, $user, $pass, $opt);
            $stmt=$pdo->prepare("select password from regix where user_name=?");
            $stmt->execute([$user_name_of_user]);
            $poas=$stmt->fetchColumn();
        }
        catch(PDOException $p)
        {
            echo "error ";
        }
        finally
        {
            $pdo=null;
        }
        $login_check = hash('sha512',$poas.$user_browser);
        if(hash_equalss($login_check,$login_string))
        {
            return true;
        } 
        else 
        {
            return false;        
        }
    }
    else
    {
        return false;
    }
}
function hash_equalss($str1, $str2) {
    if(strlen($str1) != strlen($str2)) {
      return false;
    } else {
      $res = $str1 ^ $str2;
      $ret = 0;
      for($i = strlen($res) - 1; $i >= 0; $i--) $ret |= ord($res[$i]);
      return !$ret;
    }
}
?>