<?php
/**
 * Download worker
 */

class DownloadWorker
{
	public  $mconn;
	private $download_code = 200;
	private $downloading_code = 201;
	private $failed_code = 202;
	private $random;
	private $url;
	private $filesize;
	private $filename;

	function __construct($url, $name)
	{
		require_once("/home/matrixfr/public_html/download_api/db/user_trans.php");
		$this->mconn = new Usertrans;
        $this->url = $url;
        $this->name = $name;
		$this->generate_random();
	}

	public function start_download_worker(){
		$filesize = $this->curl_get_file_size($this->url);
		if($filesize < 0)$filesize = 0;
		
		if($filesize > 0){
			$filename = $this->generate_filename();
           if($filename){

			$st = $this->mconn->savefilesize($filesize, $this->url, $this->name, $this->random, $filename, $this->downloading_code, time());
            if($st){
			   $this->filesize = $filesize;
			   $this->filename = $filename;
			   $this->download_file();
			   die();
			}

		   } else {
			// Error in extracting file extension
			$this->mconn->savefilesize($filesize, $this->url, $this->random, -1, $this->failed_code, time());
			die();
		   }
		} else {
			// Error in extracting file size
			$this->mconn->savefilesize($filesize, $this->url, $this->random, -1, $this->failed_code, time());
			die();
		}
	}

	public function return_random(){
       return $this->random;
	}

	private function generate_filename(){
		$ext = $this->extract_extension($this->url);
		if($ext){
           return ("upload/".$this->random."-".$this->name.".".$ext);
		}
		return false;
	}
	
	private function extract_extension($url){
		$ext = pathinfo($url, PATHINFO_EXTENSION);
		if($ext){
		  return $ext;
		}
		return false;
	}
	  
	private function generate_random(){
		$random1 = rand(0, 10000);
		$random2 = rand(0, 10000);
		$random3 = rand(0, 10000);
	    $this->random = ($random1.$random2.$random3);
	}

	private function curl_get_file_size($url) {
		// Assume failure.
		$result = 0;
	  
		$curl = curl_init( $url );
	  
		// Issue a HEAD request and follow any redirects.
		curl_setopt( $curl, CURLOPT_NOBODY, true );
		curl_setopt( $curl, CURLOPT_HEADER, true );
		curl_setopt( $curl, CURLOPT_RETURNTRANSFER, true );
		curl_setopt( $curl, CURLOPT_FOLLOWLOCATION, true );
		//curl_setopt( $curl, CURLOPT_USERAGENT, get_user_agent_string() );
	  
		$data = curl_exec( $curl );
	  
		if( $data && !curl_errno($curl)) {
	      
	      $http_code = curl_getinfo($curl, CURLINFO_HTTP_CODE);
		  // http://en.wikipedia.org/wiki/List_of_HTTP_status_codes
		  if( $http_code == 200 || ($http_code > 300 && $http_code <= 308) ) {
			$result = curl_getinfo($curl, CURLINFO_CONTENT_LENGTH_DOWNLOAD);
			//echo $result;
		  }
		}
	  
	    curl_close( $curl );
		return $result;
	  }

	  private function download_file(){
		
		$file = fopen ($this->url, "r");
		$save = fopen ($this->filename, "a");

		$total_downloaded_length = 0;
		$temp = 0;
	    
		if (!$save) {
		$this->mconn->update_filesize($total_downloaded_length, $this->random, $this->failed_code);
		die();
		}
	  
		if (!$file) {
		$this->mconn->update_filesize($total_downloaded_length, $this->random, $this->failed_code);
		die();
		}
	
		while (!feof ($file)) {

			$line = fgets ($file, 5024);
			fwrite($save, $line);
			$total_downloaded_length = $total_downloaded_length + strlen($line);
			//echo $total_downloaded_length."\n";
			if(($total_downloaded_length - $temp) > (10*1024)){
			  $this->mconn->update_filesize($total_downloaded_length, $this->random, $this->downloading_code);
			  $temp = $total_downloaded_length;
			}

		}
		
		if($temp != $total_downloaded_length){
		$this->mconn->update_filesize($total_downloaded_length, $this->random, $this->download_code);
		}
	  
		fclose($save);
		fclose($file);
	 }
}

?>