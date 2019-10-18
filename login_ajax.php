<?php

header("Content-Type: application/json"); // Since we are sending a JSON response here (not an HTML document), set the MIME Type to application/json

//Because you are posting the data via fetch(), php has to retrieve it elsewhere.
$json_str = file_get_contents('php://input');
//This will store the data into an associative array
$json_obj = json_decode($json_str, true);

//Variables can be accessed as such:
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

if((preg_match('/\w+/', $password))&&(preg_match('/\w+/', $username))){
//This is equivalent to what you previously did with $_POST['username'] and $_POST['password']

// Check to see if the username and password are valid.  (You learned how to do this in Module 3.)
    require 'database.php';
    // Use a prepared statement
    $stmt = $mysqli->prepare("SELECT COUNT(*), id, password FROM users WHERE username=?");
    // Bind the parameter
    // $user = (string) $_POST['usrnm1'];
    $stmt->bind_param('s', $username);
    $stmt->execute();
    // Bind the results
    $stmt->bind_result($cnt, $user_id, $pwd_hash);
    $stmt->fetch();
    $pwd_guess = $password;
    // Compare the submitted password to the actual password hash
    $validated = false;
    if(($cnt == 1 && password_verify($pwd_guess, $pwd_hash))){
        $validated = true;
    }

    //END EDIT

    if($validated){
        ini_set("session.cookie_httponly", 1);
        session_start();
        $_SESSION['user_id'] = $user_id;
        $_SESSION['username'] = $username;
        $_SESSION['token'] = bin2hex(openssl_random_pseudo_bytes(32)); 

        echo json_encode(array(
            "success" => true
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