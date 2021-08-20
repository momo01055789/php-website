<?php 
//error_reporting (E_ALL ^ E_NOTICE);
session_start();
$pagetitle = 'Categories';
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
        echo "Welcome to Categories page";
?>
        <?php }elseif($do == 'add'){ //add members page ?> 
            <h1 class="text-center">Add New Category</h1>
                <div class="container">
                <form class="form-horizontal" action = "?do=insert" method = "POST">
                <!-- /* start name field */ -->
                <div class="form-group">
                <label class="col-sm-2 control-label">Name</label>
                <div class="col-sm-10 col-md-4">
                    <input class="form-control" type="text" name="name" required="required">
                </div>
                </div>
                <!-- /* end username field */ -->
                <!-- /* start Description field */ -->
                <div class="form-group">
                <label class="col-sm-2 control-label">Description</label>
                <div class="col-sm-10 col-md-4">
                    <input class="form-control" type="text"name="description">
                </div>
                </div>
                <!-- /* end description field */ -->
                <!-- /* start ordering field */ -->
                <div class="form-group">
                <label class="col-sm-2 control-label">Ordering</label>
                <div class="col-sm-10 col-md-4">
                    <input class="form-control" type="text"name="ordering" >
                </div>
                </div>
                <!-- /* end ordering field */
                /* start visibility field */ -->
                <div class="form-group">
                <label class="col-sm-2 control-label">Visibility</label>
                <div class="col-sm-10 col-md-4">
                    <div>
                    <input id="vis-yes" type="radio" name="visibility" value ="0" />
                    <label for="vis-yes">Yes</label>
                    </div>
                    <div>
                    <input type="radio" id="vis-no" name="visibility" value ="1" />
                    <label for="vis-no">No</label>
                    </div>
                </div>
                </div>
                <!-- /* end visibility */ -->
                <div class="form-group">
                <label class="col-sm-2 control-label">Allow comments</label>
                <div class="col-sm-10 col-md-4">
                <div>
                    <input id="comm-yes" type="radio" name="commenting" value ="0" />
                    <label for="comm-yes">Yes</label>
                    </div>
                    <div>
                    <input type="radio" id="comm-no" name="commenting" value ="1" />
                    <label for="comm-no">No</label>
                    </div>
                </div>
                </div>
                <div class="form-group">
                <label class="col-sm-2 control-label">Allow ads</label>
                <div class="col-sm-10 col-md-4">
                <div>
                    <input id="ads-yes" type="radio" name="ads" value ="0" />
                    <label for="ads-yes">Yes</label>
                    </div>
                    <div>
                    <input type="radio" id="ads-no" name="ads" value ="1" />
                    <label for="ads-no">No</label>
                    </div>
                </div>
                </div>
                <!-- /* end fullname field */
                /* start submit field */ -->
                <div class="form-group">
                <div class="col-sm-offset-2 col-sm-10">
                    <input class="btn btn-primary" type="submit"value="Add Category">
                </div>
                </div>
                <!-- /* end submit field */ -->
                </form>
                </div>
    <?php   
        //insert page
    }elseif($do == 'insert'){
        if($_SERVER['REQUEST_METHOD'] == 'POST'){
            echo '<h1 class="text-center">Insert category</h1>';
            echo "<div class = 'container'";
                //get variables from the form
                if(isset($_POST['name'])){
                    $name = $_POST['name'];
                }
                if(isset($_POST['description'])){
                    $descrip = $_POST['description'];
                }
                if(isset($_POST['ordering'])){
                    $order = $_POST['ordering'];
                }
                if(isset($_POST['visibility'])){
                    $visibility = $_POST['visibility'];
                }
                if(isset($_POST['commenting'])){
                    $comment = $_POST['commenting'];
                }
                if(isset($_POST['ads'])){
                    $ads = $_POST['ads'];
                }
                
                    //check if user is in database
                    $check = checkitem('name','categories',$name);
                if($check == 1){echo 'This user already exists';}
                else{
                 //Insert info to database
                $stmt = $conn->prepare("INSERT INTO categories(name,description,ordering,visibility,allow_comment,allow_ads)
                                                    VALUES(:zname,:zdesc,:zorder,:zvisib,:zcomment,:zads)");
                $stmt->execute(array(
                    'zname' => $name,
                    'zdesc' => $descrip,
                    'zorder' => $order,
                    'zvisib' => $visibility,
                    'zcomment' => $comment,
                    'zads' => $ads,
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
        
        
        
    }elseif($do == 'update'){//update page
        

    }elseif($do == 'delete'){
        //delete member page
        
    }
else{header('location:index.php');
        exit();}