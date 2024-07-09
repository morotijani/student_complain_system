<?php

require_once ("db_connection/conn.php");

if (!student_is_logged_in()) {
    student_login_redirect();
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
            redirect(PROOT . 'board');
        } else {
            echo js_alert('Something went wrong, please try again.');
        }
    } else {
        $_SESSION['flash_error'] = 'Can not delete complaint!';
        redirect(PROOT . 'board');
    }
}


$complaint_date = ((isset($_POST['complaint_date']) ? sanitize($_POST['complaint_date']) : ''));
$message = ((isset($_POST['message']) ? $_POST['message'] : ''));
$fileNewName = '';
if (isset($_POST['submit'])) {

    // if (empty($_FILES)) {
    //     exit('$_FILES is empty - is file_uploads set to "Off" in php.ini?');
    // } else {

    //     $file = $_FILES['file'];
    //     $fileName = $_FILES['file']['name'];
    //     $fileTmpName = $_FILES['file']['tmp_name'];
    //     $fileSize = $_FILES['file']['size'];
    //     $fileError = $_FILES['file']['error'];
    //     $fileType = $_FILES['file']['type'];

    //     $fileExt = explode('.', $fileName);
    //     $fileActualExt = strtolower(end($fileExt));

    //     $allowed = array('jpg', 'jpeg', 'png', 'pdf');
    //     if (in_array($fileActualExt, $allowed)) {
    //         // code...
    //         if ($fileError === 0) {
    //             if ($fileSize < 1000000) {
    //                 // code...
    //                 $fileNewName = uniqid('', true) . "." . $fileActualExt;
    //                 $fileDestination = 'dist/media/uploads/' . $fileNewName;

    //                 move_uploaded_file($fileTmpName, $fileDestination);


    //             } else {
    //                 echo js_alert("Your file is too big!");
    //                 exit('<a href="javascript:history.go(-1)">go back</a>');
    //             }
    //         } else {
    //             echo js_alert("There was an error uploading your file!");
    //             exit('<a href="javascript:history.go(-1)">go back</a>');
    //         }
    //     } else {
    //         echo js_alert("You cannot upload files of this type!");
    //         exit('<a href="javascript:history.go(-1)">go back</a>');
    //     }
    // }

    $complaint_id = uniqid('', true);
    $createdAt = date("Y-m-d H:i:s");
    $data = [$complaint_id, $user_id, (int)$_POST['complaint_category'], $fileNewName, $message, $complaint_date, $createdAt];
    $sql = "
        INSERT INTO `complaints`(`complaint_id`, `student_id`, `category_id`, `complaint_document`, `complaint_message`, `complaint_date`, `createdAt`) 
        VALUES (?, ?, ?, ?, ?, ?, ?)
    ";
    $statement = $conn->prepare($sql);
    $result = $statement->execute($data);
    if (isset($result)) {
        $_SESSION['flash_success'] = 'New complain lodged successfully';
        redirect(PROOT . 'board');
    } else {
        echo js_alert('Something went wrong, please try again');
    }
}



