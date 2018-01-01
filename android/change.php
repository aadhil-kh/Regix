 <?php
		require('../dbconnect.php')
         $regix_id=$_POST['regix_id'];
         $old_name=$_POST['username'];
         $old_password=$_POST['password'];     
         $uorp=$_POST['uorp'];         
         try 
         { 
               $tbl=$regix_id."_staff_list";
			   $dbh = new PDO($dsn, $user, $pass, $opt);
               if($uorp=="true")
               {
                    $new_name=$_POST['change_username'];
                    $stmt= $dbh->prepare("select username from ".$tbl." where username=?");
                    $stmt->execute([$new_name]);
                    if($stmt->fetchColumn())
                    {
                        echo "username already taken";
                    }
                    else
                    {
                        $stmt= $dbh->prepare("update ".$tbl." set username=? where username=?");
                        $stmt->execute([$new_name,$old_name]);
                        echo "success";
                    }
                }
                else
                {
                    $new_password=$_POST['change_password'];
                    $stmt= $dbh->prepare("update ".$tbl." set password=? where username=?");
                    $stmt->execute([$new_password,$old_name]);
                    echo "success";
                }     
         }
         catch(PDOException $e)
         {
                echo $e->getMessage();
         }
?>
