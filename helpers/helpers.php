<?php 
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\SMTP;
use PHPMailer\PHPMailer\Exception;
require BASEURL . 'vendor/autoload.php';

function dnd($data) {
	echo "<pre>";
	print_r($data);
	echo "</pre>";
    die;
}

// Make Date Readable
function pretty_date($date){
	return date("M d, Y h:i A", strtotime($date));
}
function pretty_date_only($date){
	return date("M d, Y", strtotime($date));
}

// Display money in a readable way
function money($number) {
	return '$' . number_format($number, 2);
}

// Check For Incorrect Input Of Data
function sanitize($dirty) {
    $clean = htmlentities($dirty, ENT_QUOTES, "UTF-8");
    return trim($clean);
}

function cleanPost($post) {
    $clean = [];
    foreach ($post as $key => $value) {
      	if (is_array($value)) {
        	$ary = [];
        	foreach($value as $val) {
          		$ary[] = sanitize($val);
        	}
        	$clean[$key] = $ary;
      	} else {
        	$clean[$key] = sanitize($value);
      	}
    }
    return $clean;
}

//
function php_url_slug($string) {
 	$slug = preg_replace('/[^a-z0-9-]+/', '-', trim(strtolower($string)));
 	return $slug;
}


// REDIRECT PAGE
function redirect($url) {
    if(!headers_sent()) {
      	header("Location: {$url}");
    } else {
      	echo '<script>window.location.href="' . $url . '"</script>';
    }
    exit;
}

function issetElse($array, $key, $default = "") {
    if(!isset($array[$key]) || empty($array[$key])) {
      return $default;
    }
    return $array[$key];
}


// Email VALIDATION
function isEmail($email) {
	return (!preg_match("/^([a-z0-9\+_\-]+)(\.[a-z0-9\+_\-]+)*@([a-z0-9\-]+\.)+[a-z]{2,6}$/ix", $email)) ? FALSE : TRUE;
}

// GET USER IP ADDRESS
function getIPAddress() {  
    //whether ip is from the share internet  
    if (!empty($_SERVER['HTTP_CLIENT_IP'])) {  
        $ip = $_SERVER['HTTP_CLIENT_IP'];  
    } elseif (!empty($_SERVER['HTTP_X_FORWARDED_FOR'])) {  // whether ip is from the proxy
       $ip = $_SERVER['HTTP_X_FORWARDED_FOR'];  
     } else {  // whether ip is from the remote address 
        $ip = $_SERVER['REMOTE_ADDR'];  
    }  
    return $ip;  
}

// PRINT OUT RANDAM NUMBERS
function digit_random($digits) {
  	return rand(pow(10, $digits - 1) - 1, pow(10, $digits) - 1);
}

function js_alert($msg) {
	return '<script>alert("' . $msg . '");</script>';
}


// 
function sms_otp($msg, $phone) {
	$sender = urlencode("Inqoins VER");
    $api_url = "https://api.innotechdev.com/sendmessage.php?key=".SMS_API_KEY."&message={$msg}&senderid={$sender}&phone={$phone}";
    $json_data = file_get_contents($api_url);
    $response_data = json_decode($json_data);
    // Can be use for checks on finished / unfinished balance
    $fromAPI = 'insufficient balance, kindly credit your account';  
    if ($api_url)
    	return 1;
	else
		return 0;
}

//
function send_email($name, $to, $subject, $body) {
	$mail = new PHPMailer(true);
	try {
        $fn = $name;
        $to = $to;
        $from = MAIL_MAIL;
        $from_name = 'MIFO, Shop.';
        $subject = $subject;
        $body = $body;

        //Create an instance; passing `true` enables exceptions
        $mail = new PHPMailer(true);

        $mail->IsSMTP();
        $mail->SMTPAuth = true;

        $mail->SMTPSecure = 'ssl'; 
        $mail->Host = 'smtp.mifostore.com';
        $mail->Port = 465;  
        $mail->Username = $from;
        $mail->Password = MAIL_KEY; 

        $mail->IsHTML(true);
        $mail->WordWrap = 50;
        $mail->From = $from;
        $mail->FromName = $from_name;
        $mail->Sender = $from;
        $mail->AddReplyTo($from, $from_name);
        $mail->Subject = $subject;
        $mail->Body = $body;
        $mail->AddAddress($to);
        $mail->send();
        return true;
    } catch (Exception $e) {
    	//return "Message could not be sent. Mailer Error: {$mail->ErrorInfo}";
    	return false;
        //$message = "Please check your internet connection well...";
    }
}

