<?php
/* 
File Handling
*/

error_reporting(0);

if($_FILES["file"]["name"] != '' && isset($_POST['token']) && $_POST['token'] != ''){

    require($_SERVER['DOCUMENT_ROOT']."/download_api/db/user_trans.php");
    $getconinfo = new Usertrans;

    $token = $_POST['token'];
    $fname = $_POST['name'];
    $response = $getconinfo->verifytoken($token);
    
    if($response){
        
    //file extraction
    $name = $_FILES["file"]["name"];
    $size = $_FILES["file"]["size"];
    $test = explode(".", $_FILES["file"]["name"]);
    $extension = end($test);
    $random = generate_random();
    $filename = $random."-".$fname.".".$extension;
    //echo $name."  ".$filename;

    $location = $_SERVER['DOCUMENT_ROOT']."/uploads/".$filename;

    $getconinfo->savefilesize($size, $name, $fname, $random, $filename, 200, time(), $size);
    move_uploaded_file($_FILES["file"]["tmp_name"], $location);

    $array = array("code_status" => "200", "error_status" => "ok", "unique_id" => $random);
    echo json_encode($array);
    
    } else {
        $array = array("code_status" => "401", "error_status" => "Authentication Failure");
        echo json_encode($array);
    }

} else {
    $array = array("code_status" => "401", "error_status" => "Authentication Failure");
    echo json_encode($array);
}

function generate_random(){
    $random1 = rand(0, 10000);
    $random2 = rand(0, 10000);
    $random3 = rand(0, 10000);
    return ($random1.$random2.$random3);
}

?>