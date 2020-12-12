<?php
/* 
API for extracting download status
*/

error_reporting(0);

if(isset($_GET['id']) && !empty($_GET['id'])){
    
    require($_SERVER['DOCUMENT_ROOT']."/download_api/db/user_trans.php");
    $getconinfo = new Usertrans;
    
    $data = $getconinfo->extractinfo($_GET['id']);
    
    if($data){
        $url = $data['url'];
        $filesize = $data['filesize'];
        $updatesize = $data['updatesize'];
        $filename = $data['filename'];
        $status = $data['status'];
        $starting_time = $data['time'];

        $return_status_code; $return_status_info; $remaining_file_size; $estimated_time;

        if(!strcmp($status,"200")){
            $return_status_code = "200";
            $return_status_info = "Successfully downloaded";
        }

        if(!strcmp($status,"201")){
            $return_status_code = "201";
            $return_status_info = "Downloading in progress";
        }

        if(!strcmp($status,"202")){
            $return_status_code = "202";
            $return_status_info = "Downloading failed";
        }

        $remaining_file_size = (int)$filesize - (int)$updatesize;

        if(!strcmp($filesize, $updatesize)){
            $estimated_time = 0;
        } else {
            $t = time() - $starting_time;
            $estimated_time = ($t * $remaining_file_size) / $updatesize;
        }

        $array = array("code_status" => "200", "error_status" => "ok", "url" => $url, "file_status_code" => $return_status_code,
                        "file_status_info" => $return_status_info, "filesize" => $filesize, "updatesize" => $updatesize,
                        "remainingsize" => $remaining_file_size, "estimatedtime" => $estimated_time);
        echo json_encode($array);
      
    } else {
        $array = array("code_status" => "404", "error_status" => "Resource not found");
        echo json_encode($array);
    }
    
} else {
    $array = array("code_status" => "401", "error_status" => "Authentication Failure");
    echo json_encode($array);
}
?>