<?php
// Code in this file taken/modified from "Logging in a User" section of AJAX class wiki
header("Content-Type: application/json");

$json_str = file_get_contents('php://input');

$json_obj = json_decode($json_str, true);

$username = (string) $json_obj['username'];
$password = (string) $json_obj['password'];
if(!preg_match('/^\w+$/', $username)){
    echo json_encode(array(
        "success" => false,
        "message" => "Invalid input."
    ));
    exit;
}
if(!preg_match('/^\w+$/', $password)){
    echo json_encode(array(
        "success" => false,
        "message" => "Invalid input."
    ));
    exit;
}
// The code below was obtained from the "Checking Passwords" section example in the Web Security 2 class wiki
if((preg_match('/\w+/', $password))&&(preg_match('/\w+/', $username))){

    require 'database.php';
    $stmt = $mysqli->prepare("SELECT COUNT(*), id, password FROM users WHERE username=?");
    $stmt->bind_param('s', $username);
    $stmt->execute();
    $stmt->bind_result($cnt, $user_id, $pwd_hash);
    $stmt->fetch();
    $pwd_guess = $password;
    $validated = false;
    if(($cnt == 1 && password_verify($pwd_guess, $pwd_hash))){
        $validated = true;
    }

    if($validated){
        ini_set("session.cookie_httponly", 1);
        session_start();
        $_SESSION['user_id'] = $user_id;
        $_SESSION['username'] = $username;
        $_SESSION['token'] = bin2hex(openssl_random_pseudo_bytes(32)); 

        echo json_encode(array(
            "success" => true,
            "usrnm" => $username,
            "token" => $_SESSION['token'],
            "userid" => $_SESSION['user_id']
        ));
        exit;
    }else{
        echo json_encode(array(
            "success" => false,
            "message" => "Incorrect username or password."
        ));
        exit;
    }
}
?>