    <?php
    header("Content-Type: application/json");
    $json_str = file_get_contents('php://input');
    $json_obj = json_decode($json_str, true);
    $eventold = (string) $json_obj['eventcontentold'];
    $dateold = (string) $json_obj['dateold'];
    $timeold = (string) $json_obj['timeold'];
    $eventnew = (string) $json_obj['eventcontentnew'];
    $datenew = (string) $json_obj['datenew'];
    $timenew = (string) $json_obj['timenew'];
    $token = (string) $json_obj['token'];
    ini_set("session.cookie_httponly", 1);
    session_start();
    $userid = $_SESSION['user_id'];
    // Regular expression for event description found on https://stackoverflow.com/questions/4297173/regex-for-description-form-input
    if((!preg_match("/^[0-9a-z A-ZäöüÄÖÜ_\-']+$/", $eventold))||(!preg_match("/^[0-9a-z A-ZäöüÄÖÜ_\-']+$/", $eventnew))){
        echo json_encode(array(
            "success" => false,
            "message" => "Invalid event input."
        ));
        exit;
    }
    // Regular expression for date found on https://www.regular-expressions.info/dates.html
    if((!preg_match('/^(19|20)\d\d[- \/.](0[1-9]|1[012])[- \/.](0[1-9]|[12][0-9]|3[01])$/', $dateold))||(!preg_match('/^(19|20)\d\d[- \/.](0[1-9]|1[012])[- \/.](0[1-9]|[12][0-9]|3[01])$/', $datenew))){
        echo json_encode(array(
            "success" => false,
            "message" => "Invalid date input."
        ));
        exit;
    }
    // Regular expression for time found on https://stackoverflow.com/questions/1494671/regular-expression-for-matching-time-in-military-24-hour-format
    if((!preg_match('/^([01]\d|2[0-3]):?([0-5]\d)$/', $timeold))||(!preg_match('/^([01]\d|2[0-3]):?([0-5]\d)$/', $timenew))){
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
        $dateparts = explode("-", $dateold);
        $yearold = (int) $dateparts[0];
        $monthold = (int) $dateparts[1];
        $dayold = (int) $dateparts[2];
        $timedigparts = explode(":", $timeold);
        $time1old = (int) $timedigparts[0];
        $time2old = (int) $timedigparts[1];
        $stmt = $mysqli->prepare("select event_id from events where event_user_id=? and content=? and year=? and month=? and day=? and time1=? and time2=?");
        if(!$stmt){
            echo json_encode(array(
                "success" => false,
                "message" => "Query Prep Failed. Make sure all fields have an input."
            ));
            exit;
        }
        $stmt->bind_param('isiiiii', $userid, $eventold, $yearold, $monthold, $dayold, $time1old, $time2old);
        $stmt->execute();
        $stmt->bind_result($eventid);
        $stmt->fetch();
        $stmt->close();
        require 'database.php';
        $dateparts2 = explode("-", $datenew);
        $yearnew = (int) $dateparts2[0];
        $monthnew = (int) $dateparts2[1];
        $daynew = (int) $dateparts2[2];
        $timedigparts2 = explode(":", $timenew);
        $time1new = (int) $timedigparts2[0];
        $time2new = (int) $timedigparts2[1];
        $stmt = $mysqli->prepare("update events set content=?, year=?, month=?, day=?, time1=?, time2=? where event_id=?");
        if(!$stmt){
            echo json_encode(array(
                "success" => false,
                "message" => "Event not found."
            ));
            exit;
        }
        $stmt->bind_param('siiiiii', $eventnew, $yearnew, $monthnew, $daynew, $time1new, $time2new, $eventid);
        $stmt->execute();
        $stmt->close();
        echo json_encode(array(
            "success" => true,
            "message" => "Your event has been modified successfully!"
        ));
        exit;
    ?>