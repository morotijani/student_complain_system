<?php

	// Connection To Database
	$servername = 'localhost';
	$username = 'root';
	$password = '';
	$conn = new PDO("mysql:host=$servername;dbname=student_complaint_system", $username, $password);
	session_start();

	require_once($_SERVER['DOCUMENT_ROOT'].'/student_complaint_system/config.php');
 	require_once(BASEURL.'helpers/helpers.php');

 	// Display on Messages on Errors And Success
 	$flash = '';
 	if (isset($_SESSION['flash_success'])) {
 	 	$flash = '
 	 		<div class="bg-secondary" id="temporary">
 	 			<p class="text-center text-white">'.$_SESSION['flash_success'].'</p>
 	 		</div>';
 	 	unset($_SESSION['flash_success']);
 	}

 	if (isset($_SESSION['flash_error'])) {
 	 	$flash = '
 	 		<div class="bg-danger" id="temporary">
 	 			<p class="text-center text-white">'.$_SESSION['flash_error'].'</p>
 	 		</div>';
 	 	unset($_SESSION['flash_error']);
 	}

	

 	require BASEURL . 'vendor/autoload.php';


////////////////////////////////////////////////////////////////////////////////////////////////////////
 	// ADMIN LOGIN
 	if (isset($_SESSION['ComAdmin'])) {
 		$admin_id = $_SESSION['ComAdmin'];
 		$data = array(
 			':admin_id' => $admin_id
 		);
 		$sql = "
 			SELECT * FROM students 
 			WHERE student_id = :admin_id 
 			LIMIT 1
 		";
 		$statement = $conn->prepare($sql);
 		$statement->execute($data);

 		foreach ($statement->fetchAll() as $admin_data) {
 			$fn = explode(' ', $admin_data['fullname']);
 			$admin_data['first'] = ucwords($fn[0]);
 				$admin_data['last'] = '';
 			if (count($fn) > 1) {
 				$admin_data['last'] = ucwords($fn[1]);
 			}
 		}
 	}

/////////////////////////////////////////////////////////////////////////////////////////////////////
 	// USER LOGIN
 	if (isset($_SESSION['ComStudent'])) {
 		$user_id = $_SESSION['ComStudent'];
 		$data = array(
 			':id' => $user_id,
 			':trash' => 0
 		);
 		$sql = "
 			SELECT * FROM students 
 			WHERE id = :id 
 			AND trash = :trash 
 			LIMIT 1
 		";
 		$statement = $conn->prepare($sql);
 		$statement->execute($data);
 		if ($statement->rowCount() > 0) {
	 		foreach ($statement->fetchAll() as $user_data) {
	 			$fn = explode(' ', $user_data['fullname']);
	 			$user_data['first'] = ucwords($fn[0]);
	 			$user_data['last'] = '';
	 			if (count($fn) > 1) {
	 				$user_data['last'] = ucwords($fn[1]);
	 			}
	 		}
 		} else {
 			unset($_SESSION['ComStudent']);
 			redirect(PROOT . 'board');
 		}

 	}