?>
<!DOCTYPE html>
<html lang="en" data-bs-theme="auto">
    <head>
        <script src="dist/js/color-modes.js"></script>

        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <title>Student Complaint System</title>
        <link href="dist/css/bootstrap.min.css" rel="stylesheet">
        <meta name="theme-color" content="#712cf9">

        <style type="text/css">
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
            
            .border-dashed { --bs-border-style: dashed; }
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

    <div class="container">
        <nav>
            <ul class="nav justify-content-center">
                <li class="nav-item"><a href="index" class="nav-link px-2 text-body-secondary">Home</a></li>
                <li class="nav-item"><a href="about" class="nav-link px-2 text-body-secondary">About</a></li>
                <li class="nav-item"><a href="profile" class="nav-link px-2 text-body-secondary">Hi <?= $user_data['first']; ?>!</a></li>
                <li class="nav-item"><a href="profile" class="nav-link px-2 text-body-secondary">Profile</a></li>
                <li class="nav-item"><a href="logout" class="nav-link px-2 text-body-secondary">Logout</a></li>
            </ul>
        </nav>
    </div>
    <?= $flash; ?>
    <div class="container my-3">
        <div class="d-flex align-items-center p-3 my-3 text-white bg-purple rounded shadow-sm">
            <img class="me-3" src="dist/media/logo.png" alt="" width="48" height="48">
            <div class="lh-1">
              <h1 class="h6 mb-0 text-white lh-1">Student Complaint System</h1>
              <small>CKT-UTAS</small>
            </div>
        </div>
            
        <div class="p-5 text-center bg-body-tertiary rounded-3">
            <h1 class="text-body-emphasis">you've made <?= count_complaints_per_students($user_id); ?> complaints</h1>
            <button class="d-inline-flex align-items-center btn btn-primary btn-lg px-4 rounded-pill" type="button" data-bs-toggle="modal" data-bs-target="#exampleModal">
                Lodge complaint
                <svg class="bi ms-2" width="24" height="24"><use xlink:href="#arrow-right-short"/></svg>
            </button>
        </div>

        <div class="my-3 p-3 bg-body rounded shadow-sm">
            <h6 class="border-bottom pb-2 mb-0">Complaints</h6>
            <?php echo get_complaint_per_student($user_id); ?>

            <small class="d-block text-end mt-3">
                <a href="board">All complaints</a>
            </small>
        </div>
    </div>

    <!-- Modal -->
    <div class="modal fade" id="exampleModal" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h1 class="modal-title fs-5" id="exampleModalLabel">Make a Complain</h1>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <form method="POST" enctype="multipart/form-data">
                    <div class="modal-body">
                        <div class="mb-3">
                            <label for="complaint_date" class="form-label">Date</label>
                            <input type="date" class="form-control" id="complaint_date" name="complaint_date" placeholder="" value="<?= $complaint_date; ?>" required>
                            <div class="form-text">date of the event happening</div>
                        </div>
                        <div class="mb-3">
                            <label for="complaint_date" class="form-label">Category</label>
                            <select type="text" class="form-control" id="complaint_category" name="complaint_category" required>
                                <option value=""></option>
                                <?= get_categories(); ?>
                            </select>
                        </div>
                        <div class="mb-3">
                            <label for="message" class="form-label">Message</label>
                            <textarea class="form-control" id="message" name="message" rows="3" required><?= $message; ?></textarea>
                        </div>
                        <!-- <div class="mb-3">
                            <label for="file" class="form-label">Document (optional)</label>
                            <input type="file" class="form-control" id="file" name="file">
                            <div class="form-text">Upload any document if available for more evidence</div>
                        </div> -->
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                        <button type="submit" name="submit" class="btn btn-primary">Send complain</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <script src="dist/js/jquery-3.7.1.min.js"></script>
    <script src="dist/js/popper.min.js"></script>
    <script src="dist/js/bootstrap.min.js"></script>
    <!-- <script src="dist/js/tinymce.min.js"></script> -->

    <!-- Place the first <script> tag in your HTML's <head> -->
    <script src="https://cdn.tiny.cloud/1/87lq0a69wq228bimapgxuc63s4akao59p3y5jhz37x50zpjk/tinymce/7/tinymce.min.js" referrerpolicy="origin"></script>

    <!-- Place the following <script> and <textarea> tags your HTML's <body> -->
    <script>
        tinymce.init({
            selector: '#message',
            plugins: 'anchor autolink charmap codesample emoticons image link lists media searchreplace table visualblocks wordcount',
            toolbar: 'undo redo | blocks fontfamily fontsize | bold italic underline strikethrough | link image media table | align lineheight | numlist bullist indent outdent | emoticons charmap | removeformat',
            setup: function (editor) {
                editor.on('change', function (e) {
                    editor.save();
                });
            },
            images_upload_url: '<?= PROOT; ?>postAcceptor.php',
            //images_upload_base_path: '/dist/media/complaint_media'
        });
    </script>
</body>
</html>