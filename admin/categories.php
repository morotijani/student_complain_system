<?php 

    require_once ("../db_connection/conn.php");

    if (!admin_is_logged_in()) {
        admn_login_redirect();
    }

    include ("includes/header.inc.php");
    include ("includes/nav.inc.php");
    include ("includes/left-side-bar.inc.php");

    function display_errors($errors) {
        $display = '<ul class="bg-danger">';
        foreach ($errors as $error) {
            // code...
            $display .= '<li class="text-light">' . $error . '</li>';
        }
        $display .= '</ul>';
        return $display;
    }


    $brand_value = isset($_POST['brand']) ? sanitize($_POST['brand']) : '';
    $brand_url = php_url_slug($brand_value);
    // Edit Brand
    if (isset($_GET['edit']) && !empty($_GET['edit'])) {
        // code...
        $edit_id = sanitize((int)$_GET['edit']);

        $query = "
            SELECT * FROM mifo_brand 
            WHERE brand_id = ? 
            LIMIT 1
        ";
        $statement = $conn->prepare($query);
        $statement->execute([$edit_id]);
        $row = $statement->fetchAll();
        $count = $statement->rowCount();
        if ($count > 0) {
            $brand_value = $row[0]['brand_name'];
            $brand_banner = $row[0]['brand_banner'];
            $brand_url = php_url_slug($brand_value);
        } else {
            $_SESSION['flash_error'] = 'Unknow brand!';
            redirect(PROOT . 'admin/brands');
        }
    }

    // Delete brand
    if (isset($_GET['delete']) && !empty($_GET['delete'])) {
        // code...
        $delete_id = sanitize((int)$_GET['delete']);
        $query = "
            SELECT * FROM mifo_brand 
            WHERE brand_id = ? 
            LIMIT 1
        ";
        $statement = $conn->prepare($query);
        $statement->execute([$delete_id]);
        $row = $statement->fetchAll();
        $count = $statement->rowCount();
        if ($count > 0) {
            $filename = BASEURL . $row[0]['brand_banner'];
            if (file_exists($filename)) {
                // code...
                unlink($filename);
            }
            // code...
            $deleteQuery = "
                DELETE FROM mifo_brand 
                WHERE brand_id = ? 
            ";
            $statement = $conn->prepare($deleteQuery);
            $sub_result = $statement->execute([$delete_id]);
            if ($sub_result) {
                // code...
                $_SESSION['flash_success'] = 'Brand deleted!';
                redirect(PROOT . 'admin/brands');
            } else {
                echo js_alert('Something went wrong, please try again.');
            }
        } else {
            $_SESSION['flash_error'] = 'Unknow brand!';
            redirect(PROOT . 'admin/brands');
        }
    }

    // if add form is submitted
    $output = '';
    $errors = array();
    if (isset($_POST['add_submit'])) {

        $brand = sanitize($_POST['brand']);
        // check if brand is blank
        if ($brand == '') {
            // code...
            $errors[] .= 'You must enter a brand.';
        }

        // check if brand exist in database;
        $count_brand = $conn->query("SELECT * FROM mifo_brand WHERE brand_name = '$brand'")->rowCount();
        if (isset($_GET['edit'])) {
            $count_brand = $conn->query("SELECT * FROM mifo_brand WHERE brand_name = '$brand' AND brand_id != '$edit_id'")->rowCount();
        }
        if ($count_brand > 0) {
            // code...
            $errors[] .= $brand . ' already exist. Please choose another brand.';
            $filename = '';
        }

        if (isset($_GET['edit']) && empty($_FILES['banner']['name'])) {
            $location = $brand_banner;
        } else if (isset($_GET['edit']) && !empty($_FILES['banner']['name'])) {
            // Delete previous banner image
            $filename = BASEURL . $brand_banner;
            if (file_exists($filename)) {
                unlink($filename);
            }
            $image_test = explode(".", $_FILES["banner"]["name"]);
            $image_extension = end($image_test);
            $image_name = md5(microtime()).'.'.$image_extension;

            $location = 'shop/assets/media/collection/'.$image_name;
            move_uploaded_file($_FILES["banner"]["tmp_name"], BASEURL . $location);
        } else if (!isset($_GET['edit'])) {
            if (!empty($_FILES['banner']['name'])) {
                // code...
                $image_test = explode(".", $_FILES["banner"]["name"]);
                $image_extension = end($image_test);
                $image_name = md5(microtime()).'.'.$image_extension;

                $location = 'shop/assets/media/collection/'.$image_name;
                move_uploaded_file($_FILES["banner"]["tmp_name"], BASEURL . $location);

            } else {
                $errors[] .= 'Please select a banner image for the brand.';
            }
        }
        

        // display errors
        if (!empty($errors)) {
            $output = display_errors($errors);
        } else {
            // add brand to database
            $sql = "
                INSERT INTO mifo_brand (brand_name, brand_banner, brand_url) 
                VALUES (?, ?, ?)
            ";
            if (isset($_GET['edit'])) {
                // code...
                $sql = "
                    UPDATE mifo_brand 
                    SET brand_name = ? , brand_banner = ?, brand_url = ?  
                    WHERE brand_id = '$edit_id'
                ";
            }
            $statement = $conn->prepare($sql);
            $result = $statement->execute([$brand, $location, $brand_url]);
            if (isset($result)) {
                // code...
                $_SESSION['flash_success'] = $brand . ' brand ' . ((isset($_GET['edit'])) ? 'edited' : 'added') . '!';
                redirect(PROOT . 'admin/brands');
            } else {
                echo js_alert('Something went wrong, please try again.');
                redirect(PROOT . 'admin/brands');
            }
        }
    }

    // get brands from database    
    $sql = "SELECT * FROM mifo_brand";
    $statement = $conn->prepare($sql);
    $statement->execute();
    $result = $statement->fetchAll();


