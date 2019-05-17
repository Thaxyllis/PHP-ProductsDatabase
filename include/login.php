<?php

class Login {
	
	private $username;
	private $password;
	
	public function __construct($db) {

		$session = session_start();

		if((!isset($_SESSION['LoggedIn']) || ($_SESSION['LoggedIn'] != 'true'))) {
			if((isset($_POST['username'])) && (isset($_POST['password']))) {
				$username = $_POST['username'];
				$password = hash('sha256', $_POST['password']);
				if($db->checkLogin($username, $password)) {
					$this->username = $_POST['username'];
					$this->password = $_POST['password'];
					$_SESSION['LoggedIn'] = 'true';
					$_SESSION['username'] = $_POST['username'];
				} else {
					$loginPage = new Template();
					$loginBody = $loginPage->getHTML('login');
					echo $loginBody;
				}
			} else {
				$loginPage = new Template();
				$loginBody = $loginPage->getHTML('login');
				echo $loginBody;
			}
		}
		
	}
	
	public function getLoggedIn() {
		if ($_SESSION['LoggedIn'] == 'true') return true; else return false;
	}	
	
	private function authenticate($username, $password) {
	}
	
}
?>