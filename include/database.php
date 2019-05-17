<?php
class DB {

	private $host;
	private	$db;
	private	$user;
	private	$pass;
	private	$charset;
	
	// Main connection constructor
	public function __construct($host, $db, $user, $pass, $charset) {
		if(!isset($this->pdo)) {
			$this->host = MYSQL_HOST;
			$this->db = MYSQL_DB;
			$this->user = MYSQL_USER;
			$this->pass = MYSQL_PASS;
			$this->charset = MYSQL_CHAR;
			$dsn = "mysql:host=$this->host;dbname=$this->db;charset=$this->charset";
			$options = [
			    PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,
			    PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
			    PDO::ATTR_EMULATE_PREPARES   => false,
			];
			try {
			     $db = new PDO($dsn, $this->user, $this->pass, $this->options);
			     $this->pdo = $db;
			} catch (\PDOException $e) {
			     throw new \PDOException($e->getMessage(), (int)$e->getCode());
			}
			
		}
	}
	
	// Adds product to database
	public function addProduct($product_price, $product_quantity, $product_name, $product_description, $lang_code) {
		try {
			$this->pdo->beginTransaction();
		
			$query = "INSERT INTO `products` (price, quantity)
				  VALUES (:price, :quantity);";		  
	
			$stmt = $this->pdo->prepare($query);

			$stmt->bindValue(":price", $product_price);
			$stmt->bindValue(":quantity", $product_quantity);
			$stmt->execute();	
	
			$last_id = $this->pdo->lastInsertId();
		
			$query = "INSERT INTO `products_description` (product_id, lang_code, name, description)
				  VALUES (:id, :lang_code, :name, :description);";		  

			$stmt = $this->pdo->prepare($query);
		
			$stmt->bindValue(":id", $last_id);
			$stmt->bindValue(":lang_code", 1);	
			$stmt->bindValue(":name", $product_name);
			$stmt->bindValue(":description", $product_description);	
			$stmt->execute();	
		
			$this->pdo->commit();
		}
	
		catch (Exception $e) {
			echo $e->getMessage();
			$this->pdo->rollBack();
		}
	}
	
	// Change product values
	public function updateProduct($product_id, $product_price, $product_quantity, $product_name, $product_description, $lang_code) {
		try {
			$query = "UPDATE 
					`products` p INNER JOIN `products_description` pd 
				  SET
				  	p.price = :price, p.quantity = :quantity,
				  	pd.name = :name, pd.description = :description
				  WHERE	
				  	p.id = :id AND pd.product_id = :pid AND pd.lang_code = :lang";
			$stmt = $this->pdo->prepare($query);
			$stmt->bindValue(":id", $product_id);
			$stmt->bindValue(":pid", $product_id);	
			$stmt->bindValue(":price", $product_price);
			$stmt->bindValue(":quantity", $product_quantity);
			$stmt->bindValue(":name", $product_name);
			$stmt->bindValue(":description", $product_description);	
			$stmt->bindValue(":lang", $lang_code);
			  	
			$stmt->execute();	
		}
		
		catch (Exception $e) {
			echo $e->getMessage();
		}
	}

	public function updateProductLang($product_id, $product_name, $product_description, $lang_code) {
		try { 
			$stmtC = $this->pdo->query('SELECT * FROM `products_description` WHERE product_id = '.$product_id.' AND lang_code = '.$lang_code);
			
			if($row = $stmtC->fetch()) {
			
				$query = "UPDATE `products_description` SET name = :name, description = :description WHERE product_id = :id AND lang_code = :lang";
							  	
				$stmt = $this->pdo->prepare($query);
				$stmt->bindValue(":id", $product_id);
				$stmt->bindValue(":name", $product_name);
				$stmt->bindValue(":description", $product_description);	
				$stmt->bindValue(":lang", $lang_code);
			  	
				$stmt->execute();	
			} else {
				$query = "INSERT INTO `products_description` (product_id, lang_code, name, description)
					  VALUES (:id, :lang_code, :name, :description);";		  

				$stmt = $this->pdo->prepare($query);
		
				$stmt->bindValue(":id", $product_id);
				$stmt->bindValue(":lang_code", $lang_code);	
				$stmt->bindValue(":name", $product_name);
				$stmt->bindValue(":description", $product_description);	
				$stmt->execute();	
			}
		}
		
		catch (Exception $e) {
			echo $e->getMessage();
		}
	}

	
	
