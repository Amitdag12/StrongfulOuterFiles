<?php
include_once 'Encryption.php';
//AddNewAffiliate("admin","admin","admin@gmail.com","123456789","admin");
  function AddNewAffiliate($firstname,$lastname,$email,$password,$username){
    $encryptor=new Encryptor(GetSumOfNAme($username));
    $code =base64_encode(GenerateAffiliateCode($firstname,$lastname,$encryptor));
    if($code==NULL){
      return;
    }
    $firstname=base64_encode($encryptor->Encrypt($firstname));
    $lastname=base64_encode($encryptor->Encrypt($lastname));
    $email=base64_encode($encryptor->Encrypt($email));
    $username=base64_encode($encryptor->Encrypt($username));
    $password= base64_encode(password_hash($password,PASSWORD_DEFAULT));
          $dal = new DAL("affiliates","INSERT INTO affiliates
          VALUES (0,'$firstname','$lastname','$email','$code',0,'$username','$password')");
          $dal->SetData();
          echo "INSERT INTO affiliates
          VALUES ('$firstname','$lastname','$email','$code',0,'$username','$password')";
  }
  function GenerateAffiliateCode($firstname,$lastname,$encryptor){
    $code;
    $dal;
    $num;
    $i=0;
    do{
        if(strlen($firstname)>20){
          $firstname = substr($firstname,0,20);
        }
  $code=   $firstname . $lastname . date("H").date("i").$i;
  $dal = new DAL("affiliates","select count(*) from affiliates where code=".base64_encode($encryptor->Encrypt($code)),false);
  if($i>100){
    echo '<script language="javascript">';
    echo 'alert("there was a problem")';
    echo '</script>';
return NULL;
  }
}while (intval($dal->GetData())>0);

  //echo $code;
  return $code;
}
function GetSumOfNAme($name){
  $sum=0;
  $name=str_split($name);
      foreach ($name as $letter) {
        $sum+=intval(ord($letter));
      }
      return $sum%1400;
}
function AddTransaction($amount,$affiCode){
  $dal = new DAL("affiliates","select * from affiliates where code=".$affiCode);
  $userData=$dal->GetData();
  $oldAmount;
  while($row=$userData->fetch_assoc()){
    $oldAmount=$row["amount"];
  }
  $amount+=$oldAmount;
  $dal = new DAL("affiliates","UPDATE affiliates SET amount = $amount WHERE code=".$affiCode);
  $dal->GetData();
}
?>
