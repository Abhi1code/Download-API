<?php
/**
 * user transaction like input and output to database
 */
class Usertrans
{

	private $mconn;
	
	function __construct()
	{
		require_once('db_connect.php');
		$conn = new Dbconnect;
		$this->mconn = $conn->establish_conn();
			
	}
	
	public function savefilesize($size, $url, $name, $random, $filename, $status, $time, $updatesize=0){
		$sql = "INSERT INTO `filedata`(`id`, `url`, `filesize`, `updatesize`, `random`, `filename`, `status`, `time`) VALUES (null, :url, :size, :updatesize, :random, :filename, :status, :time)";
        $bind = $this->mconn->prepare($sql);
	
		$bind->bindParam(":url", $url);
		//$bind->bindParam(":name", $name);
		$bind->bindParam(":size", $size);
		$bind->bindParam(":random", $random);
		$bind->bindParam(":filename", $filename);
		$bind->bindParam(":status", $status);
		$bind->bindParam(":time", $time);
		$bind->bindParam(":updatesize", $updatesize);
        
        try {
        	
        	if ($bind->execute()) {
        		return true;
        	} else{
                return false;
        	}
        } catch (Exception $e) {
			//echo $this->error_response();
			return false;
        }
	}


	public function update_filesize($size, $random, $status){
		$sql = "UPDATE `filedata` SET `updatesize` = :size , `status` = :status WHERE `random` = :random";
    	$bind = $this->mconn->prepare($sql);
		$bind->bindParam(":size", $size);
		$bind->bindParam(":status", $status);
		$bind->bindParam(":random", $random);
		
    	try {
    		if ($bind->execute()) {
    			return true;
    		}else{
    			return false;
    		}
    	} catch (Exception $e) {
			//echo $e->getMessage();
			return false;
    	}
	}

	public function extractinfo($random){

		$sql = "SELECT * FROM `filedata` WHERE `random` = :random";
    	$bind = $this->mconn->prepare($sql);
		$bind->bindParam(":random", $random);

    	try {
    		if ($bind->execute()) {
    			$user = $bind->fetch(PDO::FETCH_ASSOC);
    		}
    	} catch (Exception $e) {
    		echo $e->getMessage();
    	}
    	return $user;
	}

	public function verifytoken($token){

		$sql = "SELECT `lastrequesttime` FROM `users` WHERE `token` = :token";
    	$bind = $this->mconn->prepare($sql);
		$bind->bindParam(":token", $token);

    	try {
    		if ($bind->execute()) {
				$user = $bind->fetch(PDO::FETCH_ASSOC);
				$response = ($user);
				if($response){
					return $response;
				}
    		}
    	} catch (Exception $e) {
			//echo $e->getMessage();
			return false;
    	}
    	return false;
	}

	public function update_api_time($token, $time){
		$sql = "UPDATE `users` SET `lastrequesttime` = :time WHERE `token` = :token";
		$bind = $this->mconn->prepare($sql);
		
		$bind->bindParam(":time", $time);
		$bind->bindParam(":token", $token);
		
    	try {
    		if ($bind->execute()) {
    			return true;
    		}else{
    			return false;
    		}
    	} catch (Exception $e) {
			//echo $e->getMessage();
			return false;
    	}
	}

}

?>