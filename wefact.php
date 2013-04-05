<?php
require_once 'config.php';
require_once 'wefact/api.php';

session_start();
// https://www.wefact.nl/wefact-hosting/api/debiteuren/

if(!isset($_SESSION['access_token']) && !isset($_SESSION['email'])){
  echo "[]";
} else if (isset($_GET["product"])){

  $api = new WeFactAPI();
  $parameters   = array(
            "Sort"      => "ProductName",
            "Order"     => "ASC",
            "ProductCode" => "",
            "ProductType" => "",
            "Search"    => $_GET["product"]
            );
  $product_list = $api->listProducts($WeFactApiKey, $parameters);

  echo "[";
  foreach($product_list->Result->Products as $key => $product) {
    if($key > 0){
      echo ",";
    }
    echo "{ \"name\": \"" . $product->ProductName . "\" }";
  }
  echo "]";
  //print_r($product_list->Result->Products);

} else if (isset($_GET["bedrijf"])){

  $api = new WeFactAPI();
  $parameters   = array(
            "Sort"    => "CompanyName",
            "Order"   => "ASC",
            "Search"  => $_GET["bedrijf"]
            );
  $debtor_list  = $api->listDebtors($WeFactApiKey, $parameters);

  echo "[";
  foreach($debtor_list->Result->Debtors as $key => $debtor) {
    if($key > 0){
      echo ",";
    }
    echo "{ \"name\": \"" . $debtor->CompanyName . "\", ".
           "\"id\": \"" . $debtor->Identifier . "\", ".
           "\"code\": \"" . $debtor->DebtorCode . "\" }";
  }
  echo "]";
} else if (isset($_GET["bedrijfcode"])){
  // bedrijfcode == id
  $api = new WeFactAPI();
  $debtor  = $api->getDebtor($WeFactApiKey, $_GET["bedrijfcode"]);

  echo "[";
  foreach($debtor->Result as $key => $debtor) {
    if($key > 0){
      echo ",";
    }
    echo "{ \"name\": \"" . $debtor->CompanyName . "\", " .
           "\"id\": \"" . $debtor->Identifier . "\", " .
           "\"adres\": \"" . $debtor->Address . "\", " .
           "\"postcode\": \"" . $debtor->ZipCode . "\", " .
           "\"plaats\": \"" . $debtor->City . "\", " .
           "\"email\": \"" . $debtor->EmailAddress . "\", " .
           "\"telefoon\": \"" . $debtor->PhoneNumber . "\", " .
           "\"achternaam\": \"" . $debtor->SurName . "\", " .
           "\"voorletters\": \"" . $debtor->Initials . "\"" . " }";
  }
  echo "]";
}
?>