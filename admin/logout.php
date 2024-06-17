<?php 

    require_once ("../db_connection/conn.php");

    unset($_SESSION['ComAdmin']);

    redirect(PROOT . 'admin/login');
