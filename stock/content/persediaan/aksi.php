<?php
session_start();
// error_reporting(0);
if (empty($_SESSION['login_user'])){
	header('location:keluar');
}
else{
	include "../../../konfig/koneksi.php";
	include "../../../konfig/library.php";
	include "../../../konfig/fungsi_angka.php";
	include "../../../konfig/fungsi_tanggal.php";
    include "../../../konfig/base_url.php";

	include "../../../services/send_discord.php";
    include "../../../services/get_error.php";
	
	$act=$_GET['act'];
    
	$module = "Purchase Order";
	
    //TAMBAH PO
	if($act=='data_json'){
		include "data_json.php";
	}

	else if($act=='tambah_material'){
		include "add/tambah_material.php";
	}

	else if($act=='data_json_view'){
		include "data_json_view.php";
	}

	else if($act=='data_json_view_sn'){
		include "data_json_view_sn.php";
	}

	mysqli_close($conn);
	
}
?>