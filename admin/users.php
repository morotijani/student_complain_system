<?php 

	// GET ALL USERS

    require_once ("../db_connection/conn.php");

    if (!admin_is_logged_in()) {
        admn_login_redirect();
    }

	if (!admin_has_permission()) {
	    admin_permission_redirect('index');
	}

	include ("includes/header.inc.php");
    include ("includes/nav.inc.php");
    include ("includes/left-side-bar.inc.php");

    // TERMINATE USER ACCOUNT
    if (isset($_GET['terminate']) && !empty($_GET['terminate'])) {
        $user_id = sanitize((int)$_GET['terminate']);

        if (user_exist($user_id) > 0) {
            $sql = "
                UPDATE mifo_user 
                SET user_trash = :user_trash 
                WHERE user_id = :user_id
            ";
            $statement = $conn->prepare($sql);
            $result = $statement->execute([
                ':user_trash' =>  1,
                ':user_id' => $user_id
            ]);
            if (isset($result)) {
                $_SESSION['flash_success'] = 'User <span class="bg-info">Terminated</span> successfully from the database.';
                echo '<script>window.location = "'.PROOT.'admin/users";</script>';
            }
        } else {
            $_SESSION['flash_error'] = 'User cannot be <span class="bg-info">Terminated</span> because he/she cannot be found in the database.';
            echo '<script>window.location = "'.PROOT.'admin/users";</script>'; 
        }

    }

    // RESTORE USER ACCOUNT
    if (isset($_GET['restore']) && !empty($_GET['restore'])) {
        $user_id = sanitize((int)$_GET['restore']);

        if (user_exist($user_id) > 0) {
            $sql = "
                UPDATE mifo_user 
                SET user_trash = :user_trash 
                WHERE user_id = :user_id
            ";
            $statement = $conn->prepare($sql);
            $result = $statement->execute([
                ':user_trash' =>  0,
                ':user_id' => $user_id
            ]);
            if (isset($result)) {
                $_SESSION['flash_success'] = 'User account <span class="bg-info">Restored</span> successfully.';
                echo '<script>window.location = "'.PROOT.'admin/users";</script>';
            }
        } else {
            $_SESSION['flash_error'] = 'User cannot be <span class="bg-info">Restored</span> because he/she cannot be found in the database.';
            echo '<script>window.location = "'.PROOT.'admin/users";</script>'; 
        }

    }

    // PERMENET TERMINATE USER ACCOUNT
    if (isset($_GET['delete']) && !empty($_GET['delete'])) {
        $user_id = sanitize((int)$_GET['delete']);

        if (user_exist($user_id) > 0) {
            $sql = "
                DELETE FROM mifo_user 
                WHERE user_id = :user_id
            ";
            $statement = $conn->prepare($sql);
            $result = $statement->execute([
                ':user_id' => $user_id
            ]);
            if (isset($result)) {
                $_SESSION['flash_success'] = 'User delete <span class="bg-info">Permenently</span> successfully from the database.';
                echo '<script>window.location = "'.PROOT.'admin/users";</script>';
            }
        } else {
            $_SESSION['flash_error'] = 'User cannot be deleted <span class="bg-info">Permenently</span> because he/she cannot be found in the database.';
            echo '<script>window.location = "'.PROOT.'admin/users";</script>'; 
        }

    }

    // VIEW USER DETAILS
    if (isset($_GET['view']) && !empty($_GET['view'])) {
        $user_id = sanitize((int)$_GET['view']);

        $query = "
            SELECT * FROM mifo_user 
            WHERE user_id = :user_id 
            LIMIT 1
        ";
        $statement = $conn->prepare($query);
        $statement->execute([':user_id' => $user_id]);
        $view_count = $statement->rowCount();
        $view_result = $statement->fetchAll();

        if ($view_count > 0) {
            foreach ($view_result as $view_row) {
                # code...
            }

?>
    <main class="col-md-9 ms-sm-auto col-lg-10 px-md-4">
        <div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
            <h1 class="h2">Viewing user, <span class="badge bg-info"><?= ucwords($view_row["user_fullname"]); ?></span></h1>
            <div class="btn-toolbar mb-2 mb-md-0">
                <div class="btn-group me-2">
                    <a href="<?= PROOT; ?>admin/index" class="btn btn-sm btn-outline-secondary">Home</a>
                    <a href="<?= PROOT; ?>admin/users" class="btn btn-sm btn-outline-secondary">Refresh</a>
                </div>
                <a href="<?= PROOT; ?>admin/users" class="btn btn-sm btn-outline-secondary">
                    <span data-feather="skip-back"></span>
                    GO back
                </a>
            </div>
        </div>
        <?= $flash; ?>

        <div class="card">
            <div class="card-body">
                <div class="row">
                    <div class="col-md-6">
                        <h2><u>Profile</u></h2>
                        <br>
                        <h6 class="text-secondary">Full Name</h6>
                        <p class="lead text-info"><?= ucwords($view_row["user_fullname"]); ?></p>
                        <h6 class="text-secondary">Company</h6>
                        <p class="lead text-info"><?= (($view_row["user_company"] != '')?ucwords($view_row["user_company"]):'---'); ?></p>
                        <h6 class="text-secondary">Postcode</h6>
                        <p class="lead text-info"><?= (($view_row["user_postcode"] != '')?$view_row["user_postcode"]:'---'); ?></p>
                        <h6 class="text-secondary">Telephone</h6>
                        <p class="lead text-info"><?= (($view_row["user_phone"] != '')?$view_row["user_phone"]:'---'); ?></p>
                        <h6 class="text-secondary">Email</h6>
                        <p class="lead text-info"><?= (($view_row["user_email"] != '')?$view_row["user_email"]:'---'); ?></p>
                    </div>
                    <div class="col-md-6">
                        <h2 class="text-secondary"><u>Address</u></h2>
                        <br>
                        <h6 class="text-secondary">Country</h6>
                        <p class="lead text-info"><?= (($view_row["user_country"] != '')?ucwords($view_row["user_country"]):'---'); ?></p>
                        <h6 class="text-secondary">State</h6>
                        <p class="lead text-info"><?= (($view_row["user_state"] != '')?ucwords($view_row["user_state"]):'---'); ?></p>
                        <h6 class="text-secondary">State</h6>
                        <p class="lead text-info"><?= (($view_row["user_city"] != '')?ucwords($view_row["user_city"]):'---'); ?></p>
                        <h6 class="text-secondary">Address 1</h6>
                        <p class="lead text-info"><?= (($view_row["user_address"] != '')?$view_row["user_address"]:'---'); ?></p>
                        <h6 class="text-secondary">Address 2</h6>
                        <p class="lead text-info"><?= (($view_row["user_address2"] != '')?$view_row["user_address2"]:'---'); ?></p>
                    </div>
                </div>
                <p class="mt-4">
                    <?php if ($view_row['user_trash'] == 0): ?>
                        <a href="<?= PROOT ?>admin/users?terminate=<?= $view_row['user_id']; ?>" class="btn btn-sm btn-dark">Terminate Account &nbsp;<span data-feather="user-x"></span></a>
                    <?php else: ?>
                        <a href="<?= PROOT ?>admin/users?restore=<?= $view_row['user_id']; ?>" class="btn btn-sm btn-secondary">Restore Account &nbsp;<span data-feather="user-check"></span></a>
                    <?php endif ?>
                </p>
            </div>
        </div>
    </main>
<?php 
    } else {
        $_SESSION['flash_error'] = 'User cannot be found in the database.';
        echo '<script>window.location = "'.PROOT.'admin/users";</script>';    
        }
    } else if (isset($_GET['ta']) && $_GET['ta'] == 1){
?>
            <main class="col-md-9 ms-sm-auto col-lg-10 px-md-4">
        <div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
            <h1 class="h2 text-danger">Terminated Accounts</h1>
            <div class="btn-toolbar mb-2 mb-md-0">
                <div class="btn-group me-2">
                    <a href="<?= PROOT; ?>admin/index" class="btn btn-sm btn-outline-secondary">Home</a>
                    <a href="<?= PROOT; ?>admin/users?ta=1" class="btn btn-sm btn-outline-secondary">Refresh</a>
                </div>
                <a href="<?= PROOT; ?>admin/users" class="btn btn-sm btn-outline-secondary">
                    <span data-feather="skip-back"></span>
                    Go back
                </a>
            </div>
        </div>
        <?= $flash; ?>

        <table class="table table-danger table-sm table-striped">
            <thead>
                <tr>
                    <th></th>
                    <th>Name</th>
                    <th>Email</th>
                    <th>Phone</th>
                    <th>Address</th>
                    <th>Joined Date</th>
                    <th>Last Login</th>
                    <th></th>
                </tr>
            </thead>
            <tbody>
                <?= get_all_users($user_trash = 1); ?>
            </tbody>
        </table>

    </main>


<?php } else { ?>

	<main class="col-md-9 ms-sm-auto col-lg-10 px-md-4">
        <div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
            <h1 class="h2">List of Users</h1>
            <div class="btn-toolbar mb-2 mb-md-0">
                <div class="btn-group me-2">
                    <a href="<?= PROOT; ?>admin/users" class="btn btn-sm btn-outline-secondary">Home</a>
                    <a href="<?= PROOT; ?>admin/users" class="btn btn-sm btn-outline-secondary">Refresh</a>
                </div>
                <a href="<?= PROOT; ?>admin/users?ta=1" class="btn btn-sm btn-outline-danger">
                    <span data-feather="user-x"></span>
                    Terminated Accounts
                </a>
            </div>
        </div>
        <?= $flash; ?>

        <table class="table table-success table-sm table-striped">
            <thead>
                <tr>
                    <th></th>
                    <th>Name</th>
                    <th>Email</th>
                    <th>Phone</th>
                    <th>Address</th>
                    <th>Joined Date</th>
                    <th>Last Login</th>
                    <th></th>
                </tr>
            </thead>
            <tbody>
                <?= get_all_users($user_trash = 0); ?>
            </tbody>
        </table>

    </main>

<?php 
    }
    include ("includes/footer.inc.php");

?>
