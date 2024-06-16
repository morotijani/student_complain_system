<?php 

    require_once ("db_connection/conn.php");

    unset($_SESSION['ComStudent']);

    redirect(PROOT . 'login');

?>