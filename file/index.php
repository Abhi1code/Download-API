<?php
/* 
API to serve downloaded files
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

        if(!strcmp($status,"200")){
            $array = array("code_status" => "200", "error_status" => "ok", "filename" => "https://matrixfrats.com/uploads/".$filename);
            echo json_encode($array);
        } else {
            $array = array("code_status" => "404", "error_status" => "Resource not found");
            echo json_encode($array);
        }
      
    } else {
        $array = array("code_status" => "404", "error_status" => "Resource not found");
        echo json_encode($array);
    }
    
} else {
    $array = array("code_status" => "401", "error_status" => "Authentication Failure");
    echo json_encode($array);
}
?>