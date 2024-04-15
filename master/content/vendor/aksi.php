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

	$act=$_GET['act'];
    
	$module = "Master vendor";
	
	if($act=='tambah'){
		include "tambah.php";
	}

	else if($act=='input'){
        $kode = strtoupper($_POST['kode']);

		$sql="INSERT INTO master_vendor (kode, nama, lok_provinsi_id, lok_kabupaten_id, lok_kecamatan_id, lok_kelurahan_id, alamat, kode_pos, npwp, sales_pic, sales_hp, created_at, updated_at) VALUES ('$kode', '$_POST[nama]', '$_POST[lok_provinsi_id]', '$_POST[lok_kabupaten_id]', '$_POST[lok_kecamatan_id]', '$_POST[lok_kelurahan_id]', '$_POST[alamat]', '$_POST[kode_pos]', '$_POST[npwp]', '$_POST[sales_pic]', '$_POST[sales_hp]', '$waktu_sekarang', '$waktu_sekarang')";

		mysqli_query($conn,$sql);
		$d=mysqli_insert_id($conn);

		log_activity($conn, $_SESSION['login_user'], $module, 'master_vendor', $d, 'CREATE', $_SESSION['id_session'], $waktu_sekarang);

		// header("location: vendor");
	
	}

	else if($act=='edit'){
		include "edit.php";
	}

	else if($act=='update'){
		$kode = strtoupper($_POST['kode']);

        $sql="UPDATE master_vendor SET kode='$kode', nama='$_POST[nama]', lok_provinsi_id='$_POST[lok_provinsi_id]', lok_kabupaten_id='$_POST[lok_kabupaten_id]', lok_kecamatan_id='$_POST[lok_kecamatan_id]', lok_kelurahan_id='$_POST[lok_kelurahan_id]', alamat='$_POST[alamat]', kode_pos='$_POST[kode_pos]', sales_pic='$_POST[sales_pic]', sales_hp='$_POST[sales_hp]', updated_at='$waktu_sekarang' WHERE id='$_POST[id]'";

        mysqli_query($conn,$sql);

		log_activity($conn, $_SESSION['login_user'], $module, 'master_vendor', $_POST['id'], 'UPDATE', $_SESSION['id_session'], $waktu_sekarang);

		// header("location: vendor");
	}
	
	else if($act=='delete'){
		$sql="UPDATE master_vendor SET deleted_at='$waktu_sekarang' WHERE id='$_GET[id]'";
		$result=mysqli_query($conn,$sql);

		log_activity($conn, $_SESSION['login_user'], $module, 'master_vendor', $_GET['id'], 'DELETE', $_SESSION['id_session'], $waktu_sekarang);

		header("location: vendor?message=delete");
	}

	mysqli_close($conn);
	
}
?>