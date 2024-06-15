<?php 

    require_once ("../db_connection/conn.php");

    $error = '';

    if ($_POST) {
        if (empty($_POST['admin_email']) || empty($_POST['admin_password'])) {
            $error = 'You must provide email and password.';
        }
        $query = "
            SELECT * FROM mifo_admin 
            WHERE admin_email = :admin_email 
            LIMIT 1
        ";
        $statement = $conn->prepare($query);
        $statement->execute(['admin_email' => $_POST['admin_email']]);
        $count_row = $statement->rowCount();
        $result = $statement->fetchAll();

        if ($count_row < 1) {
            $error = 'Unkown admin.';
        }

        foreach ($result as $row) {
            if (!password_verify($_POST['admin_password'], $row['admin_password'])) {
                $error = 'Unkown admin.';
            }

            if (!empty($error)) {
                $error;
            } else {
                $admin_id = $row['admin_id'];
                adminLogin($admin_id);
            }
        }
        
    }

?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="description" content="">
    <meta name="author" content="MIFO">
    <title>Dashboard signin · MIFO</title>

    <!-- Bootstrap core CSS -->
    <link href="<?= PROOT; ?>assets/css/bootstrap.min.css" rel="stylesheet">

    <style>
        .bd-placeholder-img {
            font-size: 1.125rem;
            text-anchor: middle;
            -webkit-user-select: none;
            -moz-user-select: none;
            user-select: none;
        }

        @media (min-width: 768px) {
            .bd-placeholder-img-lg {
                font-size: 3.5rem;
            }
        }
    </style>


    <!-- Custom styles for this template -->
    <link href="<?= PROOT; ?>assets/css/signin.css" rel="stylesheet">
</head>
        
<body class="text-center bg-light">
    <main class="form-signin">
        <p><?= $flash; ?></p>
        <form method="POST" action="login.php" id="admin_loginForm">
            <code class="mb-1"><?= $error; ?></code>
            <img class="mb-4" src="<?= PROOT; ?>assets/media/logo/LOGO-NBG-1.1.png" alt="" width="200" height="100">
            <div class="form-floating">
                <input type="email" class="form-control" id="admin_email" name="admin_email" autocomplete="nope">
                <label for="admin_email">Email address</label>
            </div>
            <div class="form-floating">
                <input type="password" class="form-control" id="admin_password" name="admin_password" placeholder="Password">
                <label for="admin_password">Password</label>
            </div>
            <button class="w-100 btn btn-lg btn-dark" type="submit" id="submit_form" name="submit_form">Sign in</button>
            <p class="mt-5 mb-3 text-muted">&copy; 2020-<script>document.write(new Date().getFullYear());</script></p>
        </form>
    </main>

</body>
</html>
