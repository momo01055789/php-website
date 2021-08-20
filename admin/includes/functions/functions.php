<?php
function getTitle() {

    global $pagetitle;
    if (isset($pagetitle)){
    echo $pagetitle;
    }else{ echo 'default';}
}
// redirect function
function redirecthome($themsg , $url = null , $seconds = 3){
    if($url === null){
        $url = 'index.php';
    }else{
        if(isset($_SERVER['HTTP_REFERER'])&&$_SERVER['HTTP_REFERER'] !== ''){
            $url = $_SERVER['HTTP_REFERER'];
        }else{
            $url = 'index.php';
        }
    }
    echo $themsg;
    echo "<div class = 'alert alert-danger'>You will be redirected to Homepage after $seconds seconds.</div>";
    header("refresh:$seconds;url=index.php");
    exit();
}
//function to check items in database
function checkitem($select,$from,$value){
    global $conn;
    $statement = $conn->prepare("SELECT $select FROM $from WHERE $select = ?");
    $statement ->execute(array($value));
    $count = $statement->rowCount();
    return $count;
}
//count number of items
function countitems($item ,$table){
    global $conn;
    $stmt2 = $conn->prepare("SELECT COUNT($item) FROM $table");
    $stmt2 -> execute();
    return $stmt2 ->fetchColumn();
}
//get latest function
function getlatest($select , $table , $order ,$limit = 5){
    global $conn;
    $getstmt = $conn ->prepare("SELECT $select FROM $table ORDER BY $order DESC LIMIT $limit");
    $getstmt->execute();
    $rows = $getstmt->fetchAll();
    return $rows;
}