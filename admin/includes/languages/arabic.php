<?php
    function lang($phrase){
        static $lang = array(
            'message' => 'اهلا وسهلا',
            'admin' => 'المدير'
        );
        return $lang[$phrase];
    }
?>