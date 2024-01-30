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

	$act=$_GET['act'];
    
	$module = "Master Kategori Material";
	
	if($act=='tambah'){
		include "tambah.php";
	}

	else if($act=='input'){
        
		$sql="INSERT INTO master_kategori_material (nama, created_at, updated_at) VALUES ('$_POST[nama]', '$waktu_sekarang', '$waktu_sekarang')";

		mysqli_query($conn,$sql);
		$d=mysqli_insert_id($conn);

		log_activity($conn, $_SESSION['login_user'], $module, 'master_kategori_material', $d, 'CREATE', $_SESSION['id_session'], $waktu_sekarang);	
	}

	else if($act=='edit'){
		include "edit.php";
	}

	else if($act=='update'){
		
        $sql="UPDATE master_kategori_material SET  nama='$_POST[nama]', updated_at='$waktu_sekarang' WHERE id='$_POST[id]'";

        mysqli_query($conn,$sql);

		log_activity($conn, $_SESSION['login_user'], $module, 'master_kategori_material', $_POST['id'], 'UPDATE', $_SESSION['id_session'], $waktu_sekarang);
	}
	
	else if($act=='delete'){
		$sql="UPDATE master_kategori_material SET deleted_at='$waktu_sekarang' WHERE id='$_GET[id]'";
		$result=mysqli_query($conn,$sql);

		log_activity($conn, $_SESSION['login_user'], $module, 'master_kategori_material', $_GET['id'], 'DELETE', $_SESSION['id_session'], $waktu_sekarang);

		header("location: katmaterial?message=delete");
	}

	mysqli_close($conn);
	
}
?>