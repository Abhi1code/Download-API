<?php
/**
 * database connection
 */
class Dbconnect
{
	private $hostname = 'localhost';
	private $dbname = 'test';
	private $user = 'root';
	private $password = '';
	
	public function establish_conn()
	{
		try {
			
			$conn = new PDO('mysql:host=' . $this->hostname . ';dbname=' .$this->dbname, $this->user, $this->password);
			$conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
			return $conn;

		} catch (PDOException $e) {
			echo "Database error :" . $e->getMessage();
		}
	}
}

?>