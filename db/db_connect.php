<?php
/**
 * database connection
 */
require_once($_SERVER['DOCUMENT_ROOT'].'/download_api/config.php');

class Dbconnect
{
	private $hostname = DB_HOST;
	private $dbname = DB_NAME;
	private $user = DB_USERNAME;
	private $password = DB_PASSWORD;
	
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