	// Remove all records about product
	public function removeProduct($id) {
		try {
			$this->pdo->beginTransaction();
	
			$query = "DELETE FROM `products` WHERE id=".$id;
			$stmt = $this->pdo->prepare($query);
			$stmt->execute();	

			$stmt = $this->pdo->query('SELECT * FROM `products_description` WHERE product_id = '.$id);

			while ($row = $stmt->fetch()) {
				$query2 = "DELETE FROM `products_description` WHERE desc_id=".$row['desc_id'];
				$stmt2 = $this->pdo->prepare($query2);
				$stmt2->execute();			
			}
	
		
			$this->pdo->commit();
		}
	
		catch (Exception $e) {
			echo $e->getMessage();
			$this->pdo->rollBack();
		}	
	}
	
	// Get single product data 
	public function getSingleProduct($id, $lang_code = 1) {		
		try {
			$stmt = $this->pdo->query('SELECT * FROM `products` INNER JOIN products_description WHERE products.id=products_description.product_id AND products.id='.$id.' AND products_description.lang_code ='.$lang_code);
			if($row = $stmt->fetch()) {
				$product_array = array("id"=>$row['id'], "name"=>$row['name'], "description"=>$row['description'], "price"=>$row['price'], "quantity"=>$row['quantity']);
			}
			return $product_array;
		}
		
		catch (Exception $e) {
			echo $e->getMessage();
		}
	}
	
	public function getProductTranslation($id, $lang_code) {
		try {
			$stmt = $this->pdo->query('SELECT * FROM `products_description` WHERE product_id = '.$id.' lang_code ='.$lang_code);
			if($row = $stmt->fetch()) {
				$product_array = array("id"=>$row['id'], "name"=>$row['name'], "description"=>$row['description'], "price"=>$row['price'], "quantity"=>$row['quantity']);
			}
			return $product_array;
		}
		
		catch (Exception $e) {
			echo $e->getMessage();
		}	
	}
	
	// Get all the products
	public function getMultipleProducts($lang_code = 1) {
		try {
			$products_array = [];
			$stmt = $this->pdo->query('SELECT * FROM `products` INNER JOIN products_description WHERE products.id=products_description.product_id AND products_description.lang_code='.$lang_code);
				
			while ($row = $stmt->fetch()) {
				$temp_array = array("id"=>$row['id'], "name"=>$row['name'], "description"=>$row['description'], "price"=>$row['price'], "quantity"=>$row['quantity']);
				array_push($products_array, $temp_array);
			}
		
			return $products_array;
		}
		
		catch (Exception $e) {
			echo $e->getMessage();
		}
	}

	// Generate languages info from database	
	public function getLanguages() {
		try {
			$languages_array = [];
			$stmt = $this->pdo->query('SELECT * FROM `languages`');
			
			while ($row = $stmt->fetch()) {
				array_push($languages_array, array("lang_code"=>$row['lang_code'], "name"=>$row['name']));
			}		
			return $languages_array;
		}

		catch (Exception $e) {
			echo $e->getMessage();
		}		
	}

	// Check admin/password
	public function checkLogin($user, $pass) {
		try {
			$stmt = $this->pdo->query("SELECT * FROM `users` WHERE username='".$user."' AND password='".$pass."'");
			
			if ($row = $stmt->fetch()) {
				return true;
			} else {
				return false;
			}
			return false;
		}

		catch (Exception $e) {
			echo $e->getMessage();
		}		
	}	
	
	


}
















?>