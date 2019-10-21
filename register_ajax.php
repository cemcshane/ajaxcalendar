<?php
// Code in this file taken/modified from "Logging in a User" section of AJAX class wiki
header("Content-Type: application/json");

$json_str = file_get_contents('php://input');

$json_obj = json_decode($json_str, true);


$createusername = (string) $json_obj['createusername'];
$createpassword = (string) $json_obj['createpassword'];
if(!preg_match('/^\w+$/', $createusername)){
    echo json_encode(array(
        "success" => false,
        "message" => "Invalid username."
    ));
    exit;
}
if(!preg_match('/^\w+$/', $createpassword)){
    echo json_encode(array(
        "success" => false,
        "message" => "Invalid password."
    ));
    exit;
}

require 'database.php';
$newsafepass = password_hash($createpassword, PASSWORD_DEFAULT);
$stmt = $mysqli->prepare("insert into users (username, password) values (?, ?)");
if(!$stmt){
	printf("Query Prep Failed: %s\n", $mysqli->error);
	exit;
}
$stmt->bind_param('ss', $createusername, $newsafepass);
$stmt->execute();
$stmt->close();

echo json_encode(array(
    "success" => true,
    "message" => "You've been successfully registered! Log in to start using your calendar."
));
exit;

?>