// go back
function goBack() {
	$previous = "javascript:history.go(-1)";
	if (isset($_SERVER['HTTP_REFERER'])) {
	    $previous = $_SERVER['HTTP_REFERER'];
	}
	return $previous;
}




////////////////////////////////////////////////////////////////////////////////////////////////////////


function get_header_information() {
	global $conn;
	$siteQuery = "
	    SELECT * FROM mifo_about 
	    LIMIT 1
	";
	$statement = $conn->prepare($siteQuery);
	$statement->execute();
	$site_result = $statement->fetchAll();

	foreach ($site_result as $site_row) {
	    $phone_1 = $site_row["about_phone"];
	}
	$actual_link = (isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on' ? "https" : "http") . "://$_SERVER[HTTP_HOST]$_SERVER[REQUEST_URI]";
	$actual_linkBreakDown = explode('/', $actual_link);
	$actual_linkLast = end($actual_linkBreakDown);

	$output = '';
	if ($actual_linkLast != 'signin' && $actual_linkLast != 'signin.php') {

		$output .= '
		<div class="header-eyebrow bg-dark">
		<div class="container">
		<div class="navbar navbar-dark row">
		<div class="col">
		<ul class="navbar-nav mr-auto">
		<li class="nav-item dropdown">
		<a class="nav-link dropdown-toggle" href="javascript:;" id="curency" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
		USD
		</a>
		<ul class="dropdown-menu" aria-labelledby="curency">
		<li><a class="dropdown-item" href="javascript:;">EUR</a></li>
		<li><a class="dropdown-item" href="javascript:;">RUB</a></li>
		</ul>
		</li>
		<li class="nav-item dropdown">
		<a class="nav-link dropdown-toggle" href="#!" id="language" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
		EN
		</a>
		<ul class="dropdown-menu" aria-labelledby="language">
		<li><a class="dropdown-item" href="#!">Deutsch</a></li>
		<li><a class="dropdown-item" href="#!">Russian</a></li>
		<li><a class="dropdown-item" href="#!">French</a></li>
		</ul>
		</li>
		</ul>
		</div>
		<div class="col text-right">
		<span class="phone text-white">'.$phone_1.'</span>
		</div>
		</div>
		</div>
		</div>
		';

	} else {
		$output = '';
	}
	return $output;
}

function getTitle() {
	$actual_link = (isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on' ? "https" : "http") . "://$_SERVER[HTTP_HOST]$_SERVER[REQUEST_URI]";
	$actual_linkBreakDown = explode('/', $actual_link);
	$actual_linkLast = end($actual_linkBreakDown);

	$output = '';
	if ($actual_linkLast == 'signin' || $actual_linkLast == 'signin.php') {
		$output = 'Sigin · ';
	} elseif ($actual_linkLast == 'index.php' || $actual_linkLast == 'index' || $actual_linkLast == '') {
		$output = 'Welcome · ';
	} elseif ($actual_linkLast == 'products' || $actual_linkLast == 'products.php') {
		$output = 'Products · ';
	} elseif ($actual_linkLast == 'profile' || $actual_linkLast == 'profile.php') {
		$output = 'Profile · ';
	} elseif ($actual_linkLast == 'yourpassword' || $actual_linkLast == 'yourpassword.php') {
		$output = 'My Password · ';
	} elseif ($actual_linkLast == 'youraddress' || $actual_linkLast == 'youraddress.php') {
		$output = 'My Address · ';
	} elseif ($actual_linkLast == 'yourorders' || $actual_linkLast == 'yourorders.php') {
		$output = 'My Orders · ';
	} elseif ($actual_linkLast == 'cart' || $actual_linkLast == 'cart.php') {
		$output = 'My Cart · ';
	} elseif ($actual_linkLast == 'forgotPassword' || $actual_linkLast == 'forgotPassword.php') {
		$output = 'Forgot Password · ';
	} elseif ($actual_linkLast == 'verify' || $actual_linkLast == 'verify.php') {
		$output = 'Verify Account · ';
	} elseif ($actual_linkLast == 'verified' || $actual_linkLast == 'verified.php') {
		$output = 'Account on Verification · ';
	} elseif ($actual_linkLast == 'contact-us' || $actual_linkLast == 'contact-us.php') {
		$output = 'Contact Us · ';
	} elseif ($actual_linkLast == 'resendVericode' || $actual_linkLast == 'resendVericode.php') {
		$output = 'Resend Verification Code · ';
	} elseif ($actual_linkLast == 'resetPassword' || $actual_linkLast == 'resetPassword.php') {
		$output = 'Reset Password · ';
	} elseif ($actual_linkLast == 'thankYou' || $actual_linkLast == 'thankYou.php') {
		$output = 'Thank You · ';
	}
	return $output;
}




// Sessions For login
function studentLogin($stud_id) {
	$_SESSION['ComStudent'] = $stud_id;
	global $conn;
	$data = array(
		':updatedAt' => date("Y-m-d H:i:s"),
		':id' => (int)$stud_id
	);
	$query = "
		UPDATE students 
		SET updatedAt = :updatedAt 
		WHERE id = :id";
	$statement = $conn->prepare($query);
	$result = $statement->execute($data);
	if (isset($result)) {
		$_SESSION['flash_success'] = 'You are now logged in!';
		redirect(PROOT . 'board');
	}
}

function student_is_logged_in(){
	if (isset($_SESSION['ComStudent']) && $_SESSION['ComStudent'] > 0) {
		return true;
	}
	return false;
}

// Redirect student if !logged in
function student_login_redirect($url = 'login') {
	$_SESSION['flash_error'] = 'You must be logged in to access that page.';
	redirect($url);
}


/////////////////////////////////////////////////////////////////////////////////////////////////




// Sessions For login
function adminLogin($admin_id) {
	$_SESSION['ComAdmin'] = $admin_id;
	global $conn;
	$data = array(
		':admin_last_login' => date("Y-m-d H:i:s"),
		':admin_id' => $admin_id
	);
	$query = "
		UPDATE students 
		SET createdAt = :admin_last_login 
		WHERE updatedAt = :admin_id";
	$statement = $conn->prepare($query);
	$result = $statement->execute($data);
	if (isset($result)) {
		$_SESSION['flash_success'] = 'You are now logged in!';
		redirect(PROOT . 'admin/index');
	}
}

function admin_is_logged_in(){
	if (isset($_SESSION['ComAdmin']) && $_SESSION['ComAdmin'] > 0) {
		return true;
	}
	return false;
}

// Redirect admin if !logged in
function admn_login_redirect($url = 'login') {
	$_SESSION['flash_error'] = '<div class="text-center" id="temporary" style="margin-top: 60px;">You must be logged in to access that page.</div>';
	redirect(PROOT . 'admin/' . $url);
}

// Redirect admin if do not have permission
function admin_permission_redirect($url = 'login') {
	$_SESSION['flash_error'] = '<div class="text-center" id="temporary" style="margin-top: 60px;">You do not have permission in to access that page.</div>';
	header('Location: admin/'.$url);
}








/////////////////////////////////////////////////////////////////////////////////////////////////////////



// GET complaints per students
function get_complaint_per_student($student_id) {
	global $conn;
	$output = '
		<table class="table">
		  <thead>
		    <tr>
		      <th scope="col">#</th>
		      <th scope="col">ID</th>
		      <th scope="col">Category</th>
		      <th scope="col">Event date</th>
		      <th scope="col">Content</th>
		      <th scope="col">Comment</th>
		      <th scope="col">Status</th>
		      <th scope="col"></th>
		    </tr>
		  </thead>
		  <tbody>
	';

	$query = "
		SELECT *, complaints.id AS cid FROM complaints 
		INNER JOIN categories 
		ON categories.id = complaints.category_id
		WHERE complaints.student_id = ?
		AND complaints.trash = ? 
		ORDER BY complaints.createdAt DESC
	";
	$statement = $conn->prepare($query);
	$statement->execute(
		[$student_id, 0]
	);
	$resutl = $statement->fetchAll();
	if ($statement->rowCount() > 0) {
		// code...
		
		$i = 1;
		foreach ($resutl as $row) {
			$status = "new";
	        $status_bg = "warning";
	        $comment = '';
	        if ($row['complaint_status'] == 1) {
	            // code...
	            $status = "processing";
	            $status_bg = "info";
	        } else if ($row['complaint_status'] == 2) {
	            $status = "Done";
	            $status_bg = "success";
	            $comment = "
	            	<br>
	            	<a href='?comment=".$row['complaint_id']."' class='badge bg-dark'>Comment ...</a>
	            ";
	        }

			$output .= '
				
				<tr>
				    <th scope="row">' . $i . '</th>
				    <th scope="row">' . $row["complaint_id"].'</th>
				    <td>'.ucwords($row["category"]).'</td>
				    <td>'.pretty_date_only($row["complaint_date"]).'</td>
				    <td>' . substr($row["complaint_message"], 0, 10) . ' ...</td>
				    <td>' . substr($row["complaint_comment"], 0, 10) . ' ...</td>
				    <td>
				    	<span class="badge bg-'.$status_bg.'">'.$status.'</span>
				    	' . $comment . '
				    </td>
				    <td><a href="javascript:;" data-bs-toggle="modal" data-bs-target="#complaintModal_'.$row["cid"].'">view</a></td>
				</tr>
				   
				<!-- Modal -->
				<div class="modal fade" id="complaintModal_'.$row["cid"].'" tabindex="-1" aria-labelledby="complaintModalLabel_'.$row["cid"].'" aria-hidden="true">
				  	<div class="modal-dialog modal-lg">
				    	<div class="modal-content">
				      		<div class="modal-header">
					        	<h1 class="modal-title fs-5" id="complaintModalLabel_'.$row["cid"].'">Complaint on '.pretty_date($row["createdAt"]).'</h1>
					        	<button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
					      	</div>
					      	<div class="modal-body">
					      		Category: '.ucwords($row["category"]).'
					      		<br>
					      		Event Date: '.pretty_date_only($row["complaint_date"]).'
					      		<br>
					      		Status: <span class="badge bg-'.$status_bg.'">'.$status.'</span>
					      		<br>
					      		Comment: <span>'.$row["complaint_comment"].'</span>
					      		<hr>
					        	' . nl2br($row['complaint_message']) . '
					      	</div>
					      	<div class="modal-footer">
					        	<button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
					        	<a href="' . PROOT . 'board?delete=' . $row["cid"] . '" class="btn btn-danger">Delete</a>
					      	</div>
					    </div>
				  	</div>
				</div>
			';
			$i++;
		}
		$output .= '
			</tbody>
				</table>
		';
	} else {
		$output = '
			<div class="alert alert-primary" role="alert">
				You have no complaints yet.
			</div>
		';
	}
	return $output;
}

// get all categories for complaints lodging
function get_categories() {
	global $conn;
	$output = '';
	$sql = "
		SELECT * FROM categories 
		ORDER BY category ASC
	";
	$statement = $conn->query($sql);
	$categories = $statement->fetchAll();
	foreach ($categories as $category) {
		$output .= '
			<option value="'.$category["id"].'">'.ucwords($category["category"]).'</option>
		';
	}
	return $output;
}


// count complaints per each student
function count_complaints_per_students($student_id) {
	global $conn;
	$query = "
		SELECT * FROM complaints  
		INNER JOIN categories 
		ON categories.id = complaints.category_id
		WHERE complaints.student_id = ? AND complaints.trash = ?
	";
	$statement = $conn->prepare($query);
	$statement->execute(
		[$student_id, 0]
	);
	return $statement->rowCount();
}

// count all complaints
function count_complaints() {
	global $conn;
	$query = "
		SELECT * FROM complaints 
		WHERE trash = ?
	";
	$statement = $conn->prepare($query);
	$statement->execute(
		[0]
	);
	$statement->fetchAll();
	return $statement->rowCount();
}

////////////////////////////////////////////////////////////////////////////////////////////////////

// GET ADMIN PROFILE DETAILS
function get_admin_profile() {
	global $conn;
	global $admin_data;
	$output = '';

	$query = "
		SELECT * FROM students 
		WHERE student_id = :admin_id 
		AND trash = :admin_trash 
		LIMIT 1
	";
	$statement = $conn->prepare($query);
	$statement->execute([
		':admin_id' => $admin_data['student_id'],
		':admin_trash' => 0,
	]);
	$result = $statement->fetchAll();

	foreach ($result as $row) {
		$output = '
			<h3>Name</h3>
		    <p class="lead">'.ucwords($row["fullname"]).'</p>
		    <br>
		    <h3>Email</h3>
		    <p class="lead">'.$row["email"].'</p>
		    <br>
		    <h3>Joined Date</h3>
		    <p class="lead">'.pretty_date($row["createdAt"]).'</p>
		    <br>
		    <h3>Last Login</h3>
		    <p class="lead">'.pretty_date($row["updatedAt"]).'</p>
		';
	}
	return $output;
}

// LIST * USERS
function get_all_users($user_trash = 0) {
	global $conn;

	$query = "
		SELECT * FROM mifo_user
		WHERE user_trash = :user_trash
	";
	$statement = $conn->prepare($query);
	$statement->execute([':user_trash' => $user_trash]);
	$result = $statement->fetchAll();
	$row_count = $statement->rowCount();

	$output = '';
	if ($row_count > 0) {

		$i = 1;
		foreach ($result as $row) {
			$user_last_login = $row["user_last_login"];
			if ($user_last_login == NULL) {
				$user_last_login = '<span class="text-secondary">Never</span>';
			} else {
				$user_last_login = pretty_date($user_last_login);
			}

			$output .= '
				<td>'.$i.'</td>
				<td>'.ucwords($row["user_fullname"]).'</td>
				<td>'.$row["user_email"].'</td>
				<td>'.(($row["user_phone"] != '')?$row["user_phone"]:'<span class="text-secondary">Empty</span>').'</td>
				<td>'.(($row["user_address"] != '')?ucwords($row["user_address"]):'<span class="text-secondary">Empty</span>').'</td>
				<td>'.pretty_date($row["user_joined_date"]).'</td>
				<td>'.$user_last_login.'</td>
				<td>
					<a href="users?view='.$row["user_id"].'" class="btn btn-sm btn-light"><span data-feather="eye"></span></a>&nbsp;
			';
			if ($user_trash == 1) {
				$output .= '
					<a href="users?restore='.$row["user_id"].'" class="btn btn-sm btn-secondary"><span data-feather="refresh-ccw"></span></a>&nbsp;
					<a href="users?delete='.$row["user_id"].'" class="btn btn-sm btn-warning"><span data-feather="trash"></span></a>&nbsp;
				';
			} else {
				$output .= '
					<a href="users?terminate='.$row["user_id"].'" class="btn btn-sm btn-secondary"><span data-feather="user-x"></span></a>&nbsp;
				';
			}
			$output .= '
					</td>
				</tr>
			';
			$i++;
		}
	} else {
		$output = '
			<tr>
				<td colspan="8"> - No data found under users table.</td>
			</tr>
		';
	}
	return $output;
}



//////////////////////////////////////////////////////////////////////////////////////////////////////////


// Get categories
function get_all_categories() {
	global $conn;
	$output = '';

	$query = '
		SELECT * FROM mifo_category 
		WHERE category_parent = :category_parent 
		AND category_trash = :category_trash 
		ORDER BY category ASC
		LIMIT 5
	';
	$statement = $conn->prepare($query);
	$statement->execute([
		':category_parent' 	=> 0,
		':category_trash' 	=> 0
	]);
	$result = $statement->fetchAll();

	foreach ($result as $row) {
		$output .= '
			<a class="nav-link" href="'.PROOT.'store/category/'.$row["category_id"].'">'.ucwords($row["category"]).'</a>
		';
	}
	return $output;
}






///////////////////////////////////////////////////////////////////////////////////////////////////////////

// FIND USER WITH EMAIL
 function findUserByEmail($email) {
 	global $conn;
    $sql = "
    	SELECT * FROM mifo_user 
    	WHERE user_email = :user_email
    ";
    $statement = $conn->prepare($sql);
    $statement->execute([':user_email' => $email]);
    $result = $statement->fetchAll();
    foreach ($result as $row) {
    	return $row;
    }
}

// Sessions For login
function userLogin($user_id) {
	$_SESSION['MFUser'] = $user_id;
	global $conn;
	$data = array(
		':user_last_login' => date("Y-m-d H:i:s"),
		':user_id' => (int)$user_id
	);
	$query = "
		UPDATE mifo_user 
		SET user_last_login = :user_last_login 
		WHERE user_id = :user_id";
	$statement = $conn->prepare($query);
	$result = $statement->execute($data);
	if (isset($result)) {
		$_SESSION['flash_success'] = '<div class="text-center" id="temporary">You are now logged in!</div>';
		redirect(PROOT . 'shop/index');
	}
}

function user_is_logged_in(){
	if (isset($_SESSION['MFUser']) && $_SESSION['MFUser'] > 0) {
		return true;
	}
	return false;
}

// Redirect admin if !logged in
function user_login_redirect($url = 'login') {
	$_SESSION['flash_error'] = '<div class="text-center" id="temporary" style="margin-top: 60px;">You must be logged in to access that page.</div>';
	header('Location: '.$url);
}