<?php
// making output presentable
header("Content-Type:application/json");

// to make this work on localhost ignoring the CORS
$post_body = json_decode(file_get_contents('php://input'));
if (isset($_SERVER['HTTP_ORIGIN'])) {
    //header("Access-Control-Allow-Origin: {$_SERVER['HTTP_ORIGIN']}");
    header("Access-Control-Allow-Origin: *");
    header('Access-Control-Allow-Credentials: true');    
    header("Access-Control-Allow-Methods: GET, POST, OPTIONS"); 
}   
if ($_SERVER['REQUEST_METHOD'] == 'OPTIONS') {
    if (isset($_SERVER['HTTP_ACCESS_CONTROL_REQUEST_METHOD']))
        header("Access-Control-Allow-Methods: GET, POST, OPTIONS");         
    if (isset($_SERVER['HTTP_ACCESS_CONTROL_REQUEST_HEADERS']))
        header("Access-Control-Allow-Headers:{$_SERVER['HTTP_ACCESS_CONTROL_REQUEST_HEADERS']}");

    exit(0);
} 

// calling the DB in
require_once("DB.php");

// making a connection object
$conn = Database::ConnectDb();

// defining response data
$response = array();
$response['error'] = null;
$response['ack'] = true;

function fillResponseWithError ($err = "Something went wrong..") {
    // to avoid code duplication, it'll prepare the response object based on the error message..
    global $response;
    $response['ack'] = false;
    $response['error'] = true;
    $response['message'] = $err;
}

function getNotes () {
    global $response, $conn;
    $sql = "SELECT id, comment, (dateTime * 1000) as dateTime from notes;";
    $stmt = $conn->prepare($sql); 
    $stmt->execute();
    if ($stmt->rowcount()){
        while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
            $response['data'][] = $row;
        }
    }
    else{
        $response['data'] = array();
    }
}

function addNote() {
    global $response, $conn, $post_body;
    if ($post_body && $post_body->comment){
        $comment = $post_body->comment;
        $dateTime = $post_body->dateTime;
        $sql = "INSERT INTO notes (comment, dateTime) VALUES (?, (?/1000));";
        $queryVars = [$comment, $dateTime];
        $stmt = $conn->prepare($sql);
        $stmt->execute($queryVars);
        if ($conn->lastInsertId()){
            $response['data'] =$conn->lastInsertId();
        }
        else{
            fillResponseWithError();
        }
    }
    else{
        fillResponseWithError("No post data specified..");
    }
}

function updateNote(){
    global $response, $conn, $post_body;
    $comment = $post_body->comment;
    $dateTime = $post_body->dateTime;
    $id = $post_body->id;
    $sql = "UPDATE notes SET comment = ?, dateTime = (?/1000) where id = ?;";
    $queryVars = [$comment, $dateTime, $id];
    $stmt = $conn->prepare($sql);
    $stmt->execute($queryVars);
    if ($stmt->rowcount()){
        $response['data'] = $stmt->rowcount();
    }
    else{
        fillResponseWithError();
    }
}

function deleteNote(){
    global $response, $conn, $post_body;
    $id = $post_body->id;
    $sql = "DELETE FROM notes where id = ?;";
    $stmt = $conn->prepare($sql);
    $queryVars = [$id];
    $stmt->execute($queryVars);
    if ($stmt->rowcount()){
        $response['data'] = $stmt->rowcount();
    }
    else{
        fillResponseWithError();
    }
}

if (isset($_GET['operation'])){
    if ($_GET['operation'] == "getNotes"){
        getNotes();
    }
    else if ($_GET['operation'] == 'addNote'){
        addNote();
    }
    else if ($_GET['operation'] == 'updateNote'){
        updateNote();
    }
    else if ($_GET['operation'] == 'deleteNote'){
        deleteNote();
    }
    else{
        fillResponseWithError("Invalid operation specified..");
    }
    if (isset($response['data']) && sizeof($response['data'])){
        echo json_encode($response);
    }
    else{
        echo json_encode($response);
    }
    exit;
}
else{
    fillResponseWithError("No operation specified..");
    echo json_encode($response);
    exit;
}
?>