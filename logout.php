<?php 

    require_once ("../db_connection/conn.php");

    unset($_SESSION['MFAdmin']);

    header('Location: login');

?>