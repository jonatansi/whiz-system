<?php

//session_start();
function timer(){
	$time=3000;
	$_SESSION['timeout']=time()+$time;
}
function cek_login(){
	$timeout=$_SESSION['timeout'];
	if(time()<$timeout){
		timer();
		return true;
	}else{
		include "konfig/koneksi.php";
		include "konfig/library.php";
		
		mysqli_query($conn,"UPDATE pegawai SET is_login='N' WHERE id='$_SESSION[login_user]'");

		mysqli_query($conn,"UPDATE pegawai_log_login SET logout_at='$waktu_sekarang' WHERE id_session='$_SESSION[id_session]'");
		
		unset($_SESSION['timeout']);
		return false;
	}
}
?>
