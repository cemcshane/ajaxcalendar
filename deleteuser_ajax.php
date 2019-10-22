<?php
    header("Content-Type: application/json");
    $json_str = file_get_contents('php://input');
    $json_obj = json_decode($json_str, true);
    $token = (string) $json_obj['token'];
    ini_set("session.cookie_httponly", 1);
    session_start();
    $userid = $_SESSION['user_id'];
    ini_set("session.cookie_httponly", 1);
    session_start();
    if(!preg_match('/^[1-9]+[0-9]*$/', $userid)){
        echo json_encode(array(
            "success" => false,
            "message" => "Invalid user."
        ));
        exit;
    }
    /* Code in the below if statement from "Cross-Site Request Forgery" section of Web Security 2 class wiki */
    if(!hash_equals($_SESSION['token'], $token)){
        echo json_encode(array(
            "success" => false,
            "message" => "Request forgery detected."
        ));
        exit;
    }
        require "database.php";
        $storyid = (int) $_POST['storyid'];
        $stmt = $mysqli->prepare("delete from events where event_user_id=?");
        if(!$stmt){
            echo json_encode(array(
                "success" => false,
                "message" => "Query Prep Failed. $mysqli->error"
            ));
            exit;
        }
        $stmt->bind_param('i', $userid);
        $stmt->execute();
        $stmt = $mysqli->prepare("delete from users where id=?");
        if(!$stmt){
            echo json_encode(array(
                "success" => false,
                "message" => "Query Prep Failed. $mysqli->error"
            ));
            exit;
        }
        $stmt->bind_param('i', $userid);
        $stmt->execute();
        $stmt->close();
        echo json_encode(array(
            "success" => true,
            "message" => "Account successfully deleted"
        ));
        exit;
?>