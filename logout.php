<?php
    session_start();
    $_SESSION = array();
    session_regenerate_id(TRUE);
    session_destroy();
    header('Location: index.php');
?>