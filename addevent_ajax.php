    <?php
    // Code in this file taken/modified from "Logging in a User" section of AJAX class wiki
    header("Content-Type: application/json");
    $json_str = file_get_contents('php://input');
    $json_obj = json_decode($json_str, true);
    $event = (string) $json_obj['eventcontent'];
    $date = (string) $json_obj['date'];
    $time = (string) $json_obj['time'];
    $token = (string) $json_obj['token'];
    ini_set("session.cookie_httponly", 1);
    session_start();
    $userid = $_SESSION['user_id'];
    // Regular expression for event description found on https://stackoverflow.com/questions/4297173/regex-for-description-form-input
    if(!preg_match("/^[0-9a-z A-ZäöüÄÖÜ_\-']+$/", $event)){
        echo json_encode(array(
            "success" => false,
            "message" => "Invalid event input."
        ));
        exit;
    }
    // Regular expression for date found on https://www.regular-expressions.info/dates.html
    if(!preg_match('/^(19|20)\d\d[- \/.](0[1-9]|1[012])[- \/.](0[1-9]|[12][0-9]|3[01])$/', $date)){
        echo json_encode(array(
            "success" => false,
            "message" => "Invalid date input."
        ));
        exit;
    }
    // Regular expression for time found on https://stackoverflow.com/questions/1494671/regular-expression-for-matching-time-in-military-24-hour-format
    if(!preg_match('/^([01]\d|2[0-3]):?([0-5]\d)$/', $time)){
        echo json_encode(array(
            "success" => false,
            "message" => "Invalid time input."
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
    ini_set("session.cookie_httponly", 1);
    session_start();
    /* Code in the below if statement from "Cross-Site Request Forgery" section of Web Security 2 class wiki */
    if(!hash_equals($_SESSION['token'], $token)){
        echo json_encode(array(
            "success" => false,
            "message" => "Request forgery detected."
        ));
        exit;
    }
        require 'database.php';
        $dateparts = explode("-", $date);
        $year = (int) $dateparts[0];
        $month = (int) $dateparts[1];
        $day = (int) $dateparts[2];
        $timedigparts = explode(":", $time);
        $time1 = (int) $timedigparts[0];
        $time2 = (int) $timedigparts[1];
        $stmt = $mysqli->prepare("insert into events (event_user_id, content, year, month, day, time1, time2) values (?, ?, ?, ?, ?, ?, ?)");
        if(!$stmt){
            echo json_encode(array(
                "success" => false,
                "message" => "Query Prep Failed. $mysqli->error"
            ));
            exit;
        }
        $stmt->bind_param('isiiiii', $userid, $event, $year, $month, $day, $time1, $time2);
        $stmt->execute();
        $stmt->close();
        echo json_encode(array(
            "success" => true,
            "message" => "Your event has been added successfully!"
        ));
        exit;
    ?>