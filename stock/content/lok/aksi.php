<?php
session_start();
// error_reporting(0);
if (empty($_SESSION['login_user'])){
	header('location:keluar');
}
else{
	include "../../../konfig/koneksi.php";
	include "../../../konfig/library.php";

    include "../../../services/send_discord.php";
    include "../../../services/get_error.php";
    
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