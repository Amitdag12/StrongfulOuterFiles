<?php
class DAL {
    private $tableName;
    private $data;
    private $queryText;
    private $isEncoded;
    function __construct($tableName,$queryText,$isEncoded=true){
        $this->tableName=$tableName;
        $this->queryText=$queryText;
        $this->isEncoded=$isEncoded;
    }
    public function GetData(){
      $servername = "localhost";
      $username = "admin";
      $password = "Ze8PGJLhhGG39GDj";
      $dbname = "myDB";
      $tableName=$this->tableName;
      $forbiddenWords=[1=>"drop",2=>"alter",3=>"create",4=>"update",5=>"inset"];
      for ($i=1; $i <6 ; $i++) {
        if(strpos($this->queryText, $forbiddenWords[$i] )!== false){
            echo "not good query";
            return;
        }
      }
      // Create connection
      $conn = new mysqli($servername, $username, $password, $dbname);
      // Check connection
      if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    }
    $this->data = $conn->query($this->queryText);
    $conn->close();
    return $this->data;
    }
    public function SetData(){
      $servername = "localhost";
      $username = "admin";
      $password = "Ze8PGJLhhGG39GDj";
      $dbname = "myDB";
      $tableName=$this->tableName;
      // Create connection
      if(strpos($this->queryText, "select" )!== false){
          echo "not good query";
          return;
      }
      $conn = new mysqli($servername, $username, $password, $dbname);
      // Check connection
      if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    }
    $this->data = $conn->query($this->queryText);
    $conn->close();
    }
}






?>
