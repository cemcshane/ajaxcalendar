<?php
    header("Content-Type: application/json");
    $json_str = file_get_contents('php://input');
    $json_obj = json_decode($json_str, true);
    $month = (int) $json_obj['monthnum'];
    $year = (int) $json_obj['year'];
    $token = (string) $json_obj['token'];
    ini_set("session.cookie_httponly", 1);
    session_start();
    $userid = $_SESSION['user_id'];
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
        $stmt = $mysqli->prepare("select event_id, day, content, time1, time2 from events where event_user_id=? and year=? and month=? order by day asc, time1 asc, time2 asc");
        if(!$stmt){
            echo json_encode(array(
                "success" => false,
                "message" => "Query Prep Failed."
            ));
            exit;
        }
        $stmt->bind_param('iii', $userid, $year, $month);
        $stmt->execute();
        $stmt->bind_result($eventid, $day, $event, $time1, $time2);
        // All code below found on https://stackoverflow.com/questions/37242960/mysqli-prepare-wont-output-as-json
        $eventArray = array();
        while($stmt->fetch()){
            $eventArray[]= array(
                "eventid" => $eventid,
                "day" => $day,
                "time1" => $time1,
                "time2" => $time2,
                "event" => $event
            );
        }
        $stmt->close();
        echo json_encode($eventArray);
        exit;
?>