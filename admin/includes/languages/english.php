<?php
    function lang($phrase){
        static $lang = array(
            //Dashboard words
            'message' => 'فئات',
            'admin' => 'administrator'
        );
        return $lang[$phrase];
    }
?>