<?php
session_start();
include_once "konfig/koneksi.php";
include_once "konfig/library.php";
include_once "konfig/base_url.php";
if(!empty($_SESSION['login_system']))
{
	mysqli_query($conn,"UPDATE pegawai SET is_login='N' WHERE id='$_SESSION[login_user]'");
	
	mysqli_query($conn,"UPDATE pegawai_log_login SET logout_at='$waktu_sekarang' WHERE id_session='$_SESSION[id_session]'");

	session_unset(); 
	session_destroy();
	
}
mysqli_close($conn);
header("location: $BASE_URL");
?>