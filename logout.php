<?php
    ob_start();
    echo "<meta http-equiv='refresh' content='0; url=index.php' />";
    session_start();
    session_unset();
    session_destroy();
    header('location:index.php');
    exit;
?>