?>

    <main class="col-md-9 ms-sm-auto col-lg-10 px-md-4">
        <span><?= $flash; ?></span>
        <div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
            <h1 class="h2">Brands</h1>
            <div class="btn-toolbar mb-2 mb-md-0">
                <div class="btn-group me-2">
                    <a href="<?= PROOT; ?>admin/index" class="btn btn-sm btn-outline-secondary">Go back</a>
                    <a href="<?= PROOT; ?>admin/category" class="btn btn-sm btn-outline-secondary">Refresh</a>
                </div>
                <a href="<?= PROOT; ?>admin/products" class="btn btn-sm btn-outline-secondary">
                    <span data-feather="shopping-cart"></span>
                    Products
                </a>
            </div>
        </div>

        <div class="row justify-content-center mb-5">
            <div class="col-md-6">
                <?= $output; ?>
                <form class="row gy-2 gx-3 align-items-center" action="brands<?= ((isset($_GET['edit'])) ? '?edit=' . $edit_id : ''); ?>" method="POST" enctype="multipart/form-data">
                    <div class="col-auto">
                        <label class="visually-hidden" for="brand">Name</label>
                        <input type="text" class="form-control" id="brand" name="brand" placeholder="MIFO" value="<?= $brand_value; ?>" required>
                    </div>
                    <div class="col-auto">
                        <label class="visually-hidden" for="banner">Banner</label>
                        <input type="file" class="form-control" id="banner" name="banner">
                        <input type="hidden" id="banner_value" name="banner_value" value="<?= ((isset($_GET['edit'])) ? $brand_banner : 'no_banner'); ?>">
                    </div>
                    <div class="col-auto">
                        <button type="submit" name="add_submit" id="add_submit" class="btn btn-dark"><?= ((isset($_GET['edit'])) ? 'Edit': 'Add a'); ?> Brand</button>
                        <?php if (isset($_GET['edit'])): ?>
                            <a href="<?= PROOT; ?>admin/brands" class="btn btn-secondary">Cancel</a>
                        <?php endif; ?>
                    </div>
                </form>
            </div>
            <?php if (isset($_GET['edit'])): ?>
                <center class="mt-2">
                    <img src="<?= PROOT . $row[0]['brand_banner']; ?>" alt="">                    
                </center>
            <?php endif ?>

        </div>

        <table class="table table-striped table-bordered table-sm" style="width: auto; margin: 0 auto;">
            <thead>
                <tr>
                    <th></th>
                    <th>Brand</th>
                    <th></th>
                    <th>Date Added</th>
                    <th></th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($result as $row): ?>
                    <tr>
                        <td>
                            <a href="brands?edit=<?= $row['brand_id']; ?>" class="badge bg-warning"><i data-feather="edit"></i></a>
                        </td>
                        <td><?= $row['brand_name']; ?></td>
                        <td>
                            <a href="<?= PROOT . $row['brand_banner']; ?>" target="_blank">
                                <img src="<?= PROOT . $row['brand_banner']; ?>" alt="" width="100" height="100" class="img-thumbnail">
                            </a>
                        </td>
                        <td><?= pretty_date($row['brand_added_date']); ?></td>
                        <td>
                            <a href="brands?delete=<?= $row['brand_id']; ?>" class="badge bg-danger"><i data-feather="trash-2"></i></a>
                        </td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </main>



<?php 

    include ("includes/footer.inc.php");

?>


<script type="text/javascript">
    function temp_delete_category(category_id) {
        if (confirm("Category will be DELETED including the products under it.") == true) {
            window.location = '<?= PROOT; ?>admin/category?temp_delete='+category_id+'';
        } else {
            return false;
        }
    }


    function perm_delete_category(category_id) {
        if (confirm("Category will be DELETED PERMENENTLY including the products under it.") == true) {
            window.location = '<?= PROOT; ?>admin/category?delete='+category_id+'';
        } else {
            return false;
        }
    }
</script>

