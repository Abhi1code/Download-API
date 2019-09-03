<?php
/* 
File Handling
*/

error_reporting(0);

if($_FILES["file"]["name"] != ''){

    require("../db/user_trans.php");
    $getconinfo = new Usertrans;

    //file extraction
    $name = $_FILES["file"]["name"];
    $size = $_FILES["file"]["size"];
    $test = explode(".", $_FILES["file"]["name"]);
    $extension = end($test);
    $random = generate_random();
    $filename = $random.".".$extension;
    //echo $name."  ".$filename;

    $location = '../download/upload/' .$filename;

    $getconinfo->savefilesize($size, $name, $random, "upload/".$filename, 200, time(), $size);
    move_uploaded_file($_FILES["file"]["tmp_name"], $location);

    $array = array("code_status" => "200", "error_status" => "ok", "unique_id" => $random);
    echo json_encode($array);

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