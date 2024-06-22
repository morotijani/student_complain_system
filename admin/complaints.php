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


    $category_value = isset($_POST['category']) ? sanitize($_POST['category']) : '';

    // Edit Complaint
    if (isset($_GET['update']) && !empty($_GET['update'])) {
        // code...
        $edit_id = sanitize((int)$_GET['update']);

        $query = "
            SELECT * FROM complaints 
            WHERE id = ? 
            LIMIT 1
        ";
        $statement = $conn->prepare($query);
        $statement->execute([$edit_id]);
        $row = $statement->fetchAll();
        $count = $statement->rowCount();
        if ($count > 0) {
            $category_value = $row[0]['category'];
            $updateSql = "
                UPDATE complaints 
                SET category_id = ?, complaint_message = ?, complaint_date = ? 
                WHERE id = ?
            ";
            $statement = $conn->prepare($updateSql);
            $result = $statement->execute([$_POST['complaint_category'], $_POST['message'], $_POST['complaint_date'], $edit_id]);

            if ($result) {
                // code...
                $_SESSION['flash_success'] = 'Complaint updated successfully!';
                redirect(PROOT . 'admin/complaints?view=' . $edit_id . '');
            } else {
                $_SESSION['flash_error'] = 'Complaint update error, please try again!';
                redirect(PROOT . 'admin/complaints?view=' . $edit_id . '');
            }
        } else {
            $_SESSION['flash_error'] = 'Cannot find complaint!';
            redirect(PROOT . 'admin/complaints');
        }
    }

    // Delete complaint
    if (isset($_GET['delete']) && !empty($_GET['delete'])) {
        // code...
        $delete_id = sanitize((int)$_GET['delete']);
        $query = "
            SELECT * FROM complaints 
            WHERE id = ? 
            LIMIT 1
        ";
        $statement = $conn->prepare($query);
        $statement->execute([$delete_id]);
        $row = $statement->fetchAll();
        $count = $statement->rowCount();
        if ($count > 0) {
            
            $deleteQuery = "
                DELETE FROM complaints 
                WHERE id = ? 
            ";
            $statement = $conn->prepare($deleteQuery);
            $sub_result = $statement->execute([$delete_id]);
            if ($sub_result) {

                $_SESSION['flash_success'] = 'Complaint deleted successfully!';
                redirect(PROOT . 'admin/complaints');
            } else {
                echo js_alert('Something went wrong, please try again.');
            }
        } else {
            $_SESSION['flash_error'] = 'Can not delete complaint!';
            redirect(PROOT . 'admin/complaints');
        }
    }

    // Complete complaint
    if (isset($_GET['complete']) && !empty($_GET['complete'])) {
        // code...
        $complete_id = sanitize((int)$_GET['complete']);
        $query = "
            SELECT * FROM complaints 
            WHERE id = ? 
            LIMIT 1
        ";
        $statement = $conn->prepare($query);
        $statement->execute([$complete_id]);
        $row = $statement->fetchAll();
        $count = $statement->rowCount();
        if ($count > 0) {
            
            $completeQuery = "
                UPDATE complaints 
                SET complaint_status = ? 
                WHERE id = ? 
            ";
            $statement = $conn->prepare($completeQuery);
            $sub_result = $statement->execute([2, $complete_id]);
            if ($sub_result) {

                $_SESSION['flash_success'] = 'Complaint completed successfully!';
                redirect(PROOT . 'admin/complaints');
            } else {
                echo js_alert('Something went wrong, please try again.');
            }
        } else {
            $_SESSION['flash_error'] = 'Can not complete complaint!';
            redirect(PROOT . 'admin/complaints');
        }
    }

    // get brands from database    
    $sql = "
        SELECT *, complaints.id AS cid FROM complaints 
        INNER JOIN categories 
        ON categories.id = complaints.category_id
        INNER JOIN students 
        ON students.id = complaints.student_id
        WHERE complaints.trash = 0 
        ORDER BY complaints.createdAt DESC
    ";
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
    <title>Complaints . Dashboard</title>

    
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
                        <li class="nav-item"><a class="nav-link" href="settings">Settings</a></li>
                        <li class="nav-item"><a class="nav-link" href="profile">Hello <?= $admin_data['first']; ?>!</a></li>
                        <li class="nav-item">
                            <a class="nav-link" href="logout">
                                Logout
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
            <?php 
                if (isset($_GET['view']) && !empty($_GET['view'])):
                    $view_id = (int)$_GET['view'];
                    $view = "
                        SELECT *, complaints.id AS cid FROM complaints 
                        INNER JOIN categories 
                        ON categories.id = complaints.category_id 
                        INNER JOIN students 
                        ON students.id = complaints.student_id
                        WHERE complaints.id = ? 
                        LIMIT 1
                    ";
                    $statement = $conn->prepare($view);
                    $statement->execute([$view_id]);
                    $row_view = $statement->fetchAll();
                    $count_view = $statement->rowCount();

                    if ($count_view > 0) {
                        // update status on complaint
                        $conn->query("UPDATE complaints SET complaint_status = 1 WHERE id = $view_id");

                        $status = "new";
                        $status_bg = "warning";
                        if ($row_view[0]['complaint_status'] == 1) {
                            // code...
                            $status = "processing";
                            $status_bg = "info";
                        } else if ($row_view[0]['complaint_status'] == 2) {
                            $status = "Done";
                            $status_bg = "success";
                        }
                        
                ?>
                    <h1 class="h2">View a complaint on <?= $row_view[0]['complaint_id']; ?></h1>
                    <a href="complaints" class=""><< go back</a>
                    <div class="card">
                        <div class="card-body">
                            <p class="mb-3">
                                Category: <?= ucwords($row_view[0]["category"]); ?>
                                <br>
                                Event Date: <?= pretty_date_only($row_view[0]["complaint_date"]); ?>
                                <br>
                                By: <a href="javascript:;" class="text-decoration-none" data-bs-toggle="modal" data-bs-target="#studentModal_<?= $row_view[0]['student_id']; ?>"><?= ucwords($row_view[0]['fullname']); ?></a>
                                <br>
                                Status: <span class="badge bg-<?= $status_bg ?>"><?= $status ?></span>
                                <br>
                                <hr>
                                <?= $row_view[0]['complaint_message']; ?>
                            </p>
                            <div class="d-inline-flex gap-2 mb-5">
                                <a class="d-inline-flex align-items-center btn btn-primary btn-lg px-4 rounded-pill" href="<?= PROOT; ?>admin/complaints?complete=<?= $row_view[0]['cid']; ?>" name="submit_form">
                                    Complete complaint
                                    <svg class="bi ms-2" width="24" height="24"><use xlink:href="#arrow-right-short"/></svg>
                                </a>
                                <a class="btn btn-outline-secondary btn-lg px-4 rounded-pill" href="<?= PROOT; ?>admin/complaints?delete=<?= $row_view[0]['cid']; ?>">
                                    Delete complaint
                                </a>
                            </div>
                            <br>
                            <a href="javascript:;" data-bs-toggle="modal" data-bs-target="#updateModal_<?= $row_view[0]['cid']; ?>">Update conplaint</a>
                        </div>
                    </div>

                    <!-- Student Details Modal -->
                    <div class="modal fade" id="studentModal_<?= $row_view[0]['student_id']; ?>" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="studentModalLabel_<?= $row_view[0]['student_id']; ?>" aria-hidden="true">
                        <div class="modal-dialog modal-dialog-centered modal-dialog-scrollable">
                            <div class="modal-content">
                                <div class="modal-header">
                                    <h1 class="modal-title fs-5" id="studentModalLabel_<?= $row_view[0]['student_id']; ?>">Complainer details</h1>
                                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                </div>
                                <div class="modal-body">
                                    <p>
                                        <table class="table table-sm table-striped table-bordered">
                                            <tbody>
                                                <tr><td>ID: <?= $row_view[0]['student_id']; ?></td></tr>
                                                <tr><td>Email: <?= $row_view[0]['email']; ?></td><tr>
                                                <tr><td>Full name: <?= ucwords($row_view[0]['fullname']); ?></td><tr>
                                                <tr><td>Level: <?= $row_view[0]['level']; ?></td><tr>
                                                <tr><td>Joined date: <?= pretty_date($row_view[0]['createdAt']); ?></td><tr>
                                            </tbody>
                                        </table>
                                    </p>
                                </div>
                                <div class="modal-footer">
                                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Update Modal -->
                    <div class="modal fade" id="updateModal_<?= $row_view[0]['cid']; ?>" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="updateModalLabel_<?= $row_view[0]['cid']; ?>" aria-hidden="true">
                        <div class="modal-dialog modal-dialog-centered modal-lg">
                            <div class="modal-content">
                                <div class="modal-header">
                                    <h1 class="modal-title fs-5" id="updateModalLabel_<?= $row_view[0]['cid']; ?>">Update Complain</h1>
                                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                </div>
                                <form method="POST" action="complaints?update=<?= $row_view[0]['cid']; ?>" enctype="multipart/form-data">
                                    <div class="modal-body">
                                        <div class="mb-3">
                                            <label for="complaint_date" class="form-label">Date</label>
                                            <input type="date" class="form-control" id="complaint_date" name="complaint_date" placeholder="" value="<?= $row_view[0]['complaint_date']; ?>" required>
                                            <div class="form-text">date of the event happening</div>
                                        </div>
                                        <div class="mb-3">
                                            <label for="complaint_date" class="form-label">Category</label>
                                            <select type="text" class="form-control" id="complaint_category" name="complaint_category" required>
                                                <option value="<?= $row_view[0]["category_id"]; ?>" selected>
                                                    <?= ucwords($row_view[0]["category"]); ?>
                                                </option>
                                                <?= get_categories(); ?>
                                            </select>
                                        </div>
                                        <div class="mb-3">
                                            <label for="message" class="form-label">Message</label>
                                            <textarea class="form-control" id="message" name="message" rows="3" required><?= $row_view[0]['complaint_message']; ?></textarea>
                                        </div>
                                        <!-- <div class="mb-3">
                                            <label for="file" class="form-label">Document (optional)</label>
                                            <input type="file" class="form-control" id="file" name="file">
                                            <div class="form-text">Upload any document if available for more evidence</div>
                                        </div> -->
                                    </div>
                                    <div class="modal-footer">
                                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                                        <button type="submit" name="submit" class="btn btn-primary">Update complain</button>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                <?php
                    } else {
                        $_SESSION['flash_error'] = 'Complaint cannot be found!';
                        redirect('complaints');
                    }

            ?>
            <?php else: ?>
            <h1 class="h2">Complaints</h1>
            <div class="col-md-10">
                <table class="table table-striped table-hover">
                    <thead>
                        <tr>
                            <th></th>
                            <th>ID</th>
                            <th>Category</th>
                            <th>Content</th>
                            <th>Date Added</th>
                            <th>By</th>
                            <th>Status</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php 
                            $i = 1; 
                            foreach ($result as $row): 
                                $status = "new";
                                $status_bg = "warning";
                                if ($row['complaint_status'] == 1) {
                                    // code...
                                    $status = "processing";
                                    $status_bg = "info";
                                } else if ($row['complaint_status'] == 2) {
                                    $status = "Done";
                                    $status_bg = "success";
                                }
                        ?>
                            <tr>
                                <td><?= $i; ?></td>
                                <td>
                                    <a href="complaints?view=<?= $row['cid']; ?>" class="text-decoration-none">
                                        <?= ucwords($row['complaint_id']); ?>
                                    </a>
                                </td>
                                <td><?= ucwords($row['category']); ?></td>
                                <td><?= substr($row['complaint_message'], 0, 10); ?>...</td>
                                <td><?= pretty_date($row['createdAt']); ?></td>
                                <td><?= ucwords($row['fullname']); ?></td>
                                <td><span class="badge bg-<?= $status_bg ?>"><?= $status ?></td>
                            </tr>
                        <?php $i++; endforeach; ?>
                    </tbody>
                </table>
            </div>
            <?php endif ?>
        </div>
    </main>

    <script src="<?= PROOT; ?>dist/js/jquery-3.7.1.min.js"></script>
    <script src="<?= PROOT; ?>dist/js/popper.min.js"></script>
    <script src="<?= PROOT; ?>dist/js/bootstrap.min.js"></script>

    <!-- Place the first <script> tag in your HTML's <head> -->
    <script src="https://cdn.tiny.cloud/1/87lq0a69wq228bimapgxuc63s4akao59p3y5jhz37x50zpjk/tinymce/7/tinymce.min.js" referrerpolicy="origin"></script>

    <!-- Place the following <script> and <textarea> tags your HTML's <body> -->
    <script>
      tinymce.init({
        selector: 'textarea',
        plugins: 'anchor autolink charmap codesample emoticons image link lists media searchreplace table visualblocks wordcount',
        toolbar: 'undo redo | blocks fontfamily fontsize | bold italic underline strikethrough | link image media table | align lineheight | numlist bullist indent outdent | emoticons charmap | removeformat',
        setup: function (editor) {
            editor.on('change', function (e) {
                editor.save();
            });
        }
      });
    </script>
</body>
</html>
