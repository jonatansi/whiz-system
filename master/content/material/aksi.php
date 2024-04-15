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
    
	$module = "Master material";
	
	if($act=='tambah'){
		include "tambah.php";
	}

	else if($act=='input'){
        $c=mysqli_fetch_array(mysqli_query($conn,"SELECT kode FROM master_kategori_material WHERE id='$_POST[master_kategori_material_id]'"));

		$m=mysqli_fetch_array(mysqli_query($conn,"SELECT ordering FROM master_material WHERE master_kategori_material_id='$_POST[master_kategori_material_id]'"));

		$ordering = $m['ordering']+1;
		$kode = $c['kode'].sprintf("%05s",$ordering);

		$sql="INSERT INTO master_material (kode, master_kategori_material_id, merk_type, master_satuan_id, remark, created_at, updated_at, minimum_stok, ordering) VALUES ('$kode', '$_POST[master_kategori_material_id]', '$_POST[merk_type]', '$_POST[master_satuan_id]', '$_POST[remark]', '$waktu_sekarang', '$waktu_sekarang', '$_POST[minimum_stok]', '$ordering')";

		// echo $sql;

		mysqli_query($conn,$sql);
		$d=mysqli_insert_id($conn);

		log_activity($conn, $_SESSION['login_user'], $module, 'master_material', $d, 'CREATE', $_SESSION['id_session'], $waktu_sekarang);

		// header("location: material");
	
	}

	else if($act=='edit'){
		include "edit.php";
	}

	else if($act=='update'){
		
        $sql="UPDATE master_material SET master_kategori_material_id='$_POST[master_kategori_material_id]', merk_type='$_POST[merk_type]', master_satuan_id='$_POST[master_satuan_id]', remark='$_POST[remark]', minimum_stok='$_POST[minimum_stok]', updated_at='$waktu_sekarang' WHERE id='$_POST[id]'";

        mysqli_query($conn,$sql);

		log_activity($conn, $_SESSION['login_user'], $module, 'master_material', $_POST['id'], 'UPDATE', $_SESSION['id_session'], $waktu_sekarang);

		// header("location: material");
	}
	
	else if($act=='delete'){
		$sql="UPDATE master_material SET deleted_at='$waktu_sekarang' WHERE id='$_GET[id]'";
		$result=mysqli_query($conn,$sql);

		log_activity($conn, $_SESSION['login_user'], $module, 'master_material', $_GET['id'], 'DELETE', $_SESSION['id_session'], $waktu_sekarang);

		header("location: material?message=delete");
	}

	mysqli_close($conn);
	
}
?>