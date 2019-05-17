<?php

include "config.php";
include "include/database.php";
include "include/languages.php";
include "include/functions.php";
include "include/template.php";
include "include/login.php";

	// Initialize new database object
	$db = new DB($host, $db, $user, $pass, $charset);

	// Authenticate
	$login = new Login($db);	
	if (!$login->getLoggedIn()) return;
	
	// Initialize language object
	$langs = new Languages($db->getLanguages());

	// Form functions
	postProc($db);

	$products = $db->getMultipleProducts();

	$page = new Template();
	
	$block_raw = $page->getHTML('index_block1');
	$block_complete = '';
		
	foreach($products as $key => $val) {
		$temp = $block_raw;
		$temp = str_replace('{ID}', $val['id'], $temp);
		$temp = str_replace('{NAME}', $val['name'], $temp);
		$temp = str_replace('{DESCRIPTION}', $val['description'], $temp);
		$temp = str_replace('{PRICE}', $val['price'], $temp);
		$temp = str_replace('{QUANTITY}', $val['quantity'], $temp);
		$block_complete .= $temp;								
	}

	$header = $page->getHTML('header');
	$middle = $page->getHTML('index');
	$middle = str_replace('<!-- TEMPLATE BLOCK1 -->', $block_complete, $middle);
	$footer = $page->getHTML('footer');
	$body = $header.$middle.$footer;
	echo $body;

?>