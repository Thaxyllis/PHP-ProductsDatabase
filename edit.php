<?php
include "config.php";
include "include/database.php";
include "include/languages.php";
include "include/template.php";
include "include/login.php";

	// Authenticate
	$login = new Login($db);	
	if (!$login->getLoggedIn()) return;

	$db = new DB($host, $db, $user, $pass, $charset);
	
	if(isset($_GET['id'])) {
		$product = $db->getSingleProduct($_GET['id']);
		
			$page = new Template();
			
			$middle = $page->getHTML('edit');
			$middle = str_replace('{NAME}', $product['name'], $middle);
			$middle = str_replace('{PRICE}', $product['price'], $middle);
			$middle = str_replace('{QUANTITY}', $product['quantity'], $middle);						
			$middle = str_replace('{DESCRIPTION}', $product['description'], $middle);
			$middle = str_replace('{ID}', $product['id'], $middle);						
		
			$header = $page->getHTML('header');
			$footer = $page->getHTML('footer');
			$body = $header.$middle.$footer;			
			echo $body;	
	} 
?>