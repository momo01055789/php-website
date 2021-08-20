<?php 
session_start();
$nonavbar = '';
$pagetitle = 'Login';
if(isset($_SESSION['username'])){
    header('location:dashboard.php');
}
include "includes/templates/header.php"; 
include_once "includes/functions/functions.php"; 
include "includes/languages/english.php"; 
include "./connect.php"; 
// check if user coing from post request
if($_SERVER['REQUEST_METHOD'] == 'POST'){

    $username = $_POST['user'];
    $password = $_POST['pass'];
    // check if user exists in database
    $stmt = $conn ->prepare("SELECT username, password, userid FROM users WHERE username = ? AND password = ? AND groupid = 1 LIMIT 1");
    $stmt->execute(array($username,$password));
    $row = $stmt->fetch();
    $count = $stmt->rowCount();
    // if count > 0 then
    if ($count > 0){
        $_SESSION['username'] = $username; //register session name
        $_SESSION['id'] = $row['userid'];
        header('location:dashboard.php'); //redirect to dashboard page
        exit();
    }
}
//Include navbar in all pages except admin
if(!isset($nonavbar)){include "includes/templates/navbar.php";}
?>
<form class="login" action="<?php echo $_SERVER['PHP_SELF'] ?>" method="POST">
<h4 class="text-center">Admin Login</h4>
    <input class="form-control" type="text" name="user" placeholder="Username" autocomplete="off"/>
    <input class="form-control" type="password" name="pass" placeholder="Password" autocomplete="new-password">
    <input class="btn btn-primary btn-block" type="submit" value="login"/>
</form>
<?php include "includes/templates/footer.php"; ?>
