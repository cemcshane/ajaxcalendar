<?php
    header("Content-Type: application/json");
    $json_str = file_get_contents('php://input');
    $json_obj = json_decode($json_str, true);
    $displaymonth = (int) $json_obj['displaymonth'];
    $displayyear = (int) $json_obj['displayyear'];
    $displayday = (int) $json_obj['displayday'];
    $token = (string) $json_obj['token'];
    ini_set("session.cookie_httponly", 1);
    session_start();
    $userid = $_SESSION['user_id'];
    if(!preg_match("/^[1-9]|1[012]$/", $displaymonth)){
        echo json_encode(array(
            "success" => false,
            "message" => "Invalid month."
        ));
        exit;
    }
    if(!preg_match("/^(19|20)\d\d$/", $displayyear)){
        echo json_encode(array(
            "success" => false,
            "message" => "Invalid year."
        ));
        exit;
    }
    if(!preg_match("/^([1-9]|[12][0-9]|3[01])$/", $displayday)){
        echo json_encode(array(
            "success" => false,
            "message" => "Invalid day."
        ));
        exit;
    }
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
    /* All the code below was obtained from the "Checking Passwords" section example in the Web Security 2 class wiki */
        require 'database.php';
        $stmt = $mysqli->prepare("select event_id, content, time1, time2 from events where event_user_id=? and year=? and month=? and day=? order by time1 asc");
        if(!$stmt){
            echo json_encode(array(
                "success" => false,
                "message" => "Query Prep Failed."
            ));
            exit;
        }
        $stmt->bind_param('iiii', $userid, $displayyear, $displaymonth, $displayday);
        $stmt->execute();
        $stmt->bind_result($eventid, $event, $time1, $time2);
        // All code below found on https://stackoverflow.com/questions/37242960/mysqli-prepare-wont-output-as-json
        $eventArray = array();
        while($stmt->fetch()){
            $eventArray[]= array(
                "eventid" => $eventid,
                "event" => $event,
                "time1" => $time1,
                "time2" => $time2
            );
        }
        $stmt->close();
        echo json_encode($eventArray);
        exit;
    ?>