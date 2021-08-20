<?php 
session_start();
$pagetitle = 'Members';
include "./connect.php";
if(isset($_SESSION['username'])){
    include "includes/languages/english.php";
    include "includes/templates/header.php";
    include_once "includes/functions/functions.php";
    include "includes/templates/navbar.php";
    include "includes/templates/footer.php";

    $do = '';
    if(isset($_GET['do'])){
        $do = $_GET['do'];
    }else{$do = 'manage';}
    if($do == 'manage'){ // manage members page
        $query ='';
        if(isset($_GET['page']) && $_GET['page'] == 'pending'){
            $query = 'AND regstatus = 0';
        }
        // select all users except admin
        $stmt = $conn->prepare("SELECT * FROM users WHERE groupid != 1 $query");
        $stmt -> execute();
        //assign to variable
        $rows = $stmt->fetchAll();
    ?>

        <h1 class="text-center">Manage Members</h1>
        <div class="container">
            <div class="table-responsive">
                <table class="main-table text-center table table-bordered">
                    <tr>
                        <td>id</td>
                        <td>username</td>
                        <td>email</td>
                        <td>fullname</td>
                        <td>registereddate</td>
                        <td>control</td>
                    </tr>
                    <?php foreach($rows as $row){
                        echo '<tr>';
                            echo '<td>' . $row['userid'] . '</td>';
                            echo '<td>' . $row['username'] . '</td>';
                            echo '<td>' . $row['email'] . '</td>';
                            echo '<td>' . $row['fullname'] . '</td>';
                            echo '<td></td>';
                            echo '<td>
                            <a href="members.php?do=edit&userid=' . $row['userid'] .  '"class="btn btn-success">Edit</a>
                            <a href="members.php?do=delete&userid=' . $row['userid'] .  '"class="btn btn-danger confirm">Delete</a>';

                        if($row['regstatus'] == 0){
                            echo '<a href="members.php?do=activate&userid=' . $row['userid'] .  '"class="btn btn-info activate">Activate</a>';
                        }

                        echo "</td>";
                        echo '</tr>';
                    } ?>
                </table>
            </div>
            <a href="members.php?do=add" class="btn btn-primary">Add New Member</a>
        </div>
            
        <?php }elseif($do == 'add'){ //add members page ?> 
            <h1 class="text-center">Add New Member</h1>
                <div class="container">
                <form class="form-horizontal" action = "?do=insert" method = "POST">
                <!-- /* start username field */ -->
                <div class="form-group">
                <label class="col-sm-2 control-label">Username</label>
                <div class="col-sm-10 col-md-4">
                    <input class="form-control" type="text"name="username" required="required">
                </div>
                </div>
                <!-- /* end username field */ -->
                <!-- /* start password field */ -->
                <div class="form-group">
                <label class="col-sm-2 control-label">password</label>
                <div class="col-sm-10 col-md-4">
                    <input class="form-control" type="password"name="password"autocomplete="new-password"required="required">
                </div>
                </div>
                <!-- /* end password field */ -->
                <!-- /* start email field */ -->
                <div class="form-group">
                <label class="col-sm-2 control-label">Email</label>
                <div class="col-sm-10 col-md-4">
                    <input class="form-control" type="email"name="email" required="required">
                </div>
                </div>
                <!-- /* end email field */
                /* start fullname field */ -->
                <div class="form-group">
                <label class="col-sm-2 control-label">Full name</label>
                <div class="col-sm-10 col-md-4">
                    <input class="form-control" type="text"name="fullname" required="required">
                </div>
                </div>
                <!-- /* end fullname field */
                /* start submit field */ -->
                <div class="form-group">
                <div class="col-sm-offset-2 col-sm-10">
                    <input class="btn btn-primary" type="submit"value="Add Member">
                </div>
                </div>
                <!-- /* end submit field */ -->
                </form>
                </div>
    <?php   
        //insert page
    }elseif($do == 'insert'){
        if($_SERVER['REQUEST_METHOD'] == 'POST'){
        echo '<h1 class="text-center">Update Member</h1>';
        echo "<div class = 'container'";
            //get variables from the form
            $user = $_POST['username'];
            $password = $_POST['password'];
            $email = $_POST['email'];
            $name = $_POST['fullname'];
            //validate the form
            $formerrors = array();
            if(strlen($user) < 4){
                $formerrors[] = '<div class = "alert alert-danger">username can not be less than <strong>4 characters</strong>"</div>';
            }
            if(strlen($password) > 20){
                $formerrors[] = '<div class = "alert alert-danger">username can not be more than 20 characters"</div>';
            }
            if(empty($email)){
                $formerrors[] = '<div class = "alert alert-danger">username can not be empty"</div>';
            }
            if(empty($name)){
                $formerrors[] = '<div class = "alert alert-danger">fullname can not be empty"</div>';
            }
            foreach($formerrors as $error){
                echo $error . '</br>';
            }
            //check for errors to proceed
            if(empty($formerrors)){
                //check if user is in database
                $check = checkitem('username','users',$user);
                if($check == 1){echo 'This user already exists';}
                else{
                 //Insert info to database
                $stmt = $conn->prepare("INSERT INTO users(username,password,email,fullname,regstatus)
                                                    VALUES(:zuser,:zpass,:zemail,:zfname,1)");
                $stmt->execute(array(
                    'zuser' => $user,
                    'zpass' => $password,
                    'zemail' => $email,
                    'zfname' => $name,
                ));
                $themsg = "<div class = 'alert alert-success'>" . $stmt->rowCount() . 'RECORD Inserted </div>';
                redirecthome($themsg , 'Back' );
                }
        }
        }else{
            echo '<div class = "alert alert-danger"> piss off </div>';
    }
    echo "</div>";
}           
    
    elseif ($do == 'edit'){ //edit page 
        $userid = isset($_GET['userid']) && is_numeric($_GET['userid']) ? intval($_GET['userid']) : 0;
        $stmt = $conn ->prepare("SELECT * FROM users WHERE userid = ? LIMIT 1");
        $stmt->execute(array($userid));
        $row = $stmt->fetch();
        $count = $stmt->rowCount();
        if($stmt->rowCount()>0){?>
                <h1 class="text-center">Add Member</h1>
                <div class="container">
                <form class="form-horizontal" action = "?do=update" method = "POST">
                <input type="hidden" name ='userid' value = "<?php echo $userid ?>">
                <!-- /* start username field */ -->
                <div class="form-group">
                <label class="col-sm-2 control-label">Username</label>
                <div class="col-sm-10 col-md-4">
                    <input class="form-control" type="text"name="username" value = "<?php echo $row['username']; ?>"required="required">
                </div>
                </div>
                <!-- /* end username field */ -->
                <!-- /* start password field */ -->
                <div class="form-group">
                <label class="col-sm-2 control-label">password</label>
                <div class="col-sm-10 col-md-4">
                    <input type="hidden"name="old-password" value ="<?php echo $row['password']; ?>">
                    <input class="form-control" type="password"name="new-password"autocomplete="new-password">
                </div>
                </div>
                <!-- /* end password field */ -->
                <!-- /* start email field */ -->
                <div class="form-group">
                <label class="col-sm-2 control-label">Email</label>
                <div class="col-sm-10 col-md-4">
                    <input class="form-control" type="email"name="email" value = "<?php echo $row['email']; ?> "required="required">
                </div>
                </div>
                <!-- /* end email field */
                /* start fullname field */ -->
                <div class="form-group">
                <label class="col-sm-2 control-label">Full name</label>
                <div class="col-sm-10 col-md-4">
                    <input class="form-control" type="text"name="fullname" value = "<?php echo $row['fullname']; ?> "required="required">
                </div>
                </div>
                <!-- /* end fullname field */
                /* start submit field */ -->
                <div class="form-group">
                <div class="col-sm-offset-2 col-sm-10">
                    <input class="btn btn-primary" type="submit"value="save">
                </div>
                </div>
                <!-- /* end submit field */ -->
                </form>
                </div>
        <?php 
        }else{
            $themsg = 'there is no such id';
            redirecthome($themsg);
        }
    }elseif($do == 'update'){//update page
        if($_SERVER['REQUEST_METHOD'] == 'POST'){
        echo '<h1 class="text-center">Update Member</h1>';
        echo "<div class = 'container'";
            //get variables from the form
            $id = $_POST['userid'];
            $user = $_POST['username'];
            $email = $_POST['email'];
            $name = $_POST['fullname'];
            //password trick
            // if conditon ? true : false
            $pass = empty($_POST['new-password']) ? $_POST['old-password'] : $_POST['new-password'];
            //validate the form
            $formerrors = array();
            if(strlen($user) < 4){
                $formerrors[] = '<div class = "alert alert-danger">username can not be less than <strong>4 characters</strong></div>';
            }
            if(strlen($user) > 20){
                $formerrors[] = '<div class = "alert alert-danger">username can not be more than 20 characters</div>';
            }
            if(empty($user)){
                $formerrors[] = '<div class = "alert alert-danger">username can not be empty</div>';
            }
            if(empty($email)){
                $formerrors[] = '<div class = "alert alert-danger">email can not be empty</div>';
            }
            if(empty($name)){
                $formerrors[] = '<div class = "alert alert-danger">fullname can not be empty</div>';
            }
            foreach($formerrors as $error){
                echo $error . '</br>';
            }
            //check for errors to proceed
            if(empty($formerrors)){
                 //update the database
                $stmt = $conn ->prepare("UPDATE users SET username = ? , email = ? , fullname = ? , password = ? WHERE userid = ?");
                $stmt->execute(array($user , $email, $name, $pass, $id)); 
                $themsg = '<div class="alert alert-success">' . $stmt->rowCount() . '</div>';
                redirecthome($themsg,'Back');
            }
        }else{echo 'piss off';}
        echo "</div>";
    }elseif($do == 'delete'){
        //delete member page
        echo '<h1 class="text-center">Delete Member</h1>';
        echo "<div class = 'container'";
        $userid = isset($_GET['userid']) && is_numeric($_GET['userid']) ? intval($_GET['userid']) : 0;
        $stmt = $conn ->prepare("SELECT * FROM users WHERE userid = ? LIMIT 1");
        $stmt->execute(array($userid));
        $count = $stmt->rowCount();
        if($stmt->rowCount()>0){
            $stmt=$conn->prepare('DELETE FROM users WHERE userid = :zuser');
            $stmt->bindParam(':zuser' , $userid);
            $stmt ->execute();
            echo "<div class = 'alert alert-success'>" . $stmt->rowCount() . 'RECORD Deleted </div>';
            echo '</div>';
        }else{echo 'this id does not exist';}
    }elseif($do = 'activate'){
        //activate member page
        echo '<h1 class="text-center">Activate Member</h1>';
        echo "<div class = 'container'";
        $userid = isset($_GET['userid']) && is_numeric($_GET['userid']) ? intval($_GET['userid']) : 0;
        $stmt = $conn ->prepare("UPDATE users SET regstatus = 1 WHERE userid = ?");
        $stmt->execute(array($userid));
        $count = $stmt->rowCount();
        if($stmt->rowCount()>0){
            $stmt=$conn->prepare('UPDATE users SET regstatus = 1 WHERE userid = ?');
            $stmt->bindParam(':zuser' , $userid);
            $stmt ->execute();
            echo "<div class = 'alert alert-success'>" . $stmt->rowCount() . 'Record Activated </div>';
            echo '</div>';
        }else{echo 'this id does not exist';}
    }
}else{header('location:index.php');
        exit();}
