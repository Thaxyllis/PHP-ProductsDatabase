<?php
	
	if($_GET['logout'] == 'true') {
		session_start();
		$var = "false";
		$_SESSION['LoggedIn'] = $var;
	}
		
	function postProc($db) {
	
		if(isset($_POST['add'])) {
			if(isset($_POST['price']) && isset($_POST['quantity']) && isset($_POST['name']) && isset($_POST['description'])) {
				(double) $product_price = trim(str_replace(',', '.', $_POST['price']));
				$product_quantity = trim($_POST['quantity']);
				$product_name = trim($_POST['name']);
				$product_description = trim($_POST['description']);
	
				$db->addProduct($product_price, $product_quantity, $product_name, $product_description, $lang_code);
			}
		}

		if(isset($_POST['save'])) {
			if(isset($_POST['id']) && isset($_POST['price']) && isset($_POST['quantity']) && isset($_POST['name']) && isset($_POST['description'])) {
				$product_id = $_POST['id'];
				(double) $product_price = trim(str_replace(',', '.', $_POST['price']));
				$product_quantity = trim($_POST['quantity']);
				$product_name = trim($_POST['name']);
				$product_description = trim($_POST['description']);
				$current_lang = $_POST['lang_code'];
	
				$db->updateProduct($product_id, $product_price, $product_quantity, $product_name, $product_description, $current_lang);
			}
		}

		if(isset($_POST['trans_save'])) {
			if(isset($_POST['id']) && isset($_POST['name']) && isset($_POST['description'])) {

				$product_id = $_POST['id'];
				$product_name = trim($_POST['name']);
				$product_description = trim($_POST['description']);
				$current_lang = $_POST['lang_code'];

				$db->updateProductLang($product_id, $product_name, $product_description, $current_lang);
			}
		}		
		
		if(isset($_GET['remove'])) {
			$product_id = $_GET['remove'];
	
			$db->removeProduct($product_id);
		}

	}
?>