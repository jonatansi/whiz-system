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
    
	$module = "Master customer";
	
	if($act=='tambah'){
		include "tambah.php";
	}

	else if($act=='input'){
		$d=mysqli_fetch_array(mysqli_query($conn,"SELECT kode FROM master_cabang WHERE id='$_SESSION[master_cabang_id]' AND deleted_at IS NULL"));
		$e=mysqli_fetch_array(mysqli_query($conn,"SELECT MAX(counter) AS counter FROM master_customer WHERE master_cabang_id='$_SESSION[master_cabang_id]' AND deleted_at IS NULL"));
		$counter = $e['counter']+1;
		$urutan_nomor= sprintf("%06s",$counter);

		$kode = "WLH-$d[kode]-$thn".$urutan_nomor;

		$sql="INSERT INTO master_customer (kode, nama, master_cabang_id, created_at, updated_at) VALUES ('$kode', '$_POST[nama]', '$_SESSION[master_cabang_id]', '$waktu_sekarang', '$waktu_sekarang')";

		mysqli_query($conn,$sql);
		$d=mysqli_insert_id($conn);

		log_activity($conn, $_SESSION['login_user'], $module, 'master_customer', $d, 'CREATE', $_SESSION['id_session'], $waktu_sekarang);

	}

	else if($act=='edit'){
		include "edit.php";
	}

	else if($act=='update'){

        $sql="UPDATE master_customer SET nama='$_POST[nama]', updated_at='$waktu_sekarang' WHERE id='$_POST[id]'";

        mysqli_query($conn,$sql);

		log_activity($conn, $_SESSION['login_user'], $module, 'master_customer', $_POST['id'], 'UPDATE', $_SESSION['id_session'], $waktu_sekarang);
	}
	
	else if($act=='delete'){
		$sql="UPDATE master_customer SET deleted_at='$waktu_sekarang' WHERE id='$_GET[id]'";
		$result=mysqli_query($conn,$sql);

		log_activity($conn, $_SESSION['login_user'], $module, 'master_customer', $_GET['id'], 'DELETE', $_SESSION['id_session'], $waktu_sekarang);

		header("location: customer?message=delete");
	}

	mysqli_close($conn);
	
}
?>