<html>

<head>
     <meta charset="UTF-8">
     <title>Verify Login</title>
</head>

<?php
$dbserver = "sql2.njit.edu"; //might be 127.0.0.1 depending on backend or who has db
$username = "jar59"; //again depends on what user uses
$password = "popish75"; //change depending on user
$dbname = "jar59"; //change depending on user

/*
send in user input as parameter, check against usernames in Db
returns true if one is found, return false if not
*/

function usernameVerify($conn,$unameIn)
{
 
    $query = "SELECT username FROM Users";
    $result = mysqli_query($conn,$query);

    while($row = mysqli_fetch_array($result)){  //compare input with row stuff, no echo
	      foreach ($row as $element) {
		  if(strcmp((string)$unameIn,(string)$element) == 0) {
                    return true;   
                  }
	      }
    }

    return false;											 
}

/*
From Db take the password from matching username
compare user-input to password saved on server in Db
Will also need to check salt as well as hashing, save salt in Db as well
append salt to the input password then hash the inputpass+salt combo
salt+password hash will be saved in Db once user registered.
*/
function passwordVerify($unameIn, $passIn, $dbConn)
{   
    $intoSQL = mysqli_real_escape_string($dbConn, $unameIn);    

    $query = "SELECT password FROM Users WHERE username='".$intoSQL."'";
    $result = mysqli_query($dbConn,$query); 
    $userPass = mysqli_fetch_array($result);

    if(strcmp((string)$userPass[0],(string)$passIn) == 0) {
	 return true;   
    }
    
   return false;
         
}


$conn = mysqli_connect($dbserver,$username,$password,$dbname);
     if (!$conn) {
          die("Connection failed: " . mysqli_error());
     }
mysqli_select_db($dbname);


$usernmIn=$_POST["studUsernm"]; //post or get
$userpassIn=$_POST["studPass"]; //post or get
/* else-if ladder starting with username, if not exist alert(),
   moving onto password check, if not verified alert()
*/
if(usernameVerify($conn,$usernmIn)) {
  if(passwordVerify($usernmIn,$userpassIn,$conn)) {
    //wherever we need to be directed to once verified
  }
  else {
    echo "Invalid Username or Password because password";
  }
}
else {
  echo "Invalid Username or Password because username";
}

?>

</html> 
