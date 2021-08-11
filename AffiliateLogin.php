<?php
include_once 'GetDataFromDB.php';
include_once 'Encryption.php';
session_start();
// Initialize the session
// Check if the user is already logged in, if yes then redirect him to welcome page
if($_SESSION){
if($_SESSION["loggedin"]){
  echo $_SESSION["loggedin"];
  //  header("location: index.php");
    exit;
}
}
// Define variables and initialize with empty values
$username = $password = "";
$login_err=$username_err = $password_err = "";

// Processing form data when form is submitted
if($_SERVER["REQUEST_METHOD"] == "POST"){

    // Check if username is empty
    if(empty(trim($_POST["username"]))){
        $username_err = "Please enter username.";
    } else{
        $username = trim($_POST["username"]);
    }

    // Check if password is empty
    if(empty(trim($_POST["password"]))){
        $password_err = "Please enter your password.";
    } else{
        $password = trim($_POST["password"]);
    }

    // Validate credentials
    if(empty($username_err) && empty($password_err)){
        $encryptor=new Encryptor(GetSumOfNAme($username));
        $username=base64_encode($encryptor->Encrypt($username));
        $sql = "SELECT * FROM affiliates WHERE username = '$username'";
        $dal = new DAL("affiliates",$sql);
        $userData=$dal->GetData();
        try {
          $dbPass="";
          if($userData!=NULL){

              while($row=$userData->fetch_assoc()){
                $dbPass=$row["password"];
              }
            if(password_verify($password, base64_decode($dbPass))){
              $userData=$dal->GetData();
              while($row=$userData->fetch_assoc()){
                $_SESSION["UserType"]="affiliate";
                $_SESSION["loggedin"]="loggedin";
                $_SESSION["UserCode"]=base64_decode($row["code"]);
                $_SESSION["UserBalance"]=$encryptor->Decrypt(base64_decode($row["amount"]));
                $_SESSION["name"]=$encryptor->Decrypt(base64_decode($row["firstname"]))." ".$encryptor->Decrypt(base64_decode($row["lastname"]));
              }
            }
            else {
            $login_err="either your username or password are not correct";
            }
          }
        } catch (\Exception $e) {

        }


$username="";
}
}
function GetSumOfNAme($name){
  $sum=0;
  $name=str_split($name);
      foreach ($name as $letter) {
        $sum+=intval(ord($letter));
      }
      return $sum%1400;
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Login</title>
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.css">
    <style type="text/css">
        body{ font: 14px sans-serif; }
        .wrapper{ width: 350px; padding: 20px; }
    </style>
</head>
<body>
    <div class="wrapper">
        <h2>Login</h2>
        <p>Please fill in your credentials to login.</p>
        <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post">
            <div class="form-group <?php echo (!empty($username_err)) ? 'has-error' : ''; ?>">
                <label>Username</label>
                <input type="text" name="username" class="form-control" value="<?php echo $username; ?>">
                <span class="help-block"><?php echo $username_err; ?></span>
            </div>
            <div class="form-group <?php echo (!empty($password_err)) ? 'has-error' : ''; ?>">
                <label>Password</label>
                <input type="password" name="password" class="form-control">
                <span class="help-block"><?php echo $password_err; ?></span>
            </div>
            <div class="form-group">
                <input type="submit" class="btn btn-primary" value="Login">
                <span class="help-block"><?php echo $login_err; ?></span>
            </div>
        </form>
    </div>
</body>
</html>
