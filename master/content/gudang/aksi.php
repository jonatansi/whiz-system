<?php
session_start();
// error_reporting(0);
if (empty($_SESSION['login_user'])){
	header('location:keluar');
}
else{
	include "../../../konfig/koneksi.php";
	include "../../../konfig/library.php";
	include "../../../konfig/fungsi_log_activity.php";
	include "../../../konfig/fungsi_thumb.php";
    include "../../../konfig/base_url.php";
	include "../../../konfig/fungsi_form_modal.php";

	include "../../../services/send_discord.php";
    include "../../../services/get_error.php";
	
	$act=$_GET['act'];
    
	$module = "Master gudang";
	
	if($act=='tambah'){
		include "tambah.php";
	}

	else if($act=='input'){
		$kode = strtoupper($_POST['kode']);

		$sql="INSERT INTO master_gudang (kode, nama, master_cabang_id, remark, created_at, updated_at) VALUES ('$kode', '$_POST[nama]', '$_POST[master_cabang_id]', '$_POST[remark]', '$waktu_sekarang', '$waktu_sekarang')";

		mysqli_query($conn,$sql);
		$d=mysqli_insert_id($conn);

		log_activity($conn, $_SESSION['login_user'], $module, 'master_gudang', $d, 'CREATE', $_SESSION['id_session'], $waktu_sekarang);	
	}

	else if($act=='edit'){
		include "edit.php";
	}

	else if($act=='update'){
		$kode = strtoupper($_POST['kode']);

        $sql="UPDATE master_gudang SET kode='$kode', nama='$_POST[nama]', master_cabang_id='$_POST[master_cabang_id]', remark='$_POST[remark]', updated_at='$waktu_sekarang' WHERE id='$_POST[id]'";

        mysqli_query($conn,$sql);

		log_activity($conn, $_SESSION['login_user'], $module, 'master_gudang', $_POST['id'], 'UPDATE', $_SESSION['id_session'], $waktu_sekarang);
	}
	
	else if($act=='delete'){
		$sql="UPDATE master_gudang SET deleted_at='$waktu_sekarang' WHERE id='$_GET[id]'";
		$result=mysqli_query($conn,$sql);

		log_activity($conn, $_SESSION['login_user'], $module, 'master_gudang', $_GET['id'], 'DELETE', $_SESSION['id_session'], $waktu_sekarang);

		header("location: gudang?message=delete");
	}

	mysqli_close($conn);
	
}
?>