<?php
session_start();
// error_reporting(0);
if (empty($_SESSION['login_user'])){
	header('location:keluar');
}
else{
	include "../../../../konfig/koneksi.php";
	include "../../../../konfig/library.php";
	include "../../../../konfig/fungsi_log_activity.php";
	include "../../../../konfig/fungsi_thumb.php";
    include "../../../../konfig/base_url.php";
	include "../../../../konfig/myencrypt.php";
	include "../../../../konfig/fungsi_form_modal.php";

	include "../../../services/send_discord.php";
    include "../../../services/get_error.php";
	
	$act=$_GET['act'];
    
	$module = "User";
	
	if($act=='tambah'){
		include "tambah.php";
	}

	else if($act=='input'){
        
		$password=encrypt($_POST['password']);

		$sql="INSERT INTO pegawai (master_cabang_id, username, password, nama, email, no_handphone, jabatan, created_at, updated_at) VALUES ('$_POST[master_cabang_id]', '$_POST[username]', '$password', '$_POST[nama]', '$_POST[email]', '$_POST[no_handphone]', '$_POST[jabatan]', '$waktu_sekarang', '$waktu_sekarang')";

		mysqli_query($conn,$sql);
		$d=mysqli_insert_id($conn);

		log_activity($conn, $_SESSION['login_user'], $module, 'pegawai', $d, 'CREATE', $_SESSION['id_session'], $waktu_sekarang);

		// header("location: user");
	
	}

	else if($act=='edit'){
		include "edit.php";
	}

	else if($act=='update'){

		$password=encrypt($_POST['password']);
		
        $sql="UPDATE pegawai SET master_cabang_id='$_POST[master_cabang_id]', username='$_POST[username]', password='$password', nama='$_POST[nama]', email='$_POST[email]', no_handphone='$_POST[no_handphone]', jabatan='$_POST[jabatan]', updated_at='$waktu_sekarang' WHERE id='$_POST[id]'";

        mysqli_query($conn,$sql);

		log_activity($conn, $_SESSION['login_user'], $module, 'pegawai', $_POST['id'], 'UPDATE', $_SESSION['id_session'], $waktu_sekarang);

		// header("location: user");
	}
	
	else if($act=='delete'){
		$sql="UPDATE pegawai SET deleted_at='$waktu_sekarang' WHERE id='$_GET[id]'";
		$result=mysqli_query($conn,$sql);

		log_activity($conn, $_SESSION['login_user'], $module, 'pegawai', $_GET['id'], 'DELETE', $_SESSION['id_session'], $waktu_sekarang);

		header("location: user?message=delete");
	}

	mysqli_close($conn);
	
}
?>