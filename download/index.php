<?php
/* 
 Initiate service worker
*/

error_reporting(0);
set_time_limit(0);
ignore_user_abort(true);

if(isset($_POST['url']) && !empty($_POST['url']) && isset($_POST['token']) && !empty($_POST['token'])){
    
    $url = $_POST['url'];
    $token = $_POST['token'];

    require("download_worker.php");
    $worker = new DownloadWorker($url);

    ob_end_clean();
    header("Connection: close\r\n");
    header("Content-Encoding: none\r\n");
    ob_start();

    $response = $worker->mconn->verifytoken($token);
    
    if($response){
      $time_gap = time() - $response['lastrequesttime'];
    
      if($time_gap >= 60){
        
        $worker->mconn->update_api_time($token, time());
        $response = json_encode(array("code_status" => "200", "error_status" => "ok", "unique_id" => $worker->return_random()));
        echo ($response);

      } else {

        $response = json_encode(array("code_status" => "429", "error_status" => "Too many request"));
        echo ($response);
      }
    } else {

      $array = array("code_status" => "401", "error_status" => "Authentication Failure");
      echo (json_encode($array));
    }

    $size = ob_get_length();
    header("Content-Length: $size");

    ob_end_flush();     // Strange behaviour, will not work
    flush();            // Unless both are called !
    ob_end_clean();

    $worker->start_download_worker();    // Download worker will be started

} else {

  $array = array("code_status" => "401", "error_status" => "Authentication Failure");
  echo json_encode($array);
}

?>