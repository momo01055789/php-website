<?php 
session_start();
$pagetitle = 'Dashboard';
if(isset($_SESSION['username'])){
    include "includes/../connect.php";
    include "includes/languages/english.php";
    include "includes/templates/header.php";
    include_once "includes/functions/functions.php";
    include "includes/templates/navbar.php";
    include "includes/templates/footer.php";
    $stmt2 = $conn->prepare("SELECT COUNT(userid) FROM users");
    $stmt2 -> execute();
?>
<div class="container home-stats text-center">
    <h1>Dashboard</h1>
    <div class="row">
        <div class="col-md-3">
            <div class="stat st-members">
                Total members
                <span><a href ="members.php"><?php echo countitems('userid' ,'users'); ?></a></span>
            </div>
        </div>
        <div class="col-md-3">
            <div class="stat st-pending">
                Pending members
                <span><a href='members.php?do=manage&page=pending'><?php echo checkitem('regstatus','users',0); ?></a></span>
            </div>
        </div>
        <div class="col-md-3">
            <div class="stat st-items">
                Total items
                <span>522</span>
            </div>
        </div>
        <div class="col-md-3">
            <div class="stat st-comments">
                Total comments
                <span>555</span>
            </div>
        </div>
    </div>
</div>
<div class="container latest">
    <div class="row">
        <div class="col-sm-6">
            <div class="panel panel-default">
                <div class="panel-heading">Latest Registered Users</div>
                <div class="panel-body">
                    <ul class ="list-unstyled latest-users">
                    <?php $thelatest = getlatest('*' , 'users' , 'userid');
                    foreach ($thelatest as $user){
                        echo '<li>' .  $user['username'] . '<span class = "btn btn-success float-end"><a href="members.php?do=edit&userid=' . $user['userid'] . '">Edit</a></span></li>';
                    }
                    ?>
                    </ul>
                </div>
            </div>
        </div>
        <div class="col-sm-6">
            <div class="panel panel-default">
                <div class="panel-heading">Latest Items</div>
                <div class="panel-body">Test</div>
            </div>
        </div>
    </div>
</div>
<?php
}else{header('location:index.php');
        exit();
    }
?>