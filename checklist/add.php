<?php
include("classes/autoload.php");

if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    $session_token = $_POST['session_token'] ?? '';
    $name = $_POST['name'] ?? '';

    $description = $_POST['description'] ?? '';

    if (empty($description))
    {
        $description = "No description";
    }
    $query = "select userid from sessions where token = '$session_token' limit 1";

    $DB = new Database();
    $result = $DB->read($query);

    if($result)
    {
        $userid = $result[0]['userid'];

        $query = "insert into challenges (userid, name, description, times_a_week) values ('$userid', '$name','$description', 7)";

        $DB = new Database();
        $DB->save($query);

        http_response_code(200);
        echo json_encode(['status' => 'success', 'message' => 'Data stored successfully']);
    } else 
    {
    http_response_code(405);
    echo json_encode(['status' => 'error', 'message' => 'Method not allowed']);
    }
    
} else {
    // Respond with a method not allowed code if the request method is not POST
    http_response_code(405);
    echo json_encode(['status' => 'error', 'message' => 'Method not allowed']);
}
?>
