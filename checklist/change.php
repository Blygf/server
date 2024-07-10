<?php
session_start();
include("classes/autoload.php");

$DB = new Database();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $method = isset($_POST['method']) ? filter_var($_POST['method'], FILTER_SANITIZE_STRING) : '';

    if ($method === 'DELETE') {
        // Handle DELETE method
        $token = isset($_POST['token']) ? filter_var($_POST['token'], FILTER_SANITIZE_STRING) : '';
        $challengeId = isset($_POST['challengeId']) ? filter_var($_POST['challengeId'], FILTER_SANITIZE_STRING) : '';
        $query = "SELECT userid FROM sessions WHERE token = '$token' LIMIT 1";
        $result = $DB->read($query);
        
        if ($result && isset($result[0]['userid'])) {
            $userid = $result[0]['userid'];

            if ($token && $challengeId) {
                $challengeIds = json_decode($challengeId, true); // Decode JSON array
                if (is_array($challengeIds)) {
                    foreach ($challengeIds as $item) {
                        // Example: Delete the challenge from the database
                        $query = "DELETE FROM challenges WHERE userid = '$userid' AND challengeid = '$item' limit 1";
                        $DB->save($query);
                    }
                    echo json_encode(['status' => 'success', 'message' => 'Challenge(s) deleted successfully']);
                } else {
                    echo json_encode(['status' => 'error', 'message' => 'Invalid challengeId format']);
                }
            } else {
                echo json_encode(['status' => 'error', 'message' => 'Missing token or challengeId']);
            }
        } else {
            echo json_encode(['status' => 'error', 'message' => 'Invalid token']);
        }
    } elseif ($method === 'EDIT') {
        // Handle EDIT method
        $token = $_POST['token'] ?? '';
        $name = $_POST['name'] ?? '';
        $description = $_POST['description'] ?? '';
        $challengeId = $_POST['challengeId'] ?? '';
        $query = "SELECT userid FROM sessions WHERE token = '$token' LIMIT 1";
        $result = $DB->read($query);
        
        if ($result && isset($result[0]['userid'])) {
            $userid = $result[0]['userid'];
            if ($userid && $token && $name && $description && $challengeId) {
                // Insert your EDIT logic here

                // Example: Update the challenge in the database
                $query = "UPDATE challenges SET name = '$name', description = '$description' WHERE userid = '$userid' AND challengeid = '$challengeId'";
                $result = $DB->save($query);

                if ($result) {
                    echo json_encode(['status' => 'success', 'message' => 'Challenge updated successfully']);
                } else {
                    echo json_encode(['status' => 'error', 'message' => 'Failed to update challenge']);
                }
            } else {
                echo json_encode(['status' => 'error', 'message' => 'Missing token, name, description, or challengeId']);
            }
        } else {
            echo json_encode(['status' => 'error', 'message' => 'Invalid token']);
        }
    } else {
        echo json_encode(['status' => 'error', 'message' => 'Invalid method']);
    }
} else {
    echo json_encode(['status' => 'error', 'message' => 'Invalid request method']);
}
?>
