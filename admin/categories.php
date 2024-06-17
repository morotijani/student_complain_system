<?php 

    require_once ("../db_connection/conn.php");

    if (!admin_is_logged_in()) {
        admn_login_redirect();
    }


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
            SELECT * FROM categories 
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
            SELECT * FROM categories 
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
                DELETE FROM categories 
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
        $count_brand = $conn->query("SELECT * FROM categories WHERE brand_name = '$brand'")->rowCount();
        if (isset($_GET['edit'])) {
            $count_brand = $conn->query("SELECT * FROM categories WHERE brand_name = '$brand' AND brand_id != '$edit_id'")->rowCount();
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
                INSERT INTO categories (brand_name, brand_banner, brand_url) 
                VALUES (?, ?, ?)
            ";
            if (isset($_GET['edit'])) {
                // code...
                $sql = "
                    UPDATE categories 
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
    $sql = "SELECT * FROM categories";
    $statement = $conn->prepare($sql);
    $statement->execute();
    $result = $statement->fetchAll();


?>
<!DOCTYPE html>
<html lang="en" data-bs-theme="auto">
  <head>
    <script src="<?= PROOT; ?>dist/js/color-modes.js"></script>

    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="description" content="">
    <meta name="generator" content="Hugo 0.122.0">
    <title>Category . Dashboard</title>

    
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
        .container {
  max-width: 960px;
}

.icon-link > .bi {
  width: .75em;
  height: .75em;
}

/*
 * Custom translucent site header
 */

.site-header {
  background-color: rgba(0, 0, 0, .85);
  -webkit-backdrop-filter: saturate(180%) blur(20px);
  backdrop-filter: saturate(180%) blur(20px);
}
.site-header a {
  color: #8e8e8e;
  transition: color .15s ease-in-out;
}
.site-header a:hover {
  color: #fff;
  text-decoration: none;
}
    </style>

</head>
<body>
    <svg xmlns="http://www.w3.org/2000/svg" class="d-none">
        <symbol id="check2" viewBox="0 0 16 16">
            <path d="M13.854 3.646a.5.5 0 0 1 0 .708l-7 7a.5.5 0 0 1-.708 0l-3.5-3.5a.5.5 0 1 1 .708-.708L6.5 10.293l6.646-6.647a.5.5 0 0 1 .708 0z"/>
        </symbol>
        <symbol id="circle-half" viewBox="0 0 16 16">
            <path d="M8 15A7 7 0 1 0 8 1v14zm0 1A8 8 0 1 1 8 0a8 8 0 0 1 0 16z"/>
        </symbol>
        <symbol id="moon-stars-fill" viewBox="0 0 16 16">
            <path d="M6 .278a.768.768 0 0 1 .08.858 7.208 7.208 0 0 0-.878 3.46c0 4.021 3.278 7.277 7.318 7.277.527 0 1.04-.055 1.533-.16a.787.787 0 0 1 .81.316.733.733 0 0 1-.031.893A8.349 8.349 0 0 1 8.344 16C3.734 16 0 12.286 0 7.71 0 4.266 2.114 1.312 5.124.06A.752.752 0 0 1 6 .278z"/>
            <path d="M10.794 3.148a.217.217 0 0 1 .412 0l.387 1.162c.173.518.579.924 1.097 1.097l1.162.387a.217.217 0 0 1 0 .412l-1.162.387a1.734 1.734 0 0 0-1.097 1.097l-.387 1.162a.217.217 0 0 1-.412 0l-.387-1.162A1.734 1.734 0 0 0 9.31 6.593l-1.162-.387a.217.217 0 0 1 0-.412l1.162-.387a1.734 1.734 0 0 0 1.097-1.097l.387-1.162zM13.863.099a.145.145 0 0 1 .274 0l.258.774c.115.346.386.617.732.732l.774.258a.145.145 0 0 1 0 .274l-.774.258a1.156 1.156 0 0 0-.732.732l-.258.774a.145.145 0 0 1-.274 0l-.258-.774a1.156 1.156 0 0 0-.732-.732l-.774-.258a.145.145 0 0 1 0-.274l.774-.258c.346-.115.617-.386.732-.732L13.863.1z"/>
        </symbol>
        <symbol id="sun-fill" viewBox="0 0 16 16">
            <path d="M8 12a4 4 0 1 0 0-8 4 4 0 0 0 0 8zM8 0a.5.5 0 0 1 .5.5v2a.5.5 0 0 1-1 0v-2A.5.5 0 0 1 8 0zm0 13a.5.5 0 0 1 .5.5v2a.5.5 0 0 1-1 0v-2A.5.5 0 0 1 8 13zm8-5a.5.5 0 0 1-.5.5h-2a.5.5 0 0 1 0-1h2a.5.5 0 0 1 .5.5zM3 8a.5.5 0 0 1-.5.5h-2a.5.5 0 0 1 0-1h2A.5.5 0 0 1 3 8zm10.657-5.657a.5.5 0 0 1 0 .707l-1.414 1.415a.5.5 0 1 1-.707-.708l1.414-1.414a.5.5 0 0 1 .707 0zm-9.193 9.193a.5.5 0 0 1 0 .707L3.05 13.657a.5.5 0 0 1-.707-.707l1.414-1.414a.5.5 0 0 1 .707 0zm9.193 2.121a.5.5 0 0 1-.707 0l-1.414-1.414a.5.5 0 0 1 .707-.707l1.414 1.414a.5.5 0 0 1 0 .707zM4.464 4.465a.5.5 0 0 1-.707 0L2.343 3.05a.5.5 0 1 1 .707-.707l1.414 1.414a.5.5 0 0 1 0 .708z"/>
        </symbol>
    </svg>

    <div class="dropdown position-fixed bottom-0 end-0 mb-3 me-3 bd-mode-toggle">
        <button class="btn btn-bd-primary py-2 dropdown-toggle d-flex align-items-center"
            id="bd-theme"
            type="button"
            aria-expanded="false"
            data-bs-toggle="dropdown"
            aria-label="Toggle theme (auto)">
            <svg class="bi my-1 theme-icon-active" width="1em" height="1em"><use href="#circle-half"></use></svg>
            <span class="visually-hidden" id="bd-theme-text">Toggle theme</span>
        </button>
        <ul class="dropdown-menu dropdown-menu-end shadow" aria-labelledby="bd-theme-text">
            <li>
                <button type="button" class="dropdown-item d-flex align-items-center" data-bs-theme-value="light" aria-pressed="false">
                    <svg class="bi me-2 opacity-50" width="1em" height="1em"><use href="#sun-fill"></use></svg>
                    Light
                    <svg class="bi ms-auto d-none" width="1em" height="1em"><use href="#check2"></use></svg>
                </button>
            </li>
            <li>
                <button type="button" class="dropdown-item d-flex align-items-center" data-bs-theme-value="dark" aria-pressed="false">
                    <svg class="bi me-2 opacity-50" width="1em" height="1em"><use href="#moon-stars-fill"></use></svg>
                    Dark
                    <svg class="bi ms-auto d-none" width="1em" height="1em"><use href="#check2"></use></svg>
                </button>
            </li>
            <li>
                <button type="button" class="dropdown-item d-flex align-items-center active" data-bs-theme-value="auto" aria-pressed="true">
                    <svg class="bi me-2 opacity-50" width="1em" height="1em"><use href="#circle-half"></use></svg>
                    Auto
                    <svg class="bi ms-auto d-none" width="1em" height="1em"><use href="#check2"></use></svg>
                </button>
            </li>
        </ul>
    </div>

    
    <svg xmlns="http://www.w3.org/2000/svg" class="d-none">
        <symbol id="aperture" fill="none" stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" viewBox="0 0 24 24">
            <circle cx="12" cy="12" r="10"/>
            <path d="M14.31 8l5.74 9.94M9.69 8h11.48M7.38 12l5.74-9.94M9.69 16L3.95 6.06M14.31 16H2.83m13.79-4l-5.74 9.94"/>
        </symbol>
        <symbol id="cart" viewBox="0 0 16 16">
            <path d="M0 1.5A.5.5 0 0 1 .5 1H2a.5.5 0 0 1 .485.379L2.89 3H14.5a.5.5 0 0 1 .49.598l-1 5a.5.5 0 0 1-.465.401l-9.397.472L4.415 11H13a.5.5 0 0 1 0 1H4a.5.5 0 0 1-.491-.408L2.01 3.607 1.61 2H.5a.5.5 0 0 1-.5-.5zM3.102 4l.84 4.479 9.144-.459L13.89 4H3.102zM5 12a2 2 0 1 0 0 4 2 2 0 0 0 0-4zm7 0a2 2 0 1 0 0 4 2 2 0 0 0 0-4zm-7 1a1 1 0 1 1 0 2 1 1 0 0 1 0-2zm7 0a1 1 0 1 1 0 2 1 1 0 0 1 0-2z"/>
        </symbol>
        <symbol id="chevron-right" viewBox="0 0 16 16">
            <path fill-rule="evenodd" d="M4.646 1.646a.5.5 0 0 1 .708 0l6 6a.5.5 0 0 1 0 .708l-6 6a.5.5 0 0 1-.708-.708L10.293 8 4.646 2.354a.5.5 0 0 1 0-.708z"/>
        </symbol>
    </svg>

    <nav class="navbar navbar-expand-md bg-dark sticky-top border-bottom" data-bs-theme="dark">
        <div class="container">
            <a class="navbar-brand d-md-none" href="#">
                <svg class="bi" width="24" height="24"><use xlink:href="#aperture"/></svg>
                SCS
            </a>
            <button class="navbar-toggler" type="button" data-bs-toggle="offcanvas" data-bs-target="#offcanvas" aria-controls="offcanvas" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="offcanvas offcanvas-end" tabindex="-1" id="offcanvas" aria-labelledby="offcanvasLabel">
                <div class="offcanvas-header">
                    <h5 class="offcanvas-title" id="offcanvasLabel">SCS</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="offcanvas" aria-label="Close"></button>
                </div>
                <div class="offcanvas-body">
                    <ul class="navbar-nav flex-grow-1 justify-content-between">
                        <li class="nav-item">
                            <a class="nav-link" href="#">
                                <svg class="bi" width="24" height="24"><use xlink:href="#aperture"/></svg>
                            </a>
                        </li>
                        <li class="nav-item"><a class="nav-link" href="index">Dashboard</a></li>
                        <li class="nav-item"><a class="nav-link" href="categories">Categories</a></li>
                        <li class="nav-item"><a class="nav-link" href="complaints">Complaints</a></li>
                        <li class="nav-item"><a class="nav-link" href="students">Students</a></li>
                        <li class="nav-item"><a class="nav-link" href="profile">Profile</a></li>
                        <li class="nav-item"><a class="nav-link" href="profile">Hello Admin!</a></li>
                        <li class="nav-item">
                            <a class="nav-link" href="#">
                                <svg class="bi" width="24" height="24"><use xlink:href="#arrow-left"/></svg>
                            </a>
                        </li>
                    </ul>
                </div>
            </div>
        </div>
    </nav>

    <main>
        <span><?= $flash; ?></span>
        <div class="row justify-content-center position-relative overflow-hidden p-3 p-md-5 m-md-3 bg-body-tertiary">
            <h1 class="h2">Categories</h1>
            <div class="col-md-5">
                <?= $output; ?>
                <form class="row gy-2 gx-3 align-items-center mb-3" action="brands<?= ((isset($_GET['edit'])) ? '?edit=' . $edit_id : ''); ?>" method="POST" enctype="multipart/form-data">
                    <div class="col-auto">
                        <label class="visually-hidden" for="brand">Name</label>
                        <input type="text" class="form-control" id="brand" name="brand" placeholder="eg, Bulling" value="<?= $brand_value; ?>" required>
                    </div>
                    <div class="col-auto">
                        <button type="submit" name="add_submit" id="add_submit" class="btn btn-dark"><?= ((isset($_GET['edit'])) ? 'Edit': 'Add a'); ?> Category</button>
                        <?php if (isset($_GET['edit'])): ?>
                            <a href="<?= PROOT; ?>admin/brands" class="btn btn-secondary">Cancel</a>
                        <?php endif; ?>
                    </div>
                </form>

     

                <table class="table table-striped table-bordered table-sm" style="width: auto; margin: 0 auto;">
                    <thead>
                        <tr>
                            <th></th>
                            <th>Category</th>
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
            </div>
        </div>
    </main>

    <script src="<?= PROOT; ?>dist/js/popper.min.js"></script>
    <script src="<?= PROOT; ?>dist/js/bootstrap.min.js"></script>
</body>
</html>
