<?php
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

require_once("DB.php");

$conn = Database::ConnectDb();

$response = array();
$response['error'] = null;
$response['ack'] = true;

if (isset($_GET['operation'])){
    if ($_GET['operation'] == "getNotes"){
        $sql = "SELECT id, comment, (dateTime * 1000) as dateTime from notes;";
        $result = mysqli_query($conn,$sql);
        if ($result->num_rows > 0) {
            while($row = $result->fetch_assoc()) {
                $response['data'][] = $row;
            }
        } else {
            $response['data'] = array();
        }
    }
    else if ($_GET['operation'] == 'addNote'){
        $comment = $post_body->comment;
        $dateTime = $post_body->dateTime;
        $sql = "INSERT INTO notes (comment, dateTime) VALUES ('$comment', ($dateTime/1000));";
        // echo $sql;exit;
        $result = mysqli_query($conn, $sql);
        if (mysqli_insert_id($conn)){
            $response['data'] = mysqli_insert_id($conn);
        }
    }
    else if ($_GET['operation'] == 'updateNote'){
        $comment = $post_body->comment;
        $dateTime = $post_body->dateTime;
        $id = $post_body->id;
        $sql = "UPDATE notes SET comment = '$comment', dateTime = $dateTime where id = $id;;";
        // echo $sql;exit;
        $result = mysqli_query($conn, $sql);
        if (mysqli_affected_rows($conn)){
            $response['data'] = true;
        }
    }
    else if ($_GET['operation'] == 'deleteNote'){
        $id = $post_body->id;
        $sql = "DELETE FROM notes where id = $id;";
        $result = mysqli_query($conn, $sql);
        if (mysqli_affected_rows($conn)){
            $response['data'] = true;
        }
    }
    else{
        $response['error'] = "no operation specified..";
    }
    if (sizeof($response['data'])){
        echo json_encode($response);
    }
    else{
        echo json_encode($response);
    }
    exit;
}
else{
    echo "please specify operation..";
}
?>