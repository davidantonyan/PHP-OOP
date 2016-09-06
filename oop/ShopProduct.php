<?php

namespace OOP;

class ShopProduct
{
	private $title;
	private $authorMainName;
	private $authorFirstName;
	private $price; 
	private $discount = 0;
	private $id       = 0;
	
	public function __construct($title, $firstName, $mainName, $price)
	{
		$this->title 		   = $title;
		$this->authorMainName  = $mainName;
		$this->authorFirstName = $firstName;
		$this->price           = $price;
	}

	public function setId($id)
	{
		$this->id = $id;
	}

	public function getAuthorFirstName()
	{
		return $this->authorFirstName;
	}

	public function getAuthorMainName()
	{
		return $this->authorMainName;
	}

	public function setDiscount($num)
	{
		$this->discount = $num;
	}

	public function getDiscount()
	{
		return $this->discount;
	}

	public function getTitle()
	{
		return $this->title;
	}

	public function getPrice()
	{
		return ($this->price - $this->discount);
	}

	public function getAuthor()
	{
		return sprintf("%s %s",$this->authorFirstName, $this->authorMainName);
	}

	public function getSummaryLine()
	{
		return sprintf("%s ( %s )", $this->title, $this->getAuthor());
	}

	public static function getInstance($id, \PDO $pdo){
		$stmt   = $pdo->prepare("select * from products where id=?");
		$result = $stmt->execute(array($id));
		$row    = $stmt->fetch();

		if(empty($row)){ return null; }

		if($row['type'] === "book"){
			$product = new BookProduct(
				$row["title"],
				$row["firstname"],
				$row["mainname"],
				$row["price"],
				$row["numpages"]
			);
		}elseif($row['type'] === "cd"){
			$product = new CDProduct(
				$row["title"],
				$row["firstname"],
				$row["mainname"],
				$row["price"],
				$row["playlength"]
			);
		}else{
			$product = new ShopProduct(
				$row["title"],
				$row["firstname"],
				$row["mainname"],
				$row["price"]
			);
		}

		$product->setId($id);
		$product->setDiscount($row['discount']);

		return $product;
	}
}

class CDProduct extends ShopProduct
{
	private $playLength = 0;
	
	public function __construct($title, $firstName, $mainName, $price, $playLength)
	{
		parent::__construct($title, $firstName, $mainName, $price);
		$this->playLength = $playLength;
	}

	public function getPlayLength()
	{
		return $this->playLength;
	}

	public function getSummaryLine()
	{
		return sprintf("%s (playing time: %s)", parent::getSummaryLine(), $this->playLength);
	}
}


class BookProduct extends ShopProduct
{
	private $numPages = 0;
	
	public function __construct($title, $firstName, $mainName, $price, $numPages)
	{
		parent::__construct($title, $firstName, $mainName, $price);
		$this->numPages = $numPages;
	}

	public function getNumbersOfPages()
	{
		return $this->numPages;
	}

	public function getSummaryLine()
	{
		return sprintf("%s (book pages: %s)", parent::getSummaryLine(), $this->numPages);
	}
}

class ShopProductWriter
{
	public function write(ShopProduct $shopProduct)
	{
		print "{$shopProduct->getSummaryLine()}"." price: ({$shopProduct->getPrice()})\n";
	}
}


$dsn      = 'mysql:dbname=shop;host=127.0.0.1';
$user     = 'root';
$password = '';

try {

    $pdo = new \PDO($dsn, $user, $password);
    $pdo->setAttribute(\PDO::ATTR_ERRMODE, \PDO::ERRMODE_EXCEPTION);
    $obj = ShopProduct::getInstance(1, $pdo);

    var_dump($obj);

}catch(\PDOException $e) {
    print "Connection failed: {$e->getMessage()}";
}

// $product1 = new BookProduct("Heart of a Dog", "Mikhail", "Bulgakov", 5.99, 200);
// $product2 = new CDProduct("You Are So Beautiful", "Joe", "Cocker", 15.99, 2.39);
// $writer = new ShopProductWriter();

// $writer->write($product1); 
// $writer->write($product2);

//page 70


