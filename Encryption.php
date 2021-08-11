<?php
include_once 'GetDataFromDB.php';
class Encryptor{
    private $date;
      function __construct($date){
          $this->date=$date;
      }
      public function Encrypt($text){
        $dal = new DAL("hashes","SELECT hash from hashes where id=$this->date",false);
        $hash = $dal->GetData();

        if($hash==NULL){
          return;
        }
         while($row = $hash->fetch_assoc()) {
              $hash=$row["hash"];
              break;
         }
         $ciphering = "AES-128-CTR";
         // Use OpenSSl Encryption method
         $iv_length = openssl_cipher_iv_length($ciphering);
         $options = 0;
         $encryption_iv = '1234567891012345';
         $encryption_key = $hash;
         $encryption = openssl_encrypt($text, $ciphering,$encryption_key, $options, $encryption_iv);
         return $encryption;
}

    public function Decrypt($text){
      $dal = new DAL("hashes","SELECT hash from hashes where id=$this->date",false);
      $hash = $dal->GetData();
       while($row = $hash->fetch_assoc()) {
            $hash=$row["hash"];
            break;
       }
       $ciphering = "AES-128-CTR";
       $iv_length = openssl_cipher_iv_length($ciphering);
       $options = 0;
       $decryption_iv = '1234567891012345';
       $decryption_key = $hash;

       // Use openssl_decrypt() function to decrypt the data
       $decryption=openssl_decrypt($text, $ciphering,
               $decryption_key, $options, $decryption_iv);
      return $decryption;
    }
}





?>
