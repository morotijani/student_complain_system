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
<html lang="en" data-bs-theme="auto">
<head>
    <script src="<?= PROOT; ?>dist/js/color-modes.js"></script>

    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Admin . Dashboard</title>
    <link href="<?= PROOT; ?>dist/css/bootstrap.min.css" rel="stylesheet">
    <meta name="theme-color" content="#712cf9">

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

        .b-example-divider {
            width: 100%;
            height: 3rem;
            background-color: rgba(0, 0, 0, .1);
            border: solid rgba(0, 0, 0, .15);
            border-width: 1px 0;
            box-shadow: inset 0 .5em 1.5em rgba(0, 0, 0, .1), inset 0 .125em .5em rgba(0, 0, 0, .15);
        }

        .b-example-vr {
            flex-shrink: 0;
            width: 1.5rem;
            height: 100vh;
        }

        .bi {
            vertical-align: -.125em;
            fill: currentColor;
        }

        .nav-scroller {
            position: relative;
            z-index: 2;
            height: 2.75rem;
            overflow-y: hidden;
        }

        .nav-scroller .nav {
            display: flex;
            flex-wrap: nowrap;
            padding-bottom: 1rem;
            margin-top: -1px;
            overflow-x: auto;
            text-align: center;
            white-space: nowrap;
            -webkit-overflow-scrolling: touch;
        }

        .btn-bd-primary {
            --bd-violet-bg: #712cf9;
            --bd-violet-rgb: 112.520718, 44.062154, 249.437846;

            --bs-btn-font-weight: 600;
            --bs-btn-color: var(--bs-white);
            --bs-btn-bg: var(--bd-violet-bg);
            --bs-btn-border-color: var(--bd-violet-bg);
            --bs-btn-hover-color: var(--bs-white);
            --bs-btn-hover-bg: #6528e0;
            --bs-btn-hover-border-color: #6528e0;
            --bs-btn-focus-shadow-rgb: var(--bd-violet-rgb);
            --bs-btn-active-color: var(--bs-btn-hover-color);
            --bs-btn-active-bg: #5a23c8;
            --bs-btn-active-border-color: #5a23c8;
        }

        .bd-mode-toggle {
            z-index: 1500;
        }

        .bd-mode-toggle .dropdown-menu .active .bi {
            display: block !important;
        }
    </style>
    <style type="text/css">
        html,
        body {
          height: 100%;
        }

        .form-signin {
          max-width: 330px;
          padding: 1rem;
        }

        .form-signin .form-floating:focus-within {
          z-index: 2;
        }

        .form-signin input[type="email"] {
          margin-bottom: -1px;
          border-bottom-right-radius: 0;
          border-bottom-left-radius: 0;
        }

        .form-signin input[type="password"] {
          margin-bottom: 10px;
          border-top-left-radius: 0;
          border-top-right-radius: 0;
        }
    </style>
</head>
        
<body class="text-center bg-light">
    <main class="form-signin">
        <?= $flash; ?>
        <form method="POST">
            <code class="mb-1"><?= $error; ?></code>
            <img class="mb-4" src="<?= PROOT; ?>dist/media/logo.png" alt="" width="72" height="72">
            <h1 class="h3 mb-3 fw-normal">Please sign in</h1>

            <div class="form-floating">
              <input type="email" class="form-control" id="admin_email" name="admin_email" placeholder="name@example.com">
              <label for="admin_email">Email address</label>
            </div>
            <div class="form-floating">
              <input type="password" class="form-control" id="admin_password" name="admin_password" placeholder="Password">
              <label for="admin_password">Password</label>
            </div>

            <div class="form-check text-start my-3">
              <input class="form-check-input" type="checkbox" value="remember-me" id="flexCheckDefault">
              <label class="form-check-label" for="flexCheckDefault">
                Remember me
              </label>
            </div>
            <button class="btn btn-primary w-100 py-2" type="submit" name="submit_form">Sign in</button>

        </form>
    </main>

    <script src="<?= PROOT; ?>dist/js/popper.min.js"></script>
    <script src="<?= PROOT; ?>dist/js/bootstrap.min.js"></script>
</body>
</html>
