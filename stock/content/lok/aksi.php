<?php
session_start();
// error_reporting(0);
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

if (empty($_SESSION['login_user'])){
	header('location:keluar');
}
else{
	include "../../../konfig/koneksi.php";
	include "../../../konfig/library.php";

	$act=$_GET['act'];
    
    if($act=='propinsi'){
        include "propinsi.php";
    }

    else if($act=='kabupaten'){
        include "kabupaten.php";
    }

    else if($act=='kecamatan'){
        include "kecamatan.php";
    }

    else if($act=='kelurahan'){
        include "kelurahan.php";
    }

    mysqli_close($conn_api);
	
}
?>