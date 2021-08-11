<?php
include_once 'GetDataFromDB.php';
function AddNewAffiliate($id){
        $dal = new DAL("wishlist","INSERT INTO wishlist
        VALUES ($id,'')");
        $dal->SetData();
}
function AddTowishlist($id,$wishlistItems){
  $dal = new DAL("wishlist","select * from wishlist where ID=".$id);
  $userData=$dal->GetData();
  $oldwishlistItems;
  while($row=$userData->fetch_assoc()){
    $oldwishlistItems=$row["wishlistItems"];
  }
  $wishlistItems.=$oldwishlistItems;
  $dal = new DAL("wishlist","UPDATE wishlist SET wishlistItems = $wishlistItems WHERE ID=".$id);
  $dal->GetData();
}


 ?>
