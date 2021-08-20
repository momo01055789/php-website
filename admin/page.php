<?php
    $do = '';
    if(isset($_GET['do'])){
        $do = $_GET['do'];
    }else{$do = 'manage';}
    //If this page is manage
    if($do == 'manage'){
        echo 'welcome hoe';
        echo "<a href='page.php?do=add'> add new category </a>";
    }elseif ($do == 'add'){
        echo 'welcome to add page mamas';
    }elseif($do == 'delete'){
        echo 'welcome to delete page';
    }
    else{ echo 'error page not found';}