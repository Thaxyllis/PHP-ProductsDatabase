<?php
// INIT
session_start();
error_reporting(E_ALL & ~E_NOTICE);


include "config.php";
require "include/rest_products.php";


$products = new Products();
 
// PROCESS REQUEST
header('Content-Type: application/json');
$session = session_start();
if (isset($_POST['req'])) { switch ($_POST['req']) {
  default:
    echo json_encode([
      "status" => false,
      "message" => "Invalid Request"
    ]);
    break;
 
  case "get-all":
    check_session();
    $all = $products->getAll($_POST['lang']);
    echo json_encode([
      "status" => $all==false?false:true,
      "data" => $all
    ]);
    break;

  case "get-languages":
    check_session();
    $all = $products->getLanguages();
    echo json_encode([
      "status" => $all==false?false:true,
      "data" => $all
    ]);
    break;

  case "get-single-product":
    check_session();
    $usr = $products->getSingleProduct($_POST['product']);
    echo json_encode([
      "status" => $usr==false?false:true,
      "data" => $usr
    ]);
    break;

case "addProduct":
    check_session();
    $add = $products->addProduct($_POST['price'], $_POST['quantity'], $_POST['name'], $_POST['description'], $_POST['lang']); 
    echo json_encode([
      "status" => $add,
      "message" => $add ? "Product Added" : "Error adding product"
    ]); 
    break;


  case "remove":
    check_session();
    $rem = $products->remove($_POST['id']);
    echo json_encode([
      "status" => $rem,
      "message" => $rem ? "Product removed" : "Error removing product"
    ]);     
    break;


  case "update":
    check_session();
    $upd = $products->update($_POST['id'], $_POST['price'], $_POST['quantity'], $_POST['name'], $_POST['description'], $_POST['lang']); 
    echo json_encode([
      "status" => $upd,
      "message" => $upd ? "Product updated" : "Error updating product"
    ]);     
    break;

  case "updateLang":
    check_session();
    $updL = $products->updateProductLang($_POST['id'], $_POST['name'], $_POST['description'], $_POST['lang']); 
    echo json_encode([
      "status" => $updL,
      "message" => $updL ? "Product language updated" : "Error updating product language"
    ]);     
    break;

  case "login":
    if (is_array($_SESSION['user'])) {
      die(json_encode([
        "status" => true,
        "message" => "Already signed in"
      ]));
    }
      $login = $products->login($_POST['user'], $_POST['pass']);
      if(is_array($login)) {
	      $_SESSION['user'] = true;
      } 
      echo json_encode([
      "status" => $login==false?false:true,
      "message" => is_array($login) ? "OK" : "Invalid login"
    ]);

    break;
  
  case "logoff":
    unset($_SESSION['user']);
    echo json_encode([
      "status" => true,
      "message" => "Logged off"
    ]);
    break;
}}

function check_session() {
    if (!$_SESSION['user']) {
      die(json_encode([
        "status" => true,
        "message" => "Not logged in"
      ]));
    }
}
?>