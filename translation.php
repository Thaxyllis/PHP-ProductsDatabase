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
		
	$langs = new Languages($db->getLanguages());
	
	$lang_array = $langs->getLangArray();
	
	$page = new Template();
	
	$block_raw = $page->getHTML('translation_block1');
	$block_complete = '';
	
	if(isset($_GET['id'])) {
		foreach ($lang_array as $key => $val) {
			$product = $db->getSingleProduct($_GET['id'], $val['lang_code']);

			$temp = $block_raw;
			$temp = str_replace('{ID}', $_GET['id'], $temp);
			$temp = str_replace('{NAME}', $product['name'], $temp);
			$temp = str_replace('{DESCRIPTION}', $product['description'], $temp);
			$temp = str_replace('{LANG}', $val['name'], $temp);
			$temp = str_replace('{LANG_CODE}', $val['lang_code'], $temp);			
			$block_complete .= $temp;
		}
		
		$header = $page->getHTML('header');
		$middle = $page->getHTML('translation');
		$middle = str_replace('<!-- TEMPLATE BLOCK1 -->', $block_complete, $middle);
		$footer = $page->getHTML('footer');
		$body = $header.$middle.$footer;
		echo $body;
	} 
?>