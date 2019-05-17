<?php
class Products{
  private $pdo = null;
  private $stmt = null;
  public $error = "";

  /* [THE BASICS] */
  function __construct(){
    try {
      $this->pdo = new PDO(
        "mysql:host=".MYSQL_HOST.";dbname=".MYSQL_DB.";charset=".MYSQL_CHAR,
        MYSQL_USER, MYSQL_PASS, [
          PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
          PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
          PDO::ATTR_EMULATE_PREPARES => false,
        ]
      );
    } catch (Exception $ex) { die($ex->getMessage()); }
  }

  function __destruct(){
    if ($this->stmt!==null) { $this->stmt = null; }
    if ($this->pdo!==null) { $this->pdo = null; }
  }

  function query($sql, $cond=[]){
    try {
      $this->stmt = $this->pdo->prepare($sql);
      $this->stmt->execute($cond);
    } catch (Exception $ex) { 
      $this->error = $ex->getMessage();
      return false;
    }
    $this->stmt = null;
    return true;
  }

  function getAll($lang){
    $this->stmt = $this->pdo->prepare("SELECT * FROM `products` INNER JOIN `products_description` WHERE products.id=products_description.product_id AND products_description.lang_code=".$lang);
    $this->stmt->execute();
    $products = $this->stmt->fetchAll();
    return count($products)==0 ? false : $products;
  }
  
  function getLanguages(){
    $this->stmt = $this->pdo->prepare("SELECT * FROM `languages`");
    $this->stmt->execute();
    $langs = $this->stmt->fetchAll();
    return count($langs)==0 ? false : $langs;
  }

  function getSingleProduct($product, $lang){
    $this->stmt = $this->pdo->prepare("SELECT * FROM `products` INNER JOIN `products_description` WHERE products.id=products_description.product_id AND products.id=".$product." AND products_description.lang_code=".$lang);
    $this->stmt->execute();
    $product = $this->stmt->fetchAll();
    return count($product)==0 ? false : $product[0];
  }

  function addProduct($product_price, $product_quantity, $product_name, $product_description, $lang_code){
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
    $stmt->bindValue(":lang_code", $lang_code);	
    $stmt->bindValue(":name", $product_name);
    $stmt->bindValue(":description", $product_description);	
    $stmt->execute();	
		
    return $this->pdo->commit();
  }

  function remove($id){

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
    return $this->pdo->commit();
  }
  

  function update($product_id, $product_price, $product_quantity, $product_name, $product_description, $lang_code){
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
			  	
    return $stmt->execute();		
  }
  
    public function updateProductLang($product_id, $product_name, $product_description, $lang_code) {
    	$stmtC = $this->pdo->query('SELECT * FROM `products_description` WHERE product_id = '.$product_id.' AND lang_code = '.$lang_code);
	
    	if($row = $stmtC->fetch()) {
			
    		$query = "UPDATE `products_description` SET name = :name, description = :description WHERE product_id = :id AND lang_code = :lang";
							  	
		$stmt = $this->pdo->prepare($query);
		$stmt->bindValue(":id", $product_id);
		$stmt->bindValue(":name", $product_name);
		$stmt->bindValue(":description", $product_description);	
		$stmt->bindValue(":lang", $lang_code);
			  	
		return $stmt->execute();	
    	} else {
		$query = "INSERT INTO `products_description` (product_id, lang_code, name, description)
			  VALUES (:id, :lang_code, :name, :description);";		  

		$stmt = $this->pdo->prepare($query);
		
		$stmt->bindValue(":id", $product_id);
		$stmt->bindValue(":lang_code", $lang_code);	
		$stmt->bindValue(":name", $product_name);
		$stmt->bindValue(":description", $product_description);	
		return $stmt->execute();	
	}
  } 
  
  public function login($user, $pass) {   
    $this->stmt = $this->pdo->prepare("SELECT * FROM `users` WHERE username='".$user."' AND password='".hash('sha256', $pass)."'");
    $this->stmt->execute();
    $log = $this->stmt->fetchAll();    
    return count($log)==0 ? false : $log[0];
  }
 
}
?>