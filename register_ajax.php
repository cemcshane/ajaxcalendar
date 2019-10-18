<?php

header("Content-Type: application/json"); // Since we are sending a JSON response here (not an HTML document), set the MIME Type to application/json

//Because you are posting the data via fetch(), php has to retrieve it elsewhere.
$json_str = file_get_contents('php://input');
//This will store the data into an associative array
$json_obj = json_decode($json_str, true);

//Variables can be accessed as such:
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