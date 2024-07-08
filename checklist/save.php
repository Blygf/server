<?php
session_start();
include("classes/autoload.php");

$response = array('status' => 'error', 'message' => 'Invalid request');

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $DB = new Database();

    $session_token = $_POST['session_token'] ?? '';
    $challenge_id = $_POST['challenge_id'] ?? '';
    $status = $_POST['status'] ?? '';

    if (!empty($session_token) && !empty($challenge_id) && !empty($status)) {
        // Check if the session token is valid
        $query = "SELECT userid FROM sessions WHERE token = '$session_token' LIMIT 1";
        $result = $DB->read($query);
        $userid = $result[0]['userid'];
        $query = "SELECT challengeid FROM challenges WHERE userid = '$userid' AND challengeid = '$challenge_id' LIMIT 1";
        $result = $DB->read($query);

        if ($result) {
            $challenge_id_to_change = $result[0]['challengeid'];

            if ($status === 'CHECKED') {
                $query = "insert into completed (challengeid) values ('$challenge_id_to_change')";
                $DB->save($query);

                $response = array('status' => 'success', 'message' => 'Challenge checked successfully');
            } elseif ($status === 'UNCHECKED') {
                $query = "DELETE FROM completed WHERE challengeid = '$challenge_id_to_change'";
                $DB->save($query);
                $response = array('status' => 'success', 'message' => 'Challenge unchecked successfully');
            } else {
                $response['message'] = 'Invalid status';
            }
        } else {
            $response['message'] = 'Invalid session token';
        }
    } else {
        $response['message'] = 'Missing required parameters';
    }
}

header('Content-Type: application/json');
echo json_encode($response);
?>
