#!/usr/bin/php
<html>

<head>
     <meta charset="UTF-8">
     <title>Verify Login</title>
</head>

<?php
$dbserver = "localhost"; //might be 127.0.0.1
$username = "root";
$password = "Jrosal1219";
$dbname = "login";

/*
send in user input as parameter, check against usernames in Db
returns true if one is found, return false if not
*/

function usernameVerify($unameIn)
{
 
    $query = "SELECT username FROM users";
    $result = mysql_query($query);
	 
    while($row =  mysql_fetch_assoc($result)){  //compare input with row stuff, no echo
	      foreach ($row as $element) {
	          if(strcmp($unameIn,$element) == 0) {
                    return true;   
                  }
	      }
    }

    return false;											 
}

/*
From Db take the salt and append to user input
compare user-input+salt to password+salt(hashed) saved on server in Db
*/
function passwordVerify($unameIn, $passIn, $dbConn)
{   

    $intoSQL = mysqli_real_escape_string($dbConn, $unameIn);    

    $query = "SELECT salt FROM users WHERE username='".$intoSQL."'";
    $result = mysql_query($query); //Should be salt of appropriate user to check pass
    
    $compPass=$passIn . $result; //input-password + salt

    $query = "SELECT password FROM users WHERE username='".$intoSQL."'";
    $userPass = mysql_query($query); 

    if(strcmp($userPass,SHA1($compPass)) == 0) {
         return true;   
    }
    
   return false;
         
}

/*

function addUser() //take in username, password. will get salt (hashed? SHA1()) here from
                   //mcrypt_create_iv? when saving to Db save username, salt+pass, salt
{  //basically when asked to create check database if not exist then do(mysql code?)(transaction?)

//prepared statements look something like this, check book as well.

$query = $mysqli->prepare("INSERT INTO `table` (`col1`, `col2`) VALUES (?, ?)");
$col1 = 100;
$col2 = 14;
$query->bind_param('ii', $col1, $col2);
$query->execute();
$query->close();
}

*/

$conn = mysql_connect($dbserver,$username,$password,$dbname);
     if (!$conn) {
          die("Connection failed: " . mysql_error());
     }
mysql_select_db($dbname);

$usernmIn=$_GET["tableChoice"];
$userpassIn=$_GET["..."];

/* else-if ladder starting with username, if not exist alert(),
   moving onto password check, if not verified alert()
*/

if(usernameVerify($usernmIn)) {
  if(passwordVerify($usernmIn,$userpassIn,$conn)) {
    alert("You are successfully logged in");
  }
  else {
    alert("Invalid Username or Password");
  }
}
else {
  alert("Invalid Username or Password");
}

?>

</html